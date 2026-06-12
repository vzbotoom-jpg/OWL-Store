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
        $totalWeight = $cartItems->sum(function($item) {
            return ($item->product->weight ?? 1) * $item->quantity;
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
     * Get shipping methods based on location
     */
    public function getShipping(Request $request)
    {
        $request->validate([
            'province' => 'required|string',
            'city' => 'required|string',
            'weight' => 'required|integer|min:1'
        ]);
        
        $province = $request->province;
        $city = $request->city;
        $weight = $request->weight;
        
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
            'save_address' => 'nullable|boolean'
        ]);
        
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
        
        // Calculate shipping cost
        $totalWeight = $cartItems->sum(function($item) {
            return ($item->product->weight ?? 1) * $item->quantity;
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
            
            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $this->generateOrderNumber(),
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
            
            // Clear session discount
            session()->forget(['cart_discount', 'coupon_code', 'coupon_id', 'shipping_cost']);
            
            // Update coupon usage if applied
            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('used_count');
            }
            
            DB::commit();
            
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
        $number = Order::count() + 1;
        
        do {
            $orderNumber = $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT) . $random;
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
        $weightKg = ceil($weight / 1000);
        
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
        
        $shippingMethods[] = [
            'id' => 'jne_yes',
            'name' => 'JNE YES (Yakin Esok Sampai)',
            'cost' => max(25000, $weightKg * 25000),
            'estimation' => '1-2 hari',
            'courier' => 'JNE',
            'description' => 'Pengiriman cepat khusus area tertentu'
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
     * Get bank accounts for payment
     */
    private function getBanks()
    {
        // Try to get from settings first
        $savedBanks = setting('bank_accounts');
        if ($savedBanks) {
            $banks = json_decode($savedBanks, true);
            if (!empty($banks)) {
                return $banks;
            }
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