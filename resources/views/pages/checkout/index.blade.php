@extends('layouts.app')
@section('title', 'Checkout — OWL Store')

@section('content')

<div class="bg-gray-100 min-h-screen py-6 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Checkout</h1>
            <p class="text-gray-500 text-sm mt-1">Review pesanan Anda sebelum checkout</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ==================== LEFT COLUMN (Form) ==================== --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- ALAMAT PENGIRIMAN --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-map-pin text-[#e8a020] text-xl"></i>
                            <h2 class="font-bold text-gray-800">Alamat Pengiriman</h2>
                        </div>
                        <button type="button" onclick="openAddressModal()" class="text-sm text-[#e8a020] hover:underline">
                            Pilih Alamat Lain
                        </button>
                    </div>
                    
                    <div id="selectedAddressDisplay" class="p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="ti ti-user-check text-green-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800" id="addressName">{{ Auth::user()->name ?? 'Belum diisi' }}</div>
                                <div class="text-sm text-gray-600 mt-0.5" id="addressPhone">{{ Auth::user()->phone ?? '-' }}</div>
                                <div class="text-sm text-gray-600 mt-1" id="addressFull">-</div>
                                <div class="text-xs text-gray-400 mt-1" id="addressLabel">
                                    <span class="bg-gray-100 px-2 py-0.5 rounded">ALAMAT</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-green-600 font-semibold">Standar</div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="address" id="addressInput" value="">
                    <input type="hidden" name="city" id="cityInput" value="">
                    <input type="hidden" name="province" id="provinceInput" value="">
                    <input type="hidden" name="postal_code" id="postalCodeInput" value="">
                </div>

                {{-- PRODUK YANG DIPESAN --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-package text-[#e8a020] text-xl"></i>
                            <h2 class="font-bold text-gray-800">Produk yang Dipesan</h2>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @foreach($cartItems as $item)
                        <div class="p-4 flex gap-4">
                            <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ti ti-package text-gray-300 text-3xl"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-sm">{{ $item->product->name }}</h3>
                                        @if($item->variant)
                                        <p class="text-xs text-gray-400 mt-0.5">Varian: {{ $item->variant }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-red-500">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                        <div class="text-xs text-gray-400 line-through mt-0.5">
                                            @if($item->product->price_original)
                                            Rp {{ number_format($item->product->price_original * $item->quantity, 0, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="text-xs text-gray-500">Jumlah: {{ $item->quantity }}</div>
                                    <div class="text-xs text-gray-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- VOUCHER TOKO --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="ti ti-ticket text-[#e8a020] text-xl"></i>
                                <h2 class="font-bold text-gray-800">Voucher Toko</h2>
                            </div>
                            <button onclick="openVoucherModal()" class="text-sm text-[#e8a020] hover:underline">
                                Pilih Voucher
                            </button>
                        </div>
                    </div>
                    <div class="p-5" id="voucherDisplay">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="ti ti-ticket text-gray-400"></i>
                                <span class="text-gray-500 text-sm">Belum ada voucher dipilih</span>
                            </div>
                            <span class="text-xs text-gray-400">-Rp 0</span>
                        </div>
                    </div>
                    <input type="hidden" name="voucher_code" id="voucherCode" value="">
                    <input type="hidden" name="voucher_discount" id="voucherDiscount" value="0">
                </div>

                {{-- PESAN UNTUK PENJUAL --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-message-circle text-[#e8a020] text-xl"></i>
                            <h2 class="font-bold text-gray-800">Pesan untuk Penjual</h2>
                        </div>
                    </div>
                    <div class="p-5">
                        <textarea name="notes" id="notesInput" rows="3"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                                  placeholder="Tulis catatan untuk penjual (opsional)..."></textarea>
                    </div>
                </div>

                {{-- OPSI PENGIRIMAN --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-truck text-[#e8a020] text-xl"></i>
                            <h2 class="font-bold text-gray-800">Opsi Pengiriman</h2>
                        </div>
                    </div>
                    <div class="p-5 space-y-3" id="shippingMethodsContainer">
                        <div class="text-center py-4 text-gray-400">
                            <i class="ti ti-truck text-2xl mb-2 block"></i>
                            Pilih alamat terlebih dahulu
                        </div>
                    </div>
                    <input type="hidden" name="shipping_method" id="shippingMethod" value="">
                    <input type="hidden" name="shipping_cost" id="shippingCost" value="0">
                </div>

                {{-- METODE PEMBAYARAN --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-credit-card text-[#e8a020] text-xl"></i>
                            <h2 class="font-bold text-gray-800">Metode Pembayaran</h2>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        @foreach([
                            ['bank_transfer', 'Transfer Bank', 'ti-building-bank', 'BCA, Mandiri, BRI, BNI'],
                            ['qris', 'QRIS', 'ti-qrcode', 'Scan menggunakan aplikasi bank atau e-wallet'],
                            ['gopay', 'GoPay', 'ti-wallet', 'Bayar menggunakan GoPay'],
                            ['cod', 'COD (Bayar di Tempat)', 'ti-truck-delivery', 'Khusus area Yogyakarta'],
                        ] as $payment)
                        <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="payment_method" value="{{ $payment[0] }}" class="payment-radio text-[#e8a020] w-4 h-4" {{ $loop->first ? 'checked' : '' }}>
                            <i class="ti {{ $payment[2] }} text-gray-500 text-xl w-8"></i>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 text-sm">{{ $payment[1] }}</div>
                                <div class="text-xs text-gray-400">{{ $payment[3] }}</div>
                            </div>
                            <i class="ti ti-chevron-right text-gray-300"></i>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ==================== RIGHT COLUMN (Summary) ==================== --}}
            <div class="lg:col-span-1">

                {{-- RINGKASAN BELANJA --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800">Ringkasan Belanja</h2>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Produk ({{ $cartItems->sum('quantity') }} item)</span>
                            <span class="text-gray-800 font-semibold" id="subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Diskon</span>
                            <span class="text-red-500" id="totalDiscount">-Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="text-gray-800" id="displayShippingCost">-</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Biaya Layanan</span>
                            <span class="text-gray-800">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm" id="voucherRow" style="display: none;">
                            <span class="text-gray-500">Voucher Diskon</span>
                            <span class="text-green-600" id="voucherAmountDisplay">-Rp 0</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3">
                            <div class="flex justify-between text-base">
                                <span class="font-bold text-gray-800">Total Pembayaran</span>
                                <span class="font-bold text-red-500 text-xl" id="grandTotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1" id="totalSavings">Hemat Rp {{ number_format(($subtotal - $total) + ($discount ?? 0), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <input type="hidden" id="baseSubtotal" value="{{ $subtotal }}">
                    <input type="hidden" id="baseDiscount" value="{{ $discount ?? 0 }}">
                    <input type="hidden" id="baseTotal" value="{{ $total }}">

                    <div class="p-5 border-t border-gray-100">
                        <button type="button" id="submitOrderBtn"
                                class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="ti ti-credit-card text-lg"></i> Buat Pesanan
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-3">
                            Dengan melanjutkan, Anda menyetujui Syarat & Ketentuan
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL ALAMAT ==================== --}}
<div id="addressModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 max-h-[80vh] overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between sticky top-0">
            <h3 class="text-white font-bold">Pilih Alamat Pengiriman</h3>
            <button onclick="closeAddressModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <div class="p-4 space-y-3 max-h-[60vh] overflow-y-auto" id="addressListContainer">
            <div class="text-center py-8 text-gray-400">Memuat alamat...</div>
        </div>
        <div class="p-4 border-t border-gray-100">
            <a href="{{ route('user.addresses') }}" target="_blank" class="w-full flex items-center justify-center gap-2 text-[#e8a020] text-sm font-medium">
                <i class="ti ti-plus"></i> Tambah Alamat Baru
            </a>
        </div>
    </div>
</div>

{{-- ==================== MODAL VOUCHER ==================== --}}
<div id="voucherModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 max-h-[80vh] overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between sticky top-0">
            <h3 class="text-white font-bold">Pilih Voucher</h3>
            <button onclick="closeVoucherModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <div class="p-4 space-y-3 max-h-[60vh] overflow-y-auto" id="voucherListContainer">
            <div class="text-center py-8 text-gray-400">Memuat voucher...</div>
        </div>
    </div>
</div>

<form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}" style="display: none;">
    @csrf
    <input type="text" name="name" id="formName">
    <input type="email" name="email" id="formEmail">
    <input type="text" name="phone" id="formPhone">
    <input type="text" name="address" id="formAddress">
    <input type="text" name="city" id="formCity">
    <input type="text" name="province" id="formProvince">
    <input type="text" name="postal_code" id="formPostalCode">
    <input type="text" name="payment_method" id="formPaymentMethod">
    <input type="text" name="shipping_method" id="formShippingMethod">
    <input type="text" name="notes" id="formNotes">
    <input type="text" name="voucher_code" id="formVoucherCode">
</form>

@push('scripts')
<script>
    let currentShippingCost = 0;
    let selectedAddress = null;
    let selectedVoucher = null;

    function loadDefaultAddressFromServer() {
        @if(Auth::check() && $defaultAddress)
            const defaultAddress = @json($defaultAddress);
            if (defaultAddress && defaultAddress.address) {
                document.getElementById('addressName').innerText = defaultAddress.name || '{{ Auth::user()->name }}';
                document.getElementById('addressPhone').innerText = defaultAddress.phone || '{{ Auth::user()->phone ?? "-" }}';
                document.getElementById('addressFull').innerText = `${defaultAddress.address}, ${defaultAddress.city}, ${defaultAddress.province} ${defaultAddress.postal_code || ''}`;
                document.getElementById('addressLabel').innerHTML = `<span class="bg-gray-100 px-2 py-0.5 rounded">${defaultAddress.label || 'ALAMAT'}</span>`;
                
                document.getElementById('addressInput').value = defaultAddress.address || '';
                document.getElementById('cityInput').value = defaultAddress.city || '';
                document.getElementById('provinceInput').value = defaultAddress.province || '';
                document.getElementById('postalCodeInput').value = defaultAddress.postal_code || '';
                
                selectedAddress = defaultAddress;
                loadShippingMethods();
                return true;
            }
        @endif
        return false;
    }

    function loadAddresses() {
        fetch('{{ route("user.addresses.list") }}')
            .then(res => res.json())
            .then(data => {
                if (data.addresses && data.addresses.length > 0) {
                    renderAddressList(data.addresses);
                } else {
                    document.getElementById('addressListContainer').innerHTML = `<div class="text-center py-8 text-gray-400"><i class="ti ti-map-pin-off text-4xl mb-2 block"></i>Belum ada alamat tersimpan</div>`;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function renderAddressList(addresses) {
        const container = document.getElementById('addressListContainer');
        container.innerHTML = addresses.map(addr => `
            <div onclick='selectAddress(${JSON.stringify(addr).replace(/'/g, "\\'")})' class="border border-gray-100 rounded-xl p-4 cursor-pointer hover:border-[#e8a020] transition-all">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0"><i class="ti ti-home text-gray-500"></i></div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2"><span class="font-semibold text-gray-800">${escapeHtml(addr.name)}</span>${addr.is_default ? '<span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Utama</span>' : ''}</div>
                        <div class="text-sm text-gray-600 mt-0.5">${escapeHtml(addr.phone)}</div>
                        <div class="text-sm text-gray-600 mt-1">${escapeHtml(addr.address)}, ${escapeHtml(addr.city)}, ${escapeHtml(addr.province)} ${addr.postal_code || ''}</div>
                        <div class="text-xs text-gray-400 mt-1">${escapeHtml(addr.label || 'Alamat')}</div>
                    </div>
                    <i class="ti ti-check text-green-500 ${addr.is_default ? 'opacity-100' : 'opacity-0'}"></i>
                </div>
            </div>
        `).join('');
    }

    function selectAddress(address) {
        selectedAddress = address;
        document.getElementById('addressName').innerText = address.name;
        document.getElementById('addressPhone').innerText = address.phone;
        document.getElementById('addressFull').innerText = `${address.address}, ${address.city}, ${address.province} ${address.postal_code || ''}`;
        document.getElementById('addressLabel').innerHTML = `<span class="bg-gray-100 px-2 py-0.5 rounded">${address.label || 'ALAMAT'}</span>`;
        
        document.getElementById('addressInput').value = address.address;
        document.getElementById('cityInput').value = address.city;
        document.getElementById('provinceInput').value = address.province;
        document.getElementById('postalCodeInput').value = address.postal_code || '';
        
        closeAddressModal();
        loadShippingMethods();
    }

    function openAddressModal() {
        document.getElementById('addressModal').classList.remove('hidden');
        loadAddresses();
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.add('hidden');
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function loadShippingMethods() {
        const province = document.getElementById('provinceInput').value;
        const city = document.getElementById('cityInput').value;
        
        if (!province || !city) {
            document.getElementById('shippingMethodsContainer').innerHTML = `<div class="text-center py-4 text-gray-400"><i class="ti ti-truck text-2xl mb-2 block"></i>Pilih alamat terlebih dahulu</div>`;
            return;
        }
        
        fetch('{{ route("checkout.shipping") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ province: province, city: city, weight: {{ $totalWeight ?? 1 }} })
        })
        .then(res => res.json())
        .then(data => {
            if (data.methods && data.methods.length > 0) {
                let html = '';
                data.methods.forEach((method, index) => {
                    html += `
                        <label class="flex items-center justify-between p-4 border border-gray-100 rounded-xl cursor-pointer hover:border-[#e8a020] transition-all">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_radio" value="${method.id}" data-cost="${method.cost}" class="shipping-radio text-[#e8a020]" ${index === 0 ? 'checked' : ''}>
                                <div><div class="font-semibold text-gray-800">${method.name}</div><div class="text-xs text-gray-400">Estimasi: ${method.estimation}</div></div>
                            </div>
                            <div class="text-right"><div class="font-bold text-[#1a2744]">${method.cost === 0 ? 'GRATIS' : 'Rp ' + formatNumber(method.cost)}</div>${method.cost === 0 ? '<div class="text-xs text-green-500">Gratis Ongkir</div>' : ''}</div>
                        </label>
                    `;
                });
                document.getElementById('shippingMethodsContainer').innerHTML = html;
                
                document.querySelectorAll('.shipping-radio').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const cost = parseInt(this.dataset.cost);
                        document.getElementById('shippingMethod').value = this.value;
                        document.getElementById('shippingCost').value = cost;
                        updateTotalSummary(cost);
                    });
                });
                
                const checkedRadio = document.querySelector('.shipping-radio:checked');
                if (checkedRadio) {
                    const cost = parseInt(checkedRadio.dataset.cost);
                    document.getElementById('shippingMethod').value = checkedRadio.value;
                    document.getElementById('shippingCost').value = cost;
                    updateTotalSummary(cost);
                }
            } else {
                document.getElementById('shippingMethodsContainer').innerHTML = `<div class="text-center py-4 text-red-400"><i class="ti ti-alert-circle text-2xl mb-2 block"></i>Tidak ada metode pengiriman untuk lokasi ini</div>`;
            }
        })
        .catch(error => {
            document.getElementById('shippingMethodsContainer').innerHTML = `<div class="text-center py-4 text-red-400"><i class="ti ti-alert-circle text-2xl mb-2 block"></i>Gagal memuat metode pengiriman</div>`;
        });
    }

    function loadVouchers() {
        fetch('{{ route("checkout.vouchers") }}')
            .then(res => res.json())
            .then(data => {
                if (data.vouchers && data.vouchers.length > 0) {
                    renderVoucherList(data.vouchers);
                } else {
                    document.getElementById('voucherListContainer').innerHTML = `<div class="text-center py-8 text-gray-400"><i class="ti ti-ticket-off text-4xl mb-2 block"></i>Belum ada voucher tersedia</div>`;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function renderVoucherList(vouchers) {
        const subtotal = {{ $subtotal }};
        const container = document.getElementById('voucherListContainer');
        container.innerHTML = vouchers.map(voucher => {
            const isApplicable = subtotal >= voucher.min_spend;
            return `
                <div onclick="${isApplicable ? `applyVoucher(${JSON.stringify(voucher).replace(/'/g, "\\'")})` : ''}" class="border ${isApplicable ? 'border-gray-100 cursor-pointer hover:border-[#e8a020]' : 'border-gray-100 opacity-60'} rounded-xl p-4 transition-all">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0"><i class="ti ti-ticket text-amber-600"></i></div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800">${escapeHtml(voucher.name)}</div>
                            <div class="text-sm text-red-500 font-semibold">${voucher.discount_display}</div>
                            <div class="text-xs text-gray-400 mt-1">Min. belanja Rp ${formatNumber(voucher.min_spend)}</div>
                            <div class="text-xs text-gray-400">Berlaku sampai ${voucher.ends_at_formatted}</div>
                        </div>
                        <i class="ti ti-chevron-right text-gray-300"></i>
                    </div>
                </div>
            `;
        }).join('');
    }

    function applyVoucher(voucher) {
        selectedVoucher = voucher;
        const discount = calculateVoucherDiscount(voucher);
        document.getElementById('voucherDisplay').innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2"><i class="ti ti-ticket text-[#e8a020]"></i><div><span class="text-sm font-medium text-gray-800">${escapeHtml(voucher.name)}</span><div class="text-xs text-gray-400">${voucher.code}</div></div></div>
                <div class="text-right"><span class="text-green-600 font-semibold">-Rp ${formatNumber(discount)}</span><button onclick="removeVoucher()" class="text-xs text-red-400 ml-2">Hapus</button></div>
            </div>
        `;
        document.getElementById('voucherRow').style.display = 'flex';
        document.getElementById('voucherAmountDisplay').innerHTML = `-Rp ${formatNumber(discount)}`;
        document.getElementById('voucherCode').value = voucher.code;
        document.getElementById('voucherDiscount').value = discount;
        closeVoucherModal();
        updateTotalSummary(currentShippingCost);
    }

    function removeVoucher() {
        selectedVoucher = null;
        document.getElementById('voucherDisplay').innerHTML = `<div class="flex items-center justify-between"><div class="flex items-center gap-2"><i class="ti ti-ticket text-gray-400"></i><span class="text-gray-500 text-sm">Belum ada voucher dipilih</span></div><span class="text-xs text-gray-400">-Rp 0</span></div>`;
        document.getElementById('voucherRow').style.display = 'none';
        document.getElementById('voucherCode').value = '';
        document.getElementById('voucherDiscount').value = '0';
        updateTotalSummary(currentShippingCost);
    }

    function calculateVoucherDiscount(voucher) {
        const subtotal = {{ $subtotal }};
        let discount = 0;
        if (voucher.type === 'percentage') {
            discount = subtotal * (voucher.value / 100);
            if (voucher.max_discount && discount > voucher.max_discount) discount = voucher.max_discount;
        } else {
            discount = voucher.value;
        }
        return Math.min(discount, subtotal);
    }

    function openVoucherModal() {
        document.getElementById('voucherModal').classList.remove('hidden');
        loadVouchers();
    }

    function closeVoucherModal() {
        document.getElementById('voucherModal').classList.add('hidden');
    }

    function updateTotalSummary(shippingCost) {
        currentShippingCost = shippingCost;
        const subtotal = {{ $subtotal }};
        const discount = {{ $discount ?? 0 }};
        const voucherDiscount = parseInt(document.getElementById('voucherDiscount')?.value || 0);
        const totalDiscount = discount + voucherDiscount;
        const total = subtotal - totalDiscount + shippingCost;
        
        document.getElementById('displayShippingCost').innerHTML = shippingCost === 0 ? 'GRATIS' : 'Rp ' + formatNumber(shippingCost);
        document.getElementById('grandTotal').innerHTML = 'Rp ' + formatNumber(total);
        document.getElementById('totalDiscount').innerHTML = `-Rp ${formatNumber(totalDiscount)}`;
        document.getElementById('totalSavings').innerHTML = `Hemat Rp ${formatNumber((subtotal - total) + discount)}`;
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-4 py-2.5 rounded-lg shadow-lg text-white text-xs flex items-center gap-2 animate-fade-in-up ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'} text-sm"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }

    // Submit handler
    const submitBtn = document.getElementById('submitOrderBtn');
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (submitBtn && checkoutForm) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const address = document.getElementById('addressInput')?.value.trim();
            const city = document.getElementById('cityInput')?.value.trim();
            const province = document.getElementById('provinceInput')?.value.trim();
            
            if (!address) { showToast('Alamat pengiriman harus diisi', 'error'); return; }
            if (!city) { showToast('Kota harus diisi', 'error'); return; }
            if (!province) { showToast('Provinsi harus dipilih', 'error'); return; }
            
            const shippingMethod = document.getElementById('shippingMethod')?.value;
            if (!shippingMethod) { showToast('Pilih metode pengiriman terlebih dahulu', 'error'); return; }
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) { showToast('Pilih metode pembayaran terlebih dahulu', 'error'); return; }
            
            document.getElementById('formName').value = document.getElementById('addressName')?.innerText || '';
            document.getElementById('formEmail').value = '{{ Auth::user()->email ?? "" }}';
            document.getElementById('formPhone').value = document.getElementById('addressPhone')?.innerText || '';
            document.getElementById('formAddress').value = address;
            document.getElementById('formCity').value = city;
            document.getElementById('formProvince').value = province;
            document.getElementById('formPostalCode').value = document.getElementById('postalCodeInput')?.value || '';
            document.getElementById('formPaymentMethod').value = paymentMethod.value;
            document.getElementById('formShippingMethod').value = shippingMethod;
            document.getElementById('formNotes').value = document.getElementById('notesInput')?.value || '';
            document.getElementById('formVoucherCode').value = document.getElementById('voucherCode')?.value || '';
            
            submitBtn.innerHTML = '<i class="ti ti-loader rotate-animation"></i> Memproses...';
            submitBtn.disabled = true;
            checkoutForm.submit();
        });
    }
    
    const hasDefaultAddress = loadDefaultAddressFromServer();
    if (!hasDefaultAddress) loadAddresses();
</script>
@endpush

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translate(-50%, 20px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.3s ease-out forwards; }
    .rotate-animation { animation: rotate 1s linear infinite; }
</style>
@endsection