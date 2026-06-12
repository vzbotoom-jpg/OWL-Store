@extends('admin.layouts.app')
@section('title', 'Kelola Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Kategori</h1>
        <p class="text-sm text-gray-400 mt-0.5">Atur kategori produk</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
        <i class="ti ti-plus text-lg"></i> Tambah Kategori
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($categories as $cat)
    <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow group">
        <div class="flex items-center gap-4">
            {{-- Icon --}}
            <div class="w-14 h-14 bg-gradient-to-br from-[#1a2744] to-[#232f3e] rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="{{ $cat->icon ?? 'ti ti-category' }} text-[#e8a020] text-2xl"></i>
            </div>
            
            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-800 text-lg">{{ $cat->name }}</div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-400">
                        <i class="ti ti-package mr-1"></i> {{ $cat->products_count }} produk
                    </span>
                    <span class="text-xs text-gray-300">•</span>
                    <span class="text-xs text-gray-400">
                        <i class="ti ti-sort-ascending mr-1"></i> Urutan: {{ $cat->order ?? 0 }}
                    </span>
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('admin.categories.edit', $cat) }}"
                   class="w-9 h-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                    <i class="ti ti-edit text-sm"></i>
                </a>
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                      onsubmit="return confirm('Hapus kategori {{ $cat->name }}? Semua produk dalam kategori ini akan kehilangan kategori.')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-9 h-9 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors">
                        <i class="ti ti-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
        
        {{-- Slug --}}
        <div class="mt-3 pt-3 border-t border-gray-50">
            <div class="text-xs text-gray-400 flex items-center gap-1">
                <i class="ti ti-link"></i>
                <span class="font-mono">{{ $cat->slug }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-2xl py-16 flex flex-col items-center justify-center">
        <i class="ti ti-category text-5xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 text-sm font-medium">Belum ada kategori</p>
        <p class="text-gray-300 text-xs mt-1">Tambahkan kategori untuk mengelompokkan produk</p>
        <a href="{{ route('admin.categories.create') }}" class="mt-5 bg-[#1a2744] text-white text-sm font-medium px-6 py-2.5 rounded-xl hover:bg-[#232f3e] transition-colors">
            <i class="ti ti-plus mr-1"></i> Tambah Kategori Pertama
        </a>
    </div>
    @endforelse
</div>

{{-- Reorder Hint --}}
@if($categories->count() > 1)
<div class="mt-6 bg-blue-50 rounded-2xl p-4 flex items-center gap-3">
    <i class="ti ti-info-circle text-blue-500 text-xl"></i>
    <div class="flex-1">
        <p class="text-sm text-blue-700">Urutan tampil kategori dapat diatur melalui menu Edit pada setiap kategori.</p>
        <p class="text-xs text-blue-500 mt-0.5">Semakin kecil angka urutan, semakin atas posisinya.</p>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Drag and drop reorder (optional enhancement)
    // Could be implemented with SortableJS if needed
</script>
@endpush
@endsection