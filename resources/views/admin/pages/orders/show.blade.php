@extends('admin.layouts.app')
@section('title', 'Detail Pesanan')
@section('breadcrumb', 'Detail Pesanan')

@section('content')
<div class="max-w-5xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.orders.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">
            Detail Pesanan #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
        <span class="ml-auto text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i:s') }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
        
        {{-- Customer Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-4">
                <i class="ti ti-user text-[#e8a020] text-xl"></i>
                <h3 class="font-semibold text-gray-800">Informasi Pelanggan</h3>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama:</span>
                    <span class="text-gray-800 font-medium">{{ $order->user->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email:</span>
                    <span class="text-gray-800">{{ $order->user->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Telepon:</span>
                    <span class="text-gray-800">{{ $order->user->phone ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Member sejak:</span>
                    <span class="text-gray-800">{{ $order->user->created_at->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-4">
                <i class="ti ti-truck text-[#e8a020] text-xl"></i>
                <h3 class="font-semibold text-gray-800">Informasi Pengiriman</h3>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Alamat:</span>
                    <span class="text-gray-800 text-right">{{ $order->shipping_address ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">No. Resi:</span>
                    <span class="text-gray-800">
                        @if($order->resi)
                        {{ $order->resi }}
                        <a href="#" onclick="trackShipment('{{ $order->resi }}')" class="text-blue-500 text-xs ml-2">Lacak</a>
                        @else
                        <span class="text-gray-400">Belum ada</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kurir:</span>
                    <span class="text-gray-800">{{ $order->shipping_courier ?? '-' }}</span>
                </div>
            </div>
            @if(!$order->resi && $order->status === 'processed')
            <div class="mt-3">
                <input type="text" id="resiInput" placeholder="Masukkan No. Resi"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <button onclick="updateResi({{ $order->id }})"
                        class="mt-2 w-full bg-[#1a2744] text-white text-sm py-2 rounded-lg hover:bg-[#232f3e] transition-colors">
                    <i class="ti ti-truck mr-1"></i> Update Resi
                </button>
            </div>
            @endif
        </div>

        {{-- Payment Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-4">
                <i class="ti ti-credit-card text-[#e8a020] text-xl"></i>
                <h3 class="font-semibold text-gray-800">Informasi Pembayaran</h3>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Metode:</span>
                    <span class="text-gray-800">{{ ucfirst($order->payment_method ?? '-') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status:</span>
                    <span class="font-semibold px-2 py-0.5 rounded-full text-xs
                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'failed' ? 'Gagal' : 'Belum Bayar') }}
                    </span>
                </div>
                @if($order->payment_status !== 'paid')
                <button onclick="markAsPaid({{ $order->id }})"
                        class="mt-2 w-full bg-green-500 text-white text-sm py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="ti ti-circle-check mr-1"></i> Tandai Lunas
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Item Pesanan</h3>
            <button onclick="exportOrderItems({{ $order->id }})"
                    class="text-xs text-[#e8a020] hover:underline">
                <i class="ti ti-file-export mr-1"></i> Export
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <th class="px-5 py-3 text-left">Produk</th>
                        <th class="px-5 py-3 text-left">SKU</th>
                        <th class="px-5 py-3 text-left">Harga</th>
                        <th class="px-5 py-3 text-left">Qty</th>
                        <th class="px-5 py-3 text-left">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if($item->product && $item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-package text-gray-400"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-800">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->product->category->name ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500">{{ $item->product->sku ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $item->qty }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-right font-semibold text-gray-800">Subtotal</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->shipping_cost)
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-right text-gray-600">Ongkos Kirim</td>
                        <td class="px-5 py-3 text-gray-600">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($order->discount)
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-right text-gray-600">Diskon</td>
                        <td class="px-5 py-3 text-red-500">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="border-t border-gray-200">
                        <td colspan="4" class="px-5 py-3 text-right font-bold text-gray-800">Total</td>
                        <td class="px-5 py-3 font-bold text-[#e8a020] text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Order Notes --}}
    @if($order->notes)
    <div class="bg-amber-50 rounded-2xl border border-amber-100 p-5 mb-5">
        <div class="flex items-center gap-2 mb-2">
            <i class="ti ti-notes text-amber-600"></i>
            <span class="font-semibold text-amber-800">Catatan Pesanan</span>
        </div>
        <p class="text-amber-700 text-sm">{{ $order->notes }}</p>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex gap-3">
        <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="inline">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" onclick="return confirm('Batalkan pesanan ini?')"
                    class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl transition-colors">
                <i class="ti ti-x mr-1"></i> Batalkan Pesanan
            </button>
        </form>
        <button onclick="printInvoice({{ $order->id }})"
                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
            <i class="ti ti-printer mr-1"></i> Print Invoice
        </button>
        <button onclick="sendWhatsAppNotification({{ $order->id }})"
                class="px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white font-medium rounded-xl transition-colors">
            <i class="ti ti-brand-whatsapp mr-1"></i> Kirim WA
        </button>
    </div>
</div>

@push('scripts')
<script>
    function updateResi(orderId) {
        const resi = document.getElementById('resiInput').value;
        if (!resi) {
            alert('Masukkan nomor resi terlebih dahulu');
            return;
        }
        
        fetch(`/admin/orders/${orderId}/resi`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ resi: resi })
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert('No Resi berhasil diupdate!');
                location.reload();
            } else {
                alert('Gagal update resi');
            }
        });
    }

    function markAsPaid(orderId) {
        if (confirm('Tandai pesanan ini sebagai LUNAS?')) {
            fetch(`/admin/orders/${orderId}/mark-paid`, {
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
    }

    function printInvoice(orderId) {
        window.open(`/admin/orders/${orderId}/print`, '_blank');
    }

    function sendWhatsAppNotification(orderId) {
        window.open(`https://wa.me/{{ $order->user->phone ?? '' }}?text=Halo%2C%20pesanan%20Anda%20#${orderId}%20sedang%20diproses.`, '_blank');
    }

    function trackShipment(resi) {
        alert('Tracking: ' + resi + '\nFitur tracking akan segera hadir');
    }

    function exportOrderItems(orderId) {
        window.location.href = `/admin/orders/${orderId}/export`;
    }
</script>
@endpush
@endsection