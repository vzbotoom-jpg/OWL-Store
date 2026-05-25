@extends('admin.layouts.app')
@section('title', 'Kelola Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800">Kelola Kategori</h1>
    <a href="{{ route('admin.categories.create') }}"
       class="flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
        <i class="ti ti-plus text-lg"></i> Tambah Kategori
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($categories as $cat)
    <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="{{ $cat->icon ?? 'ti ti-category' }} text-blue-600 text-2xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-800">{{ $cat->name }}</div>
            <div class="text-xs text-gray-400 mt-0.5">{{ $cat->products_count }} produk</div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.categories.edit', $cat) }}"
               class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                <i class="ti ti-edit text-sm"></i>
            </a>
            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                  onsubmit="return confirm('Hapus kategori ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors">
                    <i class="ti ti-trash text-sm"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">
        <i class="ti ti-category text-4xl mb-3 block"></i>
        Belum ada kategori
    </div>
    @endforelse
</div>
@endsection