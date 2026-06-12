<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — OWL Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-xs">

        {{-- Logo --}}
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5">
                <div class="w-7 h-7 bg-[#e8a020] rounded-lg flex items-center justify-center shadow-sm">
                    <i class="ti ti-flame text-white text-sm"></i>
                </div>
                <div>
                    <div class="font-bold text-[#1a2744] text-xs">OWL Store</div>
                    <div class="text-[#e8a020] text-[6px] tracking-widest">by OptimaWeld</div>
                </div>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100">
            <div class="text-center mb-3">
                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-1.5">
                    <i class="ti ti-lock-question text-amber-600 text-sm"></i>
                </div>
                <h2 class="text-sm font-bold text-gray-800">Lupa Password?</h2>
                <p class="text-[10px] text-gray-500 mt-0.5">Masukkan email Anda</p>
            </div>

            @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-[10px] px-2 py-1.5 rounded-md mb-3 text-center">
                {{ session('status') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-[10px] px-2 py-1.5 rounded-md mb-3 text-center">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-2.5">
                @csrf

                <div>
                    <div class="relative">
                        <i class="ti ti-mail absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="Email"
                               class="w-full pl-8 pr-2.5 py-1.5 border border-gray-200 rounded-md text-xs focus:outline-none focus:border-[#e8a020] focus:ring-1 focus:ring-[#e8a020]/20">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold py-1.5 rounded-md text-xs transition-all">
                    Kirim Link Reset
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-[10px] text-gray-400 hover:text-[#e8a020] transition-colors inline-flex items-center gap-0.5">
                    <i class="ti ti-arrow-left text-xs"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

</body>
</html>