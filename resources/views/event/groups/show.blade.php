@extends('layouts.public')

@section('title', $group->name . ' — DIGANOVA')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-6"
     x-data="groupApp()"
     x-init="init()">

    {{-- Header --}}
    <div class="mb-4">
        <a href="{{ route('event.groups.index') }}" class="text-sm text-nova-600 hover:underline">
            ← Semua Grup
        </a>
    </div>

    <div class="bg-gradient-to-br from-nova-600 via-violet-500 to-crystal-400 rounded-2xl p-6 text-white mb-4">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl flex-shrink-0">
                💬
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl md:text-2xl font-bold">{{ $group->name }}</h1>
                <p class="text-sm text-white/90 mt-1">
                    {{ $group->event->title }} · {{ $group->members->count() }} anggota
                </p>
                @if ($group->description)
                    <p class="text-xs text-white/80 mt-2">{{ $group->description }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-t-xl border border-gray-200 border-b-0 flex overflow-x-auto">
        <button type="button" @click="switchTab('feed')"
                :class="tab === 'feed' ? 'border-nova-600 text-nova-700 bg-nova-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
            📢 Feed
        </button>
        <button type="button" @click="switchTab('chat')"
                :class="tab === 'chat' ? 'border-nova-600 text-nova-700 bg-nova-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
            💬 Chat
        </button>
        <button type="button" @click="switchTab('members')"
                :class="tab === 'members' ? 'border-nova-600 text-nova-700 bg-nova-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
            👥 Anggota ({{ $group->members->count() }})
        </button>
    </div>

    <div class="bg-white rounded-b-xl border border-gray-200 p-5">

        {{-- ============ TAB: FEED ============ --}}
        <div x-show="tab === 'feed'">

            @if ($isGroupAdmin)
                <div class="mb-5 bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">✍️ Buat Pengumuman</h3>
                    <form method="POST" action="{{ route('event.groups.posts.store', $group) }}" class="space-y-3">
                        @csrf
                        <input type="text" name="title" required maxlength="255"
                               placeholder="Judul pengumuman..."
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-nova-500 outline-none">
                        <textarea name="body" required maxlength="3000" rows="3"
                                  placeholder="Isi pengumuman..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-nova-500 outline-none">{{ old('body') }}</textarea>
                        <div class="flex justify-between items-center">
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_pinned" value="1" class="rounded border-gray-300 text-nova-600">
                                <span>📌 Pin di atas</span>
                            </label>
                            <button type="submit"
                                    class="bg-nova-600 hover:bg-nova-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                Post
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if ($posts->isEmpty())
                <div class="text-center py-12">
                    <div class="text-4xl mb-2">📢</div>
                    <p class="text-gray-700 font-medium">Belum ada pengumuman</p>
                    <p class="text-sm text-gray-500 mt-1">Pengumuman dari admin akan muncul di sini</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($posts as $post)
                        <div class="border {{ $post->is_pinned ? 'border-nova-300 bg-nova-50/30' : 'border-gray-200' }} rounded-lg p-4">
                            @if ($post->is_pinned)
                                <div class="inline-flex items-center gap-1 text-xs bg-nova-600 text-white px-2 py-0.5 rounded-full font-medium mb-2">
                                    📌 Pinned
                                </div>
                            @endif
                            <h4 class="font-bold text-gray-900">{{ $post->title }}</h4>
                            <p class="text-sm text-gray-700 mt-2 whitespace-pre-line">{{ $post->body }}</p>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    <strong>{{ $post->author->name }}</strong> ·
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                                @if ($post->user_id === $currentUserId || auth()->user()->isAdmin())
                                    <div class="flex gap-2">
                                        @if ($isGroupAdmin)
                                            <form method="POST" action="{{ route('event.groups.posts.pin', [$group, $post]) }}">
                                                @csrf
                                                <button class="text-xs text-gray-500 hover:text-nova-600">
                                                    {{ $post->is_pinned ? 'Unpin' : 'Pin' }}
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('event.groups.posts.destroy', [$group, $post]) }}"
                                              onsubmit="return confirm('Hapus pengumuman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ============ TAB: CHAT ============ --}}
        <div x-show="tab === 'chat'" x-cloak>
            <div class="flex flex-col" style="height: 60vh; max-height: 600px;">

                {{-- Messages Area --}}
                <div x-ref="chatBox"
                     class="flex-1 overflow-y-auto bg-gray-50 rounded-lg p-3 space-y-2 border border-gray-200"
                     style="scroll-behavior: smooth;">

                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.is_mine ? 'flex justify-end' : 'flex justify-start'">
                            <div class="flex gap-2 max-w-[75%]"
                                 :class="msg.is_mine ? 'flex-row-reverse' : 'flex-row'">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-nova-500 to-crystal-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                     x-text="msg.avatar_initial"></div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5 px-1"
                                       :class="msg.is_mine ? 'text-right' : 'text-left'"
                                       x-text="msg.is_mine ? 'Kamu' : msg.user"></p>
                                    <div class="px-3 py-2 rounded-lg text-sm break-words"
                                         :class="msg.is_mine ? 'bg-nova-600 text-white rounded-tr-none' : 'bg-white text-gray-900 rounded-tl-none border border-gray-200'">
                                        <p x-text="msg.body" class="whitespace-pre-line"></p>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5 px-1"
                                       :class="msg.is_mine ? 'text-right' : 'text-left'"
                                       x-text="msg.time"></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="messages.length === 0" class="text-center py-12 text-gray-500 text-sm">
                        Belum ada pesan. Mulai obrolan!
                    </div>
                </div>

                {{-- Input --}}
                <form @submit.prevent="sendMessage()" class="mt-3 flex gap-2">
                    <input type="text" x-model="newMessage" maxlength="1000"
                           placeholder="Tulis pesan..."
                           autocomplete="off"
                           class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
                    <button type="submit" :disabled="sending || !newMessage.trim()"
                            class="bg-nova-600 hover:bg-nova-700 text-white px-6 py-2.5 rounded-lg font-medium transition disabled:opacity-50">
                        <span x-show="!sending">Kirim</span>
                        <span x-show="sending">...</span>
                    </button>
                </form>

                <p class="text-xs text-gray-400 text-center mt-2" x-show="polling">
                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                    Live — update otomatis tiap 5 detik
                </p>
            </div>
        </div>

        {{-- ============ TAB: MEMBERS ============ --}}
        <div x-show="tab === 'members'" x-cloak>
            @php
                $admins = $group->members->filter(fn($m) => $m->pivot->role === 'admin');
                $regularMembers = $group->members->filter(fn($m) => $m->pivot->role !== 'admin');
            @endphp

            @if ($admins->isNotEmpty())
                <div class="mb-4">
                    <h3 class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2">Admin</h3>
                    <div class="space-y-2">
                        @foreach ($admins as $member)
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-nova-500 to-crystal-400 flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ '@' . $member->username }}</p>
                                </div>
                                <span class="text-xs bg-nova-100 text-nova-700 px-2 py-0.5 rounded font-medium">Admin</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <h3 class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2">
                    Anggota ({{ $regularMembers->count() }})
                </h3>
                <div class="space-y-2">
                    @foreach ($regularMembers as $member)
                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">{{ $member->name }}</p>
                                <p class="text-xs text-gray-500">{{ '@' . $member->username }}</p>
                            </div>
                            <span class="text-xs text-gray-400">Bergabung {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() : '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>

<script>
function groupApp() {
    return {
        tab: 'feed',
        messages: @json($messages->map(fn($m) => [
            'id' => $m->id,
            'user' => $m->user->name,
            'username' => '@' . $m->user->username,
            'avatar_initial' => strtoupper(substr($m->user->name, 0, 1)),
            'body' => $m->message,
            'time' => $m->created_at->format('H:i'),
            'is_mine' => $m->user_id === auth()->id(),
        ])),
        newMessage: '',
        sending: false,
        polling: false,
        pollInterval: null,

        init() {
            this.$watch('tab', (val) => {
                if (val === 'chat') {
                    this.startPolling();
                    this.scrollToBottom();
                } else {
                    this.stopPolling();
                }
            });
        },

        switchTab(name) {
            this.tab = name;
        },

        startPolling() {
            if (this.pollInterval) return;
            this.polling = true;
            this.pollInterval = setInterval(() => this.fetchMessages(), 5000);
        },

        stopPolling() {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
            }
            this.polling = false;
        },

        async fetchMessages() {
            try {
                const res = await fetch('{{ route('event.groups.messages.index', $group) }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });
                if (!res.ok) return;
                const data = await res.json();

                if (data.messages.length !== this.messages.length) {
                    this.messages = data.messages;
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {}
        },

        async sendMessage() {
            const text = this.newMessage.trim();
            if (!text || this.sending) return;

            this.sending = true;

            try {
                const res = await fetch('{{ route('event.groups.messages.store', $group) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ message: text }),
                });

                if (res.ok) {
                    const data = await res.json();
                    this.messages.push(data.message);
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