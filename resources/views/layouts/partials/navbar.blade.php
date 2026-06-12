<header class="bg-[#1a2744] sticky top-0 z-50 shadow-lg">
    {{-- Top bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-14 gap-3">

            {{-- Logo Desktop --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 group">
                <div class="w-9 h-9 bg-[#e8a020] rounded-xl flex items-center justify-center shadow-md transition-all group-hover:scale-105">
                    <i class="ti ti-flame text-white text-xl"></i>
                </div>
                <div class="hidden sm:block">
                    <div class="text-white font-bold text-base leading-tight">OWL Store</div>
                    <div class="text-[#e8a020] text-[8px] leading-none tracking-wider">by OptimaWeld</div>
                </div>
            </a>

            {{-- Search Bar --}}
            <form action="{{ route('products.index') }}" method="GET" class="hidden md:flex flex-1 max-w-xl mx-4">
                <div class="relative flex w-full">
                    <select name="category" 
                            class="absolute left-0 top-0 h-full bg-gray-100 text-gray-700 text-xs px-3 rounded-l-xl border-0 focus:ring-0 cursor-pointer hidden lg:block">
                        <option value="">Semua Kategori</option>
                        <option value="meja-kantor">Meja Kantor</option>
                        <option value="meja-makan">Meja Makan</option>
                        <option value="kursi">Kursi</option>
                        <option value="rak">Rak Besi</option>
                        <option value="lemari">Lemari Besi</option>
                        <option value="custom">Custom Order</option>
                    </select>
                    <input type="text" name="q" 
                           placeholder="Cari furnitur besi, meja kantor, kursi..."
                           value="{{ request('q') }}"
                           class="w-full bg-white text-gray-800 text-sm pl-4 lg:pl-36 pr-12 py-2.5 rounded-xl border-0 focus:ring-2 focus:ring-[#e8a020]/50 outline-none">
                    <button type="submit" 
                            class="absolute right-0 top-0 h-full px-4 bg-[#e8a020] hover:bg-[#d4911a] rounded-r-xl text-[#1a2744] transition-colors flex items-center justify-center">
                        <i class="ti ti-search text-lg font-bold"></i>
                    </button>
                </div>
            </form>

            {{-- Mobile Search Button --}}
            <button id="mobileSearchBtn" class="md:hidden text-blue-200 hover:text-white transition-colors">
                <i class="ti ti-search text-2xl"></i>
            </button>

            {{-- Actions --}}
            <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">

                @auth
                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center gap-2 text-blue-200 hover:text-white transition-colors group">
                        <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] font-bold text-sm shadow-md">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm hidden sm:block max-w-[100px] truncate font-medium">{{ Auth::user()->name }}</span>
                        <i class="ti ti-chevron-down text-xs hidden sm:block transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" 
                         @click.outside="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                        
                        <div class="px-4 py-3 border-b border-gray-100 mb-1">
                            <div class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</div>
                        </div>
                        
                        @foreach([
                            ['ti-layout-dashboard', 'Dashboard Saya', route('user.dashboard')],
                            ['ti-shopping-bag', 'Pesanan Saya', route('user.orders')],
                            ['ti-heart', 'Wishlist', route('user.wishlist')],
                            ['ti-map-pin', 'Alamat Saya', route('user.addresses')],
                            ['ti-user', 'Profil Saya', route('user.profile')],
                            ['ti-settings', 'Pengaturan', route('user.settings')],
                        ] as [$icon, $label, $url])
                        <a href="{{ $url }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1a2744] transition-colors">
                            <i class="ti {{ $icon }} text-gray-400 text-base w-5"></i> {{ $label }}
                        </a>
                        @endforeach
                        
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                    <i class="ti ti-logout text-base w-5"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                {{-- Guest Actions --}}
                <a href="{{ route('login') }}" 
                   class="hidden sm:flex flex-col items-center text-blue-200 hover:text-white transition-colors">
                    <i class="ti ti-user text-xl"></i>
                    <span class="text-[9px] font-medium">Masuk</span>
                </a>
                <a href="{{ route('register') }}" 
                   class="hidden sm:block bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] text-xs font-bold px-4 py-1.5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    Daftar
                </a>
                @endauth

                {{-- Cart --}}
                <a href="#" class="relative flex flex-col items-center text-blue-200 hover:text-white transition-colors group">
                    <i class="ti ti-shopping-cart text-2xl"></i>
                    <span class="text-[9px] font-medium hidden sm:block">Keranjang</span>
                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-md">0</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Mobile Search Form (Hidden by default) --}}
    <div id="mobileSearchForm" class="hidden md:hidden px-4 pb-3">
        <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
            <select name="category" class="bg-gray-100 text-gray-700 text-xs px-3 py-2 rounded-xl border-0">
                <option value="">Semua</option>
                <option value="meja-kantor">Meja Kantor</option>
                <option value="meja-makan">Meja Makan</option>
                <option value="kursi">Kursi</option>
                <option value="rak">Rak Besi</option>
                <option value="custom">Custom</option>
            </select>
            <input type="text" name="q" placeholder="Cari produk..."
                   value="{{ request('q') }}"
                   class="flex-1 bg-white text-gray-800 text-sm px-4 py-2 rounded-xl border-0 focus:ring-2 focus:ring-[#e8a020]/50">
            <button type="submit" class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] px-4 rounded-xl transition-colors">
                <i class="ti ti-search text-lg"></i>
            </button>
        </form>
    </div>

    {{-- Category Navigation --}}
    <nav class="bg-[#232f3e] border-t border-blue-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto scrollbar-hide py-2">
                {{-- All Products --}}
                <a href="{{ route('products.index') }}" 
                   class="flex items-center gap-1.5 text-blue-200 hover:text-white text-xs px-3 py-1.5 rounded-lg whitespace-nowrap transition-all {{ request()->routeIs('products.index') && !request('category') ? 'bg-[#e8a020]/20 text-white font-medium' : 'hover:bg-white/10' }}">
                    <i class="ti ti-layout-grid text-sm"></i> Semua Produk
                </a>
                
                {{-- Categories --}}
                @foreach([
                    ['meja-kantor', 'ti-layout-board', 'Meja Kantor', true],
                    ['meja-makan', 'ti-tools', 'Meja Makan', true],
                    ['kursi', 'ti-armchair', 'Kursi & Bangku', true],
                    ['rak', 'ti-building-warehouse', 'Rak Besi', true],
                    ['lemari', 'ti-door', 'Lemari Besi', true],
                    ['outdoor', 'ti-tree', 'Furnitur Outdoor', false],
                    ['custom', 'ti-pencil-ruler', 'Custom Order', true],
                ] as [$slug, $icon, $label, $show])
                @if($show)
                <a href="{{ route('products.category', $slug) }}" 
                   class="flex items-center gap-1.5 text-blue-200 hover:text-white text-xs px-3 py-1.5 rounded-lg whitespace-nowrap transition-all hover:bg-white/10 {{ request()->segment(2) === $slug ? 'bg-[#e8a020]/20 text-white' : '' }}">
                    <i class="ti {{ $icon }} text-sm"></i> {{ $label }}
                </a>
                @endif
                @endforeach
                
                {{-- Hot Deals Badge --}}
                <div class="ml-auto hidden lg:flex items-center gap-2">
                    <div class="h-6 w-px bg-blue-800"></div>
                    <a href="{{ route('products.index') }}?sort=discount" 
                       class="flex items-center gap-1 bg-red-500/20 text-red-300 text-xs px-3 py-1.5 rounded-lg whitespace-nowrap hover:bg-red-500/30 transition-all">
                        <i class="ti ti-flame text-sm"></i> Hot Deals!
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

@push('scripts')
<script>
    // Mobile search toggle
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const mobileSearchForm = document.getElementById('mobileSearchForm');
    
    if (mobileSearchBtn) {
        mobileSearchBtn.addEventListener('click', function() {
            mobileSearchForm.classList.toggle('hidden');
        });
    }
    
    // Close mobile search when clicking outside (optional)
    document.addEventListener('click', function(event) {
        if (mobileSearchForm && !mobileSearchForm.classList.contains('hidden')) {
            if (!mobileSearchBtn.contains(event.target) && !mobileSearchForm.contains(event.target)) {
                mobileSearchForm.classList.add('hidden');
            }
        }
    });
</script>
@endpush