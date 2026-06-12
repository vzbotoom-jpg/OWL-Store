@extends('admin.layouts.app')
@section('title', 'Tambah Produk')
@section('breadcrumb', 'Tambah Produk')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h1>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-semibold text-gray-700">Informasi Dasar</h3>
                <span class="text-xs text-red-500">* Wajib diisi</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 @error('name') border-red-400 @enderror"
                           placeholder="Contoh: Meja Kantor Besi Minimalis">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] @error('category_id') border-red-400 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Badge</label>
                    <select name="badge" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        <option value="">Tidak ada</option>
                        @foreach(['Terlaris', 'Baru', 'Hot', '-10%', '-15%', '-20%', '-30%'] as $b)
                        <option value="{{ $b }}" {{ old('badge') === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Harga Jual <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020] @error('price') border-red-400 @enderror"
                               placeholder="0">
                    </div>
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Harga Asli (Coret)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">Rp</span>
                        <input type="number" name="price_original" value="{{ old('price_original') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#e8a020]"
                               placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] @error('stock') border-red-400 @enderror">
                    @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Berat (kg)</label>
                    <input type="text" name="weight" value="{{ old('weight') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="Contoh: ±18 kg">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Deskripsi Singkat</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                              placeholder="Deskripsi singkat produk...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Specifications --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">Spesifikasi Produk</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Material</label>
                    <input type="text" name="material" value="{{ old('material') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="Besi hollow + kaca tempered">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Finishing</label>
                    <input type="text" name="finishing" value="{{ old('finishing') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="Cat powder coating">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Ukuran</label>
                    <input type="text" name="size" value="{{ old('size') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="120×60×75 cm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Waktu Produksi</label>
                    <input type="text" name="production_days" value="{{ old('production_days') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="3–5 hari kerja">
                </div>
            </div>

            <div class="flex flex-wrap gap-5 pt-3">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_custom" value="1" {{ old('is_custom') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-[#e8a020] focus:ring-[#e8a020]">
                    Bisa Custom Order
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-[#e8a020] focus:ring-[#e8a020]">
                    Produk Unggulan (Featured)
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-4 h-4 rounded border-gray-300 text-[#e8a020] focus:ring-[#e8a020]">
                    Aktif / Tampilkan di Toko
                </label>
            </div>
        </div>

        {{-- Product Images --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3 mb-4">Foto Produk</h3>
            
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-[#e8a020] transition-colors cursor-pointer"
                 onclick="document.getElementById('imageInput').click()">
                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(event)">
                <div id="previewWrap" class="hidden mb-4">
                    <img id="preview" class="w-32 h-32 object-cover rounded-xl mx-auto">
                </div>
                <i class="ti ti-cloud-upload text-3xl text-gray-300 mb-2 block"></i>
                <p class="text-sm text-gray-400 mb-3">Klik atau drag & drop untuk upload foto</p>
                <p class="text-xs text-gray-300">Rekomendasi ukuran: 800x800px, maks 2MB</p>
            </div>

            {{-- Multiple Images (Optional) --}}
            <div class="mt-4">
                <label class="block text-xs font-semibold text-gray-500 mb-2">Galeri Produk (Opsional)</label>
                <input type="file" name="gallery[]" multiple accept="image/*"
                       class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl px-4 py-2.5">
                <p class="text-xs text-gray-400 mt-1">Bisa pilih lebih dari satu gambar</p>
            </div>
        </div>

        {{-- SEO Section --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">SEO (Search Engine Optimization)</h3>
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                       placeholder="Judul untuk SEO (kosongkan untuk menggunakan nama produk)">
                <p class="text-xs text-gray-400 mt-1">Rekomendasi: 50-60 karakter</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Meta Description</label>
                <textarea name="meta_description" rows="2"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                          placeholder="Deskripsi untuk SEO...">{{ old('meta_description') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Rekomendasi: 150-160 karakter</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-8 py-3 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="ti ti-device-floppy text-lg"></i> Simpan Produk
            </button>
            <button type="button" onclick="saveAndAddNew()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-3 rounded-xl text-sm transition-colors">
                Simpan & Tambah Lagi
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

    function saveAndAddNew() {
        const form = document.querySelector('form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'save_and_new';
        input.value = '1';
        form.appendChild(input);
        form.submit();
    }
</script>
@endpush
@endsection