<footer class="bg-[#1a2744] text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">

        {{-- Trust Bar --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 pb-8 border-b border-blue-900/50 mb-10">
            @foreach([
                ['ti-shield-check', 'Garansi 1 Tahun', 'Setiap produk bergaransi', 'text-green-400'],
                ['ti-truck', 'Gratis Ongkir Jogja', 'Area Yogyakarta & sekitar', 'text-blue-400'],
                ['ti-star', 'Rating 5.0', 'Dari ratusan pembeli', 'text-yellow-400'],
                ['ti-headset', 'Respon Cepat', 'Balas dalam < 1 jam', 'text-purple-400'],
            ] as [$icon, $title, $sub, $color])
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-[#e8a020]/20 transition-all duration-300">
                    <i class="ti {{ $icon }} {{ $color }} text-xl"></i>
                </div>
                <div>
                    <div class="text-white text-xs font-semibold">{{ $title }}</div>
                    <div class="text-blue-300 text-[10px] mt-0.5">{{ $sub }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Main Footer Links --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 pb-10 border-b border-blue-900/50 mb-8">
            
            {{-- Brand Column --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-[#e8a020] rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ti ti-flame text-white text-xl"></i>
                    </div>
                    <div>
                        <div class="font-bold text-base">OWL Store</div>
                        <div class="text-[#e8a020] text-[9px] tracking-widest">by OptimaWeld</div>
                    </div>
                </div>
                <p class="text-blue-200 text-xs leading-relaxed mb-4">
                    Furnitur besi premium, dibuat langsung oleh pengrajin las profesional Yogyakarta. Custom ukuran & warna tersedia.
                </p>
                <div class="flex gap-3">
                    <a href="https://wa.me/6283844029190" target="_blank"
                       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white text-xs font-medium px-4 py-2 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="ti ti-brand-whatsapp text-base"></i> Chat via WhatsApp
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-8 h-8 bg-white/10 hover:bg-[#e8a020] rounded-lg transition-colors">
                        <i class="ti ti-brand-instagram text-sm"></i>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-8 h-8 bg-white/10 hover:bg-[#e8a020] rounded-lg transition-colors">
                        <i class="ti ti-brand-facebook text-sm"></i>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-8 h-8 bg-white/10 hover:bg-[#e8a020] rounded-lg transition-colors">
                        <i class="ti ti-brand-youtube text-sm"></i>
                    </a>
                </div>
            </div>

            {{-- Kategori --}}
            <div>
                <h4 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                    <i class="ti ti-category text-[#e8a020] text-base"></i> Kategori
                </h4>
                <ul class="space-y-2.5">
                    @foreach([
                        ['meja-kantor', 'Meja Kantor'],
                        ['meja-makan', 'Meja Makan'],
                        ['kursi', 'Kursi & Bangku'],
                        ['rak', 'Rak Besi'],
                        ['lemari', 'Lemari Besi'],
                        ['custom', 'Custom Order'],
                    ] as [$slug, $label])
                    <li>
                        <a href="{{ route('products.category', $slug) }}" 
                           class="text-blue-300 hover:text-white text-xs transition-colors flex items-center gap-1.5 group">
                            <i class="ti ti-chevron-right text-[10px] text-[#e8a020] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Informasi --}}
            <div>
                <h4 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                    <i class="ti ti-info-circle text-[#e8a020] text-base"></i> Informasi
                </h4>
                <ul class="space-y-2.5">
                    @foreach([
                        ['/about', 'Tentang Kami'],
                        ['/panduan', 'Panduan Pembelian'],
                        ['#', 'Cara Pemesanan'],
                        ['#', 'Pengiriman & Ongkir'],
                        ['#', 'Garansi & Retur'],
                        ['#', 'Syarat & Ketentuan'],
                        ['#', 'Kebijakan Privasi'],
                    ] as [$url, $label])
                    <li>
                        <a href="{{ $url }}" 
                           class="text-blue-300 hover:text-white text-xs transition-colors flex items-center gap-1.5 group">
                            <i class="ti ti-chevron-right text-[10px] text-[#e8a020] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Layanan Pelanggan --}}
            <div>
                <h4 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                    <i class="ti ti-headset text-[#e8a020] text-base"></i> Layanan
                </h4>
                <ul class="space-y-2.5">
                    @foreach([
                        ['#', 'Pusat Bantuan'],
                        ['#', 'Hubungi Kami'],
                        ['#', 'FAQ'],
                        ['#', 'Lacak Pesanan'],
                        ['#', 'Pengembalian Barang'],
                        ['#', 'Komplain & Saran'],
                    ] as [$url, $label])
                    <li>
                        <a href="{{ $url }}" 
                           class="text-blue-300 hover:text-white text-xs transition-colors flex items-center gap-1.5 group">
                            <i class="ti ti-chevron-right text-[10px] text-[#e8a020] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Kontak & Our E-Store --}}
            <div>
                <h4 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                    <i class="ti ti-mail text-[#e8a020] text-base"></i> Kontak
                </h4>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-2.5 text-blue-300 text-xs">
                        <i class="ti ti-map-pin text-[#e8a020] mt-0.5"></i>
                        <span>Yogyakarta, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-blue-300 text-xs">
                        <i class="ti ti-brand-whatsapp text-[#e8a020]"></i>
                        <a href="https://wa.me/6283844029190" class="hover:text-white transition-colors">+62 838-4402-9190</a>
                    </li>
                    <li class="flex items-center gap-2.5 text-blue-300 text-xs">
                        <i class="ti ti-mail text-[#e8a020]"></i>
                        <a href="mailto:optimaweld21@gmail.com" class="hover:text-white transition-colors">optimaweld21@gmail.com</a>
                    </li>
                    <li class="flex items-start gap-2.5 text-blue-300 text-xs">
                        <i class="ti ti-clock text-[#e8a020] mt-0.5"></i>
                        <div>
                            Senin–Sabtu: 08.00–17.00<br>
                            <span class="text-red-400">Minggu: Tutup</span>
                        </div>
                    </li>
                </ul>

                {{-- Our E-Store --}}
                <div>
                    <h4 class="text-white font-bold text-xs mb-3 uppercase tracking-wider flex items-center gap-2">
                        <i class="ti ti-shopping-cart text-[#e8a020] text-sm"></i> Our E-Store
                    </h4>
                    <div class="flex flex-col gap-2">
                        <a href="#" target="_blank" 
                           class="flex items-center gap-2 text-blue-300 hover:text-white text-xs transition-colors group">
                            <div class="w-7 h-7 bg-orange-500 rounded-lg flex items-center justify-center">
                                <i class="ti ti-brand-shopee text-white text-sm"></i>
                            </div>
                            Shopee
                            <i class="ti ti-external-link text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                        <a href="#" target="_blank" 
                           class="flex items-center gap-2 text-blue-300 hover:text-white text-xs transition-colors group">
                            <div class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="ti ti-shopping-cart text-white text-sm"></i>
                            </div>
                            Tokopedia
                            <i class="ti ti-external-link text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                        <a href="#" class="flex items-center gap-2 text-blue-300 hover:text-white text-xs transition-colors group">
                            <div class="w-7 h-7 bg-purple-600 rounded-lg flex items-center justify-center">
                                <i class="ti ti-shopping-bag text-white text-sm"></i>
                            </div>
                            OWL Store
                            <i class="ti ti-external-link text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                        <a href="#" class="flex items-center gap-2 text-blue-300 hover:text-white text-xs transition-colors group">
                            <div class="w-7 h-7 bg-amber-600 rounded-lg flex items-center justify-center">
                                <i class="ti ti-file-description text-white text-sm"></i>
                            </div>
                            E-Catalog
                            <i class="ti ti-download text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment & Shipping Methods --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pb-8 border-b border-blue-900/50 mb-6">
            
            {{-- Metode Pembayaran --}}
            <div>
                <h4 class="text-white font-bold text-xs mb-3 uppercase tracking-wider flex items-center gap-2">
                    <i class="ti ti-credit-card text-[#e8a020] text-sm"></i> Metode Pembayaran
                </h4>
                <div class="flex flex-wrap gap-2 items-center">
                    <div class="bg-white rounded-lg px-3 py-1.5 flex items-center justify-center h-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.png/320px-Logo_QRIS.png" alt="QRIS" class="h-5 object-contain" loading="lazy">
                    </div>
                    <div class="bg-white rounded-lg px-3 py-1.5 flex items-center justify-center h-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/320px-Bank_Central_Asia.svg.png" alt="BCA" class="h-4 object-contain" loading="lazy">
                    </div>
                    <div class="bg-white rounded-lg px-3 py-1.5 flex items-center justify-center h-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/320px-BANK_BRI_logo.svg.png" alt="BRI" class="h-4 object-contain" loading="lazy">
                    </div>
                    <div class="bg-white rounded-lg px-3 py-1.5 flex items-center justify-center h-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/58/Mandiri_logo.svg/320px-Mandiri_logo.svg.png" alt="Mandiri" class="h-4 object-contain" loading="lazy">
                    </div>
                    <div class="bg-white rounded-lg px-3 py-1.5 flex items-center justify-center h-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BNI_logo.svg/320px-BNI_logo.svg.png" alt="BNI" class="h-4 object-contain" loading="lazy">
                    </div>
                </div>
                <p class="text-blue-400 text-[10px] mt-2">Transfer Bank · QRIS · E-Wallet</p>
            </div>

            {{-- Pengiriman --}}
            <div>
                <h4 class="text-white font-bold text-xs mb-3 uppercase tracking-wider flex items-center gap-2">
                    <i class="ti ti-truck text-[#e8a020] text-sm"></i> Pengiriman
                </h4>
                <div class="flex flex-wrap gap-2 items-center">
                    <div class="bg-white/10 rounded-lg px-3 py-1.5">
                        <span class="text-xs text-white">JNE</span>
                    </div>
                    <div class="bg-white/10 rounded-lg px-3 py-1.5">
                        <span class="text-xs text-white">J&T</span>
                    </div>
                    <div class="bg-white/10 rounded-lg px-3 py-1.5">
                        <span class="text-xs text-white">SiCepat</span>
                    </div>
                    <div class="bg-white/10 rounded-lg px-3 py-1.5">
                        <span class="text-xs text-white">GoSend</span>
                    </div>
                    <div class="bg-white/10 rounded-lg px-3 py-1.5">
                        <span class="text-xs text-white">GrabExpress</span>
                    </div>
                </div>
                <p class="text-blue-400 text-[10px] mt-2">Gratis ongkir area Yogyakarta & sekitarnya</p>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-blue-400">
            <div class="flex items-center gap-2">
                <i class="ti ti-copyright"></i>
                <span>2026 OWL Store by OptimaWeld Indonesia. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition-colors flex items-center gap-1">
                    <i class="ti ti-shield text-[#e8a020] text-xs"></i> Privacy Policy
                </a>
                <span class="text-blue-700">|</span>
                <a href="#" class="hover:text-white transition-colors flex items-center gap-1">
                    <i class="ti ti-file-text text-[#e8a020] text-xs"></i> Terms of Service
                </a>
            </div>
        </div>

    </div>
</footer>