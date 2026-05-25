@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['ti-currency-dollar', 'Total Pendapatan', 'Rp '.number_format($stats['total_revenue'],0,',','.'), 'bg-green-500'],
        ['ti-shopping-bag',    'Total Pesanan',     $stats['total_orders'],                                  'bg-blue-500'],
        ['ti-package',         'Total Produk',      $stats['total_products'],                                'bg-amber-500'],
        ['ti-users',           'Total Pengguna',    $stats['total_users'],                                   'bg-purple-500'],
    ] as [$icon, $label, $value, $color])
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="w-10 h-10 {{ $color }} rounded-xl flex items-center justify-center mb-3">
            <i class="ti {{ $icon }} text-white text-xl"></i>
        </div>
        <div class="text-2xl font-bold text-gray-800 mb-1">{{ $value }}</div>
        <div class="text-xs text-gray-500">{{ $label }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Grafik pendapatan --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-800">Pendapatan Bulanan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Grafik pendapatan tahun 2026</p>
            </div>
        </div>
        <canvas id="revenueChart" height="120"></canvas>
    </div>

    {{-- Status pesanan --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-5">Status Pesanan</h3>
        <canvas id="orderChart" height="180"></canvas>
        <div class="space-y-2 mt-4">
            @foreach([
                ['Pending',    'bg-amber-400',  $stats['pending_orders']],
                ['Selesai',    'bg-green-400',  '0'],
            ] as [$label, $color, $count])
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full {{ $color }}"></div>
                    <span class="text-gray-600">{{ $label }}</span>
                </div>
                <span class="font-semibold text-gray-800">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Pesanan terbaru --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">ID</th>
                    <th class="px-5 py-3 text-left">Pelanggan</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recent_orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-[#1a2744] rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <span class="text-gray-700 font-medium">{{ $order->user->name ?? 'Guest' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        @php
                        $sc = match($order->status) {
                            'pending'   => 'bg-amber-100 text-amber-700',
                            'processed' => 'bg-blue-100 text-blue-700',
                            'shipped'   => 'bg-purple-100 text-purple-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            default     => 'bg-gray-100 text-gray-700',
                        };
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                        <i class="ti ti-shopping-bag text-3xl mb-2 block"></i>
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('revenueChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Pendapatan',
            data: [2400000,3200000,2800000,4100000,3700000,5200000,4800000,6100000,5500000,7200000,6800000,8500000],
            borderColor: '#e8a020',
            backgroundColor: 'rgba(232,160,32,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#e8a020',
            pointRadius: 4,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => 'Rp '+(v/1000000).toFixed(1)+'jt', font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
new Chart(document.getElementById('orderChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Pending','Diproses','Dikirim','Selesai','Dibatalkan'],
        datasets: [{ data: [3,5,8,24,2], backgroundColor: ['#fbbf24','#60a5fa','#a78bfa','#34d399','#f87171'], borderWidth: 0 }]
    },
    options: { responsive: true, cutout: '70%', plugins: { legend: { display: false } } }
});
</script>
@endpush