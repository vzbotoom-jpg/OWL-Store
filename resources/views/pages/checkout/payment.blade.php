@extends('layouts.app')
@section('title', 'Pembayaran — OWL Store')

@section('content')

<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#1a2744] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-credit-card text-[#e8a020] text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Pembayaran</h1>
            <p class="text-gray-500 text-sm mt-1">Pesanan #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        {{-- Payment Methods --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">Pilih Metode Pembayaran</h2>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach([
                    ['bank_transfer', 'Transfer Bank', 'Pilih rekening bank dan transfer sesuai nominal', 'ti-building-bank'],
                    ['qris', 'QRIS', 'Scan QR code untuk pembayaran instan', 'ti-qrcode'],
                    ['virtual_account', 'Virtual Account', 'Dapatkan nomor VA untuk pembayaran', 'ti-credit-card'],
                ] as $payment)
                <label class="flex items-start gap-4 p-5 cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="payment_method" value="{{ $payment[0] }}" class="mt-1 text-[#e8a020]" {{ $loop->first ? 'checked' : '' }}>
                    <i class="ti {{ $payment[3] }} text-gray-500 text-2xl"></i>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800">{{ $payment[1] }}</div>
                        <p class="text-xs text-gray-400">{{ $payment[2] }}</p>
                    </div>
                    <i class="ti ti-chevron-right text-gray-300"></i>
                </label>
                @endforeach
            </div>

            <div class="p-6 bg-gray-50">
                <div id="bankTransferInfo" class="space-y-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Silakan transfer ke rekening berikut:</h3>
                    <div class="space-y-3">
                        @foreach($banks as $bank)
                        <div class="bg-white rounded-xl p-4 flex items-center justify-between shadow-sm">
                            <div>
                                <div class="font-semibold text-gray-800">{{ $bank['bank_name'] }}</div>
                                <div class="text-xl font-mono font-bold text-[#1a2744] mt-1">{{ $bank['account_number'] }}</div>
                                <div class="text-xs text-gray-500">a.n. {{ $bank['account_name'] }}</div>
                            </div>
                            <button onclick="copyToClipboard('{{ $bank['account_number'] }}')" 
                                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                <i class="ti ti-copy"></i> Salin
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 mt-4">
                        <div class="flex items-start gap-3">
                            <i class="ti ti-info-circle text-amber-500 mt-0.5"></i>
                            <div class="text-sm text-amber-700">
                                <p class="font-semibold mb-1">Informasi Penting:</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Total pembayaran: <span class="font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span></li>
                                    <li>• Batas waktu pembayaran: {{ $order->created_at->addDay()->format('d M Y H:i') }}</li>
                                    <li>• Transfer sesuai nominal agar terdeteksi otomatis</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <button onclick="confirmPayment()" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors mt-2">
                        <i class="ti ti-circle-check mr-2"></i> Saya Sudah Transfer
                    </button>
                </div>

                <div id="qrisInfo" class="hidden text-center space-y-4">
                    <div class="bg-white rounded-xl p-6">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('https://owlstore.id/payment/' . $order->id) }}" 
                             alt="QRIS Code" class="w-48 h-48 mx-auto">
                        <p class="text-sm text-gray-600 mt-4">Scan QR code menggunakan aplikasi bank atau e-wallet Anda</p>
                        <p class="text-xs text-gray-400 mt-2">Nominal: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <button onclick="confirmPayment()" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors">
                        <i class="ti ti-circle-check mr-2"></i> Konfirmasi Pembayaran
                    </button>
                </div>

                <div id="vaInfo" class="hidden text-center space-y-4">
                    <div class="bg-white rounded-xl p-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-credit-card text-blue-600 text-3xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Nomor Virtual Account Anda:</p>
                        <div class="text-2xl font-mono font-bold text-[#1a2744] bg-gray-100 p-3 rounded-xl">
                            {{ $virtualAccount ?? '9123' . str_pad($order->id, 10, '0', STR_PAD_LEFT) }}
                        </div>
                        <p class="text-xs text-gray-400 mt-3">Gunakan nomor VA di atas untuk pembayaran melalui ATM, Mobile Banking, atau Internet Banking</p>
                    </div>
                    <button onclick="confirmPayment()" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors">
                        <i class="ti ti-circle-check mr-2"></i> Konfirmasi Pembayaran
                    </button>
                </div>
            </div>
        </div>

        {{-- Back to Order --}}
        <div class="text-center mt-6">
            <a href="{{ route('user.orders.show', $order->id) }}" class="text-sm text-gray-500 hover:text-[#e8a020] transition-colors">
                <i class="ti ti-arrow-left mr-1"></i> Kembali ke Detail Pesanan
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Toggle payment method info
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const bankInfo = document.getElementById('bankTransferInfo');
    const qrisInfo = document.getElementById('qrisInfo');
    const vaInfo = document.getElementById('vaInfo');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            bankInfo.classList.add('hidden');
            qrisInfo.classList.add('hidden');
            vaInfo.classList.add('hidden');
            
            if (this.value === 'bank_transfer') {
                bankInfo.classList.remove('hidden');
            } else if (this.value === 'qris') {
                qrisInfo.classList.remove('hidden');
            } else if (this.value === 'virtual_account') {
                vaInfo.classList.remove('hidden');
            }
        });
    });
    
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        showToast('Nomor rekening disalin!', 'success');
    }
    
    function confirmPayment() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        showToast('Pembayaran dikonfirmasi. Pesanan akan diproses.', 'success');
        setTimeout(() => {
            window.location.href = '{{ route("user.orders.show", $order->id) }}';
        }, 2000);
    }
    
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl shadow-lg text-white text-sm flex items-center gap-2 animate-fade-in-up ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'}"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endpush
@endsection