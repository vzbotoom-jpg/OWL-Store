@extends('admin.layouts.app')
@section('title', 'Manajemen Diskon')
@section('breadcrumb', 'Diskon')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Manajemen Diskon</h1>
        <p class="text-sm text-gray-400 mt-0.5">Kelola kupon dan promo</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.discounts.create') }}"
           class="flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
            <i class="ti ti-plus text-lg"></i> Tambah Diskon
        </a>
    </div>
</div>

{{-- Active Promos Banner --}}
<div class="bg-gradient-to-r from-[#1a2744] to-[#2a3d54] rounded-2xl p-5 mb-6 text-white">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <i class="ti ti-ticket text-[#e8a020] text-2xl mb-2 block"></i>
            <h3 class="font-bold text-lg">Promo Aktif</h3>
            <p class="text-blue-200 text-sm mt-1">Diskon dan kupon yang sedang berjalan</p>
        </div>
        <div class="text-right">
            <div class="text-3xl font-bold text-[#e8a020]" id="activeDiscountCount">0</div>
            <div class="text-xs text-blue-200">Diskon Aktif</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    {{-- Discount Types Summary --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-3">Jenis Diskon</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600"><i class="ti ti-percent text-[#e8a020] mr-2"></i> Persentase</span>
                <span class="font-semibold text-gray-800" id="percentageCount">0</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600"><i class="ti ti-currency-dollar text-[#e8a020] mr-2"></i> Nominal</span>
                <span class="font-semibold text-gray-800" id="nominalCount">0</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600"><i class="ti ti-gift text-[#e8a020] mr-2"></i> Buy X Get Y</span>
                <span class="font-semibold text-gray-800" id="bogoCount">0</span>
            </div>
        </div>
    </div>

    {{-- Usage Stats --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-3">Statistik Penggunaan</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600"><i class="ti ti-check-circle text-green-500 mr-2"></i> Digunakan</span>
                <span class="font-semibold text-gray-800" id="usedCount">0</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600"><i class="ti ti-clock text-amber-500 mr-2"></i> Belum Digunakan</span>
                <span class="font-semibold text-gray-800" id="unusedCount">0</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600"><i class="ti ti-calendar-off text-red-500 mr-2"></i> Kadaluarsa</span>
                <span class="font-semibold text-gray-800" id="expiredCount">0</span>
            </div>
        </div>
    </div>

    {{-- Total Discount Value --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-3">Total Nilai Diskon</h3>
        <div class="text-center">
            <div class="text-3xl font-bold text-green-600" id="totalDiscountValue">Rp 0</div>
            <p class="text-xs text-gray-400 mt-2">Nilai diskon yang telah diberikan</p>
        </div>
    </div>
</div>

{{-- Discount List --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-800">Daftar Diskon</h3>
        <div class="relative">
            <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="searchInput" placeholder="Cari kupon..."
                   class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:border-[#e8a020]">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <th class="px-5 py-3 text-left">Kode</th>
                    <th class="px-5 py-3 text-left">Nama</th>
                    <th class="px-5 py-3 text-left">Diskon</th>
                    <th class="px-5 py-3 text-left">Min. Belanja</th>
                    <th class="px-5 py-3 text-left">Masa Berlaku</th>
                    <th class="px-5 py-3 text-left">Penggunaan</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody id="discountsTable">
                <tr><td colspan="8" class="px-5 py-8 text-center text-gray-400">Loading...</td></tr>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100 flex justify-center">
        <div id="pagination" class="flex gap-2"></div>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div id="discountModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between sticky top-0">
            <h3 class="text-white font-bold" id="modalTitle">Tambah Diskon</h3>
            <button onclick="closeDiscountModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form id="discountForm" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="discount_id" id="discountId">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Diskon</label>
                    <input type="text" name="name" id="discountName" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="Contoh: Promo Grand Opening">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kode Kupon</label>
                    <input type="text" name="code" id="discountCode" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] uppercase"
                           placeholder="GRANDOPENING20">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tipe Diskon</label>
                    <select name="type" id="discountType" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                        <option value="percentage">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                        <option value="bogo">Buy X Get Y</option>
                    </select>
                </div>
                
                <div id="valueField">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5" id="valueLabel">Nilai Diskon</label>
                    <input type="number" name="value" id="discountValue" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="20">
                </div>
                
                <div id="bogoField" style="display: none;">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Buy X Get Y</label>
                    <div class="flex gap-2">
                        <input type="number" name="buy_qty" placeholder="Beli" class="w-1/2 border border-gray-200 rounded-xl px-4 py-3 text-sm">
                        <input type="number" name="get_qty" placeholder="Dapat" class="w-1/2 border border-gray-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Min. Belanja</label>
                    <input type="number" name="min_spend" id="minSpend" value="0"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Maks. Diskon</label>
                    <input type="number" name="max_discount" id="maxDiscount"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="Tidak terbatas">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kuota Penggunaan</label>
                    <input type="number" name="usage_limit" id="usageLimit"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                           placeholder="Tidak terbatas">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Per User</label>
                    <input type="number" name="per_user_limit" id="perUserLimit" value="1"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tanggal Mulai</label>
                    <input type="datetime-local" name="starts_at" id="startsAt"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tanggal Berakhir</label>
                    <input type="datetime-local" name="ends_at" id="endsAt"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
                
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-[#e8a020]">
                        <span class="text-sm text-gray-600">Aktif</span>
                    </label>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                Simpan Diskon
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentPage = 1;
    
    document.getElementById('discountType').addEventListener('change', function() {
        const valueField = document.getElementById('valueField');
        const bogoField = document.getElementById('bogoField');
        const valueLabel = document.getElementById('valueLabel');
        
        if (this.value === 'bogo') {
            valueField.style.display = 'none';
            bogoField.style.display = 'block';
        } else {
            valueField.style.display = 'block';
            bogoField.style.display = 'none';
            valueLabel.innerText = this.value === 'percentage' ? 'Nilai Diskon (%)' : 'Nilai Diskon (Rp)';
        }
    });
    
    function loadDiscounts() {
        const search = document.getElementById('searchInput').value;
        fetch(`/admin/discounts/data?page=${currentPage}&search=${search}`)
            .then(res => res.json())
            .then(data => {
                renderDiscounts(data.discounts);
                updateStats(data.stats);
                renderPagination(data.pagination);
            });
    }
    
    function renderDiscounts(discounts) {
        const tbody = document.getElementById('discountsTable');
        if (!discounts || discounts.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-5 py-8 text-center text-gray-400">Belum ada diskon</td></tr>';
            return;
        }
        
        tbody.innerHTML = discounts.map(d => `
            <tr class="border-t border-gray-50 hover:bg-gray-50">
                <td class="px-5 py-3">
                    <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded">${d.code}</span>
                </td>
                <td class="px-5 py-3 font-medium text-gray-800">${d.name}</td>
                <td class="px-5 py-3">
                    <span class="font-semibold text-green-600">${d.discount_display}</span>
                </td>
                <td class="px-5 py-3 text-gray-600">${d.min_spend > 0 ? 'Rp ' + formatNumber(d.min_spend) : 'Tanpa minimal'}</td>
                <td class="px-5 py-3 text-xs text-gray-500">
                    ${d.starts_at_formatted} - ${d.ends_at_formatted}
                </td>
                <td class="px-5 py-3 text-gray-600">
                    ${d.used_count} / ${d.usage_limit || '∞'}
                </td>
                <td class="px-5 py-3">
                    ${d.is_active && d.is_valid ? 
                        '<span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">Aktif</span>' : 
                        '<span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-100 text-red-700">Tidak Aktif</span>'}
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-2">
                        <button onclick="editDiscount(${d.id})" class="text-blue-600 hover:text-blue-800">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button onclick="deleteDiscount(${d.id})" class="text-red-500 hover:text-red-700">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    function updateStats(stats) {
        document.getElementById('activeDiscountCount').innerText = stats.active || 0;
        document.getElementById('percentageCount').innerText = stats.percentage || 0;
        document.getElementById('nominalCount').innerText = stats.nominal || 0;
        document.getElementById('bogoCount').innerText = stats.bogo || 0;
        document.getElementById('usedCount').innerText = stats.used || 0;
        document.getElementById('unusedCount').innerText = stats.unused || 0;
        document.getElementById('expiredCount').innerText = stats.expired || 0;
        document.getElementById('totalDiscountValue').innerText = 'Rp ' + formatNumber(stats.total_value || 0);
    }
    
    function renderPagination(pagination) {
        const container = document.getElementById('pagination');
        if (!pagination || pagination.last_page <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let html = '';
        if (pagination.current_page > 1) {
            html += `<button onclick="goToPage(${pagination.current_page - 1})" class="w-8 h-8 rounded-lg border border-gray-200">‹</button>`;
        }
        
        for (let i = 1; i <= pagination.last_page; i++) {
            if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)) {
                html += `<button onclick="goToPage(${i})" class="w-8 h-8 rounded-lg border ${i === pagination.current_page ? 'bg-[#e8a020] text-[#1a2744] border-[#e8a020]' : 'border-gray-200'}">${i}</button>`;
            } else if (i === pagination.current_page - 2 || i === pagination.current_page + 2) {
                html += `<span class="w-8 h-8 flex items-center justify-center">...</span>`;
            }
        }
        
        if (pagination.current_page < pagination.last_page) {
            html += `<button onclick="goToPage(${pagination.current_page + 1})" class="w-8 h-8 rounded-lg border border-gray-200">›</button>`;
        }
        
        container.innerHTML = html;
    }
    
    function goToPage(page) {
        currentPage = page;
        loadDiscounts();
    }
    
    function openDiscountModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Diskon';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('discountForm').action = '{{ route("admin.discounts.store") }}';
        document.getElementById('discountForm').reset();
        document.getElementById('discountId').value = '';
        document.getElementById('discountModal').classList.remove('hidden');
    }
    
    function closeDiscountModal() {
        document.getElementById('discountModal').classList.add('hidden');
    }
    
    function editDiscount(id) {
        fetch(`/admin/discounts/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalTitle').innerText = 'Edit Diskon';
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('discountForm').action = `/admin/discounts/${id}`;
                document.getElementById('discountId').value = id;
                document.getElementById('discountName').value = data.name;
                document.getElementById('discountCode').value = data.code;
                document.getElementById('discountType').value = data.type;
                document.getElementById('discountValue').value = data.value;
                document.getElementById('minSpend').value = data.min_spend;
                document.getElementById('maxDiscount').value = data.max_discount;
                document.getElementById('usageLimit').value = data.usage_limit;
                document.getElementById('perUserLimit').value = data.per_user_limit;
                document.getElementById('startsAt').value = data.starts_at?.slice(0, 16);
                document.getElementById('endsAt').value = data.ends_at?.slice(0, 16);
                document.getElementById('discountModal').classList.remove('hidden');
            });
    }
    
    function deleteDiscount(id) {
        if (confirm('Hapus diskon ini?')) {
            fetch(`/admin/discounts/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => loadDiscounts());
        }
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    document.getElementById('searchInput').addEventListener('input', () => {
        currentPage = 1;
        loadDiscounts();
    });
    
    loadDiscounts();
</script>
@endpush
@endsection