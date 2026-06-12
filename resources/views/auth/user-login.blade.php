<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — OWL Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <div class="w-8 h-8 bg-[#e8a020] rounded-lg flex items-center justify-center shadow-sm">
                    <i class="ti ti-flame text-white text-base"></i>
                </div>
                <div>
                    <div class="font-bold text-[#1a2744] text-sm">OWL Store</div>
                    <div class="text-[#e8a020] text-[7px] tracking-widest">by OptimaWeld</div>
                </div>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-xl p-5 shadow-md border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 text-center mb-4">Masuk</h2>

            {{-- Error Messages --}}
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 text-xs px-3 py-2 rounded-lg mb-3">
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-xs px-3 py-2 rounded-lg mb-3">
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" class="space-y-3">
                @csrf

                <div>
                    <div class="relative">
                        <i class="ti ti-mail absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="Email"
                               class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#e8a020] focus:ring-1 focus:ring-[#e8a020]/20">
                    </div>
                </div>

                <div>
                    <div class="relative" x-data="{ show: false }">
                        <i class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input :type="show ? 'text' : 'password'" name="password" required
                               placeholder="Password"
                               class="w-full pl-9 pr-9 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#e8a020] focus:ring-1 focus:ring-[#e8a020]/20">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="ti ti-eye text-sm" x-show="!show"></i>
                            <i class="ti ti-eye-off text-sm" x-show="show"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-1.5 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3 h-3 rounded border-gray-300 text-[#e8a020]">
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-[#e8a020] hover:underline">Lupa password?</a>
                </div>

                <button type="submit"
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold py-2 rounded-lg text-sm transition-all">
                    Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-2 my-4">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400">atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Social Buttons --}}
            <div class="space-y-2">
                <a href="#" 
                   class="w-full flex items-center justify-center gap-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm py-2 rounded-lg transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>Google</span>
                </a>
                <a href="#" 
                   class="w-full flex items-center justify-center gap-2 bg-black hover:bg-gray-900 text-white text-sm py-2 rounded-lg transition-all">
                    <i class="ti ti-brand-apple text-base"></i>
                    <span>Apple</span>
                </a>
            </div>

            {{-- Register Link --}}
            <p class="text-center text-xs text-gray-500 mt-4">
                Belum punya akun? <a href="{{ route('register') }}" class="text-[#e8a020] font-semibold">Daftar</a>
            </p>
        </div>
    </div>

</body>
</html>