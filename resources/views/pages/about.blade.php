@extends('layouts.app')
@section('title', 'Tentang Kami — OWL Store')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-16">

    {{-- Hero --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold text-[#1a2744] mb-6">
                Tentang <span class="text-[#e8a020]">OWL Store</span>
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed mb-6">
                OWL Store adalah toko online terpercaya yang menyediakan furnitur besi berkualitas premium. Kami berdedikasi untuk menghadirkan produk terbaik dengan desain modern dan fungsi optimal untuk melengkapi ruang Anda.
            </p>
            <p class="text-gray-600 text-lg leading-relaxed mb-8">
                Dengan pengalaman lebih dari satu dekade di industri furnitur, kami memahami kebutuhan pelanggan dan berkomitmen memberikan layanan terbaik.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('products.index') }}"
                   class="px-6 py-3 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold rounded-xl transition-colors">
                    Lihat Katalog
                </a>
                <a href="{{ route('home') }}"
                   class="px-6 py-3 border-2 border-[#1a2744] text-[#1a2744] hover:bg-[#1a2744] hover:text-white font-semibold rounded-xl transition-colors">
                    Beranda
                </a>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#e8a020] to-[#d4911a] rounded-2xl h-96 flex items-center justify-center">
            <i class="ti ti-building-warehouse text-white text-8xl opacity-50"></i>
        </div>
    </div>

    {{-- Values --}}
    <div class="mb-20">
        <h2 class="text-3xl font-bold text-center text-[#1a2744] mb-12">Nilai-Nilai Kami</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                <div class="w-16 h-16 bg-[#e8a020] rounded-xl flex items-center justify-center mb-6">
                    <i class="ti ti-star text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-3">Kualitas Premium</h3>
                <p class="text-gray-600">
                    Setiap produk dipilih dengan cermat menggunakan material berkualitas tinggi untuk memastikan daya tahan dan keindahan.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                <div class="w-16 h-16 bg-[#e8a020] rounded-xl flex items-center justify-center mb-6">
                    <i class="ti ti-users-group text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-3">Kepuasan Pelanggan</h3>
                <p class="text-gray-600">
                    Kepuasan Anda adalah prioritas utama kami. Tim support kami siap membantu 24/7 untuk menjawab setiap pertanyaan.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                <div class="w-16 h-16 bg-[#e8a020] rounded-xl flex items-center justify-center mb-6">
                    <i class="ti ti-heart text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-3">Inovasi Berkelanjutan</h3>
                <p class="text-gray-600">
                    Kami terus berinovasi menghadirkan desain terbaru yang mengikuti tren global tanpa mengorbankan kualitas.
                </p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="bg-[#1a2744] rounded-2xl p-12 mb-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach([
                ['1000+', 'Produk Tersedia'],
                ['50000+', 'Pelanggan Puas'],
                ['10', 'Tahun Berpengalaman'],
                ['Gratis', 'Konsultasi'],
            ] as [$number, $label])
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-[#e8a020] mb-2">{{ $number }}</div>
                <div class="text-blue-300 text-sm md:text-base">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Team --}}
    <div class="mb-20">
        <h2 class="text-3xl font-bold text-center text-[#1a2744] mb-12">Tim Kami</h2>
        
        <div class="bg-white rounded-2xl p-12 border border-gray-100 shadow-sm text-center">
            <p class="text-gray-600 text-lg mb-6">
                Tim profesional kami terdiri dari ahli furnitur, desainer, dan customer service yang berpengalaman.
            </p>
            <p class="text-gray-600 text-lg mb-8">
                Setiap anggota tim berkomitmen untuk memberikan pengalaman berbelanja terbaik kepada Anda.
            </p>
            <div class="flex justify-center gap-4">
                <a href="mailto:info@owlstore.com" class="px-6 py-2 bg-[#e8a020] text-[#1a2744] font-semibold rounded-lg hover:bg-[#d4911a] transition-colors">
                    <i class="ti ti-mail mr-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="bg-gradient-to-r from-[#1a2744] to-[#2a3d54] rounded-2xl p-12 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Siap Mempercayai Kami?</h2>
        <p class="text-blue-300 text-lg mb-8 max-w-2xl mx-auto">
            Jelajahi koleksi lengkap furnitur besi premium kami dan temukan produk yang sempurna untuk rumah impian Anda.
        </p>
        <a href="{{ route('products.index') }}"
           class="inline-block px-8 py-3 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold rounded-xl transition-colors">
            Belanja Sekarang
        </a>
    </div>

</div>
@endsection
