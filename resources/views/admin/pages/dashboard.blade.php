@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @foreach([
        ['ti-currency-dollar', 'Total Pendapatan', 'total_revenue', 'bg-green-500'],
        ['ti-shopping-bag', 'Total Pesanan', 'total_orders', 'bg-blue-500'],
        ['ti-package', 'Total Produk', 'total_products', 'bg-amber-500'],
        ['ti-users', 'Total Pengguna', 'total_users', 'bg-purple-500'],
    ] as [$icon, $label, $key, $color])
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 {{ $color }} rounded-xl flex items-center justify-center">
                <i class="ti {{ $icon }} text-white text-2xl"></i>
            </div>
            <span class="text-xs text-green-600 font-semibold" id="trend-{{ $key }}">-</span>
        </div>
        <div class="text-2xl font-bold text-gray-800 mb-1" id="value-{{ $key }}">-</div>
        <div class="text-xs text-gray-500">{{ $label }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="lg:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-800">Grafik Pendapatan</h3>
                <p class="text-xs text-gray-400 mt-0.5" id="last-updated">Memuat data...</p>
            </div>
            <select id="yearSelect" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 text-gray-600 outline-none">
                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                <option value="{{ date('Y')-1 }}">{{ date('Y')-1 }}</option>
                <option value="{{ date('Y')-2 }}">{{ date('Y')-2 }}</option>
            </select>
        </div>
        <canvas id="revenueChart" height="200"></canvas>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Status Pesanan</h3>
        <canvas id="orderChart" height="180"></canvas>
        <div class="mt-4 space-y-2" id="orderStatusList">
            @foreach(['Pending', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'] as $label)
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                    <span class="text-gray-600">{{ $label }}</span>
                </div>
                <span class="font-semibold text-gray-800">-</span>
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
            <p class="text-xs text-gray-400 mt-0.5" id="orders-updated">-</p>
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
            <tbody id="recentOrdersTable">
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Memuat data...</td></tr>
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
            <tbody id="lowStockTable">
                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart, orderChart;
    let refreshInterval;

    // Format number ke Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Update dashboard data via AJAX
    async function refreshDashboard() {
        try {
            const year = document.getElementById('yearSelect')?.value || new Date().getFullYear();
            const response = await fetch(`/admin/dashboard-data?year=${year}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                updateStats(data.stats);
                updateCharts(data.charts);
                updateRecentOrders(data.recent_orders);
                updateLowStock(data.low_stock);
                updateLastUpdated();
            }
        } catch (error) {
            console.error('Error refreshing dashboard:', error);
        }
    }

    // Update statistik cards
    function updateStats(stats) {
        const elements = {
            total_revenue: document.getElementById('value-total_revenue'),
            total_orders: document.getElementById('value-total_orders'),
            total_products: document.getElementById('value-total_products'),
            total_users: document.getElementById('value-total_users')
        };
        
        if (elements.total_revenue) elements.total_revenue.innerText = 'Rp ' + formatRupiah(stats.total_revenue || 0);
        if (elements.total_orders) elements.total_orders.innerText = stats.total_orders || 0;
        if (elements.total_products) elements.total_products.innerText = stats.total_products || 0;
        if (elements.total_users) elements.total_users.innerText = stats.total_users || 0;
        
        // Update trends
        const trends = document.querySelectorAll('[id^="trend-"]');
        trends.forEach(el => {
            const key = el.id.replace('trend-', '');
            if (stats[`${key}_trend`]) {
                el.innerText = stats[`${key}_trend`];
                el.className = stats[`${key}_trend_class`] || 'text-xs text-green-600 font-semibold';
            }
        });
    }

    // Update charts
    function updateCharts(charts) {
        if (revenueChart) revenueChart.destroy();
        if (orderChart) orderChart.destroy();
        
        // Revenue Chart
        revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan',
                    data: charts.revenue_data || [0,0,0,0,0,0,0,0,0,0,0,0],
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
                        ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt', font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });
        
        // Order Status Chart
        orderChart = new Chart(document.getElementById('orderChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'],
                datasets: [{
                    data: charts.order_status_data || [0,0,0,0,0],
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
        
        // Update order status list
        const statusList = document.getElementById('orderStatusList');
        if (statusList && charts.order_status_data) {
            const labels = ['Pending', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];
            const colors = ['#fbbf24', '#60a5fa', '#a78bfa', '#34d399', '#f87171'];
            statusList.innerHTML = labels.map((label, i) => `
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full" style="background: ${colors[i]}"></div>
                        <span class="text-gray-600">${label}</span>
                    </div>
                    <span class="font-semibold text-gray-800">${charts.order_status_data[i] || 0}</span>
                </div>
            `).join('');
        }
    }

    // Update recent orders table
    function updateRecentOrders(orders) {
        const tbody = document.getElementById('recentOrdersTable');
        if (!orders || orders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada pesanan</td></tr>';
            return;
        }
        
        const statusColors = {
            pending: 'bg-amber-100 text-amber-700',
            processed: 'bg-blue-100 text-blue-700',
            shipped: 'bg-purple-100 text-purple-700',
            completed: 'bg-green-100 text-green-700',
            cancelled: 'bg-red-100 text-red-700'
        };
        
        tbody.innerHTML = orders.map(order => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 font-mono text-xs text-gray-500">#${String(order.id).padStart(4, '0')}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-[#1a2744] rounded-full flex items-center justify-center text-white text-xs font-bold">
                            ${order.customer_initial || 'G'}
                        </div>
                        <span class="text-gray-700 font-medium">${order.customer_name || 'Guest'}</span>
                    </div>
                </td>
                <td class="px-5 py-3 font-semibold text-gray-800">Rp ${formatRupiah(order.total)}</td>
                <td class="px-5 py-3">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full ${statusColors[order.status] || 'bg-gray-100 text-gray-700'}">
                        ${order.status_label || order.status}
                    </span>
                </td>
                <td class="px-5 py-3 text-xs text-gray-400">${order.formatted_date}</td>
                <td class="px-5 py-3">
                    <a href="/admin/orders/${order.id}" class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                        <i class="ti ti-eye text-sm"></i>
                    </a>
                </td>
            </tr>
        `).join('');
    }

    // Update low stock table
    function updateLowStock(products) {
        const tbody = document.getElementById('lowStockTable');
        if (!products || products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Semua produk memiliki stok aman</td></tr>';
            return;
        }
        
        tbody.innerHTML = products.map(product => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            ${product.image ? `<img src="/storage/${product.image}" class="w-8 h-8 rounded-lg object-cover">` : '<i class="ti ti-package text-gray-400 text-sm"></i>'}
                        </div>
                        <span class="text-gray-700">${product.name}</span>
                    </div>
                </td>
                <td class="px-5 py-3"><span class="text-red-500 font-semibold">${product.stock}</span></td>
                <td class="px-5 py-3 text-gray-600">Rp ${formatRupiah(product.price)}</td>
                <td class="px-5 py-3">
                    <a href="/admin/products/${product.id}/edit" class="text-blue-600 hover:text-blue-800">
                        <i class="ti ti-edit"></i> Edit
                    </a>
                </td>
            </tr>
        `).join('');
    }

    // Update last updated time
    function updateLastUpdated() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const updatedElement = document.getElementById('last-updated');
        const ordersUpdated = document.getElementById('orders-updated');
        
        if (updatedElement) updatedElement.innerText = `Terakhir diperbarui: ${timeString}`;
        if (ordersUpdated) ordersUpdated.innerText = `Terakhir diperbarui: ${timeString}`;
    }

    // Start real-time refresh (every 10 seconds)
    function startRealTimeRefresh() {
        refreshDashboard();
        refreshInterval = setInterval(refreshDashboard, 10000); // 10 detik
    }

    // Stop real-time refresh (call when leaving page)
    function stopRealTimeRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }
    }

    // Year selector change event
    document.getElementById('yearSelect')?.addEventListener('change', function() {
        refreshDashboard();
    });

    // Start refresh when page loads
    document.addEventListener('DOMContentLoaded', startRealTimeRefresh);
    
    // Stop refresh when page is hidden (optional, untuk performa)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopRealTimeRefresh();
        } else {
            startRealTimeRefresh();
        }
    });
</script>
@endpush