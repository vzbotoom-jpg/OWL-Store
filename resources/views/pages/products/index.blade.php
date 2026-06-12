@extends('layouts.app')
@section('title', isset($category) ? $category->name . ' — OWL Store' : 'Semua Produk — OWL Store')

@section('content')

{{-- Header Banner --}}
<section class="bg-gradient-to-r from-[#1a2744] to-[#232f3e] py-10 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-white text-2xl md:text-3xl font-bold mb-2">
                    {{ isset($category) ? $category->name : 'Semua Produk' }}
                </h1>
                <p class="text-blue-300 text-sm">
                    Furnitur besi premium buatan pengrajin las profesional Yogyakarta
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-blue-300 text-sm">Menampilkan</span>
                <span class="bg-[#e8a020] text-[#1a2744] font-bold px-3 py-1 rounded-lg text-sm">
                    {{ isset($products) ? $products->total() : 0 }}
                </span>
                <span class="text-blue-300 text-sm">produk</span>
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ==================== SIDEBAR FILTER ==================== --}}
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-24 shadow-sm">
                
                {{-- Filter Header --}}
                <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="ti ti-filter text-[#e8a020]"></i> Filter
                    </h3>
                    <button id="clearFilters" class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                        <i class="ti ti-trash mr-1"></i> Reset
                    </button>
                </div>

                {{-- Search --}}
                <div class="mb-5">
                    <div class="relative">
                        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchInput" placeholder="Cari produk..."
                               class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20">
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleCollapse('categoryCollapse')">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                            <i class="ti ti-category text-[#e8a020]"></i> Kategori
                        </h4>
                        <i id="categoryCollapseIcon" class="ti ti-chevron-down text-gray-400 text-xs transition-transform"></i>
                    </div>
                    <div id="categoryCollapse" class="space-y-1.5 mt-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-gray-800 transition-colors">
                            <input type="radio" name="category" value="" class="category-radio text-[#e8a020]" checked>
                            <i class="ti ti-layout-grid text-xs w-4"></i> Semua Produk
                        </label>
                        @foreach([
                            ['meja-kantor', 'ti-layout-board', 'Meja Kantor'],
                            ['meja-makan', 'ti-tools', 'Meja Makan'],
                            ['kursi', 'ti-armchair', 'Kursi & Bangku'],
                            ['rak', 'ti-building-warehouse', 'Rak Besi'],
                            ['lemari', 'ti-door', 'Lemari Besi'],
                            ['outdoor', 'ti-tree', 'Furnitur Outdoor'],
                            ['custom', 'ti-pencil-ruler', 'Custom Order'],
                        ] as [$slug, $icon, $label])
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-gray-800 transition-colors">
                            <input type="radio" name="category" value="{{ $slug }}" class="category-radio text-[#e8a020]">
                            <i class="ti {{ $icon }} text-xs w-4"></i> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Price Range --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-3 cursor-pointer" onclick="toggleCollapse('priceCollapse')">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                            <i class="ti ti-currency-dollar text-[#e8a020]"></i> Rentang Harga
                        </h4>
                        <i id="priceCollapseIcon" class="ti ti-chevron-down text-gray-400 text-xs transition-transform"></i>
                    </div>
                    <div id="priceCollapse">
                        <div class="flex gap-2 mb-3">
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                <input type="number" id="minPrice" placeholder="Min" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <span class="text-gray-400 self-center">-</span>
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                <input type="number" id="maxPrice" placeholder="Max" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-sm">
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach([
                                ['0-500000', 'Rp 500rb', 'bg-gray-100'],
                                ['500000-1000000', 'Rp 500rb - 1jt', 'bg-gray-100'],
                                ['1000000-3000000', 'Rp 1jt - 3jt', 'bg-gray-100'],
                                ['3000000-9999999', 'Rp 3jt+', 'bg-gray-100'],
                            ] as [$val, $label, $class])
                            <button data-price="{{ $val }}" class="price-preset text-xs px-3 py-1.5 rounded-full {{ $class }} text-gray-600 hover:bg-[#e8a020]/20 transition-colors">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Sort By --}}
                <div class="mb-5">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <i class="ti ti-arrows-sort text-[#e8a020]"></i> Urutkan
                    </h4>
                    <select id="sortBy" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-600 focus:outline-none focus:border-[#e8a020]">
                        <option value="latest">Terbaru</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                        <option value="popular">Terlaris</option>
                        <option value="rating">Rating Tertinggi</option>
                    </select>
                </div>

                {{-- Availability --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <i class="ti ti-package text-[#e8a020]"></i> Ketersediaan
                    </h4>
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" id="inStock" class="rounded border-gray-300 text-[#e8a020]">
                        <span>Stok Tersedia</span>
                    </label>
                </div>

                {{-- Active Filters --}}
                <div id="activeFilters" class="mt-5 pt-4 border-t border-gray-100 flex flex-wrap gap-2">
                    {{-- Active filter badges will appear here --}}
                </div>
            </div>
        </aside>

        {{-- ==================== PRODUCT GRID ==================== --}}
        <div class="flex-1">

            {{-- Top Bar: View Toggle & Result Count --}}
            <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Tampilan:</span>
                    <button id="gridViewBtn" class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-[#e8a020] bg-[#e8a020]/10 transition-colors">
                        <i class="ti ti-layout-grid text-lg"></i>
                    </button>
                    <button id="listViewBtn" class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#e8a020] transition-colors">
                        <i class="ti ti-layout-list text-lg"></i>
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    Menampilkan <span id="resultStart">0</span> - <span id="resultEnd">0</span> dari <span id="resultTotal">0</span> produk
                </div>
            </div>

            {{-- Loading Spinner --}}
            <div id="loadingSpinner" class="hidden text-center py-12">
                <div class="inline-block w-10 h-10 border-4 border-gray-200 border-t-[#e8a020] rounded-full animate-spin"></div>
                <p class="text-gray-400 text-sm mt-3">Memuat produk...</p>
            </div>

            {{-- Products Container --}}
            <div id="productsContainer" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @if(isset($products) && $products->count() > 0)
                    @include('pages.products._product_grid', ['products' => $products])
                @else
                    <div class="col-span-full text-center py-12 text-gray-400">
                        <i class="ti ti-package-off text-6xl mb-4 block"></i>
                        <p>Belum ada produk</p>
                    </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if(isset($products) && $products->hasPages())
            <div id="paginationContainer" class="mt-8 flex justify-center">
                {{ $products->links() }}
            </div>
            @else
            <div id="paginationContainer" class="hidden"></div>
            @endif

            {{-- Empty State --}}
            <div id="emptyState" class="hidden text-center py-16 bg-white rounded-2xl border border-gray-100">
                <i class="ti ti-package-off text-6xl text-gray-200 mb-4 block"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak ada produk ditemukan</h3>
                <p class="text-gray-400 text-sm mb-6">Coba ubah filter atau kata kunci pencarian Anda</p>
                <button onclick="resetAllFilters()" class="bg-[#1a2744] text-white px-6 py-2.5 rounded-xl hover:bg-[#232f3e] transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentPage = 1;
    let filters = {
        search: '',
        category: '',
        min_price: '',
        max_price: '',
        sort: 'latest',
        in_stock: false
    };

    // Toggle collapse sections
    function toggleCollapse(id) {
        const el = document.getElementById(id);
        const icon = document.getElementById(id + 'Icon');
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            icon.classList.remove('rotate-180');
        } else {
            el.classList.add('hidden');
            icon.classList.add('rotate-180');
        }
    }

    // Update active filters display
    function updateActiveFilters() {
        const container = document.getElementById('activeFilters');
        if (!container) return;
        container.innerHTML = '';
        
        if (filters.search) {
            addFilterBadge('Pencarian: ' + filters.search, 'search');
        }
        if (filters.category) {
            addFilterBadge('Kategori', 'category');
        }
        if (filters.min_price || filters.max_price) {
            let text = 'Harga: ';
            if (filters.min_price) text += 'Rp ' + formatNumber(filters.min_price);
            if (filters.min_price && filters.max_price) text += ' - ';
            if (filters.max_price) text += 'Rp ' + formatNumber(filters.max_price);
            addFilterBadge(text, 'price');
        }
        if (filters.in_stock) {
            addFilterBadge('Stok Tersedia', 'stock');
        }
    }

    function addFilterBadge(label, type) {
        const container = document.getElementById('activeFilters');
        if (!container) return;
        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full';
        badge.innerHTML = `${label} <button onclick="removeFilter('${type}')" class="text-gray-400 hover:text-red-500"><i class="ti ti-x text-xs"></i></button>`;
        container.appendChild(badge);
    }

    function removeFilter(type) {
        switch(type) {
            case 'search':
                filters.search = '';
                document.getElementById('searchInput') && (document.getElementById('searchInput').value = '');
                break;
            case 'category':
                filters.category = '';
                document.querySelectorAll('.category-radio').forEach(radio => radio.checked = radio.value === '');
                break;
            case 'price':
                filters.min_price = '';
                filters.max_price = '';
                document.getElementById('minPrice') && (document.getElementById('minPrice').value = '');
                document.getElementById('maxPrice') && (document.getElementById('maxPrice').value = '');
                break;
            case 'stock':
                filters.in_stock = false;
                document.getElementById('inStock') && (document.getElementById('inStock').checked = false);
                break;
        }
        currentPage = 1;
        loadProducts();
    }

    function resetAllFilters() {
        filters = {
            search: '',
            category: '',
            min_price: '',
            max_price: '',
            sort: 'latest',
            in_stock: false
        };
        if (document.getElementById('searchInput')) document.getElementById('searchInput').value = '';
        document.querySelectorAll('.category-radio').forEach(radio => radio.checked = radio.value === '');
        if (document.getElementById('minPrice')) document.getElementById('minPrice').value = '';
        if (document.getElementById('maxPrice')) document.getElementById('maxPrice').value = '';
        if (document.getElementById('inStock')) document.getElementById('inStock').checked = false;
        if (document.getElementById('sortBy')) document.getElementById('sortBy').value = 'latest';
        currentPage = 1;
        loadProducts();
    }

    // Price preset buttons
    document.querySelectorAll('.price-preset').forEach(btn => {
        btn.addEventListener('click', function() {
            const range = this.dataset.price;
            if (range) {
                const [min, max] = range.split('-');
                filters.min_price = min;
                filters.max_price = max === '9999999' ? '' : max;
                if (document.getElementById('minPrice')) document.getElementById('minPrice').value = filters.min_price;
                if (document.getElementById('maxPrice')) document.getElementById('maxPrice').value = filters.max_price;
                currentPage = 1;
                loadProducts();
            }
        });
    });

    // Load products with AJAX
    function loadProducts() {
        const spinner = document.getElementById('loadingSpinner');
        const container = document.getElementById('productsContainer');
        const pagination = document.getElementById('paginationContainer');
        const emptyState = document.getElementById('emptyState');
        
        if (spinner) spinner.classList.remove('hidden');
        
        const params = new URLSearchParams({
            page: currentPage,
            search: filters.search,
            category: filters.category,
            min_price: filters.min_price,
            max_price: filters.max_price,
            sort: filters.sort,
            in_stock: filters.in_stock ? 1 : 0
        });
        
        fetch(`{{ route('products.index') }}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (spinner) spinner.classList.add('hidden');
            
            if (container && data.html && data.html.trim() && data.html !== '<div></div>') {
                container.innerHTML = data.html;
                if (pagination && data.pagination) pagination.innerHTML = data.pagination;
                if (emptyState) emptyState.classList.add('hidden');
                
                // Update result counts
                if (document.getElementById('resultStart')) document.getElementById('resultStart').innerText = data.from || 0;
                if (document.getElementById('resultEnd')) document.getElementById('resultEnd').innerText = data.to || 0;
                if (document.getElementById('resultTotal')) document.getElementById('resultTotal').innerText = data.total || 0;
            } else if (emptyState) {
                emptyState.classList.remove('hidden');
                if (container) container.innerHTML = '';
                if (pagination) pagination.innerHTML = '';
                if (document.getElementById('resultStart')) document.getElementById('resultStart').innerText = '0';
                if (document.getElementById('resultEnd')) document.getElementById('resultEnd').innerText = '0';
                if (document.getElementById('resultTotal')) document.getElementById('resultTotal').innerText = '0';
            }
            
            updateActiveFilters();
        })
        .catch(err => {
            if (spinner) spinner.classList.add('hidden');
            console.error('Error loading products:', err);
        });
    }

    // Event listeners
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function(e) {
            filters.search = e.target.value;
            currentPage = 1;
            loadProducts();
        }, 500));
    }
    
    document.querySelectorAll('.category-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            filters.category = this.value;
            currentPage = 1;
            loadProducts();
        });
    });
    
    const minPrice = document.getElementById('minPrice');
    if (minPrice) {
        minPrice.addEventListener('change', function(e) {
            filters.min_price = e.target.value;
            currentPage = 1;
            loadProducts();
        });
    }
    
    const maxPrice = document.getElementById('maxPrice');
    if (maxPrice) {
        maxPrice.addEventListener('change', function(e) {
            filters.max_price = e.target.value;
            currentPage = 1;
            loadProducts();
        });
    }
    
    const sortBy = document.getElementById('sortBy');
    if (sortBy) {
        sortBy.addEventListener('change', function(e) {
            filters.sort = e.target.value;
            currentPage = 1;
            loadProducts();
        });
    }
    
    const inStock = document.getElementById('inStock');
    if (inStock) {
        inStock.addEventListener('change', function(e) {
            filters.in_stock = e.target.checked;
            currentPage = 1;
            loadProducts();
        });
    }
    
    // Pagination handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = new URL(e.target.closest('.pagination a').href);
            currentPage = url.searchParams.get('page') || 1;
            loadProducts();
        }
    });
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    // View toggle (grid/list)
    let isGridView = true;
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const productsContainer = document.getElementById('productsContainer');
    
    if (gridViewBtn && listViewBtn && productsContainer) {
        gridViewBtn.addEventListener('click', function() {
            isGridView = true;
            productsContainer.classList.remove('flex', 'flex-col', 'gap-3');
            productsContainer.classList.add('grid', 'grid-cols-2', 'sm:grid-cols-3', 'xl:grid-cols-4', 'gap-4');
            this.classList.add('text-[#e8a020]', 'bg-[#e8a020]/10');
            this.classList.remove('text-gray-400');
            listViewBtn.classList.remove('text-[#e8a020]', 'bg-[#e8a020]/10');
            listViewBtn.classList.add('text-gray-400');
        });
        
        listViewBtn.addEventListener('click', function() {
            isGridView = false;
            productsContainer.classList.remove('grid', 'grid-cols-2', 'sm:grid-cols-3', 'xl:grid-cols-4', 'gap-4');
            productsContainer.classList.add('flex', 'flex-col', 'gap-3');
            this.classList.add('text-[#e8a020]', 'bg-[#e8a020]/10');
            this.classList.remove('text-gray-400');
            gridViewBtn.classList.remove('text-[#e8a020]', 'bg-[#e8a020]/10');
            gridViewBtn.classList.add('text-gray-400');
        });
    }
</script>
@endpush
@endsection