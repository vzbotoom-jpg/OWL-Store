@extends('layouts.app')
@section('title', 'Profil Saya — OWL Store')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="bg-[#1a2744] rounded-2xl p-8 mb-8">
        <div class="flex items-center gap-6 mb-6">
            <div class="w-20 h-20 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] text-4xl font-bold flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-white font-bold text-2xl mb-1">{{ $user->name }}</h1>
                <p class="text-blue-300 mb-3">{{ $user->email }}</p>
                @if($user->phone)
                <p class="text-blue-300 text-sm">📱 {{ $user->phone }}</p>
                @endif
                <p class="text-blue-400 text-xs mt-3">
                    <i class="ti ti-calendar-event"></i>
                    Bergabung: {{ $user->created_at->format('d F Y') }}
                </p>
            </div>
            <a href="{{ route('user.dashboard') }}"
               class="px-4 py-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold rounded-xl transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- Content Tabs --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Sidebar Menu --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-2">
                <a href="#profile-section"
                   class="block px-4 py-3 text-gray-700 hover:bg-amber-50 rounded-xl transition-colors font-medium">
                    <i class="ti ti-user mr-2"></i> Profil Saya
                </a>
                <a href="#addresses-section"
                   class="block px-4 py-3 text-gray-700 hover:bg-amber-50 rounded-xl transition-colors font-medium">
                    <i class="ti ti-map-pin mr-2"></i> Alamat
                </a>
                <a href="#orders-section"
                   class="block px-4 py-3 text-gray-700 hover:bg-amber-50 rounded-xl transition-colors font-medium">
                    <i class="ti ti-shopping-bag mr-2"></i> Pesanan
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-gray-100">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-colors font-medium text-left">
                        <i class="ti ti-logout mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Profile Section --}}
            <div id="profile-section" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Profil</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <p class="text-gray-600">{{ $user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <p class="text-gray-600">{{ $user->phone ?? 'Tidak ada' }}</p>
                    </div>

                    <div class="pt-4">
                        <button type="button" onclick="alert('Fitur edit profil akan segera hadir')"
                                class="px-4 py-2 bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-semibold rounded-xl transition-colors">
                            <i class="ti ti-edit mr-1"></i> Edit Profil
                        </button>
                    </div>
                </div>
            </div>

            {{-- Addresses Section --}}
            <div id="addresses-section" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Alamat Saya</h2>
                    <button type="button" onclick="alert('Fitur tambah alamat akan segera hadir')"
                            class="px-3 py-1.5 bg-[#e8a020] text-[#1a2744] text-sm font-semibold rounded-lg hover:bg-[#d4911a] transition-colors">
                        <i class="ti ti-plus mr-1"></i> Tambah
                    </button>
                </div>

                @if($user->addresses->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->addresses as $address)
                        <div class="p-4 border border-gray-100 rounded-xl">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-800">{{ $address->label ?? 'Alamat' }}</h3>
                                @if($address->is_default)
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold">
                                    Utama
                                </span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-sm">{{ $address->address }}</p>
                            <p class="text-gray-600 text-sm">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                            <p class="text-gray-600 text-sm mt-2">
                                <i class="ti ti-phone"></i> {{ $address->phone }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                @else
                <div class="py-8 text-center text-gray-400">
                    <i class="ti ti-map-pin text-4xl mb-3 block"></i>
                    <p>Anda belum menambahkan alamat</p>
                </div>
                @endif
            </div>

            {{-- Orders Section --}}
            <div id="orders-section" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Pesanan Saya</h2>

                @if($user->orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->orders as $order)
                        <div class="p-4 border border-gray-100 rounded-xl">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm text-gray-500">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    <p class="font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : ($order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-xs">{{ $order->created_at->format('d F Y H:i') }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                <div class="py-8 text-center text-gray-400">
                    <i class="ti ti-shopping-bag text-4xl mb-3 block"></i>
                    <p>Anda belum melakukan pesanan</p>
                </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
