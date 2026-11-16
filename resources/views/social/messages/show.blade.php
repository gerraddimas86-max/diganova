@extends('social.layouts.app')

@section('title', 'Chat dengan ' . $other->name . ' — DIGANOVA Social')

@section('content')

@php
    $initialMessages = $messages->map(function($m) {
        return [
            'id' => $m->id,
            'body' => $m->body,
            'is_mine' => $m->sender_id === auth()->id(),
            'time' => $m->created_at->format('H:i'),
            'created_at' => $m->created_at->toIso8601String(),
            'is_edited' => $m->updated_at->gt($m->created_at) && !$m->is_deleted_for_everyone,
            'is_deleted' => $m->is_deleted_for_everyone,
        ];
    })->toArray();

    // Cek status online di awal load
    $isOnline = $other->last_seen_at && $other->last_seen_at->diffInMinutes(now()) < 5;
    $lastSeenText = $other->last_seen_at ? $other->last_seen_at->diffForHumans() : 'Tidak diketahui';
@endphp

<style>
    /* ===================== THEME VARIABLES ===================== */
    html {
        --bg-page: #000000;
        --bg-card: #0D0D0D;
        --bg-card-alt: #171717;
        --bg-inner: #050505;
        --border-card: #262626;
        --border-strong: #3F3F3F;
        
        --text-primary: #FFFFFF;
        --text-secondary: #E2E8F0;
        --text-muted: #94A3B8;
        
        --accent-blue: #3B82F6;
        --accent-blue-soft: #60A5FA;
        --shadow-color: #1F1F1F;
    }
    
    html.light-mode {
        --bg-page: #FFFFFF;
        --bg-card: #EFF6FF;
        --bg-card-alt: #DBEAFE;
        --bg-inner: #FFFFFF;
        --border-card: #BFDBFE;
        --border-strong: #93C5FD;
        
        --text-primary: #000000;
        --text-secondary: #334155;
        --text-muted: #64748B;
        
        --accent-blue: #2563EB; 
        --accent-blue-soft: #3B82F6;
        --shadow-color: #BFDBFE;
    }

    body { background-color: var(--bg-page) !important; color: var(--text-primary); }

    html .bg-\[\#0F1F3D\] { background-color: var(--bg-card) !important; }
    html .bg-\[\#152D54\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#060D1F\] { background-color: var(--bg-inner) !important; }
    html .bg-\[\#152D54\]\/50 { background-color: var(--bg-card-alt) !important; }

    html .border-\[\#1E3A8A\] { border-color: var(--border-card) !important; }
    html .border-\[\#3B82F6\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#22D3EE\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#60A5FA\] { border-color: var(--accent-blue-soft) !important; }

    html .text-white, html .f-display, html h1, html h2, html h3, html h4, html p {
        color: var(--text-primary) !important;
    }
    html .text-slate-300, html .text-slate-400 { color: var(--text-muted) !important; }
    
    html:not(.light-mode) .text-slate-200 { color: #E2E8F0 !important; }
    html.light-mode .text-slate-200 { color: #0F172A !important; }

    html.light-mode .bg-\[\#3B82F6\], html.light-mode .bg-\[\#3B82F6\] *,
    html.light-mode .bg-\[\#2563EB\], html.light-mode .bg-\[\#2563EB\] * {
        color: #FFFFFF !important;
    }

    html.light-mode .text-\[\#3B82F6\], html.light-mode .text-\[\#60A5FA\], html.light-mode .text-\[\#2563EB\] { color: #2563EB !important; }
    html:not(.light-mode) .text-\[\#3B82F6\], html:not(.light-mode) .text-\[\#60A5FA\] { color: #60A5FA !important; }

    html .shadow-\[5px_5px_0_\#060D1F\],
    html .shadow-\[3px_3px_0_\#1E3A8A\],
    html .shadow-\[2px_2px_0_\#1E3A8A\] { box-shadow: 5px 5px 0 var(--shadow-color) !important; }

    [x-cloak] { display: none !important; }

    /* Animasi Modal */
    @keyframes zoomIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .animate-zoom-in {
        animation: zoomIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" style="height: calc(100vh - 100px); min-height: 600px;">

    {{-- KOLOM KIRI: DAFTAR PERCAKAPAN --}}
    <div class="hidden lg:flex flex-col bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-[4px_4px_0_#1E3A8A] overflow-hidden h-full">
        <div class="px-5 py-4 border-b-2 border-[#1E3A8A] bg-[#152D54] flex justify-between items-center">
            <h2 class="font-bold text-white text-sm">Pesan</h2>
            <a href="{{ route('social.messages.index') }}" class="text-xs text-[#FACC15] hover:underline font-bold">Semua →</a>
        </div>

        <div class="flex-1 overflow-y-auto divide-y-2 divide-[#1E3A8A]/30">
            @foreach ($conversations as $conv)
                <a href="{{ route('social.messages.show', $conv['user']->username) }}"
                   class="flex items-center gap-3 p-4 hover:bg-[#152D54] transition {{ $conv['user']->username === $other->username ? 'bg-[#1E3A8A]/40 border-l-4 border-[#FACC15]' : '' }}">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-full border-2 border-[#1E3A8A] overflow-hidden bg-[#0F1F3D]">
                            <x-avatar :avatar="$conv['user']->avatar" :user="$conv['user']" size="w-full h-full" />
                        </div>
                        @if ($conv['unread_count'] > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-[#F43F5E] text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-[#0F1F3D] animate-bounce">
                                {{ $conv['unread_count'] }}
                            </span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <p class="font-bold text-white text-sm truncate">{{ $conv['user']->name }}</p>
                            @if ($conv['last_message_at'])
                                <span class="text-[10px] text-slate-400 whitespace-nowrap ml-2 font-semibold">
                                    {{ $conv['last_message_at']->diffForHumans(null, true, true) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-300 truncate {{ str_contains($conv['last_message'] ?? '', 'telah dihapus') ? 'italic text-slate-500' : '' }}">
                            @if ($conv['is_mine'])<span class="text-[#60A5FA] font-bold">Kamu: </span>@endif
                            {{ $conv['last_message'] ?? 'Belum ada pesan' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- KOLOM KANAN: RUANG CHAT --}}
    <div class="lg:col-span-2 bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-[4px_4px_0_#1E3A8A] overflow-hidden flex flex-col h-full relative"
         x-data="dmChat({
            username: '{{ $other->username }}',
            currentUserId: {{ auth()->id() }},
            initialMessages: @js($initialMessages),
            initialIsOnline: {{ $isOnline ? 'true' : 'false' }},
            initialLastSeen: '{{ $lastSeenText }}'
         })"
         x-init="init()">

        {{-- Header --}}
        <div class="flex items-center gap-3 p-4 border-b-2 border-[#1E3A8A] bg-[#152D54] z-10 shrink-0">
            <a href="{{ route('social.profile.show', $other->username) }}">
                <div class="w-10 h-10 rounded-full border-2 border-[#1E3A8A] overflow-hidden bg-[#0F1F3D]">
                    <x-avatar :avatar="$other->avatar" :user="$other" size="w-full h-full" />
                </div>
            </a>
            <div class="flex-1 min-w-0">
                <a href="{{ route('social.profile.show', $other->username) }}" class="font-bold text-white hover:text-[#FACC15] transition truncate block">
                    {{ $other->name }}
                </a>
                
                {{-- ✅ STATUS ONLINE / TERAKHIR DILIHAT --}}
                <div class="flex items-center gap-1.5 mt-0.5">
                    <template x-if="isOnline">
                        <span class="w-2 h-2 bg-[#22D3EE] rounded-full animate-pulse"></span>
                    </template>
                    <template x-if="!isOnline">
                        <span class="w-2 h-2 bg-slate-500 rounded-full"></span>
                    </template>
                    <p class="text-[11px] font-semibold" 
                       :class="isOnline ? 'text-[#22D3EE]' : 'text-slate-400'"
                       x-text="isOnline ? 'Online' : 'Terakhir dilihat ' + lastSeenText"></p>
                </div>
            </div>
        </div>

        {{-- Messages Area --}}
        <div x-ref="chatBox" class="flex-1 overflow-y-auto p-4 space-y-3" style="scroll-behavior: smooth;">

            <div x-show="loadingFirst" x-cloak class="text-center py-4">
                <p class="text-xs text-slate-400 font-semibold">Memuat pesan...</p>
            </div>

            <div x-show="!loadingFirst && messages.length === 0" x-cloak class="text-center py-12">
                <div class="text-5xl mb-3">👋</div>
                <p class="text-white font-bold">Belum ada pesan</p>
                <p class="text-sm text-slate-400 mt-1">Kirim pesan pertama untuk memulai percakapan!</p>
            </div>

            {{-- Looping Messages --}}
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.is_mine ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[75%] relative" :class="msg.is_mine ? 'items-end' : 'items-start'">
                        
                        {{-- EDIT MODE --}}
                        <template x-if="editingMessageId === msg.id">
                            <div class="flex flex-col gap-2 w-full min-w-[200px] z-20 relative bg-[#0F1F3D] p-2 rounded-xl border-2 border-[#FACC15]">
                                <textarea x-model="editBody" 
                                          x-ref="editInput"
                                          @keydown.enter.prevent="updateMessage(msg.id)"
                                          @keydown.escape="cancelEdit()"
                                          rows="2" 
                                          maxlength="1000"
                                          class="w-full px-3 py-2 text-sm border-2 border-[#1E3A8A] rounded-lg outline-none resize-none text-white font-medium bg-[#152D54]"></textarea>
                                <div class="flex gap-2 justify-end">
                                    <button @click="cancelEdit()" class="text-xs text-slate-400 hover:text-white font-bold px-3 py-1.5 rounded-lg border border-[#1E3A8A] bg-[#0F1F3D]">Batal</button>
                                    <button @click="updateMessage(msg.id)" :disabled="!editBody.trim() || updating" class="text-xs text-[#1E3A8A] bg-[#FACC15] hover:bg-[#EAB308] font-bold px-3 py-1.5 rounded-lg border border-[#1E3A8A] disabled:opacity-50">
                                        <span x-show="!updating">Simpan</span>
                                        <span x-show="updating">...</span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- NORMAL BUBBLE --}}
                        <template x-if="editingMessageId !== msg.id">
                            <div>
                                {{-- Isi Pesan --}}
                                <div class="px-4 py-2.5 rounded-2xl text-sm break-words border-2"
                                     :class="msg.is_deleted 
                                        ? 'bg-[#152D54] text-slate-500 italic border-[#1E3A8A] rounded-xl' 
                                        : (msg.is_mine
                                            ? 'bg-[#3B82F6] text-white border-[#1E3A8A] rounded-br-sm shadow-[2px_2px_0_#1E3A8A]'
                                            : 'bg-[#152D54] text-slate-200 border-[#1E3A8A] rounded-bl-sm')">
                                    <p x-text="msg.body" class="whitespace-pre-line font-medium"></p>
                                </div>
                            </div>
                        </template>

                        {{-- Footer: Waktu, Label, & Tombol Opsi --}}
                        <div class="flex items-center gap-2 mt-1 px-1 relative" :class="msg.is_mine ? 'justify-end' : 'justify-start'">
                            <p class="text-[10px] text-slate-500 font-bold" x-text="msg.time"></p>
                            
                            <span x-show="msg.is_edited" class="text-[9px] text-slate-500 font-bold italic">(diedit)</span>
                            
                            {{-- Tombol Titik Tiga --}}
                            <button x-show="!msg.is_deleted && editingMessageId !== msg.id" 
                                    @click="openDeleteMenu = (openDeleteMenu === msg.id ? null : msg.id)" 
                                    class="w-6 h-6 flex items-center justify-center rounded-md text-slate-500 hover:text-white hover:bg-[#1E3A8A] transition-colors" 
                                    title="Opsi pesan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>

                            {{-- Dropdown Menu Opsi --}}
                            <div x-show="openDeleteMenu === msg.id" 
                                 @click.outside="openDeleteMenu = null"
                                 x-transition.opacity.scale.origin.bottom
                                 class="absolute z-50 bottom-full mb-1 w-48 bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-xl shadow-[3px_3px_0_#060D1F] overflow-hidden flex flex-col"
                                 :class="msg.is_mine ? 'right-0 origin-bottom-right' : 'left-0 origin-bottom-left'">
                                
                                <button x-show="msg.is_mine" 
                                        @click="startEdit(msg)" 
                                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-300 hover:bg-[#152D54] hover:text-[#60A5FA] transition-colors border-b-2 border-[#1E3A8A]/50 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Pesan
                                </button>
                                
                                <button @click="confirmDelete(msg.id, 'me')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-300 hover:bg-[#152D54] hover:text-white transition-colors border-b-2 border-[#1E3A8A]/50 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus untuk saya
                                </button>
                                
                                <template x-if="msg.is_mine">
                                    <button @click="confirmDelete(msg.id, 'everyone')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#F43F5E] hover:bg-[#F43F5E] hover:text-white transition-colors flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus untuk semua
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <form @submit.prevent="send()" class="p-4 border-t-2 border-[#1E3A8A] bg-[#152D54] shrink-0 z-10">
            <div class="flex gap-2 items-end">
                <textarea x-model="newMessage"
                          @keydown.enter.prevent="send()"
                          @keydown.enter.shift.prevent="newMessage += '\n'"
                          rows="1"
                          maxlength="1000"
                          placeholder="Tulis pesan... (Enter untuk kirim)"
                          class="flex-1 px-4 py-2.5 border-2 border-[#1E3A8A] rounded-xl focus:ring-0 focus:border-[#3B82F6] outline-none resize-none text-sm text-white font-medium placeholder-slate-500 bg-[#0F1F3D]"
                          style="min-height: 44px; max-height: 120px;"></textarea>
                <button type="submit"
                        :disabled="sending || !newMessage.trim()"
                        class="bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A] px-6 py-2.5 rounded-xl font-bold text-sm transition border-2 border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] active:translate-y-0.5 active:shadow-none disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                        style="height: 44px;">
                    <span x-show="!sending">Kirim</span>
                    <span x-show="sending">...</span>
                </button>
            </div>
        </form>

        {{-- MODAL KONFIRMASI HAPUS --}}
        <div x-show="showDeleteModal" 
             x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity">
            
            <div @click.outside="showDeleteModal = false" 
                 x-show="showDeleteModal"
                 x-transition:enter="animate-zoom-in"
                 class="bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-2xl shadow-[6px_6px_0_#060D1F] w-full max-w-sm overflow-hidden relative">
                
                <div class="p-6 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-[#F43F5E]/10 border-2 border-[#F43F5E]/30 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-[#F43F5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    
                    <h3 class="text-lg font-bold text-white mb-2" 
                        x-text="deleteModalData.type === 'everyone' ? 'Hapus untuk semua orang?' : 'Hapus pesan ini?'"></h3>
                    
                    <p class="text-sm text-slate-400 leading-relaxed">
                        <span x-show="deleteModalData.type === 'everyone'">Pesan ini akan dihapus secara permanen untuk Anda dan lawan chat Anda. Tindakan ini tidak dapat dibatalkan.</span>
                        <span x-show="deleteModalData.type === 'me'">Pesan ini hanya akan dihapus dari riwayat chat Anda. Lawan chat Anda masih bisa melihatnya.</span>
                    </p>
                </div>

                <div class="flex border-t-2 border-[#1E3A8A]">
                    <button @click="showDeleteModal = false" 
                            class="flex-1 py-3.5 text-sm font-bold text-slate-400 hover:bg-[#152D54] hover:text-white transition-colors">
                        Batal
                    </button>
                    <button @click="executeDelete()" 
                            :disabled="deleting"
                            class="flex-1 py-3.5 text-sm font-bold text-[#F43F5E] hover:bg-[#F43F5E] hover:text-white transition-colors border-l-2 border-[#1E3A8A] disabled:opacity-50 flex items-center justify-center gap-2">
                        <svg x-show="deleting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        <span x-text="deleting ? 'Menghapus...' : 'Hapus'"></span>
                    </button>
                </div>
            </div>
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

        // ✅ State untuk Last Seen
        isOnline: config.initialIsOnline,
        lastSeenText: config.initialLastSeen,

        editingMessageId: null,
        editBody: '',
        updating: false,

        openDeleteMenu: null,
        showDeleteModal: false,
        deleting: false,
        deleteModalData: { id: null, type: null },

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

                // ✅ Update Status Online / Last Seen
                if (data.other_is_online !== undefined) {
                    this.isOnline = data.other_is_online;
                    this.lastSeenText = data.other_last_seen;
                }

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

        // ============= EDIT PESAN =============
        startEdit(msg) {
            this.openDeleteMenu = null;
            this.editingMessageId = msg.id;
            this.editBody = msg.body;
            this.$nextTick(() => {
                if (this.$refs.editInput) this.$refs.editInput.focus();
            });
        },

        cancelEdit() {
            this.editingMessageId = null;
            this.editBody = '';
        },

        async updateMessage(id) {
            const body = this.editBody.trim();
            if (!body || this.updating) return;
            this.updating = true;

            try {
                const res = await fetch(`/messages/${config.username}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ body }),
                });

                if (res.ok) {
                    const data = await res.json();
                    const index = this.messages.findIndex(m => m.id === id);
                    if (index !== -1) {
                        this.messages[index].body = data.message.body;
                        this.messages[index].is_edited = true;
                    }
                    this.cancelEdit();
                } else {
                    const errData = await res.json();
                    console.error("Edit Error:", errData);
                    let errorMsg = errData.message || errData.error || 'Terjadi kesalahan pada server.';
                    alert(`Gagal mengedit pesan (Status: ${res.status}).\n\nError: ${errorMsg}`);
                }
            } catch (e) {
                console.error("Fetch Error:", e);
                alert('Terjadi kesalahan jaringan.');
            }
            this.updating = false;
        },

        // ============= HAPUS PESAN (MODAL) =============
        confirmDelete(id, type) {
            this.openDeleteMenu = null;
            this.deleteModalData = { id, type };
            this.showDeleteModal = true;
        },

        async executeDelete() {
            const { id, type } = this.deleteModalData;
            if (!id || this.deleting) return;

            this.deleting = true;

            try {
                const res = await fetch(`/messages/${config.username}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ type }),
                });

                if (res.ok) {
                    if (type === 'me') {
                        this.messages = this.messages.filter(m => m.id !== id);
                    } else {
                        const index = this.messages.findIndex(m => m.id === id);
                        if (index !== -1) {
                            this.messages[index].body = 'Pesan ini telah dihapus';
                            this.messages[index].is_deleted = true;
                            this.messages[index].is_edited = false;
                        }
                    }
                    this.showDeleteModal = false;
                } else {
                    const errData = await res.json();
                    console.error("Delete Error:", errData);
                    
                    let errorMsg = errData.message || errData.error || 'Terjadi kesalahan pada server.';
                    if (errData.exception) {
                        errorMsg += `\nException: ${errData.exception}`;
                    }
                    alert(`Gagal menghapus pesan (Status: ${res.status}).\n\nPesan Error:\n${errorMsg}`);
                }
            } catch (e) {
                console.error("Fetch Error:", e);
                alert('Terjadi kesalahan jaringan / server mati.');
            }

            this.deleting = false;
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