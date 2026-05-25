<aside id="sidebar" class="w-64 bg-[#1a2744] text-white flex flex-col flex-shrink-0 transition-all duration-300 overflow-hidden">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-blue-900">
        <div class="w-9 h-9 bg-[#e8a020] rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="ti ti-flame text-white text-xl"></i>
        </div>
        <div>
            <div class="font-bold text-sm leading-none">OWL Store</div>
            <div class="text-[#e8a020] text-[10px] tracking-widest mt-0.5">ADMIN PANEL</div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
           {{ request()->routeIs('admin.dashboard') ? 'bg-[#e8a020] text-[#1a2744] font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
            <i class="ti ti-dashboard text-lg"></i> Dashboard
        </a>

        <div class="pt-3 pb-1 px-3">
            <span class="text-[10px] text-blue-500 uppercase tracking-widest font-semibold">Katalog</span>
        </div>

        <a href="{{ route('admin.products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
           {{ request()->routeIs('admin.products.*') ? 'bg-[#e8a020] text-[#1a2744] font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
            <i class="ti ti-package text-lg"></i> Produk
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
           {{ request()->routeIs('admin.categories.*') ? 'bg-[#e8a020] text-[#1a2744] font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
            <i class="ti ti-category text-lg"></i> Kategori
        </a>

        <div class="pt-3 pb-1 px-3">
            <span class="text-[10px] text-blue-500 uppercase tracking-widest font-semibold">Transaksi</span>
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
           {{ request()->routeIs('admin.orders.*') ? 'bg-[#e8a020] text-[#1a2744] font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
            <i class="ti ti-shopping-bag text-lg"></i> Pesanan
        </a>

        <div class="pt-3 pb-1 px-3">
            <span class="text-[10px] text-blue-500 uppercase tracking-widest font-semibold">Manajemen</span>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
           {{ request()->routeIs('admin.users.*') ? 'bg-[#e8a020] text-[#1a2744] font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
            <i class="ti ti-users text-lg"></i> Pengguna
        </a>

        <a href="{{ route('admin.reviews.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
           {{ request()->routeIs('admin.reviews.*') ? 'bg-[#e8a020] text-[#1a2744] font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
            <i class="ti ti-star text-lg"></i> Review
        </a>

        <div class="pt-3 pb-1 px-3">
            <span class="text-[10px] text-blue-500 uppercase tracking-widest font-semibold">Lainnya</span>
        </div>

        <a href="{{ route('home') }}" target="_blank"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-blue-200 hover:bg-white/10 hover:text-white transition-all">
            <i class="ti ti-external-link text-lg"></i> Lihat Toko
        </a>
    </nav>

    {{-- User --}}
    <div class="px-4 py-4 border-t border-blue-900">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-blue-400 truncate">{{ Auth::user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-blue-400 hover:text-red-400 transition-colors" title="Logout">
                    <i class="ti ti-logout text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</aside>