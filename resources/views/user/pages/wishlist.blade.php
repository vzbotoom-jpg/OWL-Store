@extends('layouts.app')
@section('title', 'Wishlist Saya — OWL Store')

@section('content')
<div class="bg-gray-100 min-h-screen pb-20">

    {{-- Header --}}
    <div class="bg-[#1a2744] px-4 py-4 sticky top-16 z-40">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.dashboard') }}"
               class="w-9 h-9 flex items-center justify-center text-blue-200 hover:text-white transition-colors">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <h1 class="font-bold text-white text-lg">Wishlist Saya</h1>
            <span class="ml-auto bg-[#e8a020]/20 text-[#e8a020] text-xs font-semibold px-3 py-1 rounded-full">
                {{ $totalItems }} Produk
            </span>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="ti ti-circle-check text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif

        @if($wishlists->count() > 0)

        {{-- Bulk Actions Bar --}}
        <div class="bg-white rounded-xl px-4 py-3 flex items-center justify-between shadow-sm">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                Pilih Semua
            </label>
            <button id="deleteSelected"
                    class="text-red-500 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                <i class="ti ti-trash mr-1"></i> Hapus
            </button>
        </div>

        {{-- Wishlist Items --}}
        <form id="bulkDeleteForm" action="{{ route('user.wishlist.bulk-remove') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="ids" id="selectedIds">
        </form>

        @foreach($wishlists as $item)
        <div class="bg-white rounded-2xl p-4 shadow-sm flex gap-4 wishlist-item" data-id="{{ $item->id }}">
            {{-- Checkbox --}}
            <div class="flex-shrink-0">
                <input type="checkbox" class="item-checkbox w-5 h-5 rounded border-gray-300 text-[#e8a020]" value="{{ $item->id }}">
            </div>

            {{-- Product Image --}}
            <a href="{{ route('products.show', $item->product->slug) }}" class="flex-shrink-0">
                <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                    @if($item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" class="w-24 h-24 object-cover rounded-xl">
                    @else
                        <i class="ti ti-package text-gray-300 text-4xl"></i>
                    @endif
                </div>
            </a>

            {{-- Product Info --}}
            <div class="flex-1 min-w-0">
                <a href="{{ route('products.show', $item->product->slug) }}">
                    <h3 class="font-semibold text-gray-800 text-sm leading-tight line-clamp-2">{{ $item->product->name }}</h3>
                </a>
                <div class="text-xs text-gray-400 mt-1">{{ $item->product->category->name ?? 'Uncategorized' }}</div>

                {{-- Price --}}
                <div class="mt-2">
                    @if($item->product->price_original)
                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($item->product->price_original, 0, ',', '.') }}</span>
                    @endif
                    <div class="text-red-500 font-bold text-sm">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                </div>

                {{-- Rating --}}
                <div class="flex items-center gap-1 mt-1">
                    @for($i=1; $i<=5; $i++)
                        <i class="ti ti-star text-xs {{ $i <= $item->product->rating ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
                    @endfor
                    <span class="text-[10px] text-gray-400 ml-1">({{ $item->product->review_count ?? 0 }})</span>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 mt-3">
                    <button onclick="moveToCart({{ $item->id }})"
                            class="flex-1 bg-[#e8a020] text-[#1a2744] text-xs font-semibold py-2 rounded-lg hover:bg-[#d4911a] transition-colors">
                        <i class="ti ti-shopping-cart mr-1"></i> Pindah ke Keranjang
                    </button>
                    <button onclick="removeFromWishlist({{ $item->id }})"
                            class="w-9 h-9 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors">
                        <i class="ti ti-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $wishlists->links() }}
        </div>

        @else

        {{-- Empty State --}}
        <div class="bg-white rounded-2xl py-16 flex flex-col items-center justify-center">
            <div class="relative mb-4">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center">
                    <i class="ti ti-heart text-5xl text-gray-200"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center">
                    <i class="ti ti-heart text-white text-sm"></i>
                </div>
            </div>
            <p class="text-gray-400 text-sm font-medium mt-2">Wishlist masih kosong</p>
            <p class="text-gray-300 text-xs mt-1">Yuk mulai tambahkan produk favoritmu!</p>
            <a href="{{ route('products.index') }}"
               class="mt-5 bg-[#1a2744] text-white text-sm font-medium px-6 py-2.5 rounded-xl hover:bg-[#232f3e] transition-colors">
                Mulai Belanja
            </a>
        </div>

        @endif

        {{-- Produk Rekomendasi --}}
        @if($wishlists->count() > 0)
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm mt-6">
            <div class="px-4 py-3 border-b border-gray-100 text-center">
                <span class="text-xs text-gray-400 font-medium">— Rekomendasi untuk Anda —</span>
            </div>
            <div class="grid grid-cols-2 gap-px bg-gray-100">
                @foreach([
                    ['Meja Kantor Besi Premium', 1850000, 'ti-layout-board'],
                    ['Kursi Kantor Ergonomis',  450000,  'ti-armchair'],
                    ['Rak Buku Minimalis',      680000,  'ti-building-warehouse'],
                    ['Lemari Besi 2 Pintu',      1200000, 'ti-door'],
                ] as [$name, $price, $icon])
                <a href="{{ route('products.index') }}"
                   class="bg-white flex flex-col hover:bg-gray-50 transition-colors">
                    <div class="h-24 bg-gray-50 flex items-center justify-center">
                        <i class="ti {{ $icon }} text-4xl text-gray-200"></i>
                    </div>
                    <div class="p-3">
                        <div class="text-xs text-gray-700 leading-tight line-clamp-2 mb-1">{{ $name }}</div>
                        <div class="text-sm font-bold text-red-500">Rp {{ number_format($price, 0, ',', '.') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const deleteSelectedBtn = document.getElementById('deleteSelected');

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.item-checkbox:checked').length;
        deleteSelectedBtn.disabled = checked === 0;
    }

    selectAll?.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteButton();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    // Delete selected
    deleteSelectedBtn?.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.item-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selected.length === 0) return;
        
        if (confirm(`Hapus ${selected.length} produk dari wishlist?`)) {
            document.getElementById('selectedIds').value = JSON.stringify(selected);
            document.getElementById('bulkDeleteForm').submit();
        }
    });

    // Remove single item
    function removeFromWishlist(id) {
        if (confirm('Hapus produk dari wishlist?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('user.wishlist.remove', '') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Move to cart
    function moveToCart(id) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('user.wishlist.move-to-cart', '') }}/${id}`;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
@endsection