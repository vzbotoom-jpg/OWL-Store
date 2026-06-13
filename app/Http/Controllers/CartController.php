<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display cart page
     */
    public function index()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        // Get cart items
        $cartItems = Cart::with('product')
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->get();
        
        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $discount = session('cart_discount', 0);
        $couponCode = session('coupon_code');
        $shippingCost = session('shipping_cost', 0);
        $total = $subtotal - $discount + $shippingCost;
        
        // Check free shipping eligibility
        $freeShippingThreshold = 500000;
        $freeShipping = $subtotal >= $freeShippingThreshold;
        
        // Get total weight for shipping calculation with safe handling
        $totalWeight = $cartItems->sum(function($item) {
            $weight = $item->product->weight ?? 1;
            // Remove any non-numeric characters
            $weight = preg_replace('/[^0-9.]/', '', (string)$weight);
            $weight = floatval($weight);
            // If weight becomes 0 or empty, use default 1
            if ($weight <= 0) {
                $weight = 1;
            }
            return $weight * $item->quantity;
        });
        
        // Get recommended products for empty cart
        $recommendedProducts = [];
        if ($cartItems->isEmpty()) {
            $recommendedProducts = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->limit(4)
                ->get();
        }
        
        return view('pages.cart.index', compact(
            'cartItems', 'subtotal', 'discount', 'couponCode', 
            'shippingCost', 'total', 'freeShipping', 
            'freeShippingThreshold', 'totalWeight', 'recommendedProducts'
        ));
    }
    
    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'variant' => 'nullable|string|max:255',
            'buy_now' => 'nullable|boolean'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Check if product is active
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sedang tidak tersedia'
            ], 400);
        }
        
        // Check stock availability
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk tidak mencukupi. Stok tersedia: ' . $product->stock
            ], 400);
        }
        
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        // Check if product already in cart
        $cartItem = Cart::where('product_id', $request->product_id)
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();
        
        DB::beginTransaction();
        
        try {
            if ($cartItem) {
                // Update quantity
                $newQuantity = $cartItem->quantity + $request->quantity;
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk jumlah yang diminta'
                    ], 400);
                }
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $product->price,
                    'variant' => $request->variant ?? $cartItem->variant
                ]);
                $message = 'Jumlah produk diperbarui di keranjang';
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => $userId,
                    'session_id' => $userId ? null : $sessionId,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'variant' => $request->variant
                ]);
                $message = 'Produk berhasil ditambahkan ke keranjang';
            }
            
            DB::commit();
            
            // Get updated cart count
            $cartCount = $this->getCartCount();
            
            // If buy_now is true, return success for redirect to checkout
            if ($request->buy_now) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => $cartCount,
                    'redirect' => route('checkout')
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartCount,
                'cart_total' => $this->getCartTotal()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart add error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk ke keranjang'
            ], 500);
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1|max:99'
        ]);
        
        $cartItem = Cart::findOrFail($request->cart_id);
        
        // Verify ownership
        if (!$this->verifyCartOwnership($cartItem)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        $product = $cartItem->product;
        
        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            $cartItem->update(['quantity' => $request->quantity]);
            
            // Recalculate discount if any
            $this->recalculateDiscount();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui',
                'item_subtotal' => $cartItem->price * $cartItem->quantity,
                'cart_subtotal' => $this->getCartSubtotal(),
                'cart_discount' => session('cart_discount', 0),
                'cart_total' => $this->getCartTotal(),
                'cart_count' => $this->getCartCount()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui keranjang'
            ], 500);
        }
    }
    
    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id'
        ]);
        
        $cartItem = Cart::findOrFail($request->cart_id);
        
        // Verify ownership
        if (!$this->verifyCartOwnership($cartItem)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        DB::beginTransaction();
        
        try {
            $cartItem->delete();
            
            // Recalculate discount
            $this->recalculateDiscount();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang',
                'cart_subtotal' => $this->getCartSubtotal(),
                'cart_discount' => session('cart_discount', 0),
                'cart_total' => $this->getCartTotal(),
                'cart_count' => $this->getCartCount()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart remove error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk dari keranjang'
            ], 500);
        }
    }
    
    /**
     * Apply coupon discount
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ]);
        
        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where(function($q) {
                $q->whereNull('usage_limit')
                  ->orWhereRaw('used_count < usage_limit');
            })
            ->first();
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kupon tidak valid atau sudah kadaluarsa'
            ]);
        }
        
        // Get cart subtotal
        $subtotal = $this->getCartSubtotal();
        
        // Check minimum spend
        if ($subtotal < $coupon->min_spend) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal belanja Rp ' . number_format($coupon->min_spend, 0, ',', '.') . ' untuk menggunakan kupon ini'
            ]);
        }
        
        // Calculate discount
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = $subtotal * ($coupon->value / 100);
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } elseif ($coupon->type === 'nominal') {
            $discount = min($coupon->value, $subtotal);
        } else {
            // BOGO type - handle if needed
            $discount = 0;
        }
        
        // Check per user limit if user is logged in
        if (Auth::check() && $coupon->per_user_limit > 0) {
            $userUsedCount = \App\Models\Order::where('user_id', Auth::id())
                ->where('coupon_code', $coupon->code)
                ->count();
            
            if ($userUsedCount >= $coupon->per_user_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mencapai batas penggunaan kupon ini'
                ]);
            }
        }
        
        // Apply discount
        session([
            'cart_discount' => $discount,
            'coupon_code' => $coupon->code,
            'coupon_id' => $coupon->id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil diterapkan! Diskon Rp ' . number_format($discount, 0, ',', '.'),
            'discount' => $discount,
            'coupon' => $coupon,
            'cart_total' => $subtotal - $discount
        ]);
    }
    
    /**
     * Remove coupon
     */
    public function removeCoupon()
    {
        session()->forget(['cart_discount', 'coupon_code', 'coupon_id']);
        
        return response()->json([
            'success' => true,
            'message' => 'Kupon dihapus',
            'cart_total' => $this->getCartSubtotal()
        ]);
    }
    
    /**
     * Get cart summary for AJAX
     */
    public function summary()
    {
        $cartCount = $this->getCartCount();
        $cartTotal = $this->getCartTotal();
        
        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal,
            'cart_total_formatted' => 'Rp ' . number_format($cartTotal, 0, ',', '.')
        ]);
    }
    
    /**
     * Clear entire cart
     */
    public function clear()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        DB::beginTransaction();
        
        try {
            Cart::where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->delete();
            
            session()->forget(['cart_discount', 'coupon_code', 'coupon_id', 'shipping_cost']);
            
            DB::commit();
            
            return redirect()->route('cart.index')
                ->with('success', 'Keranjang berhasil dikosongkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart clear error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengosongkan keranjang');
        }
    }
    
    /**
     * Get cart count (for navbar badge)
     */
    public function getCartCount()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        return (int) Cart::where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->sum('quantity');
    }
    
    /**
     * Merge guest cart to user cart after login
     */
    public function mergeGuestCart($userId, $sessionId)
    {
        $guestItems = Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();
        
        if ($guestItems->isEmpty()) {
            return;
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($guestItems as $guestItem) {
                $existingItem = Cart::where('user_id', $userId)
                    ->where('product_id', $guestItem->product_id)
                    ->first();
                
                if ($existingItem) {
                    $existingItem->increment('quantity', $guestItem->quantity);
                    $guestItem->delete();
                } else {
                    $guestItem->update([
                        'user_id' => $userId,
                        'session_id' => null
                    ]);
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart merge error: ' . $e->getMessage());
        }
    }
    
    /**
     * Verify cart item ownership
     */
    private function verifyCartOwnership($cartItem)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        if ($userId && $cartItem->user_id === $userId) {
            return true;
        }
        
        if (!$userId && $cartItem->session_id === $sessionId) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get cart subtotal
     */
    private function getCartSubtotal()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        return (int) Cart::with('product')
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->get()
            ->sum(function($item) {
                return $item->price * $item->quantity;
            });
    }
    
    /**
     * Get cart total (after discount)
     */
    private function getCartTotal()
    {
        $subtotal = $this->getCartSubtotal();
        $discount = session('cart_discount', 0);
        
        return $subtotal - $discount;
    }
    
    /**
     * Recalculate discount after cart changes
     */
    private function recalculateDiscount()
    {
        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->first();
            if ($coupon && $coupon->is_valid) {
                $subtotal = $this->getCartSubtotal();
                
                if ($subtotal < $coupon->min_spend) {
                    session()->forget(['cart_discount', 'coupon_code', 'coupon_id']);
                } else {
                    if ($coupon->type === 'percentage') {
                        $discount = $subtotal * ($coupon->value / 100);
                        if ($coupon->max_discount && $discount > $coupon->max_discount) {
                            $discount = $coupon->max_discount;
                        }
                    } else {
                        $discount = min($coupon->value, $subtotal);
                    }
                    session(['cart_discount' => $discount]);
                }
            } else {
                session()->forget(['cart_discount', 'coupon_code', 'coupon_id']);
            }
        }
    }
}