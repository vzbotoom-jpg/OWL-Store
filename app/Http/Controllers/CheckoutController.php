<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
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
        
        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
        }
        
        // Check stock availability
        $stockIssues = [];
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                $stockIssues[] = $item->product->name . ' (stok: ' . $item->product->stock . ')';
            }
        }
        
        if (!empty($stockIssues)) {
            return redirect()->route('cart.index')
                ->with('error', 'Stok produk tidak mencukupi: ' . implode(', ', $stockIssues));
        }
        
        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $discount = session('cart_discount', 0);
        $couponCode = session('coupon_code');
        
        // Calculate total weight with safe handling
        $totalWeight = $cartItems->sum(function($item) {
            $weight = $item->product->weight ?? 1;
            // Remove any non-numeric characters (like "kg", "±", " ", etc)
            $weight = preg_replace('/[^0-9.]/', '', (string)$weight);
            $weight = floatval($weight);
            // If weight becomes 0 or empty, use default 1
            if ($weight <= 0) {
                $weight = 1;
            }
            return $weight * $item->quantity;
        });
        
        $total = $subtotal - $discount;
        
        // Get user addresses if logged in
        $addresses = Auth::check() ? Address::where('user_id', Auth::id())->get() : collect();
        $defaultAddress = $addresses->where('is_default', true)->first();
        
        // Get banks for payment
        $banks = $this->getBanks();
        
        // Get shipping methods (initial)
        $shippingMethods = [];
        if ($defaultAddress) {
            $shippingMethods = $this->calculateShippingCost(
                $defaultAddress->province, 
                $defaultAddress->city, 
                $totalWeight
            );
        }
        
        return view('pages.checkout.index', compact(
            'cartItems', 'subtotal', 'discount', 'couponCode',
            'total', 'totalWeight', 'addresses', 'banks', 
            'defaultAddress', 'shippingMethods'
        ));
    }
    
    /**
     * Get available vouchers for checkout
     */
    public function getVouchers()
    {
        $subtotal = $this->getCurrentSubtotal();
        
        $vouchers = Coupon::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where(function($q) {
                $q->whereNull('usage_limit')
                  ->orWhereRaw('used_count < usage_limit');
            })
            ->get()
            ->map(function($voucher) use ($subtotal) {
                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'name' => $voucher->name,
                    'type' => $voucher->type,
                    'value' => $voucher->value,
                    'min_spend' => $voucher->min_spend,
                    'max_discount' => $voucher->max_discount,
                    'discount_display' => $voucher->type === 'percentage' ? $voucher->value . '%' : 'Rp ' . number_format($voucher->value, 0, ',', '.'),
                    'ends_at_formatted' => $voucher->ends_at->format('d M Y'),
                    'is_applicable' => $subtotal >= $voucher->min_spend,
                    'estimated_discount' => $this->calculateCouponDiscount($voucher, $subtotal)
                ];
            });
        
        return response()->json([
            'success' => true,
            'vouchers' => $vouchers
        ]);
    }
    
    /**
     * Apply voucher to checkout
     */
    public function applyVoucher(Request $request)
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
        
        $subtotal = $this->getCurrentSubtotal();
        
        if ($subtotal < $coupon->min_spend) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal belanja Rp ' . number_format($coupon->min_spend, 0, ',', '.') . ' untuk menggunakan kupon ini'
            ]);
        }
        
        $discount = $this->calculateCouponDiscount($coupon, $subtotal);
        
        session([
            'checkout_voucher' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount' => $discount
            ]
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil diterapkan!',
            'discount' => $discount,
            'voucher' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount_display' => 'Rp ' . number_format($discount, 0, ',', '.')
            ]
        ]);
    }
    
    /**
     * Remove voucher from checkout
     */
    public function removeVoucher()
    {
        session()->forget('checkout_voucher');
        
        return response()->json([
            'success' => true,
            'message' => 'Voucher dihapus'
        ]);
    }
    
    /**
     * Get current subtotal from cart
     */
    private function getCurrentSubtotal()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        return Cart::with('product')
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
     * Calculate coupon discount
     */
    private function calculateCouponDiscount($coupon, $subtotal)
    {
        if ($coupon->type === 'percentage') {
            $discount = $subtotal * ($coupon->value / 100);
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            $discount = $coupon->value;
        }
        
        return min($discount, $subtotal);
    }
    
    /**
     * Get shipping methods based on location
     */
    public function getShipping(Request $request)
    {
        $request->validate([
            'province' => 'required|string',
            'city' => 'required|string',
            'weight' => 'required|numeric|min:1'
        ]);
        
        $province = $request->province;
        $city = $request->city;
        $weight = floatval($request->weight);
        
        // Ensure weight is at least 1
        if ($weight < 1) {
            $weight = 1;
        }
        
        // Get shipping methods
        $methods = $this->calculateShippingCost($province, $city, $weight);
        
        return response()->json([
            'success' => true,
            'methods' => $methods
        ]);
    }
    
    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        // Log incoming request
        Log::info('Checkout process started', $request->all());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'payment_method' => 'required|in:bank_transfer,qris,gopay,cod',
            'shipping_method' => 'required|string',
            'notes' => 'nullable|string|max:500',
            'save_address' => 'nullable|boolean',
            'voucher_code' => 'nullable|string|max:50'
        ]);
        
        Log::info('Validation passed');
        
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
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong.');
        }
        
        // Final stock check
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok produk {$item->product->name} tidak mencukupi.");
            }
        }
        
        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $discount = session('cart_discount', 0);
        $couponCode = session('coupon_code');
        $couponId = session('coupon_id');
        
        // Check for checkout voucher
        $voucherDiscount = 0;
        $voucherCode = $request->voucher_code;
        if ($voucherCode) {
            $voucher = Coupon::where('code', $voucherCode)->first();
            if ($voucher && $voucher->is_valid) {
                $voucherDiscount = $this->calculateCouponDiscount($voucher, $subtotal);
                if ($voucherDiscount > 0) {
                    $discount += $voucherDiscount;
                    $couponCode = $voucherCode;
                    $couponId = $voucher->id;
                }
            }
        }
        
        // Calculate total weight with safe handling
        $totalWeight = $cartItems->sum(function($item) {
            $weight = $item->product->weight ?? 1;
            // Remove any non-numeric characters (like "kg", "±", " ", etc)
            $weight = preg_replace('/[^0-9.]/', '', (string)$weight);
            $weight = floatval($weight);
            // If weight becomes 0 or empty, use default 1
            if ($weight <= 0) {
                $weight = 1;
            }
            return $weight * $item->quantity;
        });
        
        $shippingCost = $this->getShippingCost($request->shipping_method, $request->province, $request->city, $totalWeight);
        $total = $subtotal - $discount + $shippingCost;
        
        DB::beginTransaction();
        
        try {
            // Save address if requested and user is logged in
            if ($request->save_address && Auth::check()) {
                Address::create([
                    'user_id' => $userId,
                    'label' => 'Rumah',
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'province' => $request->province,
                    'postal_code' => $request->postal_code,
                    'is_default' => Address::where('user_id', $userId)->count() === 0
                ]);
            }
            
            // Create order number first (for logging)
            $orderNumber = $this->generateOrderNumber();
            Log::info('Generated order number: ' . $orderNumber);
            
            // Create order
$order = Order::create([
    'user_id' => $userId,
    'order_number' => $orderNumber,
    'subtotal' => $subtotal,
    'discount' => $discount,
    'shipping_cost' => $shippingCost,
    'total' => $total,
    'payment_method' => $request->payment_method,
    'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'unpaid',
    'status' => 'pending',
    'shipping_address' => $request->address,
    'shipping_city' => $request->city,
    'shipping_province' => $request->province,
    'shipping_postal_code' => $request->postal_code,
    'shipping_courier' => $request->shipping_method,
    'customer_name' => $request->name,
    'customer_email' => $request->email,
    'customer_phone' => $request->phone,
    'notes' => $request->notes,
    'coupon_code' => $couponCode,
    'coupon_id' => $couponId
]);
            
            Log::info('Order created successfully with ID: ' . $order->id);
            
            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->price * $item->quantity,
                    'variant' => $item->variant
                ]);
                
                // Update product stock
                $product = Product::find($item->product_id);
                $product->decrement('stock', $item->quantity);
                $product->increment('sold_count', $item->quantity);
            }
            
            // Clear cart
            Cart::where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->delete();
            
            // Clear session discount and voucher
            session()->forget(['cart_discount', 'coupon_code', 'coupon_id', 'shipping_cost', 'checkout_voucher']);
            
            // Update coupon usage if applied
            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('used_count');
            }
            
            DB::commit();
            
            Log::info('Checkout completed successfully for order: ' . $order->order_number);
            
            // Send order confirmation email
            $this->sendOrderConfirmation($order);
            
            // Create notification for admin
            $this->createAdminNotification($order);
            
            // Redirect based on payment method
            if ($request->payment_method === 'cod') {
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Pesanan berhasil dibuat!');
            }
            
            return redirect()->route('checkout.payment', $order->id)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            Log::error('Checkout trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display payment page
     */
    public function payment($orderId)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
        
        // Check if order is already paid
        if ($order->payment_status === 'paid') {
            return redirect()->route('user.orders.show', $orderId)
                ->with('info', 'Pesanan ini sudah lunas.');
        }
        
        // Check if payment deadline has passed (24 hours)
        if ($order->created_at->addDay()->isPast() && $order->payment_status !== 'paid') {
            $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);
            return redirect()->route('user.orders.show', $orderId)
                ->with('error', 'Waktu pembayaran telah habis. Pesanan dibatalkan.');
        }
        
        $banks = $this->getBanks();
        $virtualAccount = $this->generateVirtualAccount($order);
        
        // Get payment deadline
        $paymentDeadline = $order->created_at->addDay();
        
        return view('pages.checkout.payment', compact('order', 'banks', 'virtualAccount', 'paymentDeadline'));
    }
    
    /**
     * Confirm payment (manual confirmation)
     */
    public function confirmPayment(Request $request, $orderId)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
            'bank_from' => 'required|string',
            'account_name' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);
        
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);
        
        if ($order->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Pesanan sudah lunas.');
        }
        
        // Verify amount matches order total (allow small difference)
        $amountDiff = abs($request->amount - $order->total);
        if ($amountDiff > 5000) {
            return redirect()->back()->with('error', 'Jumlah transfer tidak sesuai dengan total pembayaran.');
        }
        
        // Save payment proof
        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        
        // Update order
        $order->update([
            'payment_status' => 'pending_verification',
            'payment_proof' => $proofPath,
            'payment_bank_from' => $request->bank_from,
            'payment_account_name' => $request->account_name,
            'payment_amount' => $request->amount
        ]);
        
        // Notify admin
        $this->notifyAdminPaymentConfirmation($order);
        
        return redirect()->route('user.orders.show', $orderId)
            ->with('success', 'Bukti pembayaran diterima. Kami akan memverifikasi dalam 1x24 jam.');
    }
    
    /**
     * Display success page after order
     */
    public function success($orderId)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
        
        $banks = $this->getBanks();
        
        return view('pages.checkout.success', compact('order', 'banks'));
    }
    
    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'OWL';
        $date = now()->format('ymd');
        $random = strtoupper(Str::random(4));
        
        do {
            $orderNumber = $prefix . $date . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . $random;
            $exists = Order::where('order_number', $orderNumber)->exists();
            if ($exists) {
                $random = strtoupper(Str::random(4));
            }
        } while ($exists);
        
        return $orderNumber;
    }
    
    /**
     * Calculate shipping cost based on location
     */
    private function calculateShippingCost($province, $city, $weight)
    {
        // Ensure weight is numeric and at least 1
        $weight = floatval($weight);
        if ($weight <= 0) {
            $weight = 1;
        }
        
        $shippingMethods = [];
        
        // Free shipping for Yogyakarta area
        $yogyakartaCities = ['Yogyakarta', 'Sleman', 'Bantul', 'Kulon Progo', 'Gunung Kidul'];
        $isYogyakarta = false;
        foreach ($yogyakartaCities as $ygCity) {
            if (stripos($city, $ygCity) !== false) {
                $isYogyakarta = true;
                break;
            }
        }
        
        if ($isYogyakarta) {
            $shippingMethods[] = [
                'id' => 'free',
                'name' => 'Gratis Ongkir (Area Yogyakarta)',
                'cost' => 0,
                'estimation' => '1-2 hari',
                'courier' => 'Internal',
                'description' => 'Gratis ongkir untuk area Yogyakarta dan sekitarnya'
            ];
        }
        
        // Calculate base cost based on weight (per kg)
        $weightKg = max(1, ceil($weight / 1000));
        
        // JNE
        $jneCost = $weightKg * 15000;
        $shippingMethods[] = [
            'id' => 'jne_reg',
            'name' => 'JNE Reguler',
            'cost' => max(10000, $jneCost),
            'estimation' => '3-5 hari',
            'courier' => 'JNE',
            'description' => 'Layanan reguler dengan tracking'
        ];
        
        // J&T
        $jntCost = $weightKg * 14000;
        $shippingMethods[] = [
            'id' => 'jnt_reg',
            'name' => 'J&T Express',
            'cost' => max(10000, $jntCost),
            'estimation' => '2-4 hari',
            'courier' => 'J&T',
            'description' => 'Layanan ekspres dengan harga terjangkau'
        ];
        
        // SiCepat
        $sicepatCost = $weightKg * 12000;
        $shippingMethods[] = [
            'id' => 'sicepat_reg',
            'name' => 'SiCepat Reguler',
            'cost' => max(10000, $sicepatCost),
            'estimation' => '2-4 hari',
            'courier' => 'SiCepat',
            'description' => 'Layanan cepat dengan tracking real-time'
        ];
        
        return $shippingMethods;
    }
    
    /**
     * Get shipping cost by method ID
     */
    private function getShippingCost($methodId, $province, $city, $weight)
    {
        $methods = $this->calculateShippingCost($province, $city, $weight);
        
        foreach ($methods as $method) {
            if ($method['id'] === $methodId) {
                return $method['cost'];
            }
        }
        
        return 15000; // Default shipping cost
    }
    
    /**
     * Helper function to sanitize weight value
     */
    private function sanitizeWeight($weight)
    {
        if (is_null($weight)) {
            return 1;
        }
        
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9.]/', '', (string)$weight);
        $numeric = floatval($cleaned);
        
        return $numeric > 0 ? $numeric : 1;
    }
    
    /**
     * Get bank accounts for payment
     */
    private function getBanks()
    {
        // Try to get from database settings if table exists
        try {
            if (\Schema::hasTable('settings')) {
                $setting = \DB::table('settings')->where('key', 'bank_accounts')->first();
                if ($setting && $setting->value) {
                    $banks = json_decode($setting->value, true);
                    if (!empty($banks)) {
                        return $banks;
                    }
                }
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet or error, use default
            \Log::warning('Could not load bank settings from database: ' . $e->getMessage());
        }
        
        // Default banks
        return [
            [
                'bank_name' => 'Bank BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT Optima Weld Indonesia',
                'color' => 'blue'
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '9876543210',
                'account_name' => 'PT Optima Weld Indonesia',
                'color' => 'blue'
            ],
            [
                'bank_name' => 'Bank BRI',
                'account_number' => '5551234567',
                'account_name' => 'PT Optima Weld Indonesia',
                'color' => 'green'
            ]
        ];
    }
    
    /**
     * Generate virtual account number
     */
    private function generateVirtualAccount($order)
    {
        // Format: 9123 + order_id padded to 10 digits
        return '9123' . str_pad($order->id, 10, '0', STR_PAD_LEFT);
    }
    
    /**
     * Send order confirmation email
     */
    private function sendOrderConfirmation($order)
    {
        try {
            // Queue email for better performance
            // Mail::to($order->customer_email)->queue(new OrderConfirmationMail($order));
            
            // For now, just log
            Log::info('Order confirmation email would be sent to: ' . $order->customer_email);
            Log::info('Order #' . $order->order_number . ' created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation: ' . $e->getMessage());
        }
    }
    
    /**
     * Create notification for admin
     */
    private function createAdminNotification($order)
    {
        try {
            // Create notification record
            Notification::create([
                'type' => 'new_order',
                'title' => 'Pesanan Baru',
                'message' => 'Pesanan #' . $order->order_number . ' dari ' . $order->customer_name . ' (Rp ' . number_format($order->total, 0, ',', '.') . ')',
                'link' => route('admin.orders.show', $order->id),
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create admin notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Notify admin about payment confirmation
     */
    private function notifyAdminPaymentConfirmation($order)
    {
        try {
            Notification::create([
                'type' => 'payment_confirmation',
                'title' => 'Konfirmasi Pembayaran',
                'message' => $order->customer_name . ' telah mengkonfirmasi pembayaran untuk pesanan #' . $order->order_number,
                'link' => route('admin.orders.show', $order->id),
                'is_read' => false
            ]);
            
            Log::info('Payment confirmation notification created for order: ' . $order->order_number);
        } catch (\Exception $e) {
            Log::error('Failed to notify admin: ' . $e->getMessage());
        }
    }
}