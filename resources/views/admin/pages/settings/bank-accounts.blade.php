@extends('admin.layouts.app')
@section('title', 'Rekening Bank')
@section('breadcrumb', 'Rekening Bank')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Rekening Bank</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola rekening bank untuk pembayaran</p>
        </div>
        <button onclick="openBankModal()"
                class="flex items-center gap-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
            <i class="ti ti-plus text-lg"></i> Tambah Rekening
        </button>
    </div>

    <div id="banksList" class="space-y-3">
        @forelse($banks ?? [] as $bank)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-{{ $bank['color'] ?? 'blue' }}-100 rounded-xl flex items-center justify-center">
                    <i class="ti ti-building-bank text-{{ $bank['color'] ?? 'blue' }}-600 text-2xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">{{ $bank['bank_name'] }}</div>
                    <div class="text-sm text-gray-600">{{ $bank['account_number'] }}</div>
                    <div class="text-xs text-gray-400">a.n. {{ $bank['account_name'] }}</div>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="editBank({{ $loop->index }})" class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                    <i class="ti ti-edit text-sm"></i>
                </button>
                <button onclick="deleteBank({{ $loop->index }})" class="w-8 h-8 bg-red-50 text-red-500 rounded-lg hover:bg-red-100">
                    <i class="ti ti-trash text-sm"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl py-12 text-center border border-gray-100">
            <i class="ti ti-building-bank text-5xl text-gray-200 mb-3 block"></i>
            <p class="text-gray-400">Belum ada rekening bank</p>
            <p class="text-gray-300 text-xs mt-1">Tambahkan rekening untuk pembayaran transfer</p>
        </div>
        @endforelse
    </div>

    {{-- Info Box --}}
    <div class="mt-6 bg-blue-50 rounded-2xl p-4 flex items-start gap-3">
        <i class="ti ti-info-circle text-blue-500 text-xl flex-shrink-0 mt-0.5"></i>
        <div class="text-sm text-blue-700">
            <p class="font-semibold mb-1">Informasi</p>
            <p>Rekening bank akan ditampilkan di halaman checkout sebagai opsi pembayaran transfer.</p>
        </div>
    </div>
</div>

{{-- Bank Modal --}}
<div id="bankModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold" id="bankModalTitle">Tambah Rekening</h3>
            <button onclick="closeBankModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form id="bankForm" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="bank_index" id="bankIndex">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Bank</label>
                <select name="bank_name" id="bankName" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
                    <option value="BCA">Bank BCA</option>
                    <option value="Mandiri">Bank Mandiri</option>
                    <option value="BNI">Bank BNI</option>
                    <option value="BRI">Bank BRI</option>
                    <option value="CIMB">Bank CIMB Niaga</option>
                    <option value="Danamon">Bank Danamon</option>
                    <option value="Permata">Bank Permata</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nomor Rekening</label>
                <input type="text" name="account_number" id="accountNumber" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Pemilik</label>
                <input type="text" name="account_name" id="accountName" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <button type="submit" class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                Simpan Rekening
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let banks = @json($banks ?? []);
    
    function openBankModal(index = null) {
        if (index !== null) {
            document.getElementById('bankModalTitle').innerText = 'Edit Rekening';
            const bank = banks[index];
            document.getElementById('bankName').value = bank.bank_name;
            document.getElementById('accountNumber').value = bank.account_number;
            document.getElementById('accountName').value = bank.account_name;
            document.getElementById('bankIndex').value = index;
        } else {
            document.getElementById('bankModalTitle').innerText = 'Tambah Rekening';
            document.getElementById('bankForm').reset();
            document.getElementById('bankIndex').value = '';
        }
        document.getElementById('bankModal').classList.remove('hidden');
    }
    
    function closeBankModal() {
        document.getElementById('bankModal').classList.add('hidden');
    }
    
    function editBank(index) {
        openBankModal(index);
    }
    
    function deleteBank(index) {
        if (confirm('Hapus rekening bank ini?')) {
            fetch('{{ route("admin.banks.destroy") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ index: index })
            }).then(() => location.reload());
        }
    }
    
    document.getElementById('bankForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('{{ route("admin.banks.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        }).then(() => location.reload());
    });
</script>
@endpush
@endsection