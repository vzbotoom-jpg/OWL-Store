@extends('admin.layouts.app')
@section('title', 'Kelola Pengguna')
@section('breadcrumb', 'Pengguna')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Pengguna</h1>
        <p class="text-sm text-gray-400 mt-0.5">Total {{ $users->total() }} pengguna terdaftar</p>
    </div>
    <div class="flex gap-2">
        <button onclick="exportUsers()"
                class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors">
            <i class="ti ti-file-export text-lg"></i> Export
        </button>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5 flex flex-wrap gap-3 items-center justify-between">
    <div class="flex flex-wrap gap-2">
        <select id="sortBy" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="latest">Terbaru</option>
            <option value="oldest">Terlama</option>
            <option value="most_orders">Paling Banyak Pesanan</option>
        </select>
        <select id="statusFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 outline-none">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
        </select>
    </div>
    <div class="relative">
        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="searchInput" placeholder="Cari nama atau email..."
               class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:border-[#e8a020]">
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">Pengguna</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">No. HP</th>
                    <th class="px-5 py-3 text-left">Pesanan</th>
                    <th class="px-5 py-3 text-left">Total Belanja</th>
                    <th class="px-5 py-3 text-left">Bergabung</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#1a2744] rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->username ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->phone ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="font-semibold text-gray-800">{{ $user->orders_count ?? 0 }}</span>
                        <span class="text-xs text-gray-400"> pesanan</span>
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800">
                        Rp {{ number_format($user->total_spent ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer status-toggle" data-id="{{ $user->id }}"
                                   {{ $user->is_active !== false ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#e8a020] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                        </label>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}"
                               class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                                <i class="ti ti-eye text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                  onsubmit="return confirm('Hapus pengguna ini? Semua data pesanan akan terhapus.')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors">
                                    <i class="ti ti-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                        <i class="ti ti-users text-4xl mb-3 block"></i>
                        Belum ada pengguna terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
        <div class="text-xs text-gray-400">
            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
        </div>
        {{ $users->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Toggle User Status
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', async function() {
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;
            
            const response = await fetch(`/admin/users/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_active: status })
            });
            
            if (!response.ok) {
                this.checked = !this.checked;
                alert('Gagal mengubah status');
            }
        });
    });

    function exportUsers() {
        window.location.href = '{{ route("admin.users.export") }}?search=' + document.getElementById('searchInput').value;
    }
</script>
@endpush
@endsection