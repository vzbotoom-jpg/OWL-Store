@extends('layouts.app')
@section('title', 'Dashboard Saya — OWL Store')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- Sapaan --}}
    <div class="bg-[#1a2744] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] text-2xl font-bold flex-shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <div class="text-white font-bold text-lg">Halo, {{ $user->name }}! 👋</div>
            <div class="text-blue-300 text-sm mt-0.5">{{ $user->email }}</div>
        </div>
        @if(session('success'))
        <div class="ml-auto bg-green-500/20 border border-green-500/30 text-green-300 text-xs px-4 py-2 rounded-xl">
            <i class="ti ti-circle-check"></i> {{ session('success') }}
        </div>
        @endif
    </div>

    {{-- Status pesanan --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['ti-clock',         'Belum Bayar',  '0', 'bg-amber-50  text-amber-600  border-amber-100'],
            ['ti-package',       'Diproses',     '0', 'bg-blue-50   text-blue-600   border-blue-100'],
            ['ti-truck',         'Dikirim',      '0', 'bg-purple-50 text-purple-600 border-purple-100'],
            ['ti-circle-check',  'Selesai',      '0', 'bg-green-50  text-green-600  border-green-100'],
        ] as [$icon, $label, $count, $class])
        <div class="bg-white rounded-2xl border p-4 text-center {{ $class }}">
            <i class="ti {{ $icon }} text-2xl mb-2 block"></i>
            <div class="text-2xl font-bold">{{ $count }}</div>
            <div class="text-xs mt-0.5">{{ $label }}</div>
        </div>
        @endforeach
    </div>

    {{-- Menu shortcut --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach([
            ['ti-shopping-bag',  route('user.orders'), 'Pesanan Saya',  'bg-blue-50   text-blue-600'],
            ['ti-heart',         route('user.wishlist'), 'Wishlist',   'bg-red-50    text-red-500'],
            ['ti-map-pin',       route('user.addresses'), 'Alamat',     'bg-green-50  text-green-600'],
            ['ti-user',          route('user.profile'), 'Profil',         'bg-amber-50  text-amber-600'],
        ] as [$icon, $url, $label, $class])
        <a href="{{ $url }}"
           class="bg-white rounded-2xl border border-gray-100 p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div class="w-12 h-12 {{ $class }} rounded-xl flex items-center justify-center mx-auto mb-3">
                <i class="ti {{ $icon }} text-2xl"></i>
            </div>
            <div class="text-sm font-semibold text-gray-700">{{ $label }}</div>
        </a>
        @endforeach
    </div>
</div>
@endsection