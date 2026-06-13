@extends('layouts.app')
@section('title', 'Keranjang Belanja — OWL Store')

@section('content')

<div class="bg-gray-100 min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Keranjang Belanja</h1>
            <p class="text-gray-500 text-sm mt-1">Review dan selesaikan pesanan Anda</p>
        </div>

        @if(isset($cartItems) && $cartItems->count() > 0)

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- CART ITEMS --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex gap-4">
                        <div class="w-24 h-24 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="ti ti-package text-gray-300 text-3xl"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                            @if($item->variant)
                            <p class="text-xs text-gray-400 mt-0.5">Varian: {{ $item->variant }}</p>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center gap-2">
                                    <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                            class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200">
                                        <i class="ti ti-minus text-sm"></i>
                                    </button>
                                    <span class="w-10 text-center font-semibold">{{ $item->quantity }}</span>
                                    <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200">
                                        <i class="ti ti-plus text-sm"></i>
                                    </button>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-red-500">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                    <button onclick="removeFromCart({{ $item->id }})" class="text-xs text-red-400 hover:text-red-600 mt-1">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- SUMMARY --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
                    <h3 class="font-bold text-gray-800 text-lg mb-4">Ringkasan Belanja</h3>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total ({{ $cartItems->sum('quantity') }} item)</span>
                            <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Diskon</span>
                            <span class="text-red-500">-Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold">{{ $shippingCost > 0 ? 'Rp '.number_format($shippingCost,0,',','.') : 'Akan dihitung' }}</span>
                        </div>
                        <div class="border-t pt-2">
                            <div class="flex justify-between text-lg">
                                <span class="font-bold">Total</span>
                                <span class="font-bold text-red-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" 
                       class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl text-center block transition-all">
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>

        </div>

        @else

        {{-- EMPTY CART --}}
        <div class="bg-white rounded-2xl py-16 text-center">
            <i class="ti ti-shopping-cart-off text-6xl text-gray-200 mb-4 block"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Keranjang Kosong</h3>
            <p class="text-gray-400 text-sm mb-6">Yuk, mulai belanja furnitur besi premium!</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-[#1a2744] text-white px-6 py-3 rounded-xl hover:bg-[#232f3e] transition-colors">
                Mulai Belanja
            </a>
        </div>

        @endif

    </div>
</div>

@push('scripts')
<script>
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
                alert(data.message || 'Gagal update quantity');
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
</script>
@endpush

@endsection