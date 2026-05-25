@extends('admin.layouts.app')
@section('title', 'Tambah Produk')
@section('breadcrumb', 'Tambah Produk')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h1>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
          class="space-y-5">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 text-sm border-b border-gray-100 pb-3">Informasi Dasar</h3>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 @error('name') border-red-400 @enderror"
                       placeholder="Nama produk">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Kategori *</label>
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
                    <select name="badge"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        <option value="">Tidak ada</option>
                        @foreach(['Terlaris','Baru','Hot','-10%','-15%','-20%'] as $b)
                        <option value="{{ $b }}" {{ old('badge') === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Harga Jual *</label>
                    <input type="number" name="price" value="{{ old('price') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] @error('price') border-red-400 @enderror"
                           placeholder="0">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Harga Asli (coret)</label>
                    <input type="number" name="price_original" value="{{ old('price_original') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="0 (opsional)">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Stok *</label>
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
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Deskripsi Singkat</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                          placeholder="Deskripsi singkat produk">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 text-sm border-b border-gray-100 pb-3">Spesifikasi</h3>

            <div class="grid grid-cols-2 gap-4">
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

            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_custom" value="1" {{ old('is_custom') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                    Bisa Custom
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                    Produk Unggulan
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                    Aktif/Tampilkan
                </label>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 text-sm border-b border-gray-100 pb-3 mb-4">Foto Produk</h3>
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-[#e8a020] transition-colors">
                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden"
                       onchange="previewImage(event)">
                <div id="previewWrap" class="hidden mb-4">
                    <img id="preview" class="w-32 h-32 object-cover rounded-xl mx-auto">
                </div>
                <i class="ti ti-cloud-upload text-3xl text-gray-300 mb-2 block"></i>
                <p class="text-sm text-gray-400 mb-3">Klik untuk upload foto produk</p>
                <button type="button" onclick="document.getElementById('imageInput').click()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium px-4 py-2 rounded-lg transition-colors">
                    Pilih Foto
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-3 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="ti ti-check text-lg"></i> Simpan Produk
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
</script>
@endpush
@endsection