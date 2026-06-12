@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @foreach([
        ['ti-currency-dollar', 'Total Pendapatan', 'Rp '.number_format($stats['total_revenue'] ?? 0, 0, ',', '.'), 'bg-green-500', '+12%'],
        ['ti-shopping-bag', 'Total Pesanan', $stats['total_orders'] ?? 0, 'bg-blue-500', $stats['pending_orders'] ?? 0 .' pending'],
        ['ti-package', 'Total Produk', $stats['total_products'] ?? 0, 'bg-amber-500', 'Aktif'],
        ['ti-users', 'Total Pengguna', $stats['total_users'] ?? 0, 'bg-purple-500', 'Terdaftar'],
    ] as [$icon, $label, $value, $color, $trend])
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 {{ $color }} rounded-xl flex items-center justify-center">
                <i class="ti {{ $icon }} text-white text-2xl"></i>
            </div>
            <span class="text-xs text-green-600 font-semibold">{{ $trend }}</span>
        </div>
        <div class="text-2xl font-bold text-gray-800 mb-1">{{ $value }}</div>
        <div class="text-xs text-gray-500">{{ $label }}</div>
    </div>
    @endforeach
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-800">Grafik Pendapatan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pendapatan per bulan tahun 2026</p>
            </div>
            <select class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 text-gray-600 outline-none">
                <option>2026</option>
                <option>2025</option>
                <option>2024</option>
            </select>
        </div>
        <canvas id="revenueChart" height="200"></canvas>
    </div>

    {{-- Order Status Chart --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Status Pesanan</h3>
        <canvas id="orderChart" height="180"></canvas>
        <div class="mt-4 space-y-2">
            @foreach([
                ['Pending', 'bg-amber-400', $stats['pending_orders'] ?? 0],
                ['Diproses', 'bg-blue-400', $stats['processed_orders'] ?? 0],
                ['Dikirim', 'bg-purple-400', $stats['shipped_orders'] ?? 0],
                ['Selesai', 'bg-green-400', $stats['completed_orders'] ?? 0],
                ['Dibatalkan', 'bg-red-400', $stats['cancelled_orders'] ?? 0],
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

{{-- Recent Orders --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div>
            <h3 class="font-bold text-gray-800">Pesanan Terbaru</h3>
            <p class="text-xs text-gray-400 mt-0.5">5 pesanan terakhir</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#e8a020] hover:underline flex items-center gap-1">
            Lihat semua <i class="ti ti-arrow-right text-xs"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">ID Pesanan</th>
                    <th class="px-5 py-3 text-left">Pelanggan</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recent_orders ?? [] as $order)
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
                            $statusColor = match($order->status) {
                                'pending' => 'bg-amber-100 text-amber-700',
                                'processed' => 'bg-blue-100 text-blue-700',
                                'shipped' => 'bg-purple-100 text-purple-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColor }}">
                            {{ ucfirst($order->status) }}
                        </span>
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
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400">
                        <i class="ti ti-shopping-bag text-3xl mb-2 block"></i>
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Low Stock Products --}}
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Produk Stok Menipis</h3>
        <p class="text-xs text-gray-400 mt-0.5">Produk dengan stok ≤ 5</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">Produk</th>
                    <th class="px-5 py-3 text-left">Stok</th>
                    <th class="px-5 py-3 text-left">Harga</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($lowStockProducts ?? [] as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="w-8 h-8 rounded-lg object-cover">
                                @else
                                <i class="ti ti-package text-gray-400 text-sm"></i>
                                @endif
                            </div>
                            <span class="text-gray-700">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-red-500 font-semibold">{{ $product->stock }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-blue-600 hover:text-blue-800">
                            <i class="ti ti-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-gray-400">
                        <i class="ti ti-package text-3xl mb-2 block"></i>
                        Semua produk memiliki stok aman
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
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan',
                data: [2400000, 3200000, 2800000, 4100000, 3700000, 5200000, 4800000, 6100000, 5500000, 7200000, 6800000, 8500000],
                borderColor: '#e8a020',
                backgroundColor: 'rgba(232, 160, 32, 0.05)',
                borderWidth: 2.5,
                pointBackgroundColor: '#e8a020',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: {
                        callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt',
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    // Order Status Donut Chart
    const orderCtx = document.getElementById('orderChart').getContext('2d');
    new Chart(orderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'],
            datasets: [{
                data: [{{ $stats['pending_orders'] ?? 3 }}, {{ $stats['processed_orders'] ?? 5 }}, {{ $stats['shipped_orders'] ?? 8 }}, {{ $stats['completed_orders'] ?? 24 }}, {{ $stats['cancelled_orders'] ?? 2 }}],
                backgroundColor: ['#fbbf24', '#60a5fa', '#a78bfa', '#34d399', '#f87171'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush