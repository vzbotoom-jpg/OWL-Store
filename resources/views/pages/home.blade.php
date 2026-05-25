{{-- Notifikasi welcome setelah login/register --}}
@if(session('success'))
<div class="bg-green-500 text-white text-sm px-4 py-3 text-center flex items-center justify-center gap-2">
    <i class="ti ti-circle-check text-lg"></i>
    {{ session('success') }}
</div>
@endif
@extends('layouts.app')
@section('title', 'OWL Store — Furnitur Besi Premium Yogyakarta')

@section('content')

{{-- HERO --}}
<section class="bg-[#1a2744] text-white py-12 px-4">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-10">
        <div class="flex-1">
            <div class="inline-block bg-[#e8a020] text-[#1a2744] text-xs font-bold px-3 py-1 rounded mb-4 tracking-wider">
                ✦ GRAND OPENING
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold leading-tight mb-4">
                Furnitur Besi <span class="text-[#e8a020]">Premium</span><br>untuk Ruang Anda
            </h1>
            <p class="text-blue-200 text-sm leading-relaxed mb-6 max-w-md">
                Meja kantor, meja makan, kursi, rak — dibuat langsung oleh pengrajin las profesional Yogyakarta. Custom ukuran & warna tersedia.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}"
                   class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                    Belanja Sekarang
                </a>
                <a href="https://wa.me/6283844029190?text=Halo%20OWL%20Store%2C%20saya%20ingin%20pesan%20custom"
                   target="_blank"
                   class="border border-white/30 hover:border-white text-white font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                    Custom Order
                </a>
            </div>
        </div>
        <div class="flex gap-4">
            @foreach([
                ['250+', 'Produk Terjual'],
                ['5.0★', 'Rating Toko'],
                ['3 Hari', 'Produksi Cepat'],
            ] as [$num, $label])
            <div class="text-center bg-white/5 border border-white/10 rounded-2xl px-5 py-4">
                <div class="text-[#e8a020] text-xl font-bold">{{ $num }}</div>
                <div class="text-blue-300 text-xs mt-1">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PROMO BANNER --}}
<section class="bg-[#e8a020] py-3 px-4 text-center">
    <p class="text-[#1a2744] text-xs font-semibold">
        🎉 Promo Grand Opening — Diskon 20% untuk pembelian pertama + Gratis Ongkir area Yogyakarta
    </p>
</section>

{{-- KATEGORI --}}
<section class="py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">Kategori Produk</h2>
            <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach([
                ['meja-kantor',  'ti-layout-board',      'Meja Kantor',   'bg-blue-50 text-blue-600'],
                ['meja-makan',   'ti-tools',              'Meja Makan',    'bg-amber-50 text-amber-600'],
                ['kursi',        'ti-armchair',           'Kursi',         'bg-purple-50 text-purple-600'],
                ['rak',          'ti-building-warehouse', 'Rak Besi',      'bg-green-50 text-green-600'],
                ['lemari',       'ti-door',               'Lemari',        'bg-red-50 text-red-600'],
                ['custom',       'ti-pencil-ruler',       'Custom Order',  'bg-gray-50 text-gray-600'],
            ] as [$slug, $icon, $label, $color])
            <a href="{{ route('products.category', $slug) }}"
               class="bg-white rounded-2xl border border-gray-100 p-4 text-center hover:shadow-md hover:-translate-y-0.5 transition-all">
                <div class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center mx-auto mb-2">
                    <i class="ti {{ $icon }} text-xl"></i>
                </div>
                <div class="text-xs font-medium text-gray-700">{{ $label }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- PRODUK UNGGULAN --}}
<section class="py-8 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">Produk Unggulan</h2>
            <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                ['Meja Kantor Besi Minimalis 120cm', 'Besi hollow + kaca tempered', 1850000, 2200000, 'Terlaris', 'bg-[#1a2744] text-white', 'ti-layout-board'],
                ['Meja Makan Industrial 4 Kursi',   'Besi pipa + kayu jati',        3200000, null,    'Baru',     'bg-[#e8a020] text-[#1a2744]', 'ti-tools'],
                ['Kursi Besi Cafe Vintage',          'Besi tempa + busa + kain',      450000,  530000,  '-15%',     'bg-red-500 text-white',       'ti-armchair'],
                ['Rak Besi 5 Susun Serbaguna',       'Besi hollow anti karat',         680000,  750000,  null,       null,                          'ti-building-warehouse'],
            ] as [$name, $material, $price, $orig, $badge, $badgeClass, $icon])
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all">
                <div class="h-36 bg-gray-50 flex items-center justify-center relative">
                    <i class="ti {{ $icon }} text-5xl text-gray-300"></i>
                    @if($badge)
                    <span class="absolute top-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded {{ $badgeClass }}">{{ $badge }}</span>
                    @endif
                </div>
                <div class="p-3">
                    <div class="text-xs font-semibold text-gray-800 leading-tight mb-1">{{ $name }}</div>
                    <div class="text-[11px] text-gray-400 mb-2">{{ $material }}</div>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i=0;$i<5;$i++) <i class="ti ti-star text-[#e8a020] text-xs"></i>@endfor
                        <span class="text-[10px] text-gray-400">(48)</span>
                    </div>
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-sm font-bold text-red-500">Rp {{ number_format($price, 0, ',', '.') }}</span>
                        @if($orig)
                        <span class="text-[10px] text-gray-400 line-through">{{ number_format($orig, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <button class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] text-xs font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-1">
                        <i class="ti ti-shopping-cart text-sm"></i> Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- KEUNGGULAN --}}
<section class="py-12 px-4">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['ti-shield-check', 'text-blue-500',   'Garansi 1 Tahun',       'Setiap produk bergaransi penuh untuk ketenangan Anda.'],
            ['ti-truck',        'text-green-500',  'Gratis Ongkir Jogja',   'Pengiriman gratis untuk seluruh area Yogyakarta.'],
            ['ti-pencil-ruler', 'text-amber-500',  'Custom Ukuran & Warna', 'Pesan sesuai keinginan — ukuran, warna, dan desain.'],
            ['ti-clock',        'text-purple-500', 'Produksi 3–5 Hari',     'Langsung dikerjakan tim las profesional berpengalaman.'],
        ] as [$icon, $color, $title, $desc])
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <i class="ti {{ $icon }} {{ $color }} text-2xl mb-3 block"></i>
            <div class="font-semibold text-gray-800 text-sm mb-1">{{ $title }}</div>
            <div class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA CUSTOM ORDER --}}
<section class="py-12 px-4 bg-[#1a2744]">
    <div class="max-w-3xl mx-auto text-center text-white">
        <i class="ti ti-pencil-ruler text-[#e8a020] text-4xl mb-4 block"></i>
        <h2 class="text-2xl font-bold mb-3">Tidak menemukan yang Anda cari?</h2>
        <p class="text-blue-200 text-sm leading-relaxed mb-6">
            Pesan custom! Kirim ukuran, desain, atau referensi gambar — tim kami akan buatkan sesuai keinginan Anda.
        </p>
        <a href="https://wa.me/6283844029190?text=Halo%20OWL%20Store%2C%20saya%20ingin%20custom%20order"
           target="_blank"
           class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-400 text-white font-semibold px-8 py-3.5 rounded-xl transition-colors">
            <i class="ti ti-brand-whatsapp text-xl"></i>
            Konsultasi Gratis via WhatsApp
        </a>
    </div>
</section>

@endsection