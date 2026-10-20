@extends('social.layouts.app')

@section('title', 'Pesan — DIGANOVA Social')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">💬 Pesan</h1>
    <p class="text-sm text-gray-500 mt-1">Chat langsung 1-on-1 dengan user lain</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- MAIN: Conversation List --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-sm">Percakapan ({{ $conversations->count() }})</h2>
            </div>

            @if ($conversations->isEmpty())
                <div class="p-12 text-center">
                    <div class="text-5xl mb-3">💬</div>
                    <p class="text-gray-700 font-medium">Belum ada percakapan</p>
                    <p class="text-sm text-gray-500 mt-1">Mulai chat dengan user lain dari saran di samping</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($conversations as $conv)
                        <a href="{{ route('social.messages.show', $conv['user']->username) }}"
                           class="flex items-center gap-3 p-4 hover:bg-gray-50 transition {{ $conv['unread_count'] > 0 ? 'bg-violet-50/30' : '' }}">
                            <div class="relative flex-shrink-0">
                                <x-avatar :avatar="$conv['user']->avatar" :user="$conv['user']" size="w-12 h-12" />
                                @if ($conv['unread_count'] > 0)
                                    <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1.5 bg-violet-600 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                        {{ $conv['unread_count'] > 9 ? '9+' : $conv['unread_count'] }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-semibold text-gray-900 truncate {{ $conv['unread_count'] > 0 ? 'font-bold' : '' }}">
                                        {{ $conv['user']->name }}
                                    </p>
                                    @if ($conv['last_message_at'])
                                        <span class="text-xs text-gray-400 whitespace-nowrap">
                                            {{ $conv['last_message_at']->diffForHumans(null, true, true) }}
                                        </span>
                                    @endif
                                </div>
                                @if ($conv['last_message'])
                                    <p class="text-xs text-gray-500 truncate mt-0.5">
                                        @if ($conv['is_mine'])
                                            <span class="text-gray-400">Kamu: </span>
                                        @endif
                                        {{ $conv['last_message'] }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400 italic mt-0.5">Belum ada pesan</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- SIDEBAR: Start New Chat --}}
    <aside class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 p-4 sticky top-20"
             x-data="newChatSearch()">
            <h2 class="font-bold text-gray-900 text-sm mb-3">✉️ Mulai Chat Baru</h2>

            {{-- Search --}}
            <div class="relative mb-3">
                <input type="text" x-model="q"
                       @input.debounce.300ms="search()"
                       placeholder="Cari username atau nama..."
                       autocomplete="off"
                       class="w-full px-3 py-2 pl-9 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Search Results --}}
            <template x-if="q.length >= 2">
                <div>
                    <template x-if="results.length > 0">
                        <div class="space-y-1">
                            <template x-for="u in results" :key="u.username">
                                <a :href="'/messages/' + u.username"
                                   class="flex items-center gap-2 p-2 rounded-lg hover:bg-violet-50 transition">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white text-xs font-bold"
                                         x-text="u.name.charAt(0).toUpperCase()"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="u.name"></p>
                                        <p class="text-xs text-gray-500 truncate" x-text="'@' + u.username"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>
                    <template x-if="results.length === 0 && !loading">
                        <p class="text-xs text-gray-500 text-center py-2">User tidak ditemukan</p>
                    </template>
                </div>
            </template>

            {{-- Suggested Users --}}
            <template x-if="q.length < 2">
                <div>
                    <p class="text-xs text-gray-500 mb-2">Saran:</p>
                    <div class="space-y-1">
                        @foreach ($suggested as $s)
                            <a href="{{ route('social.messages.show', $s->username) }}"
                               class="flex items-center gap-2 p-2 rounded-lg hover:bg-violet-50 transition">
                                <x-avatar :avatar="$s->avatar" :user="$s" size="w-8 h-8" textSize="text-xs" />
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $s->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ '@' . $s->username }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </template>
        </div>
    </aside>
</div>

<script>
function newChatSearch() {
    return {
        q: '',
        results: [],
        loading: false,

        async search() {
            if (this.q.length < 2) {
                this.results = [];
                return;
            }
            this.loading = true;
            try {
                const res = await fetch('{{ route('social.search.suggestions') }}?q=' + encodeURIComponent(this.q), {
                    headers: { 'Accept': 'application/json' },
                });
                if (res.ok) {
                    const data = await res.json();
                    this.results = (data.users || []).filter(u => u.username !== '{{ auth()->user()->username }}');
                }
            } catch (e) {}
            this.loading = false;
        },
    };
}
</script>

@endsection