<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — OWL Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-xs">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5">
                <div class="w-7 h-7 bg-[#e8a020] rounded-lg flex items-center justify-center shadow-sm">
                    <i class="ti ti-flame text-white text-sm"></i>
                </div>
                <div>
                    <div class="font-bold text-[#1a2744] text-xs">OWL Store</div>
                    <div class="text-[#e8a020] text-[6px] tracking-widest">by OptimaWeld</div>
                </div>
            </a>
            <p class="text-[10px] text-gray-400 mt-1">Admin Dashboard</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100">
            <h2 class="text-sm font-bold text-gray-800 text-center mb-3">Masuk ke Dashboard</h2>

            {{-- Error --}}
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 text-[10px] px-2 py-1.5 rounded-md mb-3 flex items-center gap-1.5">
                <i class="ti ti-alert-circle text-sm"></i> {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-[10px] px-2 py-1.5 rounded-md mb-3">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-2.5">
                @csrf

                {{-- Email --}}
                <div>
                    <div class="relative">
                        <i class="ti ti-mail absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="admin@owlstore.id"
                               class="w-full pl-8 pr-2.5 py-1.5 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-[#e8a020] focus:ring-1 focus:ring-[#e8a020]/20
                               @error('email') border-red-400 @enderror">
                    </div>
                    @error('email')
                    <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="relative" x-data="{ show: false }">
                        <i class="ti ti-lock absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input :type="show ? 'text' : 'password'" name="password"
                               placeholder="••••••••"
                               class="w-full pl-8 pr-7 py-1.5 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-[#e8a020] focus:ring-1 focus:ring-[#e8a020]/20
                               @error('password') border-red-400 @enderror">
                        <button type="button" @click="show = !show"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="ti ti-eye text-sm" x-show="!show"></i>
                            <i class="ti ti-eye-off text-sm" x-show="show"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-1.5">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-3 h-3 rounded border-gray-300 text-[#e8a020]">
                    <label for="remember" class="text-[10px] text-gray-500">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold py-1.5 rounded-md text-xs transition-all">
                    Masuk ke Dashboard
                </button>
            </form>

            {{-- Back to Home --}}
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-[10px] text-gray-400 hover:text-[#e8a020] transition-colors inline-flex items-center gap-0.5">
                    <i class="ti ti-arrow-left text-xs"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <p class="text-center text-[9px] text-gray-400 mt-3">
            © 2026 OWL Store by OptimaWeld
        </p>
    </div>

</body>
</html>