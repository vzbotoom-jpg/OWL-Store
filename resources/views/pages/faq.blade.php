@extends('layouts.app')
@section('title', 'FAQ — OWL Store')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-r from-[#1a2744] to-[#232f3e] py-12 px-4">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Frequently Asked Questions</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">
            Temukan jawaban atas pertanyaan yang sering diajukan tentang OWL Store
        </p>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 py-12">

    {{-- Search FAQ --}}
    <div class="relative mb-8">
        <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="faqSearch" placeholder="Cari pertanyaan..."
               class="w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020]">
    </div>

    {{-- FAQ Categories --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <button class="faq-cat-btn active px-4 py-2 text-sm rounded-full bg-[#e8a020] text-[#1a2744] font-semibold" data-cat="all">Semua</button>
        <button class="faq-cat-btn px-4 py-2 text-sm rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" data-cat="order">Pesanan</button>
        <button class="faq-cat-btn px-4 py-2 text-sm rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" data-cat="payment">Pembayaran</button>
        <button class="faq-cat-btn px-4 py-2 text-sm rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" data-cat="shipping">Pengiriman</button>
        <button class="faq-cat-btn px-4 py-2 text-sm rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" data-cat="product">Produk</button>
        <button class="faq-cat-btn px-4 py-2 text-sm rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" data-cat="return">Pengembalian</button>
    </div>

    {{-- FAQ List --}}
    <div class="space-y-4" x-data="{ openFaq: null }">
        
        {{-- Order Category --}}
        <div class="faq-item" data-cat="order">
            @foreach([
                ['Bagaimana cara memesan produk di OWL Store?', 'Anda dapat memesan produk dengan mengunjungi halaman produk, pilih variasi dan jumlah, lalu klik "Tambah ke Keranjang". Setelah itu, lanjutkan ke checkout dan ikuti instruksi pembayaran.'],
                ['Apakah harus memiliki akun untuk berbelanja?', 'Ya, Anda perlu membuat akun untuk berbelanja di OWL Store. Pendaftaran gratis dan mudah, hanya memerlukan email dan password.'],
                ['Bagaimana cara melacak pesanan saya?', 'Setelah pesanan diproses, Anda akan menerima nomor resi. Lacak pesanan melalui menu "Pesanan Saya" di dashboard akun Anda.'],
                ['Bisakah saya membatalkan pesanan?', 'Pembatalan dapat dilakukan sebelum pesanan diproses. Hubungi customer service kami segera jika ingin membatalkan pesanan.'],
            ] as $i => [$question, $answer])
            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                <button @click="openFaq = openFaq === 'order_{{ $i }}' ? null : 'order_{{ $i }}'"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-gray-800">{{ $question }}</span>
                    <i class="ti ti-chevron-down text-gray-400 transition-transform" :class="openFaq === 'order_{{ $i }}' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openFaq === 'order_{{ $i }}'" x-collapse class="px-5 pb-5">
                    <p class="text-gray-600 text-sm">{{ $answer }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Payment Category --}}
        <div class="faq-item" data-cat="payment">
            @foreach([
                ['Metode pembayaran apa saja yang tersedia?', 'Kami menerima pembayaran via Transfer Bank (BCA, Mandiri, BRI, BNI), QRIS, GoPay, dan COD (terbatas area Yogyakarta).'],
                ['Apakah ada biaya admin untuk pembayaran?', 'Tidak ada biaya admin tambahan dari OWL Store. Namun, beberapa metode pembayaran mungkin dikenakan biaya dari penyedia jasa.'],
                ['Berapa lama konfirmasi pembayaran?', 'Konfirmasi pembayaran akan diproses dalam 1x24 jam setelah Anda mengupload bukti transfer.'],
                ['Apa yang terjadi jika saya telat membayar?', 'Pesanan akan otomatis dibatalkan jika pembayaran tidak dilakukan dalam batas waktu yang ditentukan (biasanya 1x24 jam).'],
            ] as $i => [$question, $answer])
            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                <button @click="openFaq = openFaq === 'payment_{{ $i }}' ? null : 'payment_{{ $i }}'"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-gray-800">{{ $question }}</span>
                    <i class="ti ti-chevron-down text-gray-400 transition-transform" :class="openFaq === 'payment_{{ $i }}' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openFaq === 'payment_{{ $i }}'" x-collapse class="px-5 pb-5">
                    <p class="text-gray-600 text-sm">{{ $answer }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Shipping Category --}}
        <div class="faq-item" data-cat="shipping">
            @foreach([
                ['Berapa biaya pengiriman?', 'Biaya pengiriman tergantung berat produk dan lokasi tujuan. Untuk area Yogyakarta, kami memberikan gratis ongkir. Gunakan kalkulator ongkir di halaman checkout untuk estimasi.'],
                ['Berapa lama waktu pengiriman?', 'Waktu pengiriman bervariasi tergantung lokasi: Area Yogyakarta: 1-2 hari, Pulau Jawa: 2-4 hari, Luar Jawa: 5-7 hari.'],
                ['Apakah produk dijamin aman selama pengiriman?', 'Ya, kami menggunakan packing yang aman dan bekerja sama dengan kurir terpercaya. Jika produk rusak dalam perjalanan, kami akan mengganti.'],
                ['Bagaimana cara melacak pengiriman?', 'Anda akan mendapatkan nomor resi melalui email dan WhatsApp. Gunakan nomor tersebut untuk melacak di website kurir terkait.'],
            ] as $i => [$question, $answer])
            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                <button @click="openFaq = openFaq === 'shipping_{{ $i }}' ? null : 'shipping_{{ $i }}'"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-gray-800">{{ $question }}</span>
                    <i class="ti ti-chevron-down text-gray-400 transition-transform" :class="openFaq === 'shipping_{{ $i }}' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openFaq === 'shipping_{{ $i }}'" x-collapse class="px-5 pb-5">
                    <p class="text-gray-600 text-sm">{{ $answer }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Product Category --}}
        <div class="faq-item" data-cat="product">
            @foreach([
                ['Apakah produk bisa custom?', 'Ya, kami menerima custom order untuk semua produk. Anda dapat memilih ukuran, warna, dan desain sesuai keinginan. Konsultasikan kebutuhan Anda via WhatsApp.'],
                ['Bahan apa yang digunakan untuk produk?', 'Kami menggunakan besi hollow berkualitas tinggi dengan finishing cat powder coating anti karat. Material yang digunakan tahan lama dan aman.'],
                ['Apakah produk sudah termasuk ongkos kirim?', 'Untuk area Yogyakarta, kami memberikan gratis ongkir. Untuk luar kota, ongkir dihitung terpisah sesuai berat dan lokasi.'],
                ['Bagaimana cara merawat produk besi?', 'Bersihkan secara rutin dengan kain lembab. Hindari paparan air berlebih. Untuk menjaga cat, hindari gesekan benda tajam.'],
            ] as $i => [$question, $answer])
            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                <button @click="openFaq = openFaq === 'product_{{ $i }}' ? null : 'product_{{ $i }}'"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-gray-800">{{ $question }}</span>
                    <i class="ti ti-chevron-down text-gray-400 transition-transform" :class="openFaq === 'product_{{ $i }}' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openFaq === 'product_{{ $i }}'" x-collapse class="px-5 pb-5">
                    <p class="text-gray-600 text-sm">{{ $answer }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Return Category --}}
        <div class="faq-item" data-cat="return">
            @foreach([
                ['Apakah bisa mengembalikan produk?', 'Ya, Anda dapat mengembalikan produk dalam waktu 7 hari setelah barang diterima jika terdapat cacat produksi atau kerusakan.'],
                ['Bagaimana prosedur pengembalian?', 'Hubungi customer service kami untuk melaporkan kerusakan dengan foto bukti. Kami akan memproses penggantian atau pengembalian dana.'],
                ['Apakah ada biaya untuk pengembalian?', 'Tidak ada biaya untuk pengembalian jika produk rusak karena cacat produksi. Untuk alasan lain, biaya pengiriman ditanggung pembeli.'],
                ['Berapa lama proses refund?', 'Refund akan diproses dalam 3-7 hari kerja setelah barang kembali ke gudang kami.'],
            ] as $i => [$question, $answer])
            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                <button @click="openFaq = openFaq === 'return_{{ $i }}' ? null : 'return_{{ $i }}'"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-gray-800">{{ $question }}</span>
                    <i class="ti ti-chevron-down text-gray-400 transition-transform" :class="openFaq === 'return_{{ $i }}' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openFaq === 'return_{{ $i }}'" x-collapse class="px-5 pb-5">
                    <p class="text-gray-600 text-sm">{{ $answer }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- Still Have Questions --}}
    <div class="mt-12 bg-gradient-to-r from-[#1a2744] to-[#232f3e] rounded-2xl p-8 text-center text-white">
        <h3 class="text-xl font-bold mb-2">Masih Punya Pertanyaan?</h3>
        <p class="text-blue-200 mb-6">Tim customer service kami siap membantu Anda</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-6 py-2.5 rounded-xl transition-colors">
                <i class="ti ti-mail"></i> Hubungi Kami
            </a>
            <a href="https://wa.me/6283844029190" target="_blank"
               class="inline-flex items-center gap-2 border border-white/30 hover:border-white text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                <i class="ti ti-brand-whatsapp"></i> Chat WhatsApp
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filter FAQ by category
    const searchInput = document.getElementById('faqSearch');
    const categoryBtns = document.querySelectorAll('.faq-cat-btn');
    const faqItems = document.querySelectorAll('.faq-item');
    
    let activeCategory = 'all';
    
    function filterFAQ() {
        const searchTerm = searchInput.value.toLowerCase();
        
        faqItems.forEach(item => {
            const itemCategory = item.dataset.cat;
            let showByCategory = activeCategory === 'all' || itemCategory === activeCategory;
            
            if (!showByCategory) {
                item.style.display = 'none';
                return;
            }
            
            if (searchTerm) {
                const questions = item.querySelectorAll('button span');
                let hasMatch = false;
                questions.forEach(q => {
                    if (q.textContent.toLowerCase().includes(searchTerm)) {
                        hasMatch = true;
                    }
                });
                item.style.display = hasMatch ? 'block' : 'none';
            } else {
                item.style.display = 'block';
            }
        });
    }
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => {
                b.classList.remove('bg-[#e8a020]', 'text-[#1a2744]', 'font-semibold');
                b.classList.add('bg-gray-100', 'text-gray-600');
            });
            this.classList.remove('bg-gray-100', 'text-gray-600');
            this.classList.add('bg-[#e8a020]', 'text-[#1a2744]', 'font-semibold');
            
            activeCategory = this.dataset.cat;
            filterFAQ();
        });
    });
    
    searchInput.addEventListener('input', filterFAQ);
</script>
@endpush
@endsection