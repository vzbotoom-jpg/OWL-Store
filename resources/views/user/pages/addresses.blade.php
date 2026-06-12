@extends('layouts.app')
@section('title', 'Alamat Saya — OWL Store')

@section('content')
<div class="bg-gray-100 min-h-screen pb-20">

    {{-- Header --}}
    <div class="bg-[#1a2744] px-4 py-4 sticky top-16 z-40">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.dashboard') }}"
                   class="w-9 h-9 flex items-center justify-center text-blue-200 hover:text-white transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <h1 class="font-bold text-white text-lg">Alamat Saya</h1>
            </div>
            <button onclick="openAddressModal()"
                    class="bg-[#e8a020] text-[#1a2744] text-sm font-semibold px-4 py-2 rounded-xl hover:bg-[#d4911a] transition-colors">
                <i class="ti ti-plus mr-1"></i> Tambah
            </button>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="ti ti-circle-check text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif

        @forelse($addresses as $address)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-gray-800">{{ $address->label }}</h3>
                            @if($address->is_default)
                            <span class="bg-[#e8a020]/10 text-[#e8a020] text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                Utama
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="editAddress({{ $address->id }})"
                                class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="ti ti-edit text-sm"></i>
                        </button>
                        @if(!$address->is_default)
                        <button onclick="deleteAddress({{ $address->id }})"
                                class="w-8 h-8 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors">
                            <i class="ti ti-trash text-sm"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <div class="space-y-1 text-sm">
                    <div class="text-gray-800">{{ $address->name }}</div>
                    <div class="text-gray-600">{{ $address->phone }}</div>
                    <div class="text-gray-500">{{ $address->address }}</div>
                    <div class="text-gray-500">{{ $address->city }}, {{ $address->province }} - {{ $address->postal_code }}</div>
                </div>
                @if(!$address->is_default)
                <button onclick="setDefaultAddress({{ $address->id }})"
                        class="mt-3 text-xs text-blue-600 font-medium hover:underline">
                    Jadikan Alamat Utama
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl py-16 flex flex-col items-center justify-center">
            <i class="ti ti-map-pin-off text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 text-sm font-medium">Belum ada alamat</p>
            <p class="text-gray-300 text-xs mt-1">Tambahkan alamat pengiriman Anda</p>
            <button onclick="openAddressModal()"
                    class="mt-5 bg-[#1a2744] text-white text-sm font-medium px-6 py-2.5 rounded-xl hover:bg-[#232f3e] transition-colors">
                <i class="ti ti-plus mr-1"></i> Tambah Alamat
            </button>
        </div>
        @endforelse

    </div>
</div>

{{-- Address Modal --}}
<div id="addressModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-[#1a2744] px-5 py-4 sticky top-0">
            <div class="flex items-center justify-between">
                <h3 class="text-white font-bold" id="modalTitle">Tambah Alamat</h3>
                <button onclick="closeAddressModal()" class="text-blue-200 hover:text-white">
                    <i class="ti ti-x text-xl"></i>
                </button>
            </div>
        </div>
        <form id="addressForm" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="address_id" id="addressId">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Label Alamat</label>
                <select name="label" id="label" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                    <option value="Rumah">🏠 Rumah</option>
                    <option value="Kantor">🏢 Kantor</option>
                    <option value="Lainnya">📍 Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Penerima</label>
                <input type="text" name="name" id="name" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Alamat Lengkap</label>
                <textarea name="address" id="address" rows="2" required
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kota</label>
                    <input type="text" name="city" id="city" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Provinsi</label>
                    <input type="text" name="province" id="province" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kode Pos</label>
                <input type="text" name="postal_code" id="postal_code" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_default" value="1" id="is_default" class="w-4 h-4 rounded border-gray-300 text-[#e8a020]">
                <span class="text-sm text-gray-600">Jadikan alamat utama</span>
            </label>
            <button type="submit"
                    class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                Simpan Alamat
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const addressModal = document.getElementById('addressModal');
    let currentEditId = null;

    function openAddressModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Alamat';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('addressForm').action = '{{ route("user.addresses.store") }}';
        document.getElementById('addressForm').reset();
        document.getElementById('addressId').value = '';
        currentEditId = null;
        addressModal.classList.remove('hidden');
    }

    function closeAddressModal() {
        addressModal.classList.add('hidden');
    }

    function editAddress(id) {
        currentEditId = id;
        fetch(`/user/addresses/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalTitle').innerText = 'Edit Alamat';
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('addressForm').action = `/user/addresses/${id}`;
                document.getElementById('addressId').value = id;
                document.getElementById('label').value = data.label;
                document.getElementById('name').value = data.name;
                document.getElementById('phone').value = data.phone;
                document.getElementById('address').value = data.address;
                document.getElementById('city').value = data.city;
                document.getElementById('province').value = data.province;
                document.getElementById('postal_code').value = data.postal_code;
                document.getElementById('is_default').checked = data.is_default === 1;
                addressModal.classList.remove('hidden');
            });
    }

    function deleteAddress(id) {
        if (confirm('Hapus alamat ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/user/addresses/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function setDefaultAddress(id) {
        if (confirm('Jadikan alamat ini sebagai utama?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/user/addresses/${id}/default`;
            form.innerHTML = `@csrf @method('PATCH')`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    addressModal.addEventListener('click', function(e) {
        if (e.target === addressModal) closeAddressModal();
    });
</script>
@endpush
@endsection