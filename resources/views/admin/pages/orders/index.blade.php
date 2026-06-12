@extends('admin.layouts.app')
@section('title', 'Kelola Pesanan')
@section('breadcrumb', 'Pesanan')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    @foreach([
        ['Pending', 'bg-amber-100 text-amber-700', $stats['pending'] ?? 0, 'ti-clock'],
        ['Diproses', 'bg-blue-100 text-blue-700', $stats['processed'] ?? 0, 'ti-package'],
        ['Dikirim', 'bg-purple-100 text-purple-700', $stats['shipped'] ?? 0, 'ti-truck'],
        ['Selesai', 'bg-green-100 text-green-700', $stats['completed'] ?? 0, 'ti-circle-check'],
        ['Dibatalkan', 'bg-red-100 text-red-700', $stats['cancelled'] ?? 0, 'ti-x'],
    ] as [$label, $color, $count, $icon])
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center hover:shadow-md transition-shadow">
        <i class="ti {{ $icon }} text-2xl mb-2 block {{ str_replace('text-', '', $color) }}"></i>
        <div class="text-2xl font-bold text-gray-800">{{ $count }}</div>
        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $color }}">{{ $label }}</span>
    </div>
    @endforeach
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5 flex flex-wrap gap-3 items-center justify-between">
    <div class="flex flex-wrap gap-2">
        <select id="statusFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="processed">Diproses</option>
            <option value="shipped">Dikirim</option>
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
        </select>
        <select id="paymentFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="">Semua Pembayaran</option>
            <option value="paid">Lunas</option>
            <option value="unpaid">Belum Bayar</option>
            <option value="failed">Gagal</option>
        </select>
        <input type="date" id="dateFrom" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600">
        <span class="text-gray-400 self-center">-</span>
        <input type="date" id="dateTo" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600">
    </div>
    <div class="relative">
        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="searchInput" placeholder="Cari order ID atau pelanggan..."
               class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:border-[#e8a020]">
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">ID Pesanan</th>
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
                    <td class="px-5 py-3">
                        <span class="font-mono text-xs font-semibold text-gray-700">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#1a2744] rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $order->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-400">{{ $order->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <div class="font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-400">{{ $order->items_count ?? $order->items->count() }} item</div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'failed' ? 'Gagal' : 'Belum Bayar') }}
                        </span>
                        @if($order->payment_method)
                        <div class="text-xs text-gray-400 mt-0.5">{{ ucfirst($order->payment_method) }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="status-form">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs border border-gray-200 rounded-lg px-2 py-1 outline-none focus:border-[#e8a020]">
                                @foreach(['pending', 'processed', 'shipped', 'completed', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">
                        {{ $order->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors"
                               title="Detail">
                                <i class="ti ti-eye text-sm"></i>
                            </a>
                            <button onclick="printInvoice({{ $order->id }})"
                                    class="w-8 h-8 bg-gray-50 text-gray-600 rounded-lg flex items-center justify-center hover:bg-gray-100 transition-colors"
                                    title="Print Invoice">
                                <i class="ti ti-printer text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <i class="ti ti-shopping-bag-off text-4xl mb-3 block"></i>
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
        <div class="text-xs text-gray-400">
            Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan
        </div>
        {{ $orders->links() }}
    </div>
</div>

@push('scripts')
<script>
    function printInvoice(orderId) {
        window.open(`/admin/orders/${orderId}/print`, '_blank');
    }

    // Filter functionality
    const filters = ['statusFilter', 'paymentFilter', 'dateFrom', 'dateTo', 'searchInput'];
    filters.forEach(filter => {
        document.getElementById(filter)?.addEventListener('change', applyFilters);
    });

    function applyFilters() {
        const params = new URLSearchParams();
        if (document.getElementById('statusFilter').value) params.set('status', document.getElementById('statusFilter').value);
        if (document.getElementById('paymentFilter').value) params.set('payment', document.getElementById('paymentFilter').value);
        if (document.getElementById('dateFrom').value) params.set('date_from', document.getElementById('dateFrom').value);
        if (document.getElementById('dateTo').value) params.set('date_to', document.getElementById('dateTo').value);
        if (document.getElementById('searchInput').value) params.set('search', document.getElementById('searchInput').value);
        window.location.href = `{{ route('admin.orders.index') }}?${params.toString()}`;
    }
</script>
@endpush
@endsection