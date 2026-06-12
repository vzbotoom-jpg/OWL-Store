@extends('layouts.app')
@section('title', 'Checkout — OWL Store')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-r from-[#1a2744] to-[#232f3e] py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-white text-2xl font-bold mb-2">Checkout</h1>
        <p class="text-blue-300 text-sm">Lengkapi data untuk menyelesaikan pesanan</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ==================== CHECKOUT FORM ==================== --}}
        <div class="lg:col-span-2">

            {{-- Progress Steps --}}
            <div class="bg-white rounded-2xl p-4 mb-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">✓</div>
                        <span class="text-gray-400">Keranjang</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-[#e8a020] mx-2"></div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold">2</div>
                        <span class="font-semibold text-gray-800">Checkout</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold">3</div>
                        <span class="text-gray-400">Selesai</span>
                    </div>
                </div>
            </div>

            <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                @csrf

                {{-- Contact Information --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-5">
                    <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="ti ti-user text-[#e8a020]"></i> Informasi Kontak
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        </div>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-5">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="ti ti-map-pin text-[#e8a020]"></i> Alamat Pengiriman
                        </h3>
                        @if(Auth::check() && Auth::user()->addresses->count() > 0)
                        <button type="button" onclick="useSavedAddress()" class="text-xs text-[#e8a020] hover:underline">
                            <i class="ti ti-bookmark"></i> Gunakan Alamat Tersimpan
                        </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="address" rows="2" required
                                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                                      placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Provinsi</label>
                            <select name="province" id="provinceSelect" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                                <option value="">Pilih Provinsi</option>
                                <option value="DIY Yogyakarta">DIY Yogyakarta</option>
                                <option value="Jawa Tengah">Jawa Tengah</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                                <option value="Jawa Barat">Jawa Barat</option>
                                <option value="Jakarta">Jakarta</option>
                                <option value="Banten">Banten</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kota</label>
                            <input type="text" name="city" id="cityInput" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                                   placeholder="Contoh: Yogyakarta, Sleman, Bantul">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kode Pos</label>
                            <input type="text" name="postal_code"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                                   placeholder="Kode Pos">
                        </div>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-5">
                    <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="ti ti-truck text-[#e8a020]"></i> Metode Pengiriman
                    </h3>
                    <div class="space-y-3" id="shippingMethodsContainer">
                        <div class="text-center py-4 text-gray-400">
                            <i class="ti ti-truck text-2xl mb-2 block"></i>
                            Pilih provinsi dan kota terlebih dahulu
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-5">
                    <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="ti ti-credit-card text-[#e8a020]"></i> Metode Pembayaran
                    </h3>
                    <div class="space-y-3">
                        @foreach([
                            ['bank_transfer', 'Transfer Bank (BCA/Mandiri/BRI/BNI)', 'ti-building-bank', 'Pilih salah satu rekening bank kami untuk transfer pembayaran. Pesanan akan diproses setelah pembayaran dikonfirmasi.'],
                            ['qris', 'QRIS (Scan Barcode)', 'ti-qrcode', 'Scan QR code menggunakan aplikasi bank atau e-wallet kesayangan Anda.'],
                            ['gopay', 'GoPay', 'ti-wallet', 'Bayar menggunakan saldo GoPay atau metode pembayaran lain dalam Gojek.'],
                        ] as $payment)
                        <label class="flex items-start gap-3 p-4 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="payment_method" value="{{ $payment[0] }}" class="payment-method mt-1 text-[#e8a020]" {{ $loop->first ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="ti {{ $payment[2] }} text-gray-500 text-lg"></i>
                                    <span class="font-semibold text-gray-800">{{ $payment[1] }}</span>
                                </div>
                                <p class="text-xs text-gray-400">{{ $payment[3] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Order Notes --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-5">
                    <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="ti ti-notes text-[#e8a020]"></i> Catatan Pesanan
                    </h3>
                    <textarea name="notes" rows="3"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                              placeholder="Tambahkan catatan untuk pesanan Anda (opsional)"></textarea>
                </div>

            </form>
        </div>

        {{-- ==================== ORDER SUMMARY ==================== --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm sticky top-24">

                <h3 class="font-bold text-gray-800 text-lg mb-4 pb-3 border-b border-gray-100">
                    Ringkasan Pesanan
                </h3>

                {{-- Order Items --}}
                <div class="max-h-64 overflow-y-auto mb-4 space-y-3">
                    @foreach($cartItems as $item)
                    <div class="flex gap-3">
                        <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="ti ti-package text-gray-300 text-lg"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-gray-800 line-clamp-1">{{ $item->product->name }}</div>
                            <div class="text-xs text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-xs font-semibold text-gray-800">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Total Summary --}}
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal ({{ $cartItems->sum('quantity') }} item)</span>
                        <span class="text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Diskon</span>
                        <span class="text-red-500">-Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="text-gray-800" id="summaryShipping">-</span>
                    </div>
                    <div class="border-t border-gray-100 pt-2 mt-2">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-red-500 text-xl" id="summaryTotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" form="checkoutForm" 
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ti ti-credit-card text-lg"></i> Buat Pesanan
                </button>

                {{-- Guarantee --}}
                <div class="mt-4 text-center text-xs text-gray-400">
                    <p>Dengan melanjutkan, Anda menyetujui</p>
                    <a href="#" class="text-[#e8a020] hover:underline">Syarat & Ketentuan</a>
                    <span class="mx-1">dan</span>
                    <a href="#" class="text-[#e8a020] hover:underline">Kebijakan Privasi</a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    let currentShippingCost = 0;
    let selectedShippingMethod = null;

    // Load shipping methods based on province & city
    const provinceSelect = document.getElementById('provinceSelect');
    const cityInput = document.getElementById('cityInput');
    
    function loadShippingMethods() {
        const province = provinceSelect.value;
        const city = cityInput.value;
        
        if (!province || !city) {
            document.getElementById('shippingMethodsContainer').innerHTML = `
                <div class="text-center py-4 text-gray-400">
                    <i class="ti ti-truck text-2xl mb-2 block"></i>
                    Pilih provinsi dan kota terlebih dahulu
                </div>
            `;
            return;
        }
        
        fetch('{{ route("checkout.shipping") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ province: province, city: city, weight: {{ $totalWeight ?? 0 }} })
        })
        .then(res => res.json())
        .then(data => {
            if (data.methods && data.methods.length > 0) {
                let html = '';
                data.methods.forEach((method, index) => {
                    html += `
                        <label class="flex items-start gap-3 p-4 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="shipping_method" value="${method.id}" class="shipping-radio mt-1" data-cost="${method.cost}" ${index === 0 ? 'checked' : ''}>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-semibold text-gray-800">${method.name}</span>
                                        <p class="text-xs text-gray-400 mt-0.5">Estimasi: ${method.estimation}</p>
                                    </div>
                                    <span class="font-bold text-[#1a2744]">Rp ${formatNumber(method.cost)}</span>
                                </div>
                            </div>
                        </label>
                    `;
                });
                document.getElementById('shippingMethodsContainer').innerHTML = html;
                
                // Add event listeners to shipping radios
                document.querySelectorAll('.shipping-radio').forEach(radio => {
                    radio.addEventListener('change', function() {
                        updateTotalSummary(parseInt(this.dataset.cost));
                    });
                });
                
                // Trigger initial update
                const checkedRadio = document.querySelector('.shipping-radio:checked');
                if (checkedRadio) {
                    updateTotalSummary(parseInt(checkedRadio.dataset.cost));
                }
            } else {
                document.getElementById('shippingMethodsContainer').innerHTML = `
                    <div class="text-center py-4 text-red-400">
                        <i class="ti ti-alert-circle text-2xl mb-2 block"></i>
                        Tidak ada metode pengiriman untuk lokasi ini
                    </div>
                `;
            }
        });
    }
    
    function updateTotalSummary(shippingCost) {
        currentShippingCost = shippingCost;
        const subtotal = {{ $subtotal }};
        const discount = {{ $discount ?? 0 }};
        const total = subtotal - discount + shippingCost;
        
        document.getElementById('summaryShipping').innerHTML = shippingCost === 0 ? 'Gratis' : 'Rp ' + formatNumber(shippingCost);
        document.getElementById('summaryTotal').innerHTML = 'Rp ' + formatNumber(total);
    }
    
    provinceSelect.addEventListener('change', loadShippingMethods);
    cityInput.addEventListener('change', loadShippingMethods);
    
    function useSavedAddress() {
        // Fetch saved addresses and populate form
        fetch('{{ route("user.addresses") }}/list')
            .then(res => res.json())
            .then(data => {
                if (data.addresses && data.addresses.length > 0) {
                    // Show address selection modal or dropdown
                    showAddressSelection(data.addresses);
                }
            });
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    // Check if user is logged in
    @guest
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl shadow-lg text-white text-sm flex items-center gap-2 animate-fade-in-up ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'}"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    @endguest
    
    @guest
    // Prompt login if not authenticated
    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('Silakan login terlebih dahulu untuk melanjutkan checkout', 'error');
        setTimeout(() => {
            window.location.href = '{{ route("login") }}?redirect=checkout';
        }, 2000);
    });
    @endguest
</script>
@endpush

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translate(-50%, 20px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.3s ease-out forwards; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection