@extends('admin.layouts.app')
@section('title', 'Pengaturan Toko')
@section('breadcrumb', 'Pengaturan')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-6">
        <div class="border-b border-gray-100">
            <div class="flex overflow-x-auto">
                <button onclick="showTab('general')" id="tabGeneralBtn"
                        class="px-6 py-4 text-sm font-medium border-b-2 transition-colors text-[#e8a020] border-[#e8a020]">
                    <i class="ti ti-building-store mr-2"></i> Umum
                </button>
                <button onclick="showTab('payment')" id="tabPaymentBtn"
                        class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-colors">
                    <i class="ti ti-credit-card mr-2"></i> Pembayaran
                </button>
                <button onclick="showTab('shipping')" id="tabShippingBtn"
                        class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-colors">
                    <i class="ti ti-truck mr-2"></i> Pengiriman
                </button>
                <button onclick="showTab('notification')" id="tabNotificationBtn"
                        class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-colors">
                    <i class="ti ti-bell mr-2"></i> Notifikasi
                </button>
                <button onclick="showTab('seo')" id="tabSeoBtn"
                        class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-colors">
                    <i class="ti ti-chart-pie mr-2"></i> SEO
                </button>
            </div>
        </div>

        {{-- General Settings Tab --}}
        <div id="generalTab" class="p-6 space-y-6">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Logo Toko</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden" id="logoPreview">
                                <i class="ti ti-building-store text-gray-400 text-3xl"></i>
                            </div>
                            <input type="file" name="logo" accept="image/*" class="text-sm">
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Toko</label>
                        <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'OWL Store' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Deskripsi Toko</label>
                        <textarea name="store_description" rows="3"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">{{ $settings['store_description'] ?? 'Furnitur besi premium buatan pengrajin las profesional Yogyakarta' }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Email Toko</label>
                        <input type="email" name="store_email" value="{{ $settings['store_email'] ?? 'info@owlstore.com' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="store_phone" value="{{ $settings['store_phone'] ?? '+62 838-4402-9190' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Alamat</label>
                        <textarea name="store_address" rows="2"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">{{ $settings['store_address'] ?? 'Yogyakarta, Indonesia' }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Jam Operasional</label>
                        <input type="text" name="store_hours" value="{{ $settings['store_hours'] ?? 'Senin–Sabtu: 08.00–17.00, Minggu: Tutup' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-2.5 rounded-xl transition-colors">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Payment Settings Tab --}}
        <div id="paymentTab" class="p-6 space-y-6 hidden">
            <form method="POST" action="{{ route('admin.settings.payment.update') }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-5">
                    <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-2">Metode Pembayaran</h3>
                    
                    <div class="grid gap-4">
                        @foreach([
                            ['bank_transfer', 'Transfer Bank', true, 'ti-building-bank'],
                            ['qris', 'QRIS', true, 'ti-qrcode'],
                            ['gopay', 'GoPay', false, 'ti-wallet'],
                            ['shopee_pay', 'ShopeePay', false, 'ti-shopping-cart'],
                            ['cod', 'COD (Bayar di Tempat)', false, 'ti-truck-delivery'],
                        ] as [$key, $label, $active, $icon])
                        <label class="flex items-center justify-between p-4 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i class="ti {{ $icon }} text-gray-500 text-xl"></i>
                                <span class="text-gray-700">{{ $label }}</span>
                            </div>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_methods[]" value="{{ $key }}"
                                       {{ $active ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Midtrans Configuration</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Merchant ID</label>
                                <input type="text" name="midtrans_merchant_id" value="{{ $settings['midtrans_merchant_id'] ?? '' }}"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Client Key</label>
                                <input type="text" name="midtrans_client_key" value="{{ $settings['midtrans_client_key'] ?? '' }}"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Server Key</label>
                                <input type="text" name="midtrans_server_key" value="{{ $settings['midtrans_server_key'] ?? '' }}"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
                            </div>
                            <div>
                                <label class="flex items-center gap-2 mt-4">
                                    <input type="checkbox" name="midtrans_sandbox" value="1" class="rounded text-[#e8a020]">
                                    <span class="text-sm text-gray-600">Mode Sandbox (Testing)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="mt-6 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-2.5 rounded-xl transition-colors">
                    Simpan Pengaturan Pembayaran
                </button>
            </form>
        </div>

        {{-- Shipping Settings Tab --}}
        <div id="shippingTab" class="p-6 space-y-6 hidden">
            <form method="POST" action="{{ route('admin.settings.shipping.update') }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-5">
                    <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-2">Zona Pengiriman</h3>
                    
                    <div id="shippingZones">
                        @foreach($shippingZones ?? [['name' => 'Yogyakarta', 'cost' => 0, 'free_threshold' => 0, 'is_active' => true]] as $zone)
                        <div class="shipping-zone p-4 border border-gray-100 rounded-xl mb-3">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Nama Zona</label>
                                    <input type="text" name="zones[0][name]" value="{{ $zone['name'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Ongkos Kirim</label>
                                    <input type="number" name="zones[0][cost]" value="{{ $zone['cost'] ?? 0 }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Gratis Ongkir (Min. Belanja)</label>
                                    <input type="number" name="zones[0][free_threshold]" value="{{ $zone['free_threshold'] ?? 0 }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="zones[0][is_active]" value="1" {{ ($zone['is_active'] ?? true) ? 'checked' : '' }} class="rounded">
                                        <span class="text-sm">Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" onclick="addShippingZone()" class="text-sm text-[#e8a020] hover:underline">
                        <i class="ti ti-plus mr-1"></i> Tambah Zona
                    </button>
                    
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Kurir Pengiriman</h3>
                        <div class="grid gap-3">
                            @foreach(['JNE', 'J&T', 'SiCepat', 'Anteraja', 'Ninja Xpress', 'GoSend', 'GrabExpress'] as $courier)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="couriers[]" value="{{ $courier }}" class="rounded text-[#e8a020]">
                                <span class="text-gray-700">{{ $courier }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="mt-6 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-2.5 rounded-xl transition-colors">
                    Simpan Pengaturan Pengiriman
                </button>
            </form>
        </div>

        {{-- Notification Settings Tab --}}
        <div id="notificationTab" class="p-6 space-y-6 hidden">
            <form method="POST" action="{{ route('admin.settings.notification.update') }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-5">
                    <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-2">Notifikasi Admin</h3>
                    <div class="grid gap-3">
                        @foreach([
                            'new_order' => 'Pesanan Baru',
                            'order_cancelled' => 'Pesanan Dibatalkan',
                            'payment_received' => 'Pembayaran Diterima',
                            'low_stock' => 'Stok Menipis',
                            'new_review' => 'Review Baru',
                        ] as $key => $label)
                        <label class="flex items-center justify-between p-3 border border-gray-100 rounded-xl">
                            <span class="text-gray-700">{{ $label }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="admin_notifications[]" value="{{ $key }}" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Notifikasi Pelanggan</h3>
                        <div class="grid gap-3">
                            @foreach([
                                'order_confirmation' => 'Konfirmasi Pesanan',
                                'payment_confirmation' => 'Konfirmasi Pembayaran',
                                'shipping_update' => 'Update Pengiriman',
                                'order_completed' => 'Pesanan Selesai',
                                'promo_notification' => 'Promo & Diskon',
                            ] as $key => $label)
                            <label class="flex items-center justify-between p-3 border border-gray-100 rounded-xl">
                                <span class="text-gray-700">{{ $label }}</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="customer_notifications[]" value="{{ $key }}" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Channel Notifikasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="channels[]" value="email" class="rounded text-[#e8a020]">
                                <span>Email</span>
                            </label>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="channels[]" value="whatsapp" class="rounded text-[#e8a020]">
                                <span>WhatsApp</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="mt-6 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-2.5 rounded-xl transition-colors">
                    Simpan Pengaturan Notifikasi
                </button>
            </form>
        </div>

        {{-- SEO Settings Tab --}}
        <div id="seoTab" class="p-6 space-y-6 hidden">
            <form method="POST" action="{{ route('admin.settings.seo.update') }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Meta Title (Homepage)</label>
                        <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? 'OWL Store — Furnitur Besi Premium Yogyakarta' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        <p class="text-xs text-gray-400 mt-1">Rekomendasi: 50-60 karakter</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Meta Description</label>
                        <textarea name="meta_description" rows="3"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">{{ $settings['meta_description'] ?? 'Toko furnitur besi premium di Yogyakarta. Meja kantor, kursi, rak besi custom dengan garansi 1 tahun dan gratis ongkir area Jogja.' }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Rekomendasi: 150-160 karakter</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Keywords (pisahkan dengan koma)</label>
                        <input type="text" name="keywords" value="{{ $settings['keywords'] ?? 'furnitur besi, meja kantor, kursi besi, rak besi, custom furnitur, yogyakarta' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Google Analytics ID</label>
                        <input type="text" name="google_analytics" value="{{ $settings['google_analytics'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                               placeholder="G-XXXXXXXXXX">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Facebook Pixel ID</label>
                        <input type="text" name="facebook_pixel" value="{{ $settings['facebook_pixel'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                </div>
                
                <button type="submit" class="mt-6 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-2.5 rounded-xl transition-colors">
                    Simpan Pengaturan SEO
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tab) {
        const tabs = ['general', 'payment', 'shipping', 'notification', 'seo'];
        tabs.forEach(t => {
            document.getElementById(t + 'Tab').classList.add('hidden');
            document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1) + 'Btn').classList.remove('text-[#e8a020]', 'border-[#e8a020]');
            document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1) + 'Btn').classList.add('text-gray-500', 'border-transparent');
        });
        
        document.getElementById(tab + 'Tab').classList.remove('hidden');
        document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1) + 'Btn').classList.remove('text-gray-500', 'border-transparent');
        document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1) + 'Btn').classList.add('text-[#e8a020]', 'border-[#e8a020]');
    }
    
    let zoneCount = {{ count($shippingZones ?? [1]) }};
    
    function addShippingZone() {
        const container = document.getElementById('shippingZones');
        const newZone = document.createElement('div');
        newZone.className = 'shipping-zone p-4 border border-gray-100 rounded-xl mb-3';
        newZone.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Nama Zona</label>
                    <input type="text" name="zones[${zoneCount}][name]" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ongkos Kirim</label>
                    <input type="number" name="zones[${zoneCount}][cost]" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Gratis Ongkir (Min. Belanja)</label>
                    <input type="number" name="zones[${zoneCount}][free_threshold]" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex items-end justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="zones[${zoneCount}][is_active]" value="1" checked class="rounded">
                        <span class="text-sm">Aktif</span>
                    </label>
                    <button type="button" onclick="this.closest('.shipping-zone').remove()" class="text-red-500 text-sm">
                        <i class="ti ti-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newZone);
        zoneCount++;
    }
</script>
@endpush
@endsection