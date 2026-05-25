<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — OWL Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#1a2744] min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#e8a020] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-flame text-white text-4xl"></i>
            </div>
            <h1 class="text-white text-2xl font-bold">OWL Store</h1>
            <p class="text-blue-300 text-sm mt-1">Admin Dashboard</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            <h2 class="text-gray-800 text-lg font-bold mb-6 text-center">Masuk ke Dashboard</h2>

            {{-- Error --}}
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                <i class="ti ti-alert-circle"></i> {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Email
                    </label>
                    <div class="relative">
                        <i class="ti ti-mail absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="admin@owlstore.id"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all
                               @error('email') border-red-400 @enderror">
                    </div>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Password
                    </label>
                    <div class="relative">
                        <i class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="password" name="password" id="password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 transition-all
                               @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword()"
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

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 text-[#e8a020] rounded border-gray-300">
                    <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 mt-2">
                    <i class="ti ti-login text-lg"></i> Masuk ke Dashboard
                </button>
            </form>
        </div>

        <p class="text-center text-blue-400 text-xs mt-6">
            © 2026 OWL Store by OptimaWeld. All rights reserved.
        </p>
    </div>

    <script>
        function togglePassword() {
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