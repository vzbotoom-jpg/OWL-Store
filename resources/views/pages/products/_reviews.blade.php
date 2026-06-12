{{-- Review Summary --}}
<div class="flex flex-col md:flex-row gap-8 mb-8">
    {{-- Overall Rating --}}
    <div class="text-center md:text-left">
        <div class="text-5xl font-bold text-gray-800">{{ number_format($product->rating ?? 5, 1) }}</div>
        <div class="flex justify-center md:justify-start gap-0.5 my-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="ti ti-star text-lg {{ $i <= ($product->rating ?? 5) ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
            @endfor
        </div>
        <div class="text-sm text-gray-500">{{ number_format($product->review_count ?? 0) }} ulasan</div>
    </div>
    
    {{-- Rating Distribution --}}
    <div class="flex-1 space-y-2">
        @php
            $ratingDistribution = [
                5 => $product->reviews->where('rating', 5)->count(),
                4 => $product->reviews->where('rating', 4)->count(),
                3 => $product->reviews->where('rating', 3)->count(),
                2 => $product->reviews->where('rating', 2)->count(),
                1 => $product->reviews->where('rating', 1)->count(),
            ];
            $total = $product->review_count ?? 1;
        @endphp
        @for($star = 5; $star >= 1; $star--)
            @php $percentage = $total > 0 ? ($ratingDistribution[$star] / $total) * 100 : 0; @endphp
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-600 w-8">{{ $star }} ★</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2">
                    <div class="bg-[#e8a020] h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <span class="text-xs text-gray-400 w-10">{{ number_format($percentage, 0) }}%</span>
            </div>
        @endfor
    </div>
</div>

{{-- Write Review Button --}}
@auth
<button onclick="openWriteReviewModal()"
        class="mb-6 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-5 py-2.5 rounded-xl transition-colors">
    <i class="ti ti-edit mr-1"></i> Tulis Ulasan
</button>
@else
<a href="{{ route('login') }}" class="inline-block mb-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-5 py-2.5 rounded-xl transition-colors">
    <i class="ti ti-login mr-1"></i> Login untuk menulis ulasan
</a>
@endauth

{{-- Reviews List --}}
<div class="space-y-5">
    @forelse($product->reviews->where('is_approved', true) as $review)
    <div class="bg-white rounded-xl p-5 border border-gray-100">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 bg-[#1a2744] rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center flex-wrap gap-2 mb-1">
                    <span class="font-semibold text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</span>
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star text-xs {{ $i <= $review->rating ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                </div>
                <div class="text-xs text-gray-400 mb-3">
                    {{ $review->created_at->format('d M Y') }}
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                @if($review->image)
                <div class="mt-3">
                    <img src="{{ Storage::url($review->image) }}" class="w-20 h-20 object-cover rounded-lg cursor-pointer" onclick="window.open(this.src)">
                </div>
                @endif
                
                {{-- Reply from Admin --}}
                @if($review->reply)
                <div class="mt-3 pl-4 border-l-2 border-[#e8a020]">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="ti ti-flame text-[#e8a020] text-xs"></i>
                        <span class="text-xs font-semibold text-[#1a2744]">OWL Store</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $review->reply }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="ti ti-star text-4xl mb-3 block"></i>
        <p>Belum ada ulasan untuk produk ini</p>
        <p class="text-xs mt-1">Jadilah yang pertama memberikan ulasan!</p>
    </div>
    @endforelse
</div>

{{-- Write Review Modal --}}
<div id="writeReviewModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold">Tulis Ulasan</h3>
            <button onclick="closeWriteReviewModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-2">Rating</label>
                <div class="flex gap-2" id="ratingStars">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="ti ti-star text-2xl text-gray-300 cursor-pointer hover:text-[#e8a020] transition-colors" data-rating="{{ $i }}"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingValue" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Ulasan</label>
                <textarea name="comment" rows="4" required
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                          placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Foto (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600">
            </div>
            <button type="submit" class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                Kirim Ulasan
            </button>
        </form>
    </div>
</div>

<script>
    let selectedRating = 0;
    
    document.querySelectorAll('#ratingStars i').forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = this.dataset.rating;
            document.getElementById('ratingValue').value = selectedRating;
            document.querySelectorAll('#ratingStars i').forEach(s => {
                const rating = parseInt(s.dataset.rating);
                if (rating <= selectedRating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-[#e8a020]');
                } else {
                    s.classList.remove('text-[#e8a020]');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });
    
    function openWriteReviewModal() {
        document.getElementById('writeReviewModal').classList.remove('hidden');
    }
    
    function closeWriteReviewModal() {
        document.getElementById('writeReviewModal').classList.add('hidden');
    }
</script>