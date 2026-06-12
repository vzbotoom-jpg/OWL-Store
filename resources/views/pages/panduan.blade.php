@extends('layouts.app')
@section('title', 'Panduan Belanja — OWL Store')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-r from-[#1a2744] to-[#232f3e] py-12 px-4">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Panduan Belanja</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">
            Mudah! Ikuti langkah-langkah berikut untuk berbelanja di OWL Store
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-12">

    {{-- Steps --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        
        {{-- Step 1 --}}
        <div class="flex gap-5">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-xl">1</div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-2">Jelajahi Produk</h3>
                <p class="text-gray-600 mb-3">Kunjungi halaman katalog produk kami dan lihat berbagai pilihan furnitur besi premium yang tersedia.</p>
                <ul class="space-y-1 text-sm text-gray-500">
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Gunakan filter untuk mencari produk spesifik</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Lihat detail lengkap dan spesifikasi produk</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Baca review dari pelanggan lain</li>
                </ul>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="flex gap-5">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-xl">2</div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-2">Tambah ke Keranjang</h3>
                <p class="text-gray-600 mb-3">Pilih variasi (warna, ukuran, dll) dan tentukan jumlah yang diinginkan.</p>
                <ul class="space-y-1 text-sm text-gray-500">
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Pilih opsi yang tersedia untuk produk</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Tentukan jumlah barang yang diinginkan</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Klik tombol "Tambah ke Keranjang"</li>
                </ul>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="flex gap-5">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-xl">3</div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-2">Login atau Daftar</h3>
                <p class="text-gray-600 mb-3">Untuk melanjutkan checkout, Anda perlu login atau membuat akun baru.</p>
                <ul class="space-y-1 text-sm text-gray-500">
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Buat akun baru dengan email dan password</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Atau login dengan akun yang sudah ada</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Data akan tersimpan untuk pembelian berikutnya</li>
                </ul>
            </div>
        </div>

        {{-- Step 4 --}}
        <div class="flex gap-5">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-xl">4</div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-2">Checkout</h3>
                <p class="text-gray-600 mb-3">Masukkan alamat pengiriman dan pilih metode pembayaran.</p>
                <ul class="space-y-1 text-sm text-gray-500">
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Isi alamat lengkap penerima</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Pilih metode pengiriman yang diinginkan</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Pilih metode pembayaran (Transfer Bank, QRIS, COD)</li>
                </ul>
            </div>
        </div>

        {{-- Step 5 --}}
        <div class="flex gap-5">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-xl">5</div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-2">Pembayaran</h3>
                <p class="text-gray-600 mb-3">Lakukan pembayaran sesuai instruksi yang diberikan.</p>
                <ul class="space-y-1 text-sm text-gray-500">
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Transfer ke rekening bank yang tersedia</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Scan QRIS untuk pembayaran instan</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Upload bukti transfer untuk konfirmasi</li>
                </ul>
            </div>
        </div>

        {{-- Step 6 --}}
        <div class="flex gap-5">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-xl">6</div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#1a2744] mb-2">Produksi & Pengiriman</h3>
                <p class="text-gray-600 mb-3">Pesanan akan diproses dan dikirim ke alamat Anda.</p>
                <ul class="space-y-1 text-sm text-gray-500">
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Produksi custom: 3-5 hari kerja</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Pengiriman 2-7 hari tergantung lokasi</li>
                    <li class="flex items-center gap-2"><i class="ti ti-check text-[#e8a020]"></i> Lacak pesanan melalui dashboard</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Info Boxes --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div class="bg-blue-50 rounded-2xl p-6 text-center">
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-clock text-blue-600 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 mb-2">Jam Operasional CS</h3>
            <p class="text-gray-600">Senin - Sabtu: 08.00 - 17.00</p>
            <p class="text-gray-400 text-sm">Minggu & Hari Libur: Tutup</p>
        </div>

        <div class="bg-green-50 rounded-2xl p-6 text-center">
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-brand-whatsapp text-green-600 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 mb-2">Butuh Bantuan?</h3>
            <p class="text-gray-600 mb-3">Hubungi customer service kami</p>
            <a href="https://wa.me/6283844029190" target="_blank" 
               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl transition-colors text-sm">
                <i class="ti ti-brand-whatsapp"></i> Chat WhatsApp
            </a>
        </div>

        <div class="bg-amber-50 rounded-2xl p-6 text-center">
            <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-currency-dollar text-amber-600 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 mb-2">Garansi & Retur</h3>
            <p class="text-gray-600">Garansi 1 tahun untuk semua produk</p>
            <p class="text-gray-400 text-sm">Retur 7 hari jika ada kerusakan</p>
        </div>
    </div>

    {{-- FAQ Section --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-[#1a2744] mb-6 text-center">Pertanyaan Umum</h2>
        
        <div class="space-y-4" x-data="{ openFaq: null }">
            @foreach([
                ['Apakah produk bisa custom ukuran?', 'Ya, semua produk kami bisa custom sesuai ukuran dan warna yang Anda inginkan. Silakan konsultasikan kebutuhan Anda via WhatsApp untuk info lebih lanjut.'],
                ['Berapa lama waktu produksi untuk custom order?', 'Waktu produksi custom order biasanya 3-5 hari kerja tergantung tingkat kerumitan pesanan.'],
                ['Apakah bisa retur jika produk rusak?', 'Tentu. Kami memberikan garansi 1 tahun untuk semua produk. Jika produk rusak karena cacat produksi, kami akan mengganti atau memperbaikinya.'],
                ['Bagaimana cara melacak pesanan?', 'Setelah pesanan dikirim, Anda akan menerima nomor resi. Lacak pesanan melalui menu "Pesanan Saya" di dashboard akun Anda.'],
                ['Apakah ada gratis ongkir?', 'Ya, kami memberikan gratis ongkir untuk area Yogyakarta dan sekitarnya. Untuk luar kota, ongkir akan dihitung sesuai berat dan lokasi.'],
                ['Metode pembayaran apa saja yang tersedia?', 'Kami menerima pembayaran via Transfer Bank (BCA, Mandiri, BRI, BNI), QRIS, GoPay, dan COD (terbatas area Yogyakarta).']
            ] as $i => [$question, $answer])
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button @click="openFaq = openFaq === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-gray-800">{{ $question }}</span>
                    <i class="ti ti-chevron-down text-gray-400 transition-transform" :class="openFaq === {{ $i }} ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openFaq === {{ $i }}" x-collapse class="px-5 pb-5">
                    <p class="text-gray-600 text-sm">{{ $answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection