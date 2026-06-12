@extends('admin.layouts.app')
@section('title', 'Edit Produk')
@section('breadcrumb', 'Edit Produk')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Edit Produk</h1>
        <span class="ml-auto text-xs text-gray-400">ID: #{{ $product->id }}</span>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-semibold text-gray-700">Informasi Dasar</h3>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                        <span class="text-xs">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                        <span class="text-xs">Unggulan</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="is_custom" value="1" {{ $product->is_custom ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                        <span class="text-xs">Custom</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Kategori</label>
                    <select name="category_id" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Badge</label>
                    <select name="badge" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        <option value="">Tidak ada</option>
                        @foreach(['Terlaris', 'Baru', 'Hot', '-10%', '-15%', '-20%', '-30%'] as $b)
                        <option value="{{ $b }}" {{ $product->badge === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Harga Jual</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Harga Asli (Coret)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">Rp</span>
                        <input type="number" name="price_original" value="{{ old('price_original', $product->price_original) }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Berat (kg)</label>
                    <input type="text" name="weight" value="{{ old('weight', $product->weight) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Deskripsi Singkat</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Specifications --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">Spesifikasi Produk</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Material</label>
                    <input type="text" name="material" value="{{ old('material', $product->material) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Finishing</label>
                    <input type="text" name="finishing" value="{{ old('finishing', $product->finishing) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Ukuran</label>
                    <input type="text" name="size" value="{{ old('size', $product->size) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Waktu Produksi</label>
                    <input type="text" name="production_days" value="{{ old('production_days', $product->production_days) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
            </div>
        </div>

        {{-- Product Images --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3 mb-4">Foto Produk</h3>
            
            @if($product->image)
            <div class="mb-4 p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-4">
                    <img src="{{ Storage::url($product->image) }}" class="w-20 h-20 object-cover rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Foto saat ini</p>
                        <label class="text-xs text-red-500 cursor-pointer hover:underline">
                            <input type="checkbox" name="remove_image" value="1" class="mr-1"> Hapus foto
                        </label>
                    </div>
                </div>
            </div>
            @endif

            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-[#e8a020] transition-colors cursor-pointer"
                 onclick="document.getElementById('imageInput').click()">
                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(event)">
                <div id="previewWrap" class="hidden mb-4">
                    <img id="preview" class="w-32 h-32 object-cover rounded-xl mx-auto">
                </div>
                <i class="ti ti-cloud-upload text-3xl text-gray-300 mb-2 block"></i>
                <p class="text-sm text-gray-400">Ganti foto produk (opsional)</p>
            </div>

            {{-- Gallery Images --}}
            @if($product->gallery && count($product->gallery) > 0)
            <div class="mt-4">
                <label class="block text-xs font-semibold text-gray-500 mb-2">Galeri Produk</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->gallery as $gallery)
                    <div class="relative">
                        <img src="{{ Storage::url($gallery) }}" class="w-16 h-16 object-cover rounded-lg">
                        <button type="button" onclick="removeGalleryImage('{{ $gallery }}')"
                                class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full text-xs">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-4">
                <label class="block text-xs font-semibold text-gray-500 mb-2">Tambah Galeri (Opsional)</label>
                <input type="file" name="gallery[]" multiple accept="image/*"
                       class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl px-4 py-2.5">
            </div>
        </div>

        {{-- SEO Section --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">SEO</h3>
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Meta Description</label>
                <textarea name="meta_description" rows="2"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none">{{ old('meta_description', $product->meta_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                <p class="text-xs text-gray-400 mt-1">Contoh: meja-kantor-besi-minimalis</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-8 py-3 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="ti ti-device-floppy text-lg"></i> Update Produk
            </button>
            <a href="{{ route('admin.products.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium px-6 py-3 rounded-xl text-sm transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('previewWrap').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeGalleryImage(imagePath) {
        if (confirm('Hapus gambar dari galeri?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.products.remove-gallery", $product->id) }}';
            form.innerHTML = `@csrf @method('DELETE')<input type="hidden" name="image" value="${imagePath}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection