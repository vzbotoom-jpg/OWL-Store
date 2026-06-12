@extends('layouts.app')
@section('title', 'Tentang Kami — OWL Store')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-r from-[#1a2744] to-[#232f3e] py-20 px-4 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 bg-[#e8a020] rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-[#e8a020] rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <div class="inline-block bg-[#e8a020]/20 rounded-full px-4 py-1 mb-6">
            <span class="text-[#e8a020] text-sm font-semibold">✦ Tentang Kami</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Membawa <span class="text-[#e8a020]">Kualitas Premium</span><br>
            ke Setiap Ruangan Anda
        </h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">
            OWL Store hadir sebagai solusi furnitur besi berkualitas tinggi dengan desain modern dan harga terjangkau.
        </p>
    </div>
</section>

{{-- Story Section --}}
<section class="py-16 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-block bg-[#e8a020]/10 rounded-full px-3 py-1 mb-4">
                    <span class="text-[#e8a020] text-xs font-semibold">Our Story</span>
                </div>
                <h2 class="text-3xl font-bold text-[#1a2744] mb-6">Perjalanan OWL Store</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Berawal dari bengkel las kecil di Yogyakarta pada tahun 2015, OptimaWeld tumbuh menjadi 
                    salah satu produsen furnitur besi terpercaya di Indonesia. Kami memulai dengan tekad 
                    untuk menghadirkan produk berkualitas tinggi dengan harga yang terjangkau.
                </p>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Pada tahun 2024, kami meluncurkan OWL Store sebagai platform digital untuk menjangkau 
                    lebih banyak pelanggan di seluruh Indonesia. Setiap produk kami dibuat dengan cermat 
                    oleh pengrajin las profesional yang berpengalaman puluhan tahun.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Hari ini, OWL Store telah melayani ribuan pelanggan dari berbagai kota di Indonesia, 
                    dari rumah tinggal hingga perkantoran dan industri.
                </p>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-[#e8a020] to-[#d4911a] rounded-3xl p-3">
                    <div class="bg-white rounded-2xl overflow-hidden">
                        <img src="https://placehold.co/600x400/1a2744/e8a020?text=OWL+Store+Workshop" 
                             alt="OWL Store Workshop"
                             class="w-full h-80 object-cover">
                    </div>
                </div>
                <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl shadow-xl p-4 hidden lg:block">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-[#e8a020] rounded-full flex items-center justify-center">
                            <i class="ti ti-flame text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-[#1a2744]">10+</div>
                            <div class="text-xs text-gray-500">Tahun Pengalaman</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Values Section --}}
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <div class="inline-block bg-[#e8a020]/10 rounded-full px-3 py-1 mb-4">
                <span class="text-[#e8a020] text-xs font-semibold">Nilai Kami</span>
            </div>
            <h2 class="text-3xl font-bold text-[#1a2744] mb-4">Yang Membuat Kami Berbeda</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Kami berkomitmen untuk memberikan yang terbaik dalam setiap aspek bisnis kami
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                [
                    'ti-star',
                    'Kualitas Premium',
                    'Setiap produk melalui kontrol kualitas ketat sebelum dikirim ke pelanggan',
                    'bg-orange-100 text-orange-600'
                ],
                [
                    'ti-users-group',
                    'Kepuasan Pelanggan',
                    'Layanan pelanggan 24/7 siap membantu Anda dengan ramah dan profesional',
                    'bg-blue-100 text-blue-600'
                ],
                [
                    'ti-heart',
                    'Inovasi Berkelanjutan',
                    'Terus berinovasi menghadirkan desain terbaru mengikuti tren global',
                    'bg-red-100 text-red-600'
                ],
                [
                    'ti-shield-check',
                    'Garansi Terjamin',
                    'Garansi 1 tahun untuk setiap produk yang Anda beli',
                    'bg-green-100 text-green-600'
                ],
                [
                    'ti-truck',
                    'Pengiriman Cepat',
                    'Gratis ongkir untuk area Yogyakarta dan sekitarnya',
                    'bg-purple-100 text-purple-600'
                ],
                [
                    'ti-recycle',
                    'Ramah Lingkungan',
                    'Menggunakan material ramah lingkungan dan proses produksi berkelanjutan',
                    'bg-teal-100 text-teal-600'
                ]
            ] as [$icon, $title, $desc, $color])
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                <div class="w-14 h-14 {{ $color }} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="ti {{ $icon }} text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="py-16 px-4 bg-[#1a2744]">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach([
                ['1,000+', 'Produk Terjual', 'ti-package'],
                ['500+', 'Pelanggan Puas', 'ti-users'],
                ['10+', 'Tahun Berpengalaman', 'ti-clock'],
                ['100%', 'Kepuasan Terjamin', 'ti-shield-check']
            ] as [$number, $label, $icon])
            <div class="text-center">
                <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti {{ $icon }} text-[#e8a020] text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-white mb-1">{{ $number }}</div>
                <div class="text-blue-300 text-sm">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Team Section --}}
<section class="py-16 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <div class="inline-block bg-[#e8a020]/10 rounded-full px-3 py-1 mb-4">
                <span class="text-[#e8a020] text-xs font-semibold">Tim Kami</span>
            </div>
            <h2 class="text-3xl font-bold text-[#1a2744] mb-4">Di Balik OWL Store</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Tim profesional yang berdedikasi memberikan yang terbaik untuk Anda
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['Ahmad Fauzi', 'Founder & CEO', 'ti-user', 'Memimpin dengan visi membawa furnitur besi berkualitas ke seluruh Indonesia'],
                ['Siti Nurjanah', 'Head of Design', 'ti-palette', 'Kreator di balik desain-desain modern dan fungsional'],
                ['Budi Santoso', 'Production Manager', 'ti-settings', 'Memastikan setiap produk berkualitas sebelum dikirim'],
                ['Dewi Lestari', 'Customer Service', 'ti-headset', 'Siap membantu Anda dengan ramah dan cepat'],
                ['Rizki Pratama', 'Quality Control', 'ti-shield-check', 'Menjamin kualitas produk yang konsisten'],
                ['Maya Sari', 'Marketing', 'ti-chart-line', 'Menghubungkan produk kami dengan pelanggan']
            ] as [$name, $role, $icon, $desc])
            <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all">
                <div class="w-24 h-24 bg-[#1a2744] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti {{ $icon }} text-[#e8a020] text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">{{ $name }}</h3>
                <p class="text-[#e8a020] text-sm font-semibold mb-3">{{ $role }}</p>
                <p class="text-gray-500 text-sm">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-16 px-4 bg-gradient-to-r from-[#1a2744] to-[#232f3e]">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Siap Mempercayai Kami?</h2>
        <p class="text-blue-200 mb-8">
            Jelajahi koleksi lengkap furnitur besi premium kami dan temukan produk yang sempurna untuk rumah impian Anda.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-8 py-3.5 rounded-xl transition-all">
                <i class="ti ti-shopping-bag"></i> Belanja Sekarang
            </a>
            <a href="https://wa.me/6283844029190" target="_blank"
               class="inline-flex items-center gap-2 border border-white/30 hover:border-white text-white font-semibold px-8 py-3.5 rounded-xl transition-all">
                <i class="ti ti-brand-whatsapp"></i> Konsultasi Gratis
            </a>
        </div>
    </div>
</section>

@endsection