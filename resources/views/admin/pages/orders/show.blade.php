@extends('admin.layouts.app')
@section('title', 'Detail Pesanan')
@section('breadcrumb', 'Detail Pesanan')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.orders.index') }}"
           class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="ti ti-arrow-left text-gray-600"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">
            Pesanan #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">Info Pelanggan</h3>
            <div class="text-sm text-gray-600 space-y-1.5">
                <div><span class="text-gray-400">Nama:</span> {{ $order->user->name ?? '-' }}</div>
                <div><span class="text-gray-400">Email:</span> {{ $order->user->email ?? '-' }}</div>
                <div><span class="text-gray-400">Tanggal:</span> {{ $order->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">Status Pesanan</h3>
            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-3">
                @csrf @method('PATCH')
                <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#e8a020]">
                    @foreach(['pending','processed','shipped','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full bg-[#e8a020] text-[#1a2744] font-semibold py-2.5 rounded-xl text-sm hover:bg-[#d4911a] transition-colors">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm">Item Pesanan</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <th class="px-5 py-3 text-left">Produk</th>
                    <th class="px-5 py-3 text-left">Harga</th>
                    <th class="px-5 py-3 text-left">Qty</th>
                    <th class="px-5 py-3 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                <tr>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $item->name }}</td>
                    <td class="px-5 py-3 text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $item->qty }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="bg-gray-50">
                    <td colspan="3" class="px-5 py-3 text-right font-bold text-gray-800">Total</td>
                    <td class="px-5 py-3 font-bold text-[#e8a020] text-base">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection