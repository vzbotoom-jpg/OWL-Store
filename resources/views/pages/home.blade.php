@extends('layouts.app')
@section('title', 'OWL Store — Furnitur Besi Premium Yogyakarta')

@section('content')

{{-- Welcome Notification after Login/Register --}}
@if(session('success'))
<div class="fixed top-20 left-1/2 -translate-x-1/2 z-50 animate-fade-in-down">
    <div class="bg-green-500 text-white text-sm px-6 py-3 rounded-full shadow-lg flex items-center gap-2">
        <i class="ti ti-circle-check text-lg"></i>
        {{ session('success') }}
    </div>
</div>
@endif

{{-- ==================== HERO SECTION ==================== --}}
<section class="relative bg-gradient-to-r from-[#1a2744] to-[#232f3e] overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-40 h-40 bg-[#e8a020] rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-60 h-60 bg-[#e8a020] rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            {{-- Hero Text --}}
            <div>
                <div class="inline-flex items-center gap-2 bg-[#e8a020]/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#e8a020] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#e8a020]"></span>
                    </span>
                    <span class="text-[#e8a020] text-xs font-semibold tracking-wide">GRAND OPENING SALE</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    Furnitur Besi 
                    <span class="text-[#e8a020] relative inline-block">
                        Premium
                        <svg class="absolute -bottom-2 left-0 w-full" height="8" viewBox="0 0 200 8" fill="none">
                            <path d="M0 4L200 4" stroke="#e8a020" stroke-width="2" stroke-dasharray="4 4"/>
                        </svg>
                    </span>
                    <br>untuk Ruang Anda
                </h1>
                
                <p class="text-blue-200 text-base md:text-lg leading-relaxed mb-8 max-w-lg">
                    Meja kantor, meja makan, kursi, rak — dibuat langsung oleh pengrajin las profesional Yogyakarta. 
                    Custom ukuran & warna tersedia.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" 
                       class="group relative inline-flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-8 py-3.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        <span>Belanja Sekarang</span>
                        <i class="ti ti-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="https://wa.me/6283844029190?text=Halo%20OWL%20Store%2C%20saya%20ingin%20pesan%20custom" 
                       target="_blank"
                       class="inline-flex items-center gap-2 border-2 border-white/30 hover:border-white text-white font-semibold px-8 py-3.5 rounded-xl transition-all duration-300 hover:bg-white/10">
                        <i class="ti ti-brand-whatsapp text-xl"></i>
                        Custom Order
                    </a>
                </div>
                
                {{-- Trust Indicators --}}
                <div class="flex flex-wrap gap-6 mt-8 pt-6 border-t border-white/10">
                    @foreach([
                        ['ti-shield-check', 'Garansi 1 Tahun'],
                        ['ti-truck', 'Gratis Ongkir Jogja'],
                        ['ti-star', 'Rating 5.0'],
                    ] as [$icon, $label])
                    <div class="flex items-center gap-2">
                        <i class="ti {{ $icon }} text-[#e8a020] text-lg"></i>
                        <span class="text-blue-300 text-xs">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            
            {{-- Hero Image / Stats --}}
            <div class="relative">
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['250+', 'Produk Terjual', 'bg-[#e8a020]/20 border-[#e8a020]/30'],
                        ['5.0★', 'Rating Toko', 'bg-white/5 border-white/10'],
                        ['3 Hari', 'Produksi Cepat', 'bg-white/5 border-white/10'],
                        ['100%', 'Kepuasan', 'bg-white/5 border-white/10'],
                    ] as [$num, $label, $bgClass])
                    <div class="text-center {{ $bgClass }} border backdrop-blur-sm rounded-2xl px-4 py-5">
                        <div class="text-3xl font-bold text-[#e8a020]">{{ $num }}</div>
                        <div class="text-blue-300 text-sm mt-1">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Floating Element --}}
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-[#e8a020]/20 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-[#e8a020]/10 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
    
    {{-- Wave Divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" fill="#f3f4f6">
            <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,100L1360,100C1280,100,1120,100,960,100C800,100,640,100,480,100C320,100,160,100,80,100L0,100Z"></path>
        </svg>
    </div>
</section>

{{-- ==================== PROMO BANNER ==================== --}}
<section class="bg-gradient-to-r from-[#e8a020] to-[#d4911a] py-4 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-[#1a2744]">
                <i class="ti ti-discount-2 text-2xl animate-pulse"></i>
                <span class="font-bold">🎉 Promo Grand Opening</span>
            </div>
            <p class="text-[#1a2744] text-sm font-semibold">
                Diskon 20% untuk pembelian pertama + Gratis Ongkir area Yogyakarta
            </p>
            <a href="{{ route('products.index') }}" class="text-xs bg-[#1a2744] text-white px-4 py-1.5 rounded-full hover:bg-[#232f3e] transition-colors">
                Klaim Promo →
            </a>
        </div>
    </div>
</section>

{{-- ==================== CATEGORIES SECTION ==================== --}}
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <div class="inline-block bg-[#e8a020]/10 rounded-full px-4 py-1.5 mb-4">
                <span class="text-[#e8a020] text-sm font-semibold">✦ Kategori Produk</span>
            </div>
            <h2 class="text-3xl font-bold text-[#1a2744] mb-3">Jelajahi Koleksi Kami</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Temukan berbagai pilihan furnitur besi premium untuk melengkapi ruangan Anda
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach([
                ['meja-kantor', 'ti-layout-board', 'Meja Kantor', 'bg-blue-50 text-blue-600', 'hover:bg-blue-100'],
                ['meja-makan', 'ti-tools', 'Meja Makan', 'bg-amber-50 text-amber-600', 'hover:bg-amber-100'],
                ['kursi', 'ti-armchair', 'Kursi', 'bg-purple-50 text-purple-600', 'hover:bg-purple-100'],
                ['rak', 'ti-building-warehouse', 'Rak Besi', 'bg-green-50 text-green-600', 'hover:bg-green-100'],
                ['lemari', 'ti-door', 'Lemari', 'bg-red-50 text-red-600', 'hover:bg-red-100'],
                ['custom', 'ti-pencil-ruler', 'Custom Order', 'bg-gray-50 text-gray-600', 'hover:bg-gray-100'],
            ] as [$slug, $icon, $label, $bgColor, $hoverColor])
            <a href="{{ route('products.category', $slug) }}" 
               class="group bg-white rounded-2xl p-5 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                <div class="w-14 h-14 {{ $bgColor }} rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="ti {{ $icon }} text-2xl"></i>
                </div>
                <div class="text-sm font-semibold text-gray-700">{{ $label }}</div>
                <div class="text-xs text-gray-400 mt-1 opacity-0 group-hover:opacity-100 transition-opacity">Lihat Semua →</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== FEATURED PRODUCTS ==================== --}}
<section class="py-16 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-10 flex-wrap gap-4">
            <div>
                <div class="inline-block bg-[#e8a020]/10 rounded-full px-4 py-1.5 mb-3">
                    <span class="text-[#e8a020] text-sm font-semibold">✦ Best Seller</span>
                </div>
                <h2 class="text-3xl font-bold text-[#1a2744]">Produk Unggulan</h2>
                <p class="text-gray-500 mt-1">Pilihan terbaik yang paling banyak diminati</p>
            </div>
            <a href="{{ route('products.index') }}" 
               class="text-[#e8a020] font-semibold hover:underline flex items-center gap-1">
                Lihat Semua <i class="ti ti-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                [
                    'name' => 'Meja Kantor Besi Minimalis 120cm',
                    'material' => 'Besi hollow + kaca tempered',
                    'price' => 1850000,
                    'original' => 2200000,
                    'badge' => 'Terlaris',
                    'badgeClass' => 'bg-red-500 text-white',
                    'icon' => 'ti-layout-board',
                    'rating' => 4.9,
                    'reviews' => 48,
                    'image' => null
                ],
                [
                    'name' => 'Meja Makan Industrial 4 Kursi',
                    'material' => 'Besi pipa + kayu jati',
                    'price' => 3200000,
                    'original' => null,
                    'badge' => 'Baru',
                    'badgeClass' => 'bg-green-500 text-white',
                    'icon' => 'ti-tools',
                    'rating' => 4.8,
                    'reviews' => 23,
                    'image' => null
                ],
                [
                    'name' => 'Kursi Besi Cafe Vintage',
                    'material' => 'Besi tempa + busa + kain',
                    'price' => 450000,
                    'original' => 530000,
                    'badge' => '-15%',
                    'badgeClass' => 'bg-orange-500 text-white',
                    'icon' => 'ti-armchair',
                    'rating' => 5.0,
                    'reviews' => 61,
                    'image' => null
                ],
                [
                    'name' => 'Rak Besi 5 Susun Serbaguna',
                    'material' => 'Besi hollow anti karat',
                    'price' => 680000,
                    'original' => 750000,
                    'badge' => null,
                    'badgeClass' => '',
                    'icon' => 'ti-building-warehouse',
                    'rating' => 4.7,
                    'reviews' => 35,
                    'image' => null
                ],
            ] as $product)
            <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                {{-- Product Image --}}
                <div class="relative bg-gray-50 h-56 flex items-center justify-center overflow-hidden">
                    @if($product['image'])
                        <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <i class="ti {{ $product['icon'] }} text-6xl text-gray-200 group-hover:text-gray-300 transition-colors"></i>
                    @endif
                    
                    @if($product['badge'])
                    <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-lg {{ $product['badgeClass'] }}">
                        {{ $product['badge'] }}
                    </span>
                    @endif
                    
                    <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:text-red-500">
                        <i class="ti ti-heart"></i>
                    </button>
                </div>
                
                {{-- Product Info --}}
                <div class="p-4">
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Furnitur Besi</div>
                    <h3 class="font-semibold text-gray-800 text-sm leading-tight line-clamp-2 mb-2 group-hover:text-[#e8a020] transition-colors">
                        {{ $product['name'] }}
                    </h3>
                    <div class="text-[10px] text-gray-400 mb-2">{{ $product['material'] }}</div>
                    
                    {{-- Rating --}}
                    <div class="flex items-center gap-1 mb-2">
                        <div class="flex items-center gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <i class="ti ti-star text-xs {{ $i <= $product['rating'] ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                        <span class="text-[10px] text-gray-400">({{ number_format($product['reviews']) }})</span>
                    </div>
                    
                    {{-- Price --}}
                    <div class="mb-3">
                        @if($product['original'])
                        <div class="text-xs text-gray-400 line-through">Rp {{ number_format($product['original'], 0, ',', '.') }}</div>
                        @endif
                        <div class="text-lg font-bold text-red-500">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                    </div>
                    
                    {{-- Add to Cart Button --}}
                    <button onclick="addToCart({{ $loop->index }})" 
                            class="w-full bg-[#1a2744] hover:bg-[#232f3e] text-white text-xs font-semibold py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="ti ti-shopping-cart text-sm"></i>
                        <span>Tambah ke Keranjang</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== ADVANTAGES SECTION ==================== --}}
<section class="py-16 px-4 bg-[#1a2744]">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <div class="inline-block bg-[#e8a020]/10 rounded-full px-4 py-1.5 mb-4">
                <span class="text-[#e8a020] text-sm font-semibold">✦ Keunggulan Kami</span>
            </div>
            <h2 class="text-3xl font-bold text-white mb-3">Mengapa Memilih OWL Store?</h2>
            <p class="text-blue-200 max-w-2xl mx-auto">
                Kami berkomitmen memberikan yang terbaik untuk kepuasan Anda
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['ti-shield-check', 'Garansi 1 Tahun', 'Setiap produk bergaransi penuh untuk ketenangan Anda.', 'text-blue-400'],
                ['ti-truck', 'Gratis Ongkir Jogja', 'Pengiriman gratis untuk seluruh area Yogyakarta.', 'text-green-400'],
                ['ti-pencil-ruler', 'Custom Ukuran & Warna', 'Pesan sesuai keinginan — ukuran, warna, dan desain.', 'text-amber-400'],
                ['ti-clock', 'Produksi 3–5 Hari', 'Langsung dikerjakan tim las profesional berpengalaman.', 'text-purple-400'],
                ['ti-headset', 'Customer Service 24/7', 'Tim support siap membantu Anda kapan saja.', 'text-red-400'],
                ['ti-receipt', 'Harga Transparan', 'Tidak ada biaya tersembunyi, harga sesuai yang tertera.', 'text-teal-400'],
                ['ti-package', 'Packing Aman', 'Produk dikemas dengan standar tinggi untuk keamanan.', 'text-indigo-400'],
                ['ti-rotate', 'Retur Mudah', 'Proses retur yang cepat dan mudah jika ada kendala.', 'text-rose-400'],
            ] as [$icon, $title, $desc, $color])
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-5 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4 {{ $color }} group-hover:scale-110 transition-transform">
                    <i class="ti {{ $icon }} text-2xl"></i>
                </div>
                <h3 class="font-bold text-white text-base mb-2">{{ $title }}</h3>
                <p class="text-blue-200 text-xs leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== TESTIMONIALS SECTION ==================== --}}
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <div class="inline-block bg-[#e8a020]/10 rounded-full px-4 py-1.5 mb-4">
                <span class="text-[#e8a020] text-sm font-semibold">✦ Testimoni</span>
            </div>
            <h2 class="text-3xl font-bold text-[#1a2744] mb-3">Apa Kata Pelanggan?</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Ribuan pelanggan telah mempercayakan kebutuhan furnitur mereka kepada kami
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['Ahmad Hidayat', 'Yogyakarta', '5.0', 'Kualitasnya luar biasa! Lasannya rapi, tidak ada sisi tajam. Kaca terpasang sempurna dan kokoh. Sudah 2 bulan dipakai kerja setiap hari, tidak ada masalah. Pengiriman tepat waktu, dikemas dengan bubble wrap tebal. Sangat rekomendasikan!', 'bg-blue-100 text-blue-600'],
                ['Siti Rahayu', 'Sleman', '5.0', 'Pesan custom ukuran 140×70cm untuk kantor. Responsnya cepat, koordinasi via WhatsApp sangat mudah. Hasil akhirnya memuaskan, persis seperti yang diminta. Teman-teman kantor pada nanya beli di mana! Pasti balik lagi.', 'bg-green-100 text-green-600'],
                ['Dani Prasetyo', 'Bantul', '4.5', 'Overall bagus dan kokoh. Kurangi 1 bintang karena pengiriman telat 1 hari dari estimasi. Tapi kualitas produknya sendiri tidak ada komplain, lasannya rapi dan finishing catnya merata. Harga worth it untuk kualitas ini.', 'bg-purple-100 text-purple-600'],
            ] as [$name, $city, $rating, $comment, $colorClass])
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 {{ $colorClass }} rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $name }}</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-0.5">
                                @for($i=1; $i<=5; $i++)
                                    <i class="ti ti-star text-xs {{ $i <= 4.5 ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-400">{{ $city }}</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $comment }}</p>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <i class="ti ti-brand-whatsapp text-green-500 text-sm"></i>
                        <span class="text-xs text-gray-400">Verified Purchase</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== CUSTOM ORDER CTA ==================== --}}
<section class="py-16 px-4 bg-gradient-to-r from-[#1a2744] to-[#232f3e] relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#e8a020] rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-[#e8a020] rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative max-w-4xl mx-auto text-center">
        <div class="w-20 h-20 bg-[#e8a020]/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="ti ti-pencil-ruler text-[#e8a020] text-4xl"></i>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Tidak Menemukan yang Anda Cari?</h2>
        <p class="text-blue-200 text-lg mb-8 max-w-2xl mx-auto">
            Pesan custom! Kirim ukuran, desain, atau referensi gambar — tim kami akan buatkan sesuai keinginan Anda.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="https://wa.me/6283844029190?text=Halo%20OWL%20Store%2C%20saya%20ingin%20custom%20order" 
               target="_blank"
               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="ti ti-brand-whatsapp text-xl"></i>
                Konsultasi Gratis via WhatsApp
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center gap-2 border-2 border-white/30 hover:border-white text-white font-semibold px-8 py-3.5 rounded-xl transition-all duration-300">
                <i class="ti ti-mail"></i>
                Kirim Email
            </a>
        </div>
    </div>
</section>

{{-- ==================== BRANDS / PARTNERS SECTION ==================== --}}
<section class="py-12 px-4 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-8">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Dipercaya oleh</p>
        </div>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 opacity-60">
            @foreach([
                ['PT Maju Jaya', 'ti-building'],
                ['CV Karya Abadi', 'ti-building'],
                ['PT Sentosa', 'ti-building'],
                ['CV Gemilang', 'ti-building'],
                ['PT Makmur', 'ti-building'],
            ] as [$name, $icon])
            <div class="flex items-center gap-2 text-gray-400">
                <i class="ti {{ $icon }} text-xl"></i>
                <span class="text-sm font-medium">{{ $name }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Auto-hide notification after 5 seconds
    setTimeout(() => {
        const notif = document.querySelector('.animate-fade-in-down');
        if (notif) {
            notif.classList.add('animate-fade-out-down');
            setTimeout(() => notif.remove(), 500);
        }
    }, 5000);
    
    // Add to cart function
    function addToCart(productId) {
        @auth
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Produk ditambahkan ke keranjang!', 'success');
                updateCartCount(data.cart_count);
            } else {
                showToast(data.message || 'Gagal menambahkan ke keranjang', 'error');
            }
        });
        @else
        showToast('Silakan login terlebih dahulu', 'error');
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 1500);
        @endauth
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
    
    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-badge, .absolute.-top-1.-right-2');
        if (cartBadge) cartBadge.innerText = count;
    }
</script>
@endpush

<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translate(-50%, -20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translate(-50%, 20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    @keyframes fade-out-down {
        from {
            opacity: 1;
            transform: translate(-50%, 0);
        }
        to {
            opacity: 0;
            transform: translate(-50%, 20px);
        }
    }
    .animate-fade-in-down {
        animation: fade-in-down 0.3s ease-out forwards;
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.3s ease-out forwards;
    }
    .animate-fade-out-down {
        animation: fade-out-down 0.3s ease-in forwards;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection