@extends('layouts.app')
@section('title', 'Semua Produk — OWL Store')

@section('content')

{{-- HEADER --}}
<section class="bg-[#1a2744] py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-white text-2xl font-bold mb-1">
            {{ isset($category) ? $category->name : 'Semua Produk' }}
        </h1>
        <p class="text-blue-300 text-sm">
            Furnitur besi premium buatan pengrajin las profesional Yogyakarta
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- SIDEBAR FILTER --}}
        <aside class="w-full lg:w-56 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 p-4 sticky top-24">
                <h3 class="font-semibold text-gray-800 text-sm mb-4 flex items-center gap-2">
                    <i class="ti ti-filter text-[#e8a020]"></i> Filter
                </h3>

                {{-- Kategori --}}
                <div class="mb-5">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kategori</div>
                    <ul class="space-y-1">
                        @foreach([
                            ['',            'Semua Produk'],
                            ['meja-kantor', 'Meja Kantor'],
                            ['meja-makan',  'Meja Makan'],
                            ['kursi',       'Kursi & Bangku'],
                            ['rak',         'Rak Besi'],
                            ['lemari',      'Lemari Besi'],
                            ['outdoor',     'Furnitur Outdoor'],
                            ['custom',      'Custom Order'],
                        ] as [$slug, $label])
                        <li>
                            <a href="{{ $slug ? route('products.category', $slug) : route('products.index') }}"
                               class="flex items-center gap-2 text-xs px-2 py-1.5 rounded-lg transition-colors
                               {{ request()->segment(2) === $slug ? 'bg-[#e8a020]/10 text-[#e8a020] font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="ti ti-chevron-right text-[10px]"></i>{{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Harga --}}
                <div class="mb-5">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Rentang Harga</div>
                    <div class="space-y-2">
                        @foreach([
                            ['0-500000',       'Di bawah Rp 500rb'],
                            ['500000-1000000', 'Rp 500rb – 1jt'],
                            ['1000000-3000000','Rp 1jt – 3jt'],
                            ['3000000-999999', 'Di atas Rp 3jt'],
                        ] as [$val, $label])
                        <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer hover:text-gray-800">
                            <input type="radio" name="price" value="{{ $val }}" class="text-[#e8a020]">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Urutkan --}}
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Urutkan</div>
                    <select class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 text-gray-600 outline-none">
                        <option>Terlaris</option>
                        <option>Harga Terendah</option>
                        <option>Harga Tertinggi</option>
                        <option>Terbaru</option>
                        <option>Rating Tertinggi</option>
                    </select>
                </div>
            </div>
        </aside>

        {{-- PRODUK GRID --}}
        <div class="flex-1">

            {{-- Sort bar --}}
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <span class="text-sm text-gray-500">Menampilkan <b class="text-gray-800">8 produk</b></span>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Tampilan:</span>
                    <button class="w-7 h-7 rounded border border-gray-200 flex items-center justify-center text-[#e8a020]">
                        <i class="ti ti-layout-grid text-sm"></i>
                    </button>
                    <button class="w-7 h-7 rounded border border-gray-200 flex items-center justify-center text-gray-400">
                        <i class="ti ti-layout-list text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach([
                    ['Meja Kantor Besi Minimalis 120cm', 'Besi hollow + kaca tempered', 1850000, 2200000, 'Terlaris', 'bg-[#1a2744] text-white',     'ti-layout-board',      4.9, 48],
                    ['Meja Makan Industrial 4 Kursi',    'Besi pipa + kayu jati',        3200000, null,    'Baru',     'bg-[#e8a020] text-[#1a2744]', 'ti-tools',             4.8, 23],
                    ['Kursi Besi Cafe Vintage',           'Besi tempa + busa + kain',      450000,  530000,  '-15%',    'bg-red-500 text-white',       'ti-armchair',          5.0, 61],
                    ['Rak Besi 5 Susun Serbaguna',        'Besi hollow anti karat',         680000,  750000,  null,      null,                          'ti-building-warehouse',4.7, 35],
                    ['Lemari Besi 2 Pintu',               'Besi plat + engsel premium',    1200000, null,    null,      null,                          'ti-door',              4.8, 19],
                    ['Meja Komputer Besi L-Shape',        'Besi hollow + MDF',             2100000, 2400000, 'Hot',     'bg-orange-500 text-white',    'ti-device-laptop',     4.9, 42],
                    ['Bangku Besi Panjang 150cm',         'Besi pipa + busa tebal',         380000,  null,    null,      null,                          'ti-align-center',      4.6, 28],
                    ['Rak Dapur Besi Stainless',          'Stainless steel food grade',     890000,  1000000, null,      null,                          'ti-building-warehouse',4.8, 31],
                ] as [$name, $material, $price, $orig, $badge, $badgeClass, $icon, $rating, $reviews])
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all group">
                    <div class="h-36 bg-gray-50 flex items-center justify-center relative">
                        <i class="ti {{ $icon }} text-5xl text-gray-200 group-hover:text-gray-300 transition-colors"></i>
                        @if($badge)
                        <span class="absolute top-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded {{ $badgeClass }}">{{ $badge }}</span>
                        @endif
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:text-red-400 transition-colors">
                            <i class="ti ti-heart text-gray-400 text-sm"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <div class="text-xs font-semibold text-gray-800 leading-tight mb-1 line-clamp-2">{{ $name }}</div>
                        <div class="text-[11px] text-gray-400 mb-1">{{ $material }}</div>
                        <div class="flex items-center gap-1 mb-2">
                            <i class="ti ti-star text-[#e8a020] text-xs"></i>
                            <span class="text-[11px] font-medium text-gray-700">{{ $rating }}</span>
                            <span class="text-[10px] text-gray-400">({{ $reviews }})</span>
                        </div>
                        <div class="flex items-baseline gap-1 mb-2">
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

            {{-- Pagination --}}
            <div class="flex items-center justify-center gap-2 mt-8">
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:border-[#e8a020] hover:text-[#e8a020] transition-colors">
                    <i class="ti ti-chevron-left text-sm"></i>
                </button>
                @foreach([1,2,3,4,5] as $p)
                <button class="w-8 h-8 rounded-lg border text-xs font-medium transition-colors
                    {{ $p === 1 ? 'bg-[#e8a020] border-[#e8a020] text-[#1a2744]' : 'border-gray-200 text-gray-600 hover:border-[#e8a020] hover:text-[#e8a020]' }}">
                    {{ $p }}
                </button>
                @endforeach
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:border-[#e8a020] hover:text-[#e8a020] transition-colors">
                    <i class="ti ti-chevron-right text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection