@extends('layouts.app')
@section('title', 'Chat — OWL Store')

@section('content')
<div class="bg-gray-100 min-h-screen pb-20">

    {{-- Header --}}
    <div class="bg-[#1a2744] px-4 py-4 sticky top-16 z-40">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.dashboard') }}"
               class="w-9 h-9 flex items-center justify-center text-blue-200 hover:text-white transition-colors">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <h1 class="font-bold text-white text-lg">Pesan</h1>
            @if($unreadCount > 0)
            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                {{ $unreadCount }} baru
            </span>
            @endif
        </div>
    </div>

    {{-- Info Banner --}}
    <div x-data="{ show: true }">
        <div x-show="show" class="bg-amber-50 border-b border-amber-100 px-4 py-3 flex items-start gap-3">
            <i class="ti ti-info-circle text-amber-500 text-lg flex-shrink-0 mt-0.5"></i>
            <p class="text-xs text-amber-700 leading-relaxed flex-1">
                Chat yang tidak aktif lebih dari 6 bulan akan dihapus secara bertahap untuk menjaga performa aplikasi.
            </p>
            <button @click="show = false" class="text-amber-400 hover:text-amber-600">
                <i class="ti ti-x text-sm"></i>
            </button>
        </div>
    </div>

    {{-- Start New Chat Button --}}
    <div class="p-4">
        <button onclick="openNewChatModal()"
                class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
            <i class="ti ti-message-circle-plus text-lg"></i> Chat Baru
        </button>
    </div>

    {{-- Chat List --}}
    <div class="px-4 space-y-2">
        @forelse($chats as $chat)
        <a href="{{ route('user.chat.room', $chat->id) }}"
           class="bg-white rounded-2xl p-4 flex gap-3 hover:shadow-md transition-shadow block">
            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                <div class="w-12 h-12 bg-[#1a2744] rounded-full flex items-center justify-center">
                    <i class="ti ti-message-circle text-[#e8a020] text-xl"></i>
                </div>
                @if($chat->last_message && !$chat->last_message->is_read && $chat->last_message->receiver_id === auth()->id())
                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                @endif
            </div>

            {{-- Chat Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-semibold text-gray-800 text-sm">
                        {{ $chat->admin ? 'CS OWL Store' : 'OWL Store Official' }}
                    </h3>
                    <span class="text-xs text-gray-400">
                        {{ $chat->last_message ? $chat->last_message->created_at->diffForHumans() : $chat->created_at->diffForHumans() }}
                    </span>
                </div>
                @if($chat->subject)
                <p class="text-xs text-gray-500 mb-0.5">{{ $chat->subject }}</p>
                @endif
                <p class="text-xs text-gray-400 truncate">
                    {{ $chat->last_message ? $chat->last_message->message : 'Tidak ada pesan' }}
                </p>
            </div>
        </a>
        @empty

        {{-- Empty State --}}
        <div class="bg-white rounded-2xl py-16 flex flex-col items-center justify-center">
            <i class="ti ti-message-circle-off text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 text-sm font-medium">Belum ada chat</p>
            <p class="text-gray-300 text-xs mt-1">Mulai chat dengan OWL Store</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal New Chat --}}
<div id="newChatModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="bg-[#1a2744] px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold">Chat Baru</h3>
            <button onclick="closeNewChatModal()" class="text-blue-200 hover:text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>

        <form action="{{ route('user.chat.start') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Subjek</label>
                <input type="text" name="subject" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020]"
                       placeholder="Tanyakan sesuatu...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Pesan</label>
                <textarea name="message" rows="4" required
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#e8a020] resize-none"
                          placeholder="Tulis pesan Anda..."></textarea>
            </div>
            <button type="submit"
                    class="w-full bg-[#e8a020] hover:bg-[#d4911a] text-[#1a2744] font-bold py-3 rounded-xl transition-colors">
                Kirim Pesan
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openNewChatModal() {
        document.getElementById('newChatModal').classList.remove('hidden');
    }

    function closeNewChatModal() {
        document.getElementById('newChatModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('newChatModal').addEventListener('click', function(e) {
        if (e.target === this) closeNewChatModal();
    });
</script>
@endpush
@endsection