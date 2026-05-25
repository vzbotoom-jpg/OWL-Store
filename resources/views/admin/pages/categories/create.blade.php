@extends('admin.layouts.app')
@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Tambah Kategori')

@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Tambah Kategori</h1>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}"
          class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Nama Kategori *</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/20 @error('name') border-red-400 @enderror"
                   placeholder="Meja Kantor">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Icon (Tabler Icons)</label>
            <input type="text" name="icon" value="{{ old('icon', 'ti ti-category') }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                   placeholder="ti ti-category">
            <p class="text-xs text-gray-400 mt-1">Contoh: ti ti-layout-board, ti ti-armchair, ti ti-door</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Urutan Tampil</label>
            <input type="number" name="order" value="{{ old('order', 0) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold px-6 py-3 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="ti ti-check"></i> Simpan
            </button>
            <a href="{{ route('admin.categories.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium px-6 py-3 rounded-xl text-sm transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection