@extends('admin.layouts.app')
@section('title', 'Laporan Penjualan')
@section('breadcrumb', 'Laporan Penjualan')

@section('content')

{{-- Period Filter --}}
<div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6">
    <div class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Periode</label>
            <select id="periodType" class="text-sm border border-gray-200 rounded-lg px-3 py-2">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly" selected>Bulanan</option>
                <option value="yearly">Tahunan</option>
                <option value="custom">Kustom</option>
            </select>
        </div>
        <div id="dateRange" class="flex gap-2">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Dari</label>
                <input type="date" id="startDate" value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                       class="text-sm border border-gray-200 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Sampai</label>
                <input type="date" id="endDate" value="{{ now()->format('Y-m-d') }}"
                       class="text-sm border border-gray-200 rounded-lg px-3 py-2">
            </div>
        </div>
        <div>
            <button onclick="loadReport()"
                    class="bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-6 py-2 rounded-lg transition-colors">
                <i class="ti ti-chart-bar mr-1"></i> Tampilkan
            </button>
        </div>
        <div class="ml-auto">
            <button onclick="exportReport('pdf')"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="ti ti-file-pdf mr-1"></i> PDF
            </button>
            <button onclick="exportReport('excel')"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="ti ti-file-spreadsheet mr-1"></i> Excel
            </button>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @foreach([
        ['ti-currency-dollar', 'Total Pendapatan', 'Rp 0', 'bg-green-500', '+12%'],
        ['ti-shopping-bag', 'Total Pesanan', '0', 'bg-blue-500', 'pesanan'],
        ['ti-package', 'Total Produk Terjual', '0', 'bg-amber-500', 'unit'],
        ['ti-users', 'Pelanggan Unik', '0', 'bg-purple-500', 'orang'],
    ] as [$icon, $label, $value, $color, $trend])
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
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

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    {{-- Revenue Chart --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Grafik Pendapatan</h3>
        <canvas id="revenueChart" height="200"></canvas>
    </div>

    {{-- Orders Chart --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Grafik Pesanan</h3>
        <canvas id="ordersChart" height="200"></canvas>
    </div>
</div>

{{-- Top Products Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-800">Produk Terlaris</h3>
        <button onclick="exportTopProducts()" class="text-xs text-[#e8a020] hover:underline">Export</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <th class="px-5 py-3 text-left">Produk</th>
                    <th class="px-5 py-3 text-left">Terjual</th>
                    <th class="px-5 py-3 text-left">Pendapatan</th>
                    <th class="px-5 py-3 text-left">Rating</th>
                </tr>
            </thead>
            <tbody id="topProductsTable">
                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Daily Sales Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Rincian Penjualan Harian</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Pesanan</th>
                    <th class="px-5 py-3 text-left">Produk Terjual</th>
                    <th class="px-5 py-3 text-left">Pendapatan</th>
                    <th class="px-5 py-3 text-left">Rata-rata</th>
                </tr>
            </thead>
            <tbody id="dailySalesTable">
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Pilih periode untuk melihat data</td></tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart, ordersChart;

    document.getElementById('periodType').addEventListener('change', function() {
        const dateRange = document.getElementById('dateRange');
        if (this.value === 'custom') {
            dateRange.style.display = 'flex';
        } else {
            dateRange.style.display = 'none';
            setDateRangeByPeriod(this.value);
        }
    });

    function setDateRangeByPeriod(period) {
        const today = new Date();
        let start = new Date();
        
        switch(period) {
            case 'daily':
                start = today;
                break;
            case 'weekly':
                start.setDate(today.getDate() - 7);
                break;
            case 'monthly':
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                break;
            case 'yearly':
                start = new Date(today.getFullYear(), 0, 1);
                break;
        }
        
        document.getElementById('startDate').value = formatDate(start);
        document.getElementById('endDate').value = formatDate(today);
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function loadReport() {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        
        fetch(`/admin/reports/sales-data?start=${start}&end=${end}`)
            .then(res => res.json())
            .then(data => {
                updateCharts(data);
                updateTopProducts(data.top_products);
                updateDailySales(data.daily_sales);
                updateSummary(data.summary);
            });
    }

    function updateCharts(data) {
        const labels = data.labels;
        const revenues = data.revenues;
        const orders = data.orders;
        
        if (revenueChart) revenueChart.destroy();
        if (ordersChart) ordersChart.destroy();
        
        revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: revenues,
                    borderColor: '#e8a020',
                    backgroundColor: 'rgba(232,160,32,0.05)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' } }
                }
            }
        });
        
        ordersChart = new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pesanan',
                    data: orders,
                    backgroundColor: '#1a2744',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    }

    function updateTopProducts(products) {
        const tbody = document.getElementById('topProductsTable');
        if (!products || products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada data produk</td></tr>';
            return;
        }
        
        tbody.innerHTML = products.map(p => `
            <tr class="border-t border-gray-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="ti ti-package text-gray-400"></i>
                        </div>
                        <span class="font-medium text-gray-800">${p.name}</span>
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-600">${p.sold} unit</td>
                <td class="px-5 py-3 font-semibold text-gray-800">Rp ${formatNumber(p.revenue)}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-1">
                        ${Array(5).fill().map((_, i) => `<i class="ti ti-star ${i < p.rating ? 'text-[#e8a020]' : 'text-gray-200'} text-xs"></i>`).join('')}
                        <span class="text-xs text-gray-400 ml-1">(${p.rating})</span>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function updateDailySales(dailySales) {
        const tbody = document.getElementById('dailySalesTable');
        if (!dailySales || dailySales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada data penjualan</td></tr>';
            return;
        }
        
        tbody.innerHTML = dailySales.map(d => `
            <tr class="border-t border-gray-50">
                <td class="px-5 py-3 text-gray-600">${d.date}</td>
                <td class="px-5 py-3 text-gray-600">${d.order_count}</td>
                <td class="px-5 py-3 text-gray-600">${d.items_sold} unit</td>
                <td class="px-5 py-3 font-semibold text-gray-800">Rp ${formatNumber(d.revenue)}</td>
                <td class="px-5 py-3 text-gray-600">Rp ${formatNumber(d.average)}</td>
            </tr>
        `).join('');
    }

    function updateSummary(summary) {
        const cards = document.querySelectorAll('.grid-cols-4 .bg-white');
        if (cards.length >= 4 && summary) {
            cards[0].querySelector('.text-2xl').innerText = `Rp ${formatNumber(summary.total_revenue || 0)}`;
            cards[1].querySelector('.text-2xl').innerText = summary.total_orders || 0;
            cards[2].querySelector('.text-2xl').innerText = summary.total_items_sold || 0;
            cards[3].querySelector('.text-2xl').innerText = summary.unique_customers || 0;
        }
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function exportReport(type) {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        window.location.href = `/admin/reports/export?type=${type}&start=${start}&end=${end}`;
    }

    function exportTopProducts() {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        window.location.href = `/admin/reports/top-products-export?start=${start}&end=${end}`;
    }

    // Load initial report
    setDateRangeByPeriod('monthly');
    loadReport();
</script>
@endpush
@endsection