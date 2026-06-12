@extends('layouts.app')
@section('title', 'Keranjang Belanja — OWL Store')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-r from-[#1a2744] to-[#232f3e] py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-white text-2xl font-bold mb-2">Keranjang Belanja</h1>
        <p class="text-blue-300 text-sm">Review dan selesaikan pesanan Anda</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ==================== CART ITEMS ==================== --}}
        <div class="lg:col-span-2">
            
            {{-- Progress Steps --}}
            <div class="bg-white rounded-2xl p-4 mb-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold">1</div>
                        <span class="font-semibold text-gray-800">Keranjang</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold">2</div>
                        <span class="text-gray-400">Checkout</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold">3</div>
                        <span class="text-gray-400">Selesai</span>
                    </div>
                </div>
            </div>

            {{-- Cart Items List --}}
            <div id="cartItemsContainer" class="space-y-3">
                @if(isset($cartItems) && $cartItems->count() > 0)
                    @foreach($cartItems as $item)
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow cart-item" data-id="{{ $item->id }}">
                        <div class="flex gap-4">
                            {{-- Product Image --}}
                            <a href="{{ route('products.show', $item->product->slug) }}" class="flex-shrink-0">
                                <div class="w-24 h-24 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden">
                                    @if($item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="ti ti-package text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                            </a>

                            {{-- Product Info --}}
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                    <div>
                                        <a href="{{ route('products.show', $item->product->slug) }}">
                                            <h3 class="font-semibold text-gray-800 text-sm leading-tight mb-1 hover:text-[#e8a020] transition-colors">
                                                {{ $item->product->name }}
                                            </h3>
                                        </a>
                                        @if($item->variant)
                                        <div class="text-xs text-gray-400 mb-2">
                                            Varian: {{ $item->variant }}
                                        </div>
                                        @endif
                                        <div class="text-sm font-bold text-red-500">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    
                                    {{-- Quantity Control --}}
                                    <div class="flex items-center gap-2">
                                        <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <i class="ti ti-minus text-sm"></i>
                                        </button>
                                        <span class="w-10 text-center text-sm font-semibold text-gray-800">{{ $item->quantity }}</span>
                                        <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors"
                                                {{ $item->quantity >= ($item->product->stock ?? 99) ? 'disabled' : '' }}>
                                            <i class="ti ti-plus text-sm"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- Subtotal & Delete --}}
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-gray-800">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </div>
                                        <button onclick="removeFromCart({{ $item->id }})"
                                                class="text-xs text-red-400 hover:text-red-600 mt-1 transition-colors">
                                            <i class="ti ti-trash mr-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    {{-- Empty Cart --}}
                    <div class="bg-white rounded-2xl py-16 text-center border border-gray-100">
                        <div class="relative inline-block">
                            <div class="w-28 h-28 bg-gray-50 rounded-full flex items-center justify-center">
                                <i class="ti ti-shopping-cart-off text-6xl text-gray-200"></i>
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#e8a020] rounded-full flex items-center justify-center">
                                <i class="ti ti-shopping-cart text-white text-sm"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mt-6 mb-2">Keranjang Belanja Kosong</h3>
                        <p class="text-gray-400 text-sm mb-6">Yuk, mulai belanja furnitur besi premium untuk ruangan Anda!</p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center gap-2 bg-[#1a2744] hover:bg-[#232f3e] text-white px-6 py-3 rounded-xl transition-colors">
                            <i class="ti ti-shopping-bag"></i> Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>

            {{-- Coupon Section --}}
            @if(isset($cartItems) && $cartItems->count() > 0)
            <div class="bg-white rounded-2xl p-5 mt-4 border border-gray-100">
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kode Voucher / Diskon</label>
                        <div class="flex gap-2">
                            <input type="text" id="couponCode" placeholder="Masukkan kode kupon"
                                   class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#e8a020]">
                            <button onclick="applyCoupon()" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-5 py-2.5 rounded-xl transition-colors">
                                <i class="ti ti-ticket mr-1"></i> Terapkan
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-sm text-[#e8a020] hover:underline whitespace-nowrap">
                        <i class="ti ti-plus mr-1"></i> Tambah Produk Lain
                    </a>
                </div>
                <div id="couponMessage" class="text-xs mt-2 hidden"></div>
            </div>
            @endif
        </div>

        {{-- ==================== ORDER SUMMARY ==================== --}}
        @if(isset($cartItems) && $cartItems->count() > 0)
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm sticky top-24">
                <h3 class="font-bold text-gray-800 text-lg mb-4 pb-3 border-b border-gray-100">
                    Ringkasan Belanja
                </h3>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Harga ({{ $cartItems->sum('quantity') }} item)</span>
                        <span class="text-gray-800 font-semibold" id="subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Diskon</span>
                        <span class="text-red-500 font-semibold" id="discount">-Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="text-gray-800 font-semibold" id="shippingCost">
                            @if(isset($freeShipping) && $freeShipping)
                                <span class="text-green-500">Gratis</span>
                            @else
                                Rp {{ number_format($shippingCost ?? 0, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    <div class="border-t border-gray-100 pt-3 mt-2">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-red-500 text-xl" id="total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        @if(isset($freeShipping) && $freeShipping)
                        <p class="text-xs text-green-500 mt-1">
                            <i class="ti ti-truck"></i> Gratis ongkir untuk pembelian di atas Rp {{ number_format($freeShippingThreshold, 0, ',', '.') }}
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Shipping Estimate --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Estimasi Pengiriman</label>
                    <select id="shippingOption" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#e8a020]">
                        <option value="0">Pilih kurir</option>
                        <option value="10000">JNE Reguler - Rp 10.000 (3-5 hari)</option>
                        <option value="15000">J&T Express - Rp 15.000 (2-4 hari)</option>
                        <option value="12000">SiCepat - Rp 12.000 (2-4 hari)</option>
                        <option value="0">GoSend - Estimasi ongkir (perhitungan otomatis)</option>
                    </select>
                </div>

                {{-- Payment Methods --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Metode Pembayaran</label>
                    <div class="space-y-2">
                        @foreach([
                            ['bank_transfer', 'Transfer Bank (BCA/Mandiri/BRI/BNI)', 'ti-building-bank'],
                            ['qris', 'QRIS', 'ti-qrcode'],
                            ['gopay', 'GoPay', 'ti-wallet'],
                            ['cod', 'COD (Bayar di Tempat)', 'ti-truck-delivery'],
                        ] as $payment)
                        <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="payment_method" value="{{ $payment[0] }}" class="payment-method text-[#e8a020]" {{ $loop->first ? 'checked' : '' }}>
                            <i class="ti {{ $payment[2] }} text-gray-500 text-lg"></i>
                            <span class="text-sm text-gray-700 flex-1">{{ $payment[1] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Catatan (Opsional)</label>
                    <textarea id="orderNotes" rows="2" 
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                              placeholder="Contoh: Tolong dibungkus dengan baik, sertakan nota..."></textarea>
                </div>

                {{-- Checkout Button --}}
                <button onclick="proceedToCheckout()" 
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ti ti-credit-card text-lg"></i> Lanjut ke Pembayaran
                </button>

                {{-- Trust Badges --}}
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex justify-center gap-4 text-xs text-gray-400">
                        <span><i class="ti ti-shield-check text-green-500"></i> Keamanan Terjamin</span>
                        <span><i class="ti ti-lock text-green-500"></i> 128-bit SSL</span>
                        <span><i class="ti ti-refresh text-green-500"></i> Retur 7 Hari</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    let currentCoupon = null;

    function updateQuantity(cartId, newQuantity) {
        if (newQuantity < 1) return;
        
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ cart_id: cartId, quantity: newQuantity })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showToast(data.message || 'Gagal update quantity', 'error');
            }
        });
    }

    function removeFromCart(cartId) {
        if (confirm('Hapus produk dari keranjang?')) {
            fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ cart_id: cartId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function applyCoupon() {
        const code = document.getElementById('couponCode').value;
        if (!code) {
            showToast('Masukkan kode kupon', 'error');
            return;
        }
        
        fetch('{{ route("cart.apply-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            const messageDiv = document.getElementById('couponMessage');
            if (data.success) {
                currentCoupon = data.coupon;
                messageDiv.className = 'text-xs text-green-600 mt-2';
                messageDiv.innerHTML = `<i class="ti ti-circle-check"></i> ${data.message}`;
                messageDiv.classList.remove('hidden');
                location.reload();
            } else {
                messageDiv.className = 'text-xs text-red-500 mt-2';
                messageDiv.innerHTML = `<i class="ti ti-alert-circle"></i> ${data.message}`;
                messageDiv.classList.remove('hidden');
            }
        });
    }

    function proceedToCheckout() {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const shippingOption = document.getElementById('shippingOption').value;
        const notes = document.getElementById('orderNotes').value;
        
        // Validate shipping
        if (shippingOption === '0') {
            showToast('Pilih metode pengiriman terlebih dahulu', 'error');
            return;
        }
        
        // Store checkout data in session storage
        sessionStorage.setItem('checkoutData', JSON.stringify({
            payment_method: paymentMethod,
            shipping_cost: shippingOption,
            notes: notes,
            coupon: currentCoupon
        }));
        
        window.location.href = '{{ route("checkout") }}';
    }

    // Shipping option change handler
    document.getElementById('shippingOption')?.addEventListener('change', function() {
        const cost = parseInt(this.value) || 0;
        const subtotal = {{ $subtotal ?? 0 }};
        const discount = {{ $discount ?? 0 }};
        const total = subtotal - discount + cost;
        
        document.getElementById('shippingCost').innerHTML = cost === 0 ? 'Akan dihitung' : 'Rp ' + formatNumber(cost);
        document.getElementById('total').innerHTML = 'Rp ' + formatNumber(total);
    });

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl shadow-lg text-white text-sm flex items-center gap-2 animate-fade-in-up ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'}"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('animate-fade-out-down');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endpush

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translate(-50%, 20px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }
    @keyframes fade-out-down {
        from { opacity: 1; transform: translate(-50%, 0); }
        to { opacity: 0; transform: translate(-50%, 20px); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.3s ease-out forwards; }
    .animate-fade-out-down { animation: fade-out-down 0.3s ease-in forwards; }
</style>
@endsection