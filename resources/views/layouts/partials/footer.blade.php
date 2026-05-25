<footer class="bg-[#1a2744] text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">

        {{-- Trust bar --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pb-8 border-b border-blue-900 mb-8">
            @foreach([
                ['ti-shield-check', 'Garansi 1 Tahun',    'Setiap produk bergaransi'],
                ['ti-truck',        'Gratis Ongkir Jogja', 'Area Yogyakarta & sekitar'],
                ['ti-star',         'Rating 5.0',          'Dari ratusan pembeli'],
                ['ti-headset',      'Respon Cepat',        'Balas dalam < 1 jam'],
            ] as [$icon, $title, $sub])
            <div class="flex items-center gap-3">
                <i class="ti {{ $icon }} text-[#e8a020] text-2xl flex-shrink-0"></i>
                <div>
                    <div class="text-white text-xs font-semibold">{{ $title }}</div>
                    <div class="text-blue-300 text-[11px]">{{ $sub }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Links: Brand (2 col) + 4 kolom = 6 col total --}}
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-6 pb-8 border-b border-blue-900 mb-6">

            {{-- Brand --}}
            <div class="col-span-2 sm:col-span-2">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-[#e8a020] rounded-lg flex items-center justify-center">
                        <i class="ti ti-flame text-white text-lg"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm">OWL Store</div>
                        <div class="text-[#e8a020] text-[9px] tracking-widest">by OptimaWeld</div>
                    </div>
                </div>
                <p class="text-blue-200 text-xs leading-relaxed mb-4">
                    Furnitur besi premium, dibuat langsung oleh pengrajin las profesional Yogyakarta.
                </p>
                <a href="https://wa.me/6283844029190"
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white text-xs font-medium px-4 py-2 rounded-lg transition-colors">
                    <i class="ti ti-brand-whatsapp text-base"></i> Chat via WhatsApp
                </a>
            </div>

            {{-- Kategori --}}
            <div class="col-span-1">
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Kategori</h4>
                <ul class="space-y-2 text-blue-200 text-xs">
                    @foreach([
                        ['meja-kantor', 'Meja Kantor'],
                        ['meja-makan',  'Meja Makan'],
                        ['kursi',       'Kursi & Bangku'],
                        ['rak',         'Rak Besi'],
                        ['custom',      'Custom Order'],
                    ] as [$slug, $label])
                    <li>
                        <a href="{{ route('products.category', $slug) }}"
                           class="hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="ti ti-chevron-right text-[10px] text-blue-500"></i>{{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Informasi --}}
            <div class="col-span-1">
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Informasi</h4>
                <ul class="space-y-2 text-blue-200 text-xs">
                    @foreach([
                        ['cara-pemesanan', 'Cara Pemesanan'],
                        ['#',              'Pengiriman & Ongkir'],
                        ['#',              'Garansi & Retur'],
                        ['#',              'Custom Order'],
                        ['#',              'Tentang Kami'],
                    ] as [$url, $label])
                    <li>
                        <a href="{{ $url }}"
                           class="hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="ti ti-chevron-right text-[10px] text-blue-500"></i>{{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Explore OWL --}}
            <div class="col-span-1">
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Explore OWL</h4>
                <ul class="space-y-2 text-blue-200 text-xs">
                    @foreach([
                        ['#', 'Tentang Kami'],
                        ['#', 'Karier'],
                        ['#', 'Kebijakan'],
                        ['#', 'Kebijakan Privasi'],
                        ['#', 'Blog & Inspirasi'],
                    ] as [$url, $label])
                    <li>
                        <a href="{{ $url }}"
                           class="hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="ti ti-chevron-right text-[10px] text-blue-500"></i>{{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Layanan --}}
            <div class="col-span-1">
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Layanan</h4>
                <ul class="space-y-2 text-blue-200 text-xs">
                    @foreach([
                        ['#', 'Pusat Bantuan'],
                        ['#', 'Metode Pembayaran'],
                        ['#', 'Lacak Pesanan'],
                        ['#', 'Gratis Ongkir'],
                        ['#', 'Garansi OWL Store'],
                    ] as [$url, $label])
                    <li>
                        <a href="{{ $url }}"
                           class="hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="ti ti-chevron-right text-[10px] text-blue-500"></i>{{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{-- Pembayaran · Pengiriman · Ikuti Kami --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 pb-8 border-b border-blue-900 mb-6">

            {{-- Metode Pembayaran --}}
            <div>
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Metode Pembayaran</h4>
                <div class="flex flex-wrap gap-2">
                    {{-- QRIS --}}
                    <div class="bg-white rounded px-2 py-1 flex items-center justify-center h-8 w-16">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.png/320px-Logo_QRIS.png"
                             alt="QRIS" class="h-5 object-contain" loading="lazy">
                    </div>
                    {{-- BCA --}}
                    <div class="bg-white rounded px-2 py-1 flex items-center justify-center h-8 w-16">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/320px-Bank_Central_Asia.svg.png"
                             alt="BCA" class="h-5 object-contain" loading="lazy">
                    </div>
                    {{-- BRI --}}
                    <div class="bg-white rounded px-2 py-1 flex items-center justify-center h-8 w-16">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/320px-BANK_BRI_logo.svg.png"
                             alt="BRI" class="h-5 object-contain" loading="lazy">
                    </div>
                </div>
            </div>

            {{-- Pengiriman --}}
            <div>
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Pengiriman</h4>
                <div class="flex flex-wrap gap-2">
                    {{-- Gosend --}}
                    <div class="bg-white rounded px-2 py-1 flex items-center justify-center h-8 w-20">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/GoSend_logo.svg/320px-GoSend_logo.svg.png"
                             alt="Gosend" class="h-5 object-contain" loading="lazy">
                    </div>
                </div>
            </div>

            {{-- Ikuti Kami --}}
            <div>
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Ikuti Kami</h4>
                <div class="flex flex-col gap-2.5">
                    <a href="#" class="flex items-center gap-2.5 text-blue-200 text-xs hover:text-white transition-colors">
                        <div class="w-7 h-7 rounded bg-blue-800 flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-brand-instagram text-sm text-white"></i>
                        </div>
                        Instagram
                    </a>
                    <a href="#" class="flex items-center gap-2.5 text-blue-200 text-xs hover:text-white transition-colors">
                        <div class="w-7 h-7 rounded bg-blue-800 flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-brand-youtube text-sm text-white"></i>
                        </div>
                        YouTube
                    </a>
                    <a href="#" class="flex items-center gap-2.5 text-blue-200 text-xs hover:text-white transition-colors">
                        <div class="w-7 h-7 rounded bg-blue-800 flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-brand-tiktok text-sm text-white"></i>
                        </div>
                        TikTok
                    </a>
                    <a href="#" class="flex items-center gap-2.5 text-blue-200 text-xs hover:text-white transition-colors">
                        <div class="w-7 h-7 rounded bg-blue-800 flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-brand-facebook text-sm text-white"></i>
                        </div>
                        Facebook
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-blue-400">
            <span>© 2026 OWL Store by OptimaWeld Indonesia. All rights reserved.</span>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <span>|</span>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>

    </div>
</footer>