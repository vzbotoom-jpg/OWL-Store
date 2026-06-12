@if($products->count() > 0)
    @foreach($products as $product)
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
        {{-- Product Image --}}
        <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden bg-gray-50 h-48">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" 
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="ti ti-package text-6xl text-gray-200"></i>
                </div>
            @endif
            
            {{-- Badge --}}
            @if($product->badge)
            <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-lg
                {{ $product->badge === 'Terlaris' ? 'bg-red-500 text-white' : 
                   ($product->badge === 'Baru' ? 'bg-green-500 text-white' : 
                   ($product->badge === 'Hot' ? 'bg-orange-500 text-white' : 
                   'bg-[#e8a020] text-[#1a2744]')) }}">
                {{ $product->badge }}
            </span>
            @endif
            
            {{-- Discount Badge --}}
            @if($product->price_original && $product->price_original > $product->price)
            @php
                $discount = round((($product->price_original - $product->price) / $product->price_original) * 100);
            @endphp
            <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg">
                -{{ $discount }}%
            </span>
            @endif
            
            {{-- Wishlist Button --}}
            <button class="absolute bottom-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:text-red-500 wishlist-btn"
                    data-id="{{ $product->id }}"
                    onclick="toggleWishlist(event, {{ $product->id }})">
                <i class="ti ti-heart text-gray-500 text-sm"></i>
            </button>
        </a>
        
        {{-- Product Info --}}
        <div class="p-4">
            {{-- Category --}}
            @if($product->category)
            <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">
                {{ $product->category->name }}
            </div>
            @endif
            
            {{-- Name --}}
            <a href="{{ route('products.show', $product->slug) }}">
                <h3 class="font-semibold text-gray-800 text-sm leading-tight line-clamp-2 mb-2 hover:text-[#e8a020] transition-colors">
                    {{ $product->name }}
                </h3>
            </a>
            
            {{-- Material --}}
            @if($product->material)
            <div class="text-[10px] text-gray-400 mb-2 line-clamp-1">
                <i class="ti ti-tools text-[10px] mr-1"></i> {{ $product->material }}
            </div>
            @endif
            
            {{-- Rating --}}
            <div class="flex items-center gap-1 mb-2">
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="ti ti-star text-xs {{ $i <= ($product->rating ?? 5) ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
                    @endfor
                </div>
                <span class="text-[10px] text-gray-400 ml-1">({{ number_format($product->review_count ?? 0) }})</span>
            </div>
            
            {{-- Price --}}
            <div class="mb-3">
                @if($product->price_original && $product->price_original > $product->price)
                <div class="text-xs text-gray-400 line-through">
                    Rp {{ number_format($product->price_original, 0, ',', '.') }}
                </div>
                @endif
                <div class="text-lg font-bold text-red-500">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
            </div>
            
            {{-- Add to Cart Button --}}
            <button onclick="addToCart({{ $product->id }})"
                    class="w-full bg-[#1a2744] hover:bg-[#232f3e] text-white text-xs font-semibold py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 group">
                <i class="ti ti-shopping-cart text-sm group-hover:scale-110 transition-transform"></i>
                <span class="hidden sm:inline">Tambah ke Keranjang</span>
                <span class="sm:hidden">Keranjang</span>
            </button>
        </div>
    </div>
    @endforeach
@endif