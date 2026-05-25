@extends('admin.layouts.app')
@section('title', 'Kelola Pesanan')
@section('breadcrumb', 'Pesanan')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    @foreach([
        ['Pending',    $stats['pending'],   'bg-amber-100 text-amber-700'],
        ['Diproses',   $stats['processed'], 'bg-blue-100 text-blue-700'],
        ['Dikirim',    $stats['shipped'],   'bg-purple-100 text-purple-700'],
        ['Selesai',    $stats['completed'], 'bg-green-100 text-green-700'],
        ['Dibatalkan', $stats['cancelled'], 'bg-red-100 text-red-700'],
    ] as [$label, $count, $class])
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <div class="text-2xl font-bold text-gray-800">{{ $count }}</div>
        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $class }}">{{ $label }}</span>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-800">Semua Pesanan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">ID</th>
                    <th class="px-5 py-3 text-left">Pelanggan</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Pembayaran</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3 font-medium text-gray-700">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs border border-gray-200 rounded-lg px-2 py-1 outline-none">
                                @foreach(['pending','processed','shipped','completed','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                            <i class="ti ti-eye text-sm"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <i class="ti ti-shopping-bag text-4xl mb-3 block"></i>
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
</div>
@endsection