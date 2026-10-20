@extends('social.layouts.app')

@section('title', 'Chat dengan ' . $other->name . ' — DIGANOVA Social')

@section('content')

<div x-data="dmChat({
        username: '{{ $other->username }}',
        currentUserId: {{ auth()->id() }},
        initialMessages: @json($messages->map(fn($m) => [
            'id' => $m->id,
            'body' => $m->body,
            'is_mine' => $m->sender_id === auth()->id(),
            'time' => $m->created_at->format('H:i'),
            'created_at' => $m->created_at->toIso8601String(),
        ])),
     })"
     x-init="init()">

    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ route('social.messages.index') }}" class="text-sm text-violet-600 hover:underline">
            ← Semua Pesan
        </a>
    </div>

    {{-- Chat Container --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col"
         style="height: calc(100vh - 200px); min-height: 500px;">

        {{-- Header --}}
        <div class="flex items-center gap-3 p-4 border-b border-gray-100 bg-white sticky top-0 z-10">
            <a href="{{ route('social.profile.show', $other->username) }}">
                <x-avatar :avatar="$other->avatar" :user="$other" size="w-10 h-10" />
            </a>
            <div class="flex-1 min-w-0">
                <a href="{{ route('social.profile.show', $other->username) }}"
                   class="font-semibold text-gray-900 hover:underline">
                    {{ $other->name }}
                </a>
                <p class="text-xs text-gray-500">{{ '@' . $other->username }}</p>
            </div>
            <div class="text-xs text-green-600 flex items-center gap-1">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Live
            </div>
        </div>

        {{-- Messages Area --}}
        <div x-ref="chatBox"
             class="flex-1 overflow-y-auto p-4 bg-gray-50 space-y-2"
             style="scroll-behavior: smooth;">

            {{-- Loading awal --}}
            <div x-show="loadingFirst" class="text-center py-4">
                <p class="text-xs text-gray-400">Memuat pesan...</p>
            </div>

            {{-- Empty state --}}
            <div x-show="!loadingFirst && messages.length === 0" class="text-center py-12">
                <div class="text-5xl mb-3">👋</div>
                <p class="text-gray-700 font-medium">Belum ada pesan</p>
                <p class="text-sm text-gray-500 mt-1">Kirim pesan pertama untuk memulai percakapan!</p>
            </div>

            {{-- Messages --}}
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.is_mine ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[70%]"
                         :class="msg.is_mine ? 'items-end' : 'items-start'">
                        <div class="px-3 py-2 rounded-2xl text-sm break-words"
                             :class="msg.is_mine
                                ? 'bg-violet-600 text-white rounded-br-sm'
                                : 'bg-white text-gray-900 border border-gray-200 rounded-bl-sm'">
                            <p x-text="msg.body" class="whitespace-pre-line"></p>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5 px-1"
                           :class="msg.is_mine ? 'text-right' : 'text-left'"
                           x-text="msg.time"></p>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <form @submit.prevent="send()" class="p-3 border-t border-gray-100 bg-white">
            <div class="flex gap-2 items-end">
                <textarea x-model="newMessage"
                          @keydown.enter.prevent="send()"
                          @keydown.enter.shift.prevent="newMessage += '\n'"
                          rows="1"
                          maxlength="1000"
                          placeholder="Tulis pesan... (Enter untuk kirim, Shift+Enter untuk baris baru)"
                          class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none resize-none text-sm"
                          style="min-height: 40px; max-height: 120px;"></textarea>
                <button type="submit"
                        :disabled="sending || !newMessage.trim()"
                        class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2 rounded-lg font-medium text-sm transition disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                        style="height: 40px;">
                    <span x-show="!sending">Kirim</span>
                    <span x-show="sending">...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function dmChat(config) {
    return {
        messages: config.initialMessages || [],
        newMessage: '',
        sending: false,
        loadingFirst: false,
        polling: false,
        pollInterval: null,
        lastMessageId: 0,

        init() {
            if (this.messages.length > 0) {
                this.lastMessageId = this.messages[this.messages.length - 1].id;
            }
            this.$nextTick(() => this.scrollToBottom());
            this.startPolling();
        },

        startPolling() {
            if (this.pollInterval) return;
            this.polling = true;
            this.pollInterval = setInterval(() => this.pollMessages(), 5000);
        },

        stopPolling() {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
            }
            this.polling = false;
        },

        async pollMessages() {
            try {
                const url = `/messages/${config.username}/poll${this.lastMessageId > 0 ? '?after_id=' + this.lastMessageId : ''}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                if (!res.ok) return;
                const data = await res.json();

                if (data.messages && data.messages.length > 0) {
                    const existingIds = new Set(this.messages.map(m => m.id));
                    let added = false;

                    data.messages.forEach(m => {
                        if (!existingIds.has(m.id)) {
                            this.messages.push(m);
                            this.lastMessageId = Math.max(this.lastMessageId, m.id);
                            added = true;
                        }
                    });

                    if (added) {
                        this.$nextTick(() => this.scrollToBottom());
                    }
                }
            } catch (e) {}
        },

        async send() {
            const body = this.newMessage.trim();
            if (!body || this.sending) return;

            this.sending = true;

            try {
                const res = await fetch(`/messages/${config.username}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ body }),
                });

                if (res.ok) {
                    const data = await res.json();
                    this.messages.push(data.message);
                    this.lastMessageId = data.message.id;
                    this.newMessage = '';
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {}

            this.sending = false;
        },

        scrollToBottom() {
            if (this.$refs.chatBox) {
                this.$refs.chatBox.scrollTop = this.$refs.chatBox.scrollHeight;
            }
        },
    };
}
</script>

@endsection