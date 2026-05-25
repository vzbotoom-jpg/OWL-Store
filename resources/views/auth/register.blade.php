<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — OWL Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-3xl shadow-xl overflow-hidden">

        {{-- Sisi Kiri --}}
        <div class="bg-[#1a2744] p-10 flex-col justify-between hidden md:flex">
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
                    Bergabung dengan<br>
                    <span class="text-[#e8a020]">OWL Store</span>
                </h2>
                <p class="text-blue-300 text-sm leading-relaxed">
                    Daftar sekarang dan nikmati promo grand opening — diskon 20% untuk pembelian pertama!
                </p>
            </div>
            <div class="bg-[#e8a020]/10 border border-[#e8a020]/30 rounded-2xl p-4 mt-8">
                <div class="text-[#e8a020] font-bold text-sm mb-1">🎉 Promo Member Baru</div>
                <div class="text-blue-200 text-xs leading-relaxed">
                    Diskon 20% untuk pembelian pertama + gratis ongkir area Yogyakarta untuk semua member baru.
                </div>
            </div>
        </div>

        {{-- Sisi Kanan --}}
        <div class="p-8 sm:p-10">

            <h2 class="text-2xl font-bold text-gray-800 mb-1">Buat Akun Baru</h2>
            <p class="text-gray-400 text-sm mb-7">Isi data di bawah untuk mendaftar</p>

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                    <div class="relative">
                        <i class="ti ti-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="Nama lengkap Anda"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all @error('name') border-red-400 @enderror">
                    </div>
                    @error('name')
                    <p class="text-red-500 text-xs mt-1"><i class="ti ti-alert-circle text-xs"></i> {{ $message }}</p>
                    @enderror
                </div>

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
                    <p class="text-red-500 text-xs mt-1"><i class="ti ti-alert-circle text-xs"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Nomor HP <span class="text-gray-300 normal-case">(opsional)</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <i class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="password" name="password" id="password"
                               placeholder="Minimal 8 karakter"
                               class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePass('password','eye1')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="ti ti-eye" id="eye1"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1"><i class="ti ti-alert-circle text-xs"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Konfirmasi Password</label>
                    <div class="relative">
                        <i class="ti ti-lock-check absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="password" name="password_confirmation" id="password2"
                               placeholder="Ulangi password"
                               class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all">
                        <button type="button" onclick="togglePass('password2','eye2')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="ti ti-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 mt-2">
                    <i class="ti ti-user-plus text-lg"></i> Daftar Sekarang
                </button>
            </form>

            {{-- Login link --}}
            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#e8a020] font-semibold hover:underline">Masuk di sini</a>
            </p>

            <p class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 flex items-center justify-center gap-1 transition-colors">
                    <i class="ti ti-arrow-left text-xs"></i> Kembali ke toko
                </a>
            </p>
        </div>
    </div>

    <script>
        function togglePass(id, iconId) {
            const pwd = document.getElementById(id);
            const icon = document.getElementById(iconId);
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