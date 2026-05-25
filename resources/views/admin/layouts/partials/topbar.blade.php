<header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center gap-4">
    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-800 transition-colors">
        <i class="ti ti-menu-2 text-xl"></i>
    </button>

    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-800">Dashboard</a>
        @hasSection('breadcrumb')
        <i class="ti ti-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">@yield('breadcrumb')</span>
        @endif
    </div>

    <div class="ml-auto flex items-center gap-3">
        <a href="{{ route('home') }}" target="_blank"
           class="hidden sm:flex items-center gap-1.5 text-xs bg-[#1a2744] text-white px-3 py-1.5 rounded-lg hover:bg-[#232f3e] transition-colors">
            <i class="ti ti-external-link text-sm"></i> Lihat Toko
        </a>
        <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-sm">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
    </div>
</header>

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    s.classList.toggle('w-64');
    s.classList.toggle('w-0');
}
</script>