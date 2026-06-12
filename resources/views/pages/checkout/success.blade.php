@extends('layouts.app')
@section('title', 'Pesanan Berhasil — OWL Store')

@section('content')

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full">

        {{-- Success Card --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden text-center">
            
            {{-- Success Animation --}}
            <div class="bg-gradient-to-r from-green-500 to-green-600 py-12">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto animate-bounce">
                    <i class="ti ti-circle-check text-green-500 text-5xl"></i>
                </div>
                <h2 class="text-white text-2xl font-bold mt-4">Pesanan Berhasil!</h2>
                <p class="text-green-100 text-sm mt-1">Terima kasih telah berbelanja di OWL Store</p>
            </div>

            {{-- Order Info --}}
            <div class="p-6">
                <div class="bg-gray-50 rounded-2xl p-4 mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500">Nomor Pesanan</span>
                        <span class="text-sm font-mono font-bold text-[#1a2744]">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500">Tanggal</span>
                        <span class="text-sm text-gray-800">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500">Total Pembayaran</span>
                        <span class="text-lg font-bold text-red-500">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Metode Pembayaran</span>
                        <span class="text-sm text-gray-800 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                    </div>
                </div>

                {{-- Payment Instructions (if bank transfer) --}}
                @if($order->payment_method === 'bank_transfer')
                <div class="bg-amber-50 rounded-2xl p-4 mb-5 text-left">
                    <h4 class="font-semibold text-amber-800 mb-2 flex items-center gap-2">
                        <i class="ti ti-info-circle"></i> Instruksi Pembayaran
                    </h4>
                    <p class="text-sm text-amber-700 mb-3">Silakan transfer ke rekening berikut:</p>
                    <div class="space-y-2">
                        @foreach($banks as $bank)
                        <div class="bg-white rounded-xl p-3 flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-gray-800">{{ $bank['bank_name'] }}</div>
                                <div class="text-sm font-mono text-gray-600">{{ $bank['account_number'] }}</div>
                                <div class="text-xs text-gray-400">a.n. {{ $bank['account_name'] }}</div>
                            </div>
                            <button onclick="copyToClipboard('{{ $bank['account_number'] }}')" 
                                    class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition-colors">
                                <i class="ti ti-copy"></i> Salin
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-amber-600 mt-3">
                        <i class="ti ti-alert-circle"></i> Batas waktu pembayaran: {{ $order->created_at->addDay()->format('d M Y H:i') }}
                    </p>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-3">
                    <a href="{{ route('user.orders.show', $order->id) }}" 
                       class="w-full bg-[#1a2744] hover:bg-[#232f3e] text-white font-semibold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="ti ti-eye"></i> Lihat Detail Pesanan
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="ti ti-shopping-bag"></i> Lanjutkan Belanja
                    </a>
                </div>
            </div>
        </div>

        {{-- Share & Social --}}
        <div class="text-center mt-6">
            <p class="text-xs text-gray-400 mb-3">Bagikan pesanan Anda</p>
            <div class="flex justify-center gap-3">
                <a href="https://wa.me/?text={{ urlencode('Saya baru saja berbelanja di OWL Store! Lihat katalognya di ' . route('home')) }}" 
                   target="_blank" class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition-colors">
                    <i class="ti ti-brand-whatsapp text-lg"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('home')) }}" 
                   target="_blank" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                    <i class="ti ti-brand-facebook text-lg"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode('Saya baru saja berbelanja di OWL Store!') }}" 
                   target="_blank" class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center text-white hover:bg-sky-600 transition-colors">
                    <i class="ti ti-brand-x text-lg"></i>
                </a>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        showToast('Nomor rekening disalin!', 'success');
    }
    
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl shadow-lg text-white text-sm flex items-center gap-2 animate-fade-in-up ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'}"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('animate-fade-out-down');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translate(-50%, 20px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }
    @keyframes fade-out-down {
        from { opacity: 1; transform: translate(-50%, 0); }
        to { opacity: 0; transform: translate(-50%, 20px); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.3s ease-out forwards; }
    .animate-fade-out-down { animation: fade-out-down 0.3s ease-in forwards; }
    .animate-bounce { animation: bounce 1s ease-in-out; }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush
@endsection