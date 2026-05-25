@extends('admin.layouts.app')
@section('title', 'Kelola Produk')
@section('breadcrumb', 'Produk')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Produk</h1>
        <p class="text-sm text-gray-400 mt-0.5">Total {{ $products->total() }} produk</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
       class="flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
        <i class="ti ti-plus text-lg"></i> Tambah Produk
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">Produk</th>
                    <th class="px-5 py-3 text-left">Kategori</th>
                    <th class="px-5 py-3 text-left">Harga</th>
                    <th class="px-5 py-3 text-left">Stok</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="w-10 h-10 rounded-xl object-cover">
                                @else
                                <i class="ti ti-package text-gray-400 text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ Str::limit($product->name, 35) }}</div>
                                <div class="text-xs text-gray-400">{{ $product->material }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="{{ $product->stock <= 5 ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                                <i class="ti ti-edit text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Hapus produk ini?')">
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
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <i class="ti ti-package text-4xl mb-3 block"></i>
                        Belum ada produk. <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:underline">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
</div>
@endsection