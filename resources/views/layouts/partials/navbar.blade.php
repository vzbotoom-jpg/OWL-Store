<header class="bg-[#1a2744] sticky top-0 z-50">
    {{-- Top bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 h-14">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 bg-[#e8a020] rounded-lg flex items-center justify-center">
                    <i class="ti ti-flame text-white text-lg"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-sm leading-none">OWL Store</div>
                    <div class="text-[#e8a020] text-[9px] leading-none tracking-widest">by OptimaWeld</div>
                </div>
            </a>

            {{-- Search --}}
            <form action="{{ route('products.index') }}" method="GET"
                  class="flex-1 flex h-9 rounded-lg overflow-hidden max-w-xl mx-auto">
                <select name="category"
                        class="bg-gray-100 text-gray-700 text-xs px-2 border-none outline-none cursor-pointer hidden sm:block">
                    <option value="">Semua</option>
                    <option value="meja">Meja</option>
                    <option value="kursi">Kursi</option>
                    <option value="rak">Rak</option>
                    <option value="custom">Custom</option>
                </select>
                <input type="text" name="q" placeholder="Cari furnitur besi, meja kantor, kursi..."
                       value="{{ request('q') }}"
                       class="flex-1 bg-white text-gray-800 text-sm px-3 outline-none border-none">
                <button type="submit"
                        class="bg-[#e8a020] px-4 text-white flex items-center justify-center hover:bg-[#d4911a] transition-colors">
                    <i class="ti ti-search text-lg"></i>
                </button>
            </form>

            {{-- Actions --}}
<div class="flex items-center gap-4 flex-shrink-0 ml-auto">

    @auth
    {{-- Sudah login --}}
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open"
                class="flex items-center gap-2 text-blue-200 hover:text-white transition-colors">
            <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="text-xs hidden sm:block">{{ Str::limit(Auth::user()->name, 12) }}</span>
            <i class="ti ti-chevron-down text-xs hidden sm:block"></i>
        </button>

        {{-- Dropdown --}}
        <div x-show="open"
             @click.outside="open = false"
             x-transition
             class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                <div class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</div>
            </div>
            <a href="{{ route('user.dashboard') }}"
               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                <i class="ti ti-layout-dashboard text-gray-400"></i> Dashboard Saya
            </a>
            <a href="#"
               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                <i class="ti ti-shopping-bag text-gray-400"></i> Pesanan Saya
            </a>
            <a href="#"
               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                <i class="ti ti-heart text-gray-400"></i> Wishlist
            </a>
            <a href="#"
               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                <i class="ti ti-user text-gray-400"></i> Profil
            </a>
            <div class="border-t border-gray-100 mt-1 pt-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 w-full text-left transition-colors">
                        <i class="ti ti-logout"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
    {{-- Belum login --}}
    <a href="{{ route('login') }}"
       class="flex flex-col items-center gap-0.5 text-blue-200 hover:text-white transition-colors hidden sm:flex">
        <i class="ti ti-user text-xl"></i>
        <span class="text-[10px]">Masuk</span>
    </a>
    <a href="{{ route('register') }}"
       class="text-xs bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-3 py-1.5 rounded-lg transition-colors hidden sm:block">
        Daftar
    </a>
    @endauth

    {{-- Keranjang --}}
    <a href="#" class="flex flex-col items-center gap-0.5 text-blue-200 hover:text-white transition-colors relative">
        <i class="ti ti-shopping-cart text-xl"></i>
        <span class="text-[10px]">Keranjang</span>
        <span class="absolute -top-1 -right-2 bg-[#e8a020] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
    </a>
</div>
        </div>
    </div> 

    {{-- Category nav --}}
    <nav class="bg-[#232f3e]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto h-9 scrollbar-hide">
                <a href="{{ route('products.index') }}"
                   class="text-blue-200 hover:text-white text-xs px-3 h-9 flex items-center gap-1 whitespace-nowrap hover:border hover:border-white/30 rounded transition-all {{ request()->routeIs('products.index') && !request('category') ? 'text-white font-medium' : '' }}">
                    <i class="ti ti-layout-grid text-xs"></i> Semua Produk
                </a>
                @foreach([
                    ['meja-kantor',  'ti-layout-board',      'Meja Kantor'],
                    ['meja-makan',   'ti-tools',              'Meja Makan'],
                    ['kursi',        'ti-armchair',           'Kursi & Bangku'],
                    ['rak',          'ti-building-warehouse', 'Rak Besi'],
                    ['lemari',       'ti-door',               'Lemari Besi'],
                    ['outdoor',      'ti-tree',               'Furnitur Outdoor'],
                    ['custom',       'ti-pencil-ruler',       'Custom Order'],
                ] as [$slug, $icon, $label])
                <a href="{{ route('products.category', $slug) }}"
                   class="text-blue-200 hover:text-white text-xs px-3 h-9 flex items-center gap-1 whitespace-nowrap hover:border hover:border-white/30 rounded transition-all">
                    <i class="ti {{ $icon }} text-xs"></i> {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </nav>
</header>