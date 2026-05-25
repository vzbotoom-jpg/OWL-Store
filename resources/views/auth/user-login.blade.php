<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — OWL Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-3xl shadow-xl overflow-hidden">

        {{-- Sisi Kiri --}}
        <div class="bg-[#1a2744] p-10 flex flex-col justify-between hidden md:flex">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-12">
                    <div class="w-10 h-10 bg-[#e8a020] rounded-xl flex items-center justify-center">
                        <i class="ti ti-flame text-white text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-white font-bold">OWL Store</div>
                        <div class="text-[#e8a020] text-[10px] tracking-widest">by OptimaWeld</div>
                    </div>
                </a>
                <h2 class="text-white text-2xl font-bold leading-snug mb-4">
                    Furnitur Besi Premium<br>
                    <span class="text-[#e8a020]">untuk Ruang Anda</span>
                </h2>
                <p class="text-blue-300 text-sm leading-relaxed">
                    Masuk untuk melihat pesanan, wishlist, dan menikmati promo eksklusif member OWL Store.
                </p>
            </div>
            <div class="space-y-3">
                @foreach([
                    ['ti-shield-check', 'Garansi 1 Tahun'],
                    ['ti-truck',        'Gratis Ongkir Jogja'],
                    ['ti-pencil-ruler', 'Custom Ukuran & Warna'],
                ] as [$icon, $text])
                <div class="flex items-center gap-3 text-blue-200 text-sm">
                    <i class="ti {{ $icon }} text-[#e8a020] text-lg"></i>
                    {{ $text }}
                </div>
                @endforeach
            </div>
        </div>

        {{-- Sisi Kanan --}}
        <div class="p-8 sm:p-10">

            {{-- Mobile logo --}}
            <div class="flex items-center gap-2 mb-8 md:hidden">
                <div class="w-8 h-8 bg-[#e8a020] rounded-lg flex items-center justify-center">
                    <i class="ti ti-flame text-white text-lg"></i>
                </div>
                <div class="font-bold text-[#1a2744]">OWL Store</div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang!</h2>
            <p class="text-gray-400 text-sm mb-7">Masuk ke akun OWL Store Anda</p>

            {{-- Error --}}
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                <i class="ti ti-alert-circle"></i> {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Email</label>
                    <div class="relative">
                        <i class="ti ti-mail absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@example.com"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all @error('email') border-red-400 @enderror">
                    </div>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <i class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="password" name="password" id="password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePass()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="ti ti-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Remember & Lupa password --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300">
                        Ingat saya
                    </label>
                    <a href="#" class="text-sm text-[#e8a020] hover:underline">Lupa password?</a>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="ti ti-login text-lg"></i> Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400">atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- WhatsApp login --}}
            <a href="https://wa.me/6283844029190?text=Halo%20OWL%20Store%2C%20saya%20ingin%20daftar%20sebagai%20member"
               target="_blank"
               class="w-full border border-gray-200 text-gray-700 font-medium py-3 rounded-xl transition-colors flex items-center justify-center gap-2 hover:bg-gray-50 text-sm">
                <i class="ti ti-brand-whatsapp text-green-500 text-lg"></i>
                Hubungi via WhatsApp
            </a>

            {{-- Register link --}}
            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#e8a020] font-semibold hover:underline">Daftar sekarang</a>
            </p>

            {{-- Back to home --}}
            <p class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 flex items-center justify-center gap-1 transition-colors">
                    <i class="ti ti-arrow-left text-xs"></i> Kembali ke toko
                </a>
            </p>
        </div>
    </div>

    <script>
        function togglePass() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'ti ti-eye-off';
            } else {
                pwd.type = 'password';
                icon.className = 'ti ti-eye';
            }
        }
    </script>
</body>
</html>