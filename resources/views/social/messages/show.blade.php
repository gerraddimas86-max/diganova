@extends('social.layouts.app')

@section('title', 'Chat dengan ' . $other->name . ' — DIGANOVA Social')

@section('content')

@php
    $currentUserId = auth()->id();
    $initialMessages = $messages->map(fn ($m) => [
        'id'         => $m->id,
        'body'       => $m->body,
        'is_mine'    => $m->sender_id === $currentUserId,
        'time'       => $m->created_at->format('H:i'),
        'created_at' => $m->created_at->toIso8601String(),
    ])->values()->all();
@endphp

<style>
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.2; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.05); }
    }
    .animate-pulse-slow { animation: pulse-slow 8s ease-in-out infinite; }
    
    .chat-scrollbar::-webkit-scrollbar { width: 6px; }
    .chat-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .chat-scrollbar::-webkit-scrollbar-thumb { background: #4382DF; border-radius: 10px; }
    .chat-scrollbar::-webkit-scrollbar-thumb:hover { background: #112E81; }
</style>

<div class="relative w-full min-h-screen bg-transparent -mt-[1px] overflow-hidden">
    
    {{-- Background Overlay --}}
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute inset-0 bg-[#090E39]/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full bg-[#4382DF] blur-[150px] opacity-20 animate-pulse-slow mix-blend-screen"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-[#4647AE] blur-[120px] opacity-20 animate-pulse-slow mix-blend-screen" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 py-6 sm:py-8 h-screen flex flex-col"
         x-data="dmChat({
            username: '{{ $other->username }}',
            currentUserId: {{ $currentUserId }},
            initialMessages: @js($initialMessages),
         })"
         x-init="init()">

        {{-- Back Link (DENGAN WADAH / CONTAINER) --}}
        <div class="mb-4 flex-shrink-0">
            <a href="{{ route('social.messages.index') }}" 
               class="inline-flex items-center gap-2 text-sm font-bold text-[#4382DF] hover:text-white transition-all duration-300 group 
                      bg-[#112E81]/40 hover:bg-[#112E81]/80 backdrop-blur-md border border-[#4382DF]/30 
                      px-4 py-2.5 rounded-xl shadow-lg hover:shadow-[#4382DF]/20">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Semua Pesan
            </a>
        </div>

        {{-- Chat Container --}}
        <div class="bg-[#112E81]/20 backdrop-blur-xl rounded-2xl border border-[#4382DF]/20 shadow-2xl overflow-hidden flex flex-col flex-1"
             style="max-height: calc(100vh - 160px);">

            {{-- Header --}}
            <div class="flex items-center gap-4 p-4 border-b border-[#4382DF]/20 bg-[#090E39]/50 sticky top-0 z-10 flex-shrink-0">
                <a href="{{ route('social.profile.show', $other->username) }}" class="flex-shrink-0 relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-[#4382DF] to-[#4647AE] rounded-full opacity-0 group-hover:opacity-100 transition duration-300 blur-sm"></div>
                    <x-avatar :avatar="$other->avatar" :user="$other" size="w-11 h-11 border-2 border-[#090E39] shadow-sm relative" />
                </a>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('social.profile.show', $other->username) }}"
                       class="font-extrabold text-white hover:text-[#4382DF] transition-colors text-base">
                        {{ $other->name }}
                    </a>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ '@' . $other->username }}</p>
                </div>
                <div class="text-[11px] font-bold text-green-400 flex items-center gap-1.5 bg-green-500/10 px-3 py-1.5 rounded-full border border-green-500/20">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Live
                </div>
            </div>

            {{-- Messages Area --}}
            <div x-ref="chatBox"
                 class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#090E39]/30 space-y-4 chat-scrollbar"
                 style="scroll-behavior: smooth;">

                <div x-show="loadingFirst" class="text-center py-4">
                    <p class="text-xs font-bold text-slate-400">Memuat pesan...</p>
                </div>

                <div x-show="!loadingFirst && messages.length === 0" class="text-center py-12">
                    <div class="w-16 h-16 mx-auto rounded-full bg-[#112E81]/50 border border-[#4382DF]/30 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-[#4382DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-lg">Belum ada pesan</p>
                    <p class="text-sm font-medium text-slate-400 mt-1">Kirim pesan pertama untuk memulai percakapan!</p>
                </div>

                {{-- Messages --}}
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.is_mine ? 'flex justify-end' : 'flex justify-start'">
                        <div class="max-w-[75%] md:max-w-[65%]"
                             :class="msg.is_mine ? 'items-end' : 'items-start'">
                            <div class="px-4 py-2.5 text-sm break-words shadow-md"
                                 :class="msg.is_mine
                                    ? 'bg-gradient-to-r from-[#112E81] to-[#4382DF] text-white rounded-2xl rounded-br-sm shadow-[#4382DF]/20'
                                    : 'bg-[#112E81]/40 backdrop-blur-md text-white border border-[#4382DF]/30 rounded-2xl rounded-bl-sm'">
                                <p x-text="msg.body" class="whitespace-pre-line leading-relaxed"></p>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 px-1"
                               :class="msg.is_mine ? 'text-right' : 'text-left'"
                               x-text="msg.time"></p>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Form Input --}}
            <form @submit.prevent="send()" class="p-3 md:p-4 border-t border-[#4382DF]/20 bg-[#090E39]/80 flex-shrink-0">
                @csrf
                <div class="flex gap-2.5 items-end">
                    <textarea x-model="newMessage"
                              @keydown.enter.prevent="send()"
                              @keydown.enter.shift.prevent="newMessage += '\n'"
                              rows="1"
                              maxlength="1000"
                              placeholder="Tulis pesan... (Enter untuk kirim)"
                              class="flex-1 px-4 py-3 border border-[#4382DF]/30 rounded-xl focus:ring-4 focus:ring-[#4382DF]/20 focus:border-[#4382DF] outline-none resize-none text-sm font-medium text-white placeholder-slate-500 transition-all bg-[#112E81]/30 focus:bg-[#112E81]/50"
                              style="min-height: 48px; max-height: 120px;"></textarea>
                    
                    {{-- Tombol Kirim Ikon Pesawat Kanan --}}
                    <button type="submit"
                            :disabled="sending || !newMessage.trim()"
                            class="bg-gradient-to-r from-[#112E81] to-[#4382DF] hover:from-[#4647AE] hover:to-[#4382DF] text-white rounded-xl transition-all duration-300 shadow-lg shadow-[#4382DF]/20 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex-shrink-0 flex items-center justify-center"
                            style="width: 48px; height: 48px;">
                        
                        <svg x-show="!sending" class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                        
                        <svg x-show="sending" class="w-5 h-5 animate-spin hidden" viewBox="0 0 24 24" fill="none" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
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
                } else {
                    const errorData = await res.text();
                    console.error('Server Error:', res.status, errorData);
                    alert('Gagal mengirim pesan. Cek Console (F12) untuk detail.');
                }
            } catch (e) {
                console.error('Network Error:', e);
                alert('Koneksi gagal. Pastikan internet stabil.');
            }

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