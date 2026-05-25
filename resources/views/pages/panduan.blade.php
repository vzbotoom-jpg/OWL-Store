@extends('layouts.app')
@section('title', 'Panduan Pembelian — OWL Store')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">

    <h1 class="text-4xl font-bold text-[#1a2744] mb-4">Panduan Pembelian</h1>
    <p class="text-gray-600 text-lg mb-12">Ikuti langkah-langkah sederhana untuk melakukan pembelian di OWL Store</p>

    {{-- Steps --}}
    <div class="space-y-8 mb-16">

        {{-- Step 1 --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    1
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-[#1a2744] mb-3">Jelajahi Produk</h3>
                    <p class="text-gray-600 mb-4">
                        Kunjungi halaman katalog produk kami dan lihat berbagai pilihan furnitur besi premium yang tersedia.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Gunakan filter untuk menemukan produk spesifik
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Lihat detail lengkap dan spesifikasi produk
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Baca review dari pelanggan lain
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    2
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-[#1a2744] mb-3">Pilih dan Tambahkan ke Keranjang</h3>
                    <p class="text-gray-600 mb-4">
                        Setelah menemukan produk yang Anda inginkan, pilih variasi (warna, ukuran, dll) dan tentukan jumlah pembelian.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Pilih opsi yang tersedia untuk produk
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Tentukan jumlah barang yang diinginkan
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Klik tombol "Tambah ke Keranjang"
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    3
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-[#1a2744] mb-3">Daftar atau Login</h3>
                    <p class="text-gray-600 mb-4">
                        Untuk melanjutkan checkout, Anda perlu membuat akun atau login jika sudah memiliki akun.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Buat akun baru dengan email dan password
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Atau login dengan akun yang sudah ada
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Data akun akan tersimpan untuk pembelian berikutnya
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Step 4 --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    4
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-[#1a2744] mb-3">Isi Data Pengiriman</h3>
                    <p class="text-gray-600 mb-4">
                        Masukkan atau pilih alamat pengiriman dan pastikan semua data sudah benar.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Masukkan alamat lengkap penerima
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Verifikasi nomor telepon
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Pilih alamat pengiriman dari daftar yang tersedia
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Step 5 --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    5
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-[#1a2744] mb-3">Pilih Metode Pengiriman</h3>
                    <p class="text-gray-600 mb-4">
                        Pilih layanan pengiriman yang sesuai dengan kebutuhan Anda.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Berbagai pilihan kurir tersedia
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Lihat estimasi waktu dan biaya pengiriman
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Pilih opsi yang paling sesuai
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Step 6 --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    6
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-[#1a2744] mb-3">Pilih Metode Pembayaran</h3>
                    <p class="text-gray-600 mb-4">
                        Pilih metode pembayaran yang Anda inginkan untuk menyelesaikan transaksi.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Transfer bank
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Kartu kredit / debit
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> E-wallet
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> COD (Bayar di Tempat)
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Step 7 --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    7
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-[#1a2744] mb-3">Konfirmasi dan Bayar</h3>
                    <p class="text-gray-600 mb-4">
                        Periksa kembali semua detail pesanan Anda dan lanjutkan ke pembayaran.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Verifikasi semua detail pesanan
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Lihat ringkasan total biaya
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ti ti-check text-[#e8a020]"></i> Klik tombol "Bayar Sekarang"
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- FAQ Section --}}
    <div class="bg-gray-50 rounded-2xl p-8 mb-12">
        <h2 class="text-2xl font-bold text-[#1a2744] mb-6">Pertanyaan Umum</h2>
        
        <div class="space-y-4">
            <details class="bg-white rounded-lg p-4 cursor-pointer border border-gray-100">
                <summary class="font-semibold text-[#1a2744] flex items-center justify-between">
                    Apakah aman berbelanja di OWL Store?
                    <i class="ti ti-chevron-down"></i>
                </summary>
                <p class="text-gray-600 mt-3">Ya, OWL Store menggunakan enkripsi SSL dan sistem keamanan terkini untuk melindungi data pribadi dan transaksi Anda.</p>
            </details>

            <details class="bg-white rounded-lg p-4 cursor-pointer border border-gray-100">
                <summary class="font-semibold text-[#1a2744] flex items-center justify-between">
                    Berapa lama proses pengiriman?
                    <i class="ti ti-chevron-down"></i>
                </summary>
                <p class="text-gray-600 mt-3">Waktu pengiriman tergantung pada pilihan kurir dan lokasi Anda. Rata-rata 3-7 hari kerja setelah barang dikemas.</p>
            </details>

            <details class="bg-white rounded-lg p-4 cursor-pointer border border-gray-100">
                <summary class="font-semibold text-[#1a2744] flex items-center justify-between">
                    Bagaimana jika barang sampai rusak?
                    <i class="ti ti-chevron-down"></i>
                </summary>
                <p class="text-gray-600 mt-3">Hubungi tim customer service kami dengan foto bukti kerusakan. Kami akan memproses penggantian atau pengembalian dana sesuai kebijakan kami.</p>
            </details>

            <details class="bg-white rounded-lg p-4 cursor-pointer border border-gray-100">
                <summary class="font-semibold text-[#1a2744] flex items-center justify-between">
                    Apakah bisa membatalkan pesanan?
                    <i class="ti ti-chevron-down"></i>
                </summary>
                <p class="text-gray-600 mt-3">Pembatalan dapat dilakukan sebelum barang dikirim. Hubungi customer service kami untuk informasi lebih lanjut.</p>
            </details>
        </div>
    </div>

    {{-- CTA --}}
    <div class="bg-[#1a2744] rounded-2xl p-8 text-center text-white">
        <h2 class="text-2xl font-bold mb-3">Masih Punya Pertanyaan?</h2>
        <p class="text-blue-200 mb-6">
            Tim customer service kami siap membantu Anda kapan saja
        </p>
        <a href="mailto:info@owlstore.com" class="inline-block px-6 py-3 bg-[#e8a020] text-[#1a2744] font-semibold rounded-xl hover:bg-[#d4911a] transition-colors">
            <i class="ti ti-mail mr-2"></i> Hubungi Kami
        </a>
    </div>

</div>

<script>
document.querySelectorAll('details').forEach(detail => {
    detail.addEventListener('click', function(e) {
        document.querySelectorAll('details').forEach(d => {
            if (d !== this) d.removeAttribute('open');
        });
    });
});
</script>
@endsection
