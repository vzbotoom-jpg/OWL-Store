@extends('layouts.app')
@section('title', $product->name . ' — OWL Store')

@section('content')

{{-- Breadcrumb --}}
<div class="bg-gray-50 border-b border-gray-100 py-3 px-4">
    <div class="max-w-7xl mx-auto">
        <nav class="flex items-center gap-2 text-xs text-gray-500 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="hover:text-[#e8a020] transition-colors">
                <i class="ti ti-home text-sm"></i> Beranda
            </a>
            <i class="ti ti-chevron-right text-[10px]"></i>
            <a href="{{ route('products.index') }}" class="hover:text-[#e8a020] transition-colors">Produk</a>
            @if($product->category)
            <i class="ti ti-chevron-right text-[10px]"></i>
            <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-[#e8a020] transition-colors">
                {{ $product->category->name }}
            </a>
            @endif
            <i class="ti ti-chevron-right text-[10px]"></i>
            <span class="text-gray-800 font-medium truncate">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

        {{-- ==================== PRODUCT GALLERY ==================== --}}
        <div>
            {{-- Main Image --}}
            <div class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden mb-4 relative group">
                <div class="aspect-square flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" 
                             alt="{{ $product->name }}"
                             id="mainImage"
                             class="w-full h-full object-cover cursor-pointer">
                    @else
                        <i class="ti ti-package text-8xl text-gray-200"></i>
                    @endif
                </div>
                <button class="absolute bottom-4 right-4 w-10 h-10 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center text-white transition-colors backdrop-blur-sm"
                        onclick="openImageZoom()">
                    <i class="ti ti-zoom-in text-lg"></i>
                </button>
            </div>
            
            {{-- Thumbnails --}}
            <div class="grid grid-cols-5 gap-2">
                @if($product->image)
                <div class="aspect-square bg-gray-50 rounded-xl border-2 border-[#e8a020] overflow-hidden cursor-pointer" onclick="changeImage('{{ Storage::url($product->image) }}')">
                    <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover">
                </div>
                @endif
                @if($product->gallery && is_array($product->gallery))
                    @foreach($product->gallery as $gallery)
                    <div class="aspect-square bg-gray-50 rounded-xl border-2 border-transparent hover:border-[#e8a020] overflow-hidden cursor-pointer transition-colors" onclick="changeImage('{{ Storage::url($gallery) }}')">
                        <img src="{{ Storage::url($gallery) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- ==================== PRODUCT INFO ==================== --}}
        <div>
            {{-- Store Info --}}
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-[#1a2744] rounded-full flex items-center justify-center">
                    <i class="ti ti-flame text-[#e8a020] text-sm"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">OWL Store Official</span>
                <span class="bg-green-50 text-green-600 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1">
                    <i class="ti ti-shield-check text-[10px]"></i> Terverifikasi
                </span>
            </div>
            
            {{-- Product Name --}}
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 leading-tight mb-3">{{ $product->name }}</h1>
            
            {{-- Rating & Sold --}}
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <div class="flex items-center gap-1">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star text-sm {{ $i <= ($product->rating ?? 5) ? 'text-[#e8a020]' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                    <span class="text-sm font-semibold text-[#e8a020] ml-1">{{ number_format($product->rating ?? 5, 1) }}</span>
                </div>
                <span class="text-xs text-gray-400">{{ number_format($product->review_count ?? 0) }} ulasan</span>
                <span class="text-xs text-gray-400 border-l border-gray-200 pl-3">
                    <i class="ti ti-package"></i> {{ number_format($product->sold_count ?? 0) }} terjual
                </span>
                <span class="text-xs text-gray-400 border-l border-gray-200 pl-3">
                    <i class="ti ti-heart"></i> {{ number_format($product->wishlist_count ?? 0) }} wishlist
                </span>
            </div>
            
            {{-- Price --}}
            <div class="bg-amber-50 rounded-xl p-4 mb-5">
                @if($product->price_original && $product->price_original > $product->price)
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-sm text-gray-400 line-through">Rp {{ number_format($product->price_original, 0, ',', '.') }}</span>
                    @php
                        $discount = round((($product->price_original - $product->price) / $product->price_original) * 100);
                    @endphp
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">-{{ $discount }}%</span>
                </div>
                @endif
                <div class="text-3xl font-bold text-red-500">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
                @if($product->stock > 0 && $product->stock <= 5)
                <div class="text-xs text-red-500 mt-1">
                    <i class="ti ti-alert-circle"></i> Stok terbatas: {{ $product->stock }} unit tersisa
                </div>
                @endif
            </div>
            
            {{-- Variants --}}
            @if($product->finishing)
            <div class="mb-4">
                <div class="text-xs font-semibold text-gray-500 mb-2">Pilih Finishing:</div>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $product->finishing) as $finish)
                    <button class="variant-btn border text-xs px-4 py-2 rounded-xl transition-all
                                   border-gray-200 text-gray-600 hover:border-[#e8a020] hover:bg-amber-50">
                        {{ trim($finish) }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
            
            @if($product->size)
            <div class="mb-4">
                <div class="text-xs font-semibold text-gray-500 mb-2">Pilih Ukuran:</div>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $product->size) as $size)
                    <button class="variant-btn border text-xs px-4 py-2 rounded-xl transition-all
                                   border-gray-200 text-gray-600 hover:border-[#e8a020] hover:bg-amber-50">
                        {{ trim($size) }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Quantity --}}
            <div class="flex items-center gap-4 mb-5">
                <span class="text-xs font-semibold text-gray-500">Jumlah:</span>
                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                    <button class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors" onclick="decrementQty()">
                        <i class="ti ti-minus text-sm"></i>
                    </button>
                    <span id="qty" class="w-12 text-center text-sm font-semibold text-gray-800">1</span>
                    <button class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors" onclick="incrementQty()">
                        <i class="ti ti-plus text-sm"></i>
                    </button>
                </div>
                <span class="text-xs text-gray-400">Stok: {{ number_format($product->stock) }} unit</span>
            </div>
            
            {{-- Action Buttons --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <button onclick="addToCart({{ $product->id }})"
                        class="border-2 border-[#e8a020] text-[#e8a020] font-semibold text-sm py-3.5 rounded-xl hover:bg-amber-50 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ti ti-shopping-cart text-lg"></i> Keranjang
                </button>
                <button id="buyNowBtn"
                        class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold text-sm py-3.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ti ti-bolt text-lg"></i> Beli Sekarang
                </button>
            </div>
            
            {{-- WhatsApp Consultation --}}
            <a href="https://wa.me/6283844029190?text=Halo%2C%20saya%20ingin%20tanya%20produk%20{{ urlencode($product->name) }}"
               target="_blank"
               class="w-full bg-[#1a2744] hover:bg-[#232f3e] text-white text-sm font-medium py-3 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 mb-5">
                <i class="ti ti-brand-whatsapp text-green-400 text-lg"></i> Konsultasi via WhatsApp
            </a>
            
            {{-- Guarantee Badges --}}
            <div class="flex flex-wrap gap-3 pt-3 border-t border-gray-100">
                @foreach([
                    ['ti-shield-check', 'Garansi 1 Tahun', 'green'],
                    ['ti-refresh', 'Retur 7 Hari', 'blue'],
                    ['ti-truck', 'Gratis Ongkir Jogja', 'purple'],
                    ['ti-lock', 'Bayar Aman', 'amber'],
                ] as [$icon, $label, $color])
                <div class="flex items-center gap-1.5">
                    <i class="ti {{ $icon }} text-{{ $color }}-500 text-sm"></i>
                    <span class="text-[10px] text-gray-500">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ==================== PRODUCT DETAILS TABS ==================== --}}
    <div class="mb-10">
        <div class="border-b border-gray-200">
            <div class="flex gap-6 overflow-x-auto">
                <button class="tab-btn py-3 text-sm font-semibold border-b-2 border-[#e8a020] text-[#e8a020]" data-tab="description">
                    Deskripsi
                </button>
                <button class="tab-btn py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="specifications">
                    Spesifikasi
                </button>
                <button class="tab-btn py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="reviews">
                    Ulasan ({{ number_format($product->review_count ?? 0) }})
                </button>
            </div>
        </div>
        
        {{-- Description Tab --}}
        <div id="tab-description" class="tab-content py-6">
            <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
            @if($product->detail)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">Detail Produk</h4>
                <div class="prose prose-sm max-w-none text-gray-600">
                    {!! nl2br(e($product->detail)) !!}
                </div>
            </div>
            @endif
        </div>
        
        {{-- Specifications Tab --}}
        <div id="tab-specifications" class="tab-content py-6 hidden">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @foreach([
                        ['Material', $product->material],
                        ['Finishing', $product->finishing],
                        ['Ukuran', $product->size],
                        ['Berat', $product->weight],
                        ['Waktu Produksi', $product->production_days],
                        ['Garansi', '1 tahun garansi konstruksi'],
                        ['Pengiriman', 'Gratis area Yogyakarta · Kargo untuk luar kota'],
                    ] as $i => [$key, $val])
                    @if($val)
                    <tr class="{{ $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="py-3 px-4 text-gray-500 font-medium w-2/5">{{ $key }}</td>
                        <td class="py-3 px-4 text-gray-800">{{ $val }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Reviews Tab --}}
        <div id="tab-reviews" class="tab-content py-6 hidden">
            <div id="reviewsContainer">
                @include('pages.products._reviews', ['product' => $product])
            </div>
        </div>
    </div>

    {{-- ==================== RELATED PRODUCTS ==================== --}}
    @if($relatedProducts && $relatedProducts->count() > 0)
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="ti ti-recommend text-[#e8a020] text-2xl"></i> Produk Terkait
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <a href="{{ route('products.show', $related->slug) }}" class="block">
                    <div class="h-36 bg-gray-50 flex items-center justify-center">
                        @if($related->image)
                            <img src="{{ Storage::url($related->image) }}" class="w-full h-full object-cover">
                        @else
                            <i class="ti ti-package text-5xl text-gray-200"></i>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-gray-800 text-xs leading-tight line-clamp-2 mb-1">{{ $related->name }}</h3>
                        <div class="text-sm font-bold text-red-500">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Image Zoom Modal --}}
<div id="imageZoomModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center" style="backdrop-filter: blur(10px);" onclick="closeImageZoom()">
    <div class="relative max-w-4xl max-h-[90vh]">
        <img id="zoomImage" src="" class="max-w-full max-h-[90vh] object-contain rounded-2xl">
        <button class="absolute -top-12 right-0 text-white hover:text-[#e8a020] transition-colors" onclick="closeImageZoom()">
            <i class="ti ti-x text-3xl"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
    let currentQty = 1;
    const maxStock = {{ $product->stock }};
    
    function decrementQty() {
        if (currentQty > 1) {
            currentQty--;
            document.getElementById('qty').innerText = currentQty;
        }
    }
    
    function incrementQty() {
        if (currentQty < maxStock) {
            currentQty++;
            document.getElementById('qty').innerText = currentQty;
        }
    }
    
    function addToCart(productId) {
        const quantity = currentQty;
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Produk ditambahkan ke keranjang!', 'success');
                updateCartCount(data.cart_count);
            } else {
                showToast(data.message || 'Gagal menambahkan ke keranjang', 'error');
            }
        });
    }
    
    function toggleWishlist(event, productId) {
        event.preventDefault();
        event.stopPropagation();
        
        fetch('{{ route("wishlist.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                const btn = event.currentTarget;
                const icon = btn.querySelector('i');
                if (data.in_wishlist) {
                    icon.classList.add('text-red-500');
                    icon.classList.remove('text-gray-500');
                } else {
                    icon.classList.remove('text-red-500');
                    icon.classList.add('text-gray-500');
                }
            } else {
                showToast(data.message || 'Silakan login terlebih dahulu', 'error');
            }
        });
    }
    
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
    }
    
    function openImageZoom() {
        const mainImage = document.getElementById('mainImage');
        document.getElementById('zoomImage').src = mainImage.src;
        document.getElementById('imageZoomModal').classList.remove('hidden');
        document.getElementById('imageZoomModal').style.display = 'flex';
    }
    
    function closeImageZoom() {
        document.getElementById('imageZoomModal').classList.add('hidden');
        document.getElementById('imageZoomModal').style.display = 'none';
    }
    
    // Tabs functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-[#e8a020]', 'text-[#e8a020]');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-[#e8a020]', 'text-[#e8a020]');
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`tab-${tabId}`).classList.remove('hidden');
        });
    });
    
    // Variant selection
    document.querySelectorAll('.variant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.variant-btn').forEach(b => {
                b.classList.remove('border-[#e8a020]', 'bg-amber-50', 'text-amber-700');
                b.classList.add('border-gray-200', 'text-gray-600');
            });
            this.classList.remove('border-gray-200', 'text-gray-600');
            this.classList.add('border-[#e8a020]', 'bg-amber-50', 'text-amber-700');
        });
    });
    
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl shadow-lg text-white text-sm flex items-center gap-2 animate-fade-in-up ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'} text-base"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('animate-fade-out-down');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-badge, .absolute.-top-1.-right-2');
        if (cartBadge) cartBadge.innerText = count;
    }
</script>
@endpush

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translate(-50%, 20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    @keyframes fade-out-down {
        from {
            opacity: 1;
            transform: translate(-50%, 0);
        }
        to {
            opacity: 0;
            transform: translate(-50%, 20px);
        }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.3s ease-out forwards;
    }
    .animate-fade-out-down {
        animation: fade-out-down 0.3s ease-in forwards;
    }
</style>
@endsection