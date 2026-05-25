@extends('admin.layouts.app')
@section('title', 'Edit Produk')
@section('breadcrumb', 'Edit Produk')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Edit Produk</h1>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 text-sm border-b border-gray-100 pb-3">Informasi Dasar</h3>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Kategori *</label>
                    <select name="category_id" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Badge</label>
                    <select name="badge" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        <option value="">Tidak ada</option>
                        @foreach(['Terlaris','Baru','Hot','-10%','-15%','-20%'] as $b)
                        <option value="{{ $b }}" {{ $product->badge === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Harga Jual *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Harga Asli (coret)</label>
                    <input type="number" name="price_original" value="{{ old('price_original', $product->price_original) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Stok *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Material</label>
                    <input type="text" name="material" value="{{ old('material', $product->material) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_custom" value="1" {{ $product->is_custom ? 'checked' : '' }} class="w-4 h-4 rounded">
                    Bisa Custom
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="w-4 h-4 rounded">
                    Produk Unggulan
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-4 h-4 rounded">
                    Aktif/Tampilkan
                </label>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 text-sm border-b border-gray-100 pb-3 mb-4">Foto Produk</h3>
            @if($product->image)
            <div class="mb-4">
                <img src="{{ Storage::url($product->image) }}" class="w-32 h-32 object-cover rounded-xl">
                <p class="text-xs text-gray-400 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
            </div>
            @endif
            <input type="file" name="image" accept="image/*"
                   class="text-sm text-gray-600 border border-gray-200 rounded-xl px-3 py-2 w-full">
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-3 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="ti ti-check text-lg"></i> Update Produk
            </button>
            <a href="{{ route('admin.products.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium px-6 py-3 rounded-xl text-sm transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection