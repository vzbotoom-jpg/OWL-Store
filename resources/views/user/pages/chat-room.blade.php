@extends('layouts.app')
@section('title', 'Chat — OWL Store')

@section('content')
<div class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Header Chat --}}
    <div class="bg-[#1a2744] px-4 py-3 flex items-center gap-3 sticky top-16 z-40">
        <a href="{{ route('user.chat') }}"
           class="w-9 h-9 flex items-center justify-center text-blue-200 hover:text-white transition-colors">
            <i class="ti ti-arrow-left text-xl"></i>
        </a>
        <div class="w-10 h-10 bg-[#e8a020] rounded-full flex items-center justify-center flex-shrink-0">
            <i class="ti ti-headset text-white text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="text-white font-semibold text-sm">
                {{ $chat->admin ? 'CS OWL Store' : 'OWL Store Official' }}
            </div>
            <div class="text-green-400 text-xs flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                Online
            </div>
        </div>
        <a href="https://wa.me/6283844029190" target="_blank"
           class="w-9 h-9 flex items-center justify-center text-blue-200 hover:text-white transition-colors">
            <i class="ti ti-brand-whatsapp text-xl"></i>
        </a>
    </div>

    {{-- Messages --}}
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4" id="chatMessages">
        @php $lastDate = null; @endphp
        @foreach($chat->messages as $message)
            @php
                $messageDate = $message->created_at->format('Y-m-d');
                $showDate = $lastDate !== $messageDate;
                $lastDate = $messageDate;
            @endphp

            @if($showDate)
            <div class="text-center">
                <span class="bg-gray-200 text-gray-500 text-[10px] px-3 py-1 rounded-full">
                    {{ $message->created_at->format('d M Y') }}
                </span>
            </div>
            @endif

            @if($message->sender_id === auth()->id())
            {{-- User Message --}}
            <div class="flex justify-end">
                <div class="max-w-[75%]">
                    <div class="bg-[#1a2744] rounded-2xl rounded-br-sm px-4 py-2.5">
                        <p class="text-sm text-white leading-relaxed">{{ $message->message }}</p>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block text-right">
                        {{ $message->created_at->format('H:i') }}
                        <i class="ti ti-check {{ $message->is_read ? 'text-green-500' : 'text-gray-400' }} ml-1"></i>
                    </span>
                </div>
            </div>
            @else
            {{-- Admin Message --}}
            <div class="flex gap-2">
                <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-headset text-white text-sm"></i>
                </div>
                <div class="max-w-[75%]">
                    <div class="bg-white rounded-2xl rounded-bl-sm px-4 py-2.5 shadow-sm">
                        <p class="text-sm text-gray-800 leading-relaxed">{{ $message->message }}</p>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block ml-1">
                        {{ $message->created_at->format('H:i') }}
                    </span>
                </div>
            </div>
            @endif
        @endforeach
    </div>

    {{-- Quick Reply Buttons --}}
    <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
        <div class="flex flex-wrap gap-2">
            @foreach(['Tanya produk', 'Custom order', 'Cek pesanan', 'Info pengiriman', 'Komplain'] as $q)
            <button onclick="sendQuickReply('{{ $q }}')"
                    class="bg-white border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-full hover:border-[#1a2744] hover:text-[#1a2744] transition-colors">
                {{ $q }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Input Chat --}}
    <div class="bg-white border-t border-gray-100 px-4 py-3 sticky bottom-0">
        <div class="flex items-center gap-3">
            <button onclick="document.getElementById('fileInput').click()"
                    class="text-gray-400 hover:text-[#e8a020] transition-colors flex-shrink-0">
                <i class="ti ti-photo text-xl"></i>
            </button>
            <input type="file" id="fileInput" accept="image/*" class="hidden" onchange="uploadFile(this)">
            <div class="flex-1 bg-gray-100 rounded-2xl flex items-center px-4 py-2.5 gap-2">
                <input type="text" id="chatInput"
                       placeholder="Ketik pesan..."
                       class="flex-1 bg-transparent text-sm text-gray-800 outline-none"
                       onkeypress="if(event.key==='Enter') sendMessage()">
                <button onclick="openEmojiPicker()"
                        class="text-gray-400 hover:text-[#e8a020] transition-colors">
                    <i class="ti ti-mood-smile text-xl"></i>
                </button>
            </div>
            <button onclick="sendMessage()"
                    class="w-10 h-10 bg-[#e8a020] rounded-full flex items-center justify-center text-[#1a2744] hover:bg-[#d4911a] transition-colors flex-shrink-0">
                <i class="ti ti-send text-lg"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatId = {{ $chat->id }};
    let lastMessageId = {{ $chat->messages->last()->id ?? 0 }};

    function scrollToBottom() {
        const container = document.getElementById('chatMessages');
        container.scrollTop = container.scrollHeight;
    }
    scrollToBottom();

    function sendMessage() {
        const input = document.getElementById('chatInput');
        const text = input.value.trim();
        if (!text) return;

        const messages = document.getElementById('chatMessages');
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        // Add user message instantly
        const userMsg = `
            <div class="flex justify-end">
                <div class="max-w-[75%]">
                    <div class="bg-[#1a2744] rounded-2xl rounded-br-sm px-4 py-2.5">
                        <p class="text-sm text-white leading-relaxed">${escapeHtml(text)}</p>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block text-right">${time} <i class="ti ti-check"></i></span>
                </div>
            </div>`;
        messages.insertAdjacentHTML('beforeend', userMsg);
        input.value = '';
        scrollToBottom();

        // Send to server
        fetch(`/user/chat/${chatId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        }).then(response => response.json()).then(data => {
            if (data.success) {
                // Update message status
                const lastMsg = messages.lastElementChild;
                if (lastMsg && lastMsg.querySelector('.ti-check')) {
                    lastMsg.querySelector('.ti-check').classList.add('ti-check-double');
                }
            }
        });
    }

    function sendQuickReply(text) {
        document.getElementById('chatInput').value = text;
        sendMessage();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function uploadFile(input) {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('attachment', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(`/user/chat/${chatId}/upload`, {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.success) {
                const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                const messages = document.getElementById('chatMessages');
                const attachmentMsg = `
                    <div class="flex justify-end">
                        <div class="max-w-[75%]">
                            <div class="bg-[#1a2744] rounded-2xl rounded-br-sm px-4 py-2.5">
                                <a href="${data.url}" target="_blank" class="flex items-center gap-2 text-white text-sm">
                                    <i class="ti ti-file"></i> ${file.name}
                                </a>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1 block text-right">${time}</span>
                        </div>
                    </div>`;
                messages.insertAdjacentHTML('beforeend', attachmentMsg);
                scrollToBottom();
            }
        });
        input.value = '';
    }

    // Polling for new messages
    setInterval(() => {
        fetch(`/user/chat/${chatId}/messages?last_id=${lastMessageId}`)
            .then(res => res.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    const messages = document.getElementById('chatMessages');
                    data.messages.forEach(msg => {
                        const isMine = msg.sender_id === {{ auth()->id() }};
                        const msgHtml = isMine ? `
                            <div class="flex justify-end">
                                <div class="max-w-[75%]">
                                    <div class="bg-[#1a2744] rounded-2xl rounded-br-sm px-4 py-2.5">
                                        <p class="text-sm text-white leading-relaxed">${escapeHtml(msg.message)}</p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1 block text-right">${msg.time}</span>
                                </div>
                            </div>` : `
                            <div class="flex gap-2">
                                <div class="w-8 h-8 bg-[#e8a020] rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="ti ti-headset text-white text-sm"></i>
                                </div>
                                <div class="max-w-[75%]">
                                    <div class="bg-white rounded-2xl rounded-bl-sm px-4 py-2.5 shadow-sm">
                                        <p class="text-sm text-gray-800 leading-relaxed">${escapeHtml(msg.message)}</p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1 block ml-1">${msg.time}</span>
                                </div>
                            </div>`;
                        messages.insertAdjacentHTML('beforeend', msgHtml);
                        lastMessageId = msg.id;
                    });
                    scrollToBottom();
                }
            });
    }, 3000);

    function openEmojiPicker() {
        alert('Fitur emoji akan segera hadir!');
    }
</script>
@endpush
@endsection