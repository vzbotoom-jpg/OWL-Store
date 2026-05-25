@extends('layouts.app')
@section('title', 'Lupa Password — OWL Store')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#e8a020] rounded-xl flex items-center justify-center">
                    <i class="ti ti-flame text-white text-2xl"></i>
                </div>
                <div class="text-left">
                    <div class="text-[#1a2744] font-bold">OWL Store</div>
                    <div class="text-[#e8a020] text-[10px] tracking-widest">Furnitur Besi Premium</div>
                </div>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100">
            <h2 class="text-2xl font-bold text-[#1a2744] mb-2 text-center">Atur Ulang Password</h2>
            <p class="text-gray-600 text-center text-sm mb-6">
                Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda
            </p>

            {{-- Success Message --}}
            @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="ti ti-circle-check"></i> {{ session('status') }}
            </div>
            @endif

            {{-- Error Message --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="ti ti-alert-circle"></i> 
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#e8a020] focus:border-transparent"
                           placeholder="Masukkan email Anda" required>
                    @error('email')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold py-2.5 rounded-lg transition-colors mt-6">
                    <i class="ti ti-send mr-2"></i> Kirim Link Reset
                </button>
            </form>

            {{-- Links --}}
            <div class="mt-6 text-center space-y-2 text-sm">
                <div>
                    <a href="{{ route('login') }}" class="text-[#e8a020] hover:text-[#d4911a] font-medium">
                        Kembali ke Login
                    </a>
                </div>
                <div>
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-[#e8a020] hover:text-[#d4911a] font-medium">
                        Daftar di sini
                    </a>
                </div>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="bg-blue-50 rounded-2xl p-6 mt-6 border border-blue-100">
            <h3 class="font-semibold text-[#1a2744] mb-3 flex items-center gap-2">
                <i class="ti ti-info-circle text-[#e8a020]"></i> Tips Keamanan
            </h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex gap-2">
                    <i class="ti ti-check text-[#e8a020] flex-shrink-0 mt-0.5"></i>
                    <span>Jangan pernah bagikan password Anda kepada siapa pun</span>
                </li>
                <li class="flex gap-2">
                    <i class="ti ti-check text-[#e8a020] flex-shrink-0 mt-0.5"></i>
                    <span>Gunakan password yang kuat dan unik untuk akun Anda</span>
                </li>
                <li class="flex gap-2">
                    <i class="ti ti-check text-[#e8a020] flex-shrink-0 mt-0.5"></i>
                    <span>Link reset password hanya berlaku 60 menit</span>
                </li>
            </ul>
        </div>

    </div>

</div>
@endsection
