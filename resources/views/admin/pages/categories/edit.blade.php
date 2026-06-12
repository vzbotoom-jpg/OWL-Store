@extends('admin.layouts.app')
@section('title', 'Edit Kategori')
@section('breadcrumb', 'Edit Kategori')

@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Edit Kategori</h1>
        <span class="ml-auto text-xs text-gray-400">ID: #{{ $category->id }}</span>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
            {{-- Category Name --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="categoryName" value="{{ old('name', $category->name) }}"
                       onkeyup="generateSlug()"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Slug --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Slug (URL)</label>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">{{ url('/category') }}/</span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                           class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
            </div>

            {{-- Icon --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Icon</label>
                <div class="flex gap-3">
                    <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon ?? 'ti ti-category') }}"
                           class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="ti ti-category">
                    <div id="iconPreview" class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <i class="{{ $category->icon ?? 'ti ti-category' }} text-[#e8a020] text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1">Icon dari <a href="https://tabler.io/icons" target="_blank" class="text-blue-500">Tabler Icons</a></p>
            </div>

            {{-- Order --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Urutan Tampil</label>
                <input type="number" name="order" value="{{ old('order', $category->order ?? 0) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                <p class="text-xs text-gray-400 mt-1">Semakin kecil angka, semakin atas posisinya</p>
            </div>
        </div>

        {{-- Products Count Info --}}
        <div class="bg-blue-50 rounded-2xl p-4 flex items-center gap-3">
            <i class="ti ti-package text-blue-500 text-xl"></i>
            <div class="flex-1">
                <p class="text-sm text-blue-700">Kategori ini memiliki <strong>{{ $category->products_count ?? 0 }}</strong> produk</p>
                <p class="text-xs text-blue-500 mt-0.5">Perubahan nama/icon tidak akan mempengaruhi produk yang sudah ada</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-3 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="ti ti-check"></i> Update Kategori
            </button>
            <a href="{{ route('admin.categories.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium px-6 py-3 rounded-xl text-sm transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function generateSlug() {
        const name = document.getElementById('categoryName').value;
        const slug = name.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim();
        if (slug) document.getElementById('slug').value = slug;
    }

    // Icon preview
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');
    
    iconInput.addEventListener('input', function() {
        const iconClass = this.value.trim();
        if (iconClass) {
            iconPreview.innerHTML = `<i class="${iconClass} text-[#e8a020] text-xl"></i>`;
        }
    });
</script>
@endpush
@endsection