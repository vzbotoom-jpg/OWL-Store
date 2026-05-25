{{-- Bottom Navigation Bar - Mobile Only --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 md:hidden z-50 safe-area-inset-bottom">
    <div class="grid grid-cols-5 gap-0">
        {{-- Beranda --}}
        <a href="{{ route('home') }}"
           class="flex flex-col items-center justify-center py-3 transition-colors
           {{ request()->routeIs('home') ? 'text-[#e8a020]' : 'text-gray-500 hover:text-[#e8a020]' }}">
            <i class="ti ti-home text-xl mb-1"></i>
            <span class="text-xs font-medium">Beranda</span>
        </a>

        {{-- Deals --}}
        <a href="{{ route('products.index') }}"
           class="flex flex-col items-center justify-center py-3 transition-colors
           {{ request()->routeIs('products.*') ? 'text-[#e8a020]' : 'text-gray-500 hover:text-[#e8a020]' }}">
            <i class="ti ti-tag text-xl mb-1"></i>
            <span class="text-xs font-medium">Deals</span>
        </a>

        {{-- Live & Video --}}
        <a href="javascript:void(0)" onclick="alert('Fitur Live & Video akan segera hadir!')"
           class="flex flex-col items-center justify-center py-3 text-gray-500 hover:text-[#e8a020] transition-colors">
            <i class="ti ti-video text-xl mb-1"></i>
            <span class="text-xs font-medium">Live</span>
        </a>

        {{-- Notifikasi --}}
        <a href="javascript:void(0)" onclick="alert('Fitur Notifikasi akan segera hadir!')"
           class="flex flex-col items-center justify-center py-3 text-gray-500 hover:text-[#e8a020] transition-colors relative">
            <i class="ti ti-bell text-xl mb-1"></i>
            <span class="text-xs font-medium">Notifikasi</span>
            {{-- Badge (optional) --}}
            <span class="absolute top-1 right-3 w-2 h-2 bg-red-500 rounded-full"></span>
        </a>

        {{-- Profile --}}
        @auth
        <a href="{{ route('user.profile') }}"
           class="flex flex-col items-center justify-center py-3 transition-colors
           {{ request()->routeIs('user.profile') ? 'text-[#e8a020]' : 'text-gray-500 hover:text-[#e8a020]' }}">
            <i class="ti ti-user text-xl mb-1"></i>
            <span class="text-xs font-medium">Profil</span>
        </a>
        @else
        <a href="{{ route('login') }}"
           class="flex flex-col items-center justify-center py-3 text-gray-500 hover:text-[#e8a020] transition-colors">
            <i class="ti ti-user text-xl mb-1"></i>
            <span class="text-xs font-medium">Login</span>
        </a>
        @endauth
    </div>
</nav>

{{-- Bottom Padding to prevent content overlap on mobile --}}
<div class="h-20 md:h-0"></div>
