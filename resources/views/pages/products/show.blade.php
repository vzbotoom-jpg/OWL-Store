@extends('layouts.app')
@section('title', 'Meja Kantor Besi Minimalis — OWL Store')

@section('content')

{{-- BREADCRUMB --}}
<div class="bg-[#232f3e] py-2 px-4">
    <div class="max-w-7xl mx-auto flex items-center gap-2 text-xs text-blue-300">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Produk</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <a href="{{ route('products.category', 'meja-kantor') }}" class="hover:text-white transition-colors">Meja Kantor</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-white">Meja Kantor Besi Minimalis 120cm</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        {{-- GALERI --}}
        <div>
            <div class="bg-gray-50 rounded-2xl border border-gray-100 h-72 flex items-center justify-center mb-3 relative">
                <i class="ti ti-layout-board text-8xl text-gray-200"></i>
                <span class="absolute bottom-3 right-3 bg-black/40 text-white text-[10px] px-2 py-1 rounded flex items-center gap-1">
                    <i class="ti ti-zoom-in text-xs"></i> Perbesar
                </span>
            </div>
            <div class="grid grid-cols-4 gap-2">
                @foreach(['ti-layout-board','ti-layout-sidebar','ti-ruler-measure','ti-palette'] as $thumb)
                <div class="bg-gray-50 rounded-xl border-2 border-gray-100 h-16 flex items-center justify-center cursor-pointer hover:border-[#e8a020] transition-colors first:border-[#e8a020]">
                    <i class="ti {{ $thumb }} text-2xl text-gray-300"></i>
                </div>
                @endforeach
            </div>
        </div>

        {{-- INFO PRODUK --}}
        <div>
            {{-- Toko --}}
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 bg-[#1a2744] rounded-full flex items-center justify-center text-[#e8a020] text-xs font-bold">OW</div>
                <span class="text-xs font-medium text-gray-700">OptimaWeld Official</span>
                <span class="bg-amber-50 text-amber-600 text-[10px] font-semibold px-2 py-0.5 rounded">
                    <i class="ti ti-shield-check text-[10px]"></i> Toko Resmi
                </span>
            </div>

            <h1 class="text-lg font-bold text-gray-800 leading-tight mb-3">
                Meja Kantor Besi Minimalis 120×60cm — Rangka Hollow + Kaca Tempered 8mm
            </h1>

            {{-- Rating --}}
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <div class="flex items-center gap-1">
                    @for($i=0;$i<5;$i++)<i class="ti ti-star text-[#e8a020] text-sm"></i>@endfor
                    <span class="text-sm font-semibold text-[#e8a020] ml-1">4.9</span>
                </div>
                <span class="text-xs text-gray-500">48 ulasan</span>
                <span class="text-xs text-gray-400 border-l border-gray-200 pl-3">312 terjual</span>
                <span class="text-xs text-gray-400 border-l border-gray-200 pl-3">
                    <i class="ti ti-heart text-red-400 text-xs"></i> 127 wishlist
                </span>
            </div>

            {{-- Harga --}}
            <div class="bg-amber-50 rounded-xl p-4 mb-4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs text-gray-400 line-through">Rp 2.200.000</span>
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">-16%</span>
                </div>
                <div class="text-2xl font-bold text-red-500">Rp 1.850.000</div>
                <div class="text-xs text-amber-600 mt-1">
                    <i class="ti ti-truck text-xs"></i> Gratis ongkir area Yogyakarta
                </div>
            </div>

            {{-- Pilihan Finishing --}}
            <div class="mb-4">
                <div class="text-xs font-semibold text-gray-500 mb-2">Pilih Finishing Cat:</div>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Hitam Doff','Putih Susu','Abu-abu','Custom Warna'] as $i => $opt)
                    <button class="border text-xs px-3 py-1.5 rounded-lg transition-colors
                        {{ $i === 0 ? 'border-[#e8a020] bg-amber-50 text-amber-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-[#e8a020]' }}">
                        {{ $opt }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Pilihan Ukuran --}}
            <div class="mb-4">
                <div class="text-xs font-semibold text-gray-500 mb-2">Pilih Ukuran:</div>
                <div class="flex flex-wrap gap-2">
                    @foreach(['100×50cm','120×60cm','140×70cm','160×80cm'] as $i => $size)
                    <button class="border text-xs px-3 py-1.5 rounded-lg transition-colors
                        {{ $i === 1 ? 'border-[#e8a020] bg-amber-50 text-amber-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-[#e8a020]' }}">
                        {{ $size }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Jumlah --}}
            <div class="flex items-center gap-3 mb-5">
                <span class="text-xs font-semibold text-gray-500">Jumlah:</span>
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-8 h-8 bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors">−</button>
                    <span class="w-10 text-center text-sm font-medium text-gray-800 border-x border-gray-200">1</span>
                    <button class="w-8 h-8 bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors">+</button>
                </div>
                <span class="text-xs text-gray-400">Stok: 8 unit</span>
            </div>

            {{-- Tombol aksi --}}
            <div class="grid grid-cols-2 gap-3 mb-3">
                <button class="border-2 border-[#e8a020] text-[#e8a020] font-semibold text-sm py-3 rounded-xl hover:bg-amber-50 transition-colors flex items-center justify-center gap-2">
                    <i class="ti ti-shopping-cart"></i> Keranjang
                </button>
                <button class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold text-sm py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="ti ti-bolt"></i> Beli Sekarang
                </button>
            </div>
            <a href="https://wa.me/6283844029190?text=Halo%2C%20saya%20ingin%20tanya%20produk%20Meja%20Kantor%20Besi%20Minimalis"
               target="_blank"
               class="w-full bg-[#1a2744] hover:bg-[#232f3e] text-white text-sm font-medium py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                <i class="ti ti-brand-whatsapp text-green-400"></i> Konsultasi via WhatsApp
            </a>

            {{-- Garansi strip --}}
            <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-gray-100">
                @foreach(['ti-shield-check:Garansi 1 Tahun','ti-refresh:Retur 7 Hari','ti-clock:Produksi 3–5 Hari','ti-lock:Bayar Aman'] as $item)
                @php [$ico, $lbl] = explode(':', $item) @endphp
                <div class="flex items-center gap-1 text-[11px] text-gray-500">
                    <i class="ti {{ $ico }} text-[#e8a020] text-sm"></i> {{ $lbl }}
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SPESIFIKASI --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h2 class="text-base font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">Spesifikasi Produk</h2>
        <table class="w-full text-sm">
            @foreach([
                ['Material Rangka',    'Besi hollow 4×4cm, tebal 1.8mm — anti karat'],
                ['Material Permukaan', 'Kaca tempered 8mm'],
                ['Ukuran Standar',     '120cm × 60cm × 75cm (P×L×T)'],
                ['Finishing',          'Cat powder coating — Hitam Doff / Putih Susu / Abu-abu / Custom'],
                ['Kapasitas Beban',    'Maks. 80 kg'],
                ['Berat Produk',       '±18 kg'],
                ['Garansi',            '1 tahun garansi konstruksi (las & rangka)'],
                ['Waktu Produksi',     '3–5 hari kerja setelah pembayaran'],
                ['Pengiriman',         'Gratis area Yogyakarta · Kargo untuk luar kota'],
            ] as $i => [$key, $val])
            <tr class="{{ $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                <td class="py-2.5 px-3 text-gray-500 font-medium w-2/5">{{ $key }}</td>
                <td class="py-2.5 px-3 text-gray-800">{{ $val }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    {{-- ULASAN --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-6 pb-3 border-b border-gray-100">Ulasan Pembeli</h2>

        {{-- Summary --}}
        <div class="flex gap-8 items-center mb-6">
            <div class="text-center">
                <div class="text-5xl font-bold text-gray-800">4.9</div>
                <div class="flex justify-center gap-0.5 my-2">
                    @for($i=0;$i<5;$i++)<i class="ti ti-star text-[#e8a020] text-lg"></i>@endfor
                </div>
                <div class="text-xs text-gray-400">dari 48 ulasan</div>
            </div>
            <div class="flex-1 space-y-1.5">
                @foreach([[5,90],[4,8],[3,2],[2,0],[1,0]] as [$star, $pct])
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 w-4 text-right">{{ $star }}</span>
                    <i class="ti ti-star text-[#e8a020] text-xs"></i>
                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                        <div class="bg-[#e8a020] h-2 rounded-full" style="width:{{ $pct }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400 w-5">{{ round(48 * $pct / 100) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Filter --}}
        <div class="flex gap-2 flex-wrap mb-5">
            @foreach(['Semua (48)','5 Bintang (43)','4 Bintang (4)','Ada Foto','Hitam Doff','120×60cm'] as $i => $f)
            <button class="text-xs px-3 py-1.5 rounded-full border transition-colors
                {{ $i === 0 ? 'bg-[#1a2744] text-white border-[#1a2744]' : 'border-gray-200 text-gray-600 hover:border-[#1a2744]' }}">
                {{ $f }}
            </button>
            @endforeach
        </div>

        {{-- Review cards --}}
        <div class="space-y-4">
            @foreach([
                ['Ahmad H.',  'AH', '#1a6ea8', '12 Mei 2026',   'Yogyakarta', 5, 'Hitam Doff · 120×60cm',
                 'Kualitasnya luar biasa! Lasannya rapi, tidak ada sisi tajam. Kaca terpasang sempurna dan kokoh. Sudah 2 bulan dipakai kerja setiap hari, tidak ada masalah. Pengiriman tepat waktu, dikemas dengan bubble wrap tebal. Sangat rekomendasikan!', 12],
                ['Siti R.',   'SR', '#1a6744', '3 April 2026',  'Sleman',     5, 'Putih Susu · Custom 140×70cm',
                 'Pesan custom ukuran 140×70cm untuk kantor. Responsnya cepat, koordinasi via WhatsApp sangat mudah. Hasil akhirnya memuaskan, persis seperti yang diminta. Teman-teman kantor pada nanya beli di mana! Pasti balik lagi.', 8],
                ['Dani P.',   'DP', '#8a3a1a', '18 Maret 2026', 'Bantul',     4, 'Abu-abu · 120×60cm',
                 'Overall bagus dan kokoh. Kurangi 1 bintang karena pengiriman telat 1 hari dari estimasi. Tapi kualitas produknya sendiri tidak ada komplain, lasannya rapi dan finishing catnya merata. Harga worth it untuk kualitas ini.', 5],
            ] as [$name, $initials, $color, $date, $city, $stars, $variant, $text, $helpful])
            <div class="border border-gray-100 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background:{{ $color }}">{{ $initials }}</div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-800">{{ $name }}</div>
                        <div class="text-[11px] text-gray-400">{{ $date }} · {{ $city }}</div>
                    </div>
                    <div class="flex gap-0.5">
                        @for($i=0;$i<5;$i++)
                        <i class="ti ti-star text-xs {{ $i < $stars ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                </div>
                <div class="text-[11px] text-gray-400 mb-2">Varian: {{ $variant }}</div>
                <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $text }}</p>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                    <span class="text-[11px] text-gray-400">Membantu?</span>
                    <button class="text-[11px] border border-gray-200 rounded px-2 py-0.5 text-gray-500 hover:border-[#e8a020] hover:text-[#e8a020] transition-colors flex items-center gap-1">
                        <i class="ti ti-thumb-up text-xs"></i> Ya ({{ $helpful }})
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <button class="border border-gray-200 text-gray-600 text-sm px-6 py-2 rounded-xl hover:border-[#1a2744] hover:text-[#1a2744] transition-colors">
                Lihat semua 48 ulasan <i class="ti ti-arrow-right text-sm"></i>
            </button>
        </div>
    </div>
</div>

@endsection