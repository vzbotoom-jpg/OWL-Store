@extends('admin.layouts.app')
@section('title', 'Kelola Review')
@section('breadcrumb', 'Review')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Review</h1>
        <p class="text-sm text-gray-400 mt-0.5">Total {{ $reviews->total() }} review</p>
    </div>
    <div class="flex gap-2">
        <select id="ratingFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="">Semua Rating</option>
            @for($i=5; $i>=1; $i--)
            <option value="{{ $i }}">{{ $i }} Bintang</option>
            @endfor
        </select>
        <div class="relative">
            <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="searchInput" placeholder="Cari review..."
                   class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:border-[#e8a020]">
        </div>
    </div>
</div>

<div class="space-y-4">
    @forelse($reviews as $review)
    <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start gap-4">
            {{-- Avatar --}}
            <div class="w-12 h-12 bg-[#1a2744] rounded-full flex items-center justify-center text-white text-base font-bold flex-shrink-0">
                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
            </div>
            
            <div class="flex-1 min-w-0">
                {{-- Header --}}
                <div class="flex items-center flex-wrap gap-3 mb-2">
                    <span class="font-semibold text-gray-800">{{ $review->user->name ?? 'User' }}</span>
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="ti ti-star-filled text-[#e8a020] text-sm"></i>
                            @else
                                <i class="ti ti-star text-gray-300 text-sm"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y H:i') }}</span>
                </div>
                
                {{-- Product Info --}}
                <div class="flex items-center gap-2 mb-3 text-xs">
                    <span class="text-gray-500">Produk:</span>
                    <a href="{{ route('admin.products.edit', $review->product_id) }}" class="text-blue-600 hover:underline">
                        {{ $review->product->name ?? 'Produk tidak ditemukan' }}
                    </a>
                </div>
                
                {{-- Review Content --}}
                <p class="text-gray-600 text-sm leading-relaxed mb-3">{{ $review->comment }}</p>
                
                {{-- Review Image --}}
                @if($review->image)
                <div class="mb-3">
                    <img src="{{ Storage::url($review->image) }}" class="w-20 h-20 object-cover rounded-lg cursor-pointer" onclick="window.open(this.src)">
                </div>
                @endif
                
                {{-- Actions --}}
                <div class="flex gap-3 pt-2 border-t border-gray-50">
                    <button onclick="toggleReviewStatus({{ $review->id }}, this)"
                            class="text-xs text-gray-500 hover:text-[#e8a020] transition-colors">
                        <i class="ti ti-eye {{ $review->is_approved ? 'text-green-500' : 'text-gray-400' }} mr-1"></i>
                        {{ $review->is_approved ? 'Tampilkan' : 'Sembunyikan' }}
                    </button>
                    <button onclick="replyToReview({{ $review->id }})"
                            class="text-xs text-gray-500 hover:text-blue-600 transition-colors">
                        <i class="ti ti-message-circle mr-1"></i> Balas
                    </button>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}"
                          onsubmit="return confirm('Hapus review ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs text-gray-500 hover:text-red-500 transition-colors">
                            <i class="ti ti-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl py-16 flex flex-col items-center justify-center border border-gray-100">
        <i class="ti ti-star text-5xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 text-sm font-medium">Belum ada review</p>
        <p class="text-gray-300 text-xs mt-1">Review akan muncul setelah pelanggan memberikan penilaian</p>
    </div>
    @endforelse

    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
</div>

{{-- Reply Modal --}}
<div id="replyModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold">Balas Review</h3>
            <button onclick="closeReplyModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <div class="p-5">
            <textarea id="replyText" rows="4" placeholder="Tulis balasan Anda..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"></textarea>
            <div class="flex gap-3 mt-4">
                <button onclick="submitReply()"
                        class="flex-1 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                    Kirim Balasan
                </button>
                <button onclick="closeReplyModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-3 rounded-xl transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentReviewId = null;

    function toggleReviewStatus(id, btn) {
        fetch(`/admin/reviews/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => response.json()).then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function replyToReview(id) {
        currentReviewId = id;
        document.getElementById('replyModal').classList.remove('hidden');
    }

    function closeReplyModal() {
        document.getElementById('replyModal').classList.add('hidden');
        currentReviewId = null;
    }

    function submitReply() {
        const reply = document.getElementById('replyText').value;
        if (!reply.trim()) {
            alert('Masukkan balasan terlebih dahulu');
            return;
        }
        
        fetch(`/admin/reviews/${currentReviewId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ reply: reply })
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert('Balasan berhasil dikirim!');
                closeReplyModal();
                location.reload();
            }
        });
    }

    // Filter functionality
    document.getElementById('ratingFilter')?.addEventListener('change', applyFilters);
    document.getElementById('searchInput')?.addEventListener('input', applyFilters);
    
    function applyFilters() {
        const params = new URLSearchParams();
        if (document.getElementById('ratingFilter').value) params.set('rating', document.getElementById('ratingFilter').value);
        if (document.getElementById('searchInput').value) params.set('search', document.getElementById('searchInput').value);
        window.location.href = `{{ route('admin.reviews.index') }}?${params.toString()}`;
    }
</script>
@endpush
@endsection