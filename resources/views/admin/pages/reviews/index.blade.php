@extends('admin.layouts.app')
@section('title', 'Kelola Review')
@section('breadcrumb', 'Review')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Review</h1>
        <p class="text-sm text-gray-400 mt-0.5">Total {{ $reviews->total() }} review</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">Pengguna</th>
                    <th class="px-5 py-3 text-left">Produk</th>
                    <th class="px-5 py-3 text-left">Rating</th>
                    <th class="px-5 py-3 text-left">Komentar</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#1a2744] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $review->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $review->product->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1">
                            @for($i = 0; $i < $review->rating; $i++)
                            <i class="ti ti-star-filled text-amber-400 text-sm"></i>
                            @endfor
                            @for($i = $review->rating; $i < 5; $i++)
                            <i class="ti ti-star text-gray-300 text-sm"></i>
                            @endfor
                            <span class="ml-2 text-xs font-semibold text-gray-600">{{ $review->rating }}/5</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">
                        <div class="max-w-xs truncate" title="{{ $review->comment }}">
                            {{ $review->comment }}
                        </div>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}"
                              onsubmit="return confirm('Hapus review ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors">
                                <i class="ti ti-trash text-sm"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <i class="ti ti-star text-4xl mb-3 block"></i>
                        Belum ada review
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
</div>
@endsection
