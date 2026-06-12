@extends('admin.layouts.app')
@section('title', 'Kelola Produk')
@section('breadcrumb', 'Produk')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Produk</h1>
        <p class="text-sm text-gray-400 mt-0.5">Total {{ $products->total() }} produk</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.products.create') }}"
           class="flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
            <i class="ti ti-plus text-lg"></i> Tambah Produk
        </a>
        <button onclick="exportProducts()"
                class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
            <i class="ti ti-file-export text-lg"></i> Export
        </button>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5 flex flex-wrap gap-3 items-center justify-between">
    <div class="flex flex-wrap gap-2">
        <select id="categoryFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="">Semua Kategori</option>
            @foreach($categories ?? [] as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select id="statusFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
        <select id="stockFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="">Semua Stok</option>
            <option value="low">Stok Menipis (≤5)</option>
            <option value="out">Habis (0)</option>
        </select>
    </div>
    <div class="relative">
        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="searchInput" placeholder="Cari produk..."
               class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:border-[#e8a020]">
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left w-10">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                    </th>
                    <th class="px-5 py-3 text-left">Produk</th>
                    <th class="px-5 py-3 text-left">Kategori</th>
                    <th class="px-5 py-3 text-left">Harga</th>
                    <th class="px-5 py-3 text-left">Stok</th>
                    <th class="px-5 py-3 text-left">Terjual</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <input type="checkbox" class="product-checkbox rounded border-gray-300" value="{{ $product->id }}">
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover">
                                @else
                                <i class="ti ti-package text-gray-400 text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ Str::limit($product->name, 35) }}</div>
                                <div class="text-xs text-gray-400">{{ $product->material ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-50 text-blue-600">
                            {{ $product->category->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="font-semibold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @if($product->price_original)
                        <div class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price_original, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="{{ $product->stock <= 5 ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ number_format($product->sold_count ?? 0) }}</td>
                    <td class="px-5 py-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer status-toggle" data-id="{{ $product->id }}"
                                   {{ $product->is_active ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                        </label>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                                <i class="ti ti-edit text-sm"></i>
                            </a>
                            <button onclick="copyProductLink({{ $product->id }})"
                                    class="w-8 h-8 bg-gray-50 text-gray-600 rounded-lg flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <i class="ti ti-copy text-sm"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Hapus produk ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors">
                                    <i class="ti ti-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                        <i class="ti ti-package text-4xl mb-3 block"></i>
                        Belum ada produk. <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:underline">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
        <div class="text-xs text-gray-400">
            Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
        </div>
        {{ $products->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Select All
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    
    selectAll?.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Toggle Status
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', async function() {
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;
            
            const response = await fetch(`/admin/products/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_active: status })
            });
            
            if (!response.ok) {
                this.checked = !this.checked;
                alert('Gagal mengubah status');
            }
        });
    });

    // Copy Product Link
    function copyProductLink(id) {
        const url = `{{ url('/products') }}/${id}`;
        navigator.clipboard.writeText(url);
        alert('Link produk disalin ke clipboard!');
    }

    // Export Products
    function exportProducts() {
        window.location.href = '{{ route("admin.products.export") }}';
    }

    // Filter and Search
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            window.location.href = `?search=${this.value}&category=${document.getElementById('categoryFilter').value}&status=${document.getElementById('statusFilter').value}&stock=${document.getElementById('stockFilter').value}`;
        }, 500);
    });
</script>
@endpush
@endsection