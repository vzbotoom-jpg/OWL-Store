@extends('layouts.app')
@section('title', 'Pengaturan Akun — OWL Store')

@section('content')
<div class="bg-gray-100 min-h-screen pb-20">

    {{-- Header --}}
    <div class="bg-[#1a2744] px-4 py-4 sticky top-16 z-40">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.dashboard') }}"
               class="w-9 h-9 flex items-center justify-center text-blue-200 hover:text-white transition-colors">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <h1 class="font-bold text-white text-lg">Pengaturan</h1>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-4">

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="ti ti-circle-check text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Alert Error --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="ti ti-alert-circle text-red-500"></i>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- Profile Section --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800">Profil</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola informasi profil Anda</p>
                </div>
                <button onclick="openProfileModal()"
                        class="text-sm text-[#e8a020] font-medium hover:underline">
                    Edit
                </button>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-16 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" class="absolute inset-0 w-16 h-16 rounded-full object-cover">
                        @endif
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="text-gray-400 text-xs">Telepon</div>
                        <div class="text-gray-800">{{ $user->phone ?? 'Belum diisi' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs">Bergabung</div>
                        <div class="text-gray-800">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                    @if($user->bio)
                    <div class="col-span-2">
                        <div class="text-gray-400 text-xs">Bio</div>
                        <div class="text-gray-800 text-sm">{{ $user->bio }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Security Section --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Keamanan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Kelola password dan keamanan akun</p>
            </div>
            <div class="divide-y divide-gray-100">
                <button onclick="openPasswordModal()"
                        class="flex items-center justify-between w-full px-5 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="ti ti-lock text-gray-400 text-xl"></i>
                        <span class="text-sm text-gray-700">Ganti Password</span>
                    </div>
                    <i class="ti ti-chevron-right text-gray-300 text-sm"></i>
                </button>
                <div class="flex items-center justify-between px-5 py-4">
                    <div class="flex items-center gap-3">
                        <i class="ti ti-shield text-gray-400 text-xl"></i>
                        <div>
                            <span class="text-sm text-gray-700">Verifikasi 2 Langkah</span>
                            <p class="text-xs text-gray-400">Amankan akun dengan verifikasi 2 langkah</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" id="twoFactorToggle"
                               {{ $user->two_factor_enabled ?? false ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e8a020]"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Notifications Section --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Notifikasi</h3>
                <p class="text-xs text-gray-400 mt-0.5">Atur preferensi notifikasi Anda</p>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="flex items-center justify-between px-5 py-4">
                    <div>
                        <span class="text-sm text-gray-700">Notifikasi Email</span>
                        <p class="text-xs text-gray-400">Terima update pesanan via email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" id="emailNotify"
                               {{ $user->notify_email_order_status ?? true ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
                <div class="flex items-center justify-between px-5 py-4">
                    <div>
                        <span class="text-sm text-gray-700">Notifikasi WhatsApp</span>
                        <p class="text-xs text-gray-400">Terupdate update pesanan via WhatsApp</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" id="whatsappNotify"
                               {{ $user->notify_whatsapp_order_status ?? true ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
                <div class="flex items-center justify-between px-5 py-4">
                    <div>
                        <span class="text-sm text-gray-700">Notifikasi Promo</span>
                        <p class="text-xs text-gray-400">Terima promo dan penawaran menarik</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" id="promoNotify">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-red-100">
            <div class="px-5 py-4 border-b border-red-100 bg-red-50">
                <h3 class="font-bold text-red-600">Zona Bahaya</h3>
                <p class="text-xs text-red-400 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
            </div>
            <div class="p-5">
                <button onclick="openDeleteModal()"
                        class="w-full bg-white border-2 border-red-500 text-red-500 font-semibold py-3 rounded-xl hover:bg-red-50 transition-colors">
                    <i class="ti ti-trash mr-2"></i> Hapus Akun
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Edit Profile Modal --}}
<div id="profileModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold">Edit Profil</h3>
            <button onclick="closeProfileModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ $user->phone }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                       placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Bio</label>
                <textarea name="bio" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                          placeholder="Ceritakan tentang diri Anda">{{ $user->bio }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Foto Profil</label>
                <input type="file" name="avatar" accept="image/*"
                       class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl px-4 py-2.5">
            </div>
            <button type="submit"
                    class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

{{-- Change Password Modal --}}
<div id="passwordModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold">Ganti Password</h3>
            <button onclick="closePasswordModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('user.security.password') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Password Baru</label>
                <input type="password" name="new_password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]">
            </div>
            <button type="submit"
                    class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                Simpan Password
            </button>
        </form>
    </div>
</div>

{{-- Delete Account Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="bg-red-500 px-5 py-4">
            <h3 class="text-white font-bold">Hapus Akun</h3>
        </div>
        <div class="p-5">
            <p class="text-gray-600 text-sm mb-4">
                Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan dan semua data Anda akan hilang permanen.
            </p>
            <form action="{{ route('user.account.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500">
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-3 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-xl transition-colors">
                        Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openProfileModal() {
        document.getElementById('profileModal').classList.remove('hidden');
    }
    function closeProfileModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }
    
    function openPasswordModal() {
        document.getElementById('passwordModal').classList.remove('hidden');
    }
    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
    }
    
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Two Factor Toggle
    document.getElementById('twoFactorToggle')?.addEventListener('change', function(e) {
        if (e.target.checked) {
            alert('Fitur verifikasi 2 langkah akan segera hadir!');
            e.target.checked = false;
        }
    });

    // Close modals when clicking outside
    document.getElementById('profileModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeProfileModal();
    });
    document.getElementById('passwordModal')?.addEventListener('click', function(e) {
        if (e.target === this) closePasswordModal();
    });
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Notification toggles (simpan ke server via AJAX)
    const toggles = ['emailNotify', 'whatsappNotify', 'promoNotify'];
    toggles.forEach(id => {
        document.getElementById(id)?.addEventListener('change', function(e) {
            fetch('{{ route("user.settings.notifications") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    type: id,
                    value: e.target.checked
                })
            }).catch(error => console.error('Error:', error));
        });
    });
</script>
@endpush
@endsection