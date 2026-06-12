@extends('layouts.app')
@section('title', 'Kontak Kami — OWL Store')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-r from-[#1a2744] to-[#232f3e] py-12 px-4">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Kontak Kami</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">
            Kami siap membantu Anda. Hubungi kami melalui berbagai channel berikut
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Contact Info Cards --}}
        <div class="lg:col-span-1 space-y-4">
            @foreach([
                ['ti-brand-whatsapp', 'WhatsApp', '+62 838-4402-9190', 'Balas dalam < 1 jam', 'https://wa.me/6283844029190', 'bg-green-500'],
                ['ti-mail', 'Email', 'optimaweld21@gmail.com', 'Balas dalam < 24 jam', 'mailto:optimaweld21@gmail.com', 'bg-blue-500'],
                ['ti-phone', 'Telepon', '+62 838-4402-9190', 'Senin-Sabtu, 08.00-17.00', 'tel:+6283844029190', 'bg-purple-500'],
                ['ti-map-pin', 'Alamat', 'Yogyakarta, Indonesia', 'Lihat di Google Maps', 'https://maps.google.com', 'bg-red-500']
            ] as [$icon, $title, $detail, $sub, $link, $color])
            <a href="{{ $link }}" target="_blank" class="block bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-all group">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 {{ $color }} rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="ti {{ $icon }} text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $title }}</h3>
                        <p class="text-gray-800 text-sm font-medium">{{ $detail }}</p>
                        <p class="text-gray-400 text-xs mt-1">{{ $sub }}</p>
                    </div>
                    <i class="ti ti-chevron-right text-gray-300 ml-auto opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Contact Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm">
                <h2 class="text-xl font-bold text-[#1a2744] mb-6">Kirim Pesan</h2>
                
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <i class="ti ti-circle-check text-green-500"></i> {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <i class="ti ti-alert-circle"></i> Terjadi kesalahan. Silakan cek form Anda.
                </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] @error('name') border-red-400 @enderror">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] @error('email') border-red-400 @enderror">
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Subjek <span class="text-red-500">*</span></label>
                            <select name="subject" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                                <option value="">Pilih Subjek</option>
                                <option value="Pertanyaan Produk">Pertanyaan Produk</option>
                                <option value="Custom Order">Custom Order</option>
                                <option value="Komplain">Komplain</option>
                                <option value="Kerjasama">Kerjasama</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="5" required
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none @error('message') border-red-400 @enderror"
                                  placeholder="Tulis pesan Anda...">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3.5 rounded-xl transition-all flex items-center justify-center gap-2">
                        <i class="ti ti-send"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Map Section --}}
    <div class="mt-12">
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="h-80 bg-gray-200 relative">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31652.63987022178!2d110.360784!3d-7.795604!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5787bd5b6bc5%3A0x21723fd4d3684f71!2sYogyakarta!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>

    {{-- Social Media --}}
    <div class="mt-8 text-center">
        <h3 class="font-semibold text-gray-800 mb-4">Ikuti Kami</h3>
        <div class="flex justify-center gap-4">
            <a href="#" class="w-12 h-12 bg-[#1a2744] rounded-full flex items-center justify-center text-white hover:bg-[#e8a020] transition-colors">
                <i class="ti ti-brand-instagram text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-[#1a2744] rounded-full flex items-center justify-center text-white hover:bg-[#e8a020] transition-colors">
                <i class="ti ti-brand-facebook text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-[#1a2744] rounded-full flex items-center justify-center text-white hover:bg-[#e8a020] transition-colors">
                <i class="ti ti-brand-youtube text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-[#1a2744] rounded-full flex items-center justify-center text-white hover:bg-[#e8a020] transition-colors">
                <i class="ti ti-brand-tiktok text-xl"></i>
            </a>
        </div>
    </div>
</div>

@endsection