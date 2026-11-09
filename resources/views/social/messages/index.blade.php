@extends('social.layouts.app')

@section('title', 'Pesan — DIGANOVA Social')

@section('content')

<div class="relative z-10 max-w-5xl mx-auto px-4 py-6 sm:py-8">

    {{-- Header --}}
    <div class="mb-6 flex items-start gap-4">
        <div class="w-12 h-12 rounded-xl bg-[#3B82F6] flex items-center justify-center flex-shrink-0 shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] -rotate-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <div>
            <h1 class="f-display text-2xl font-bold text-white tracking-tight">Pesan</h1>
            <p class="text-sm text-slate-400 mt-1 font-medium">Chat langsung 1-on-1 dengan user lain</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- MAIN: Conversation List --}}
        <div class="lg:col-span-2">
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b-2 border-[#1E3A8A] bg-[#152D54] flex items-center justify-between">
                    <h2 class="font-bold text-white text-sm flex items-center gap-2">
                        Percakapan
                        <span class="bg-[#1E3A8A] text-[#60A5FA] text-xs px-2 py-0.5 rounded-md font-extrabold border border-[#3B82F6]">
                            {{ $conversations->count() }}
                        </span>
                    </h2>
                </div>

                @if ($conversations->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="f-display text-white font-bold text-lg">Belum ada percakapan</p>
                        <p class="text-sm font-medium text-slate-400 mt-1">Mulai chat dengan user lain dari saran di samping</p>
                    </div>
                @else
                    <div class="divide-y-2 divide-[#1E3A8A]/50">
                        @foreach ($conversations as $conv)
                            <a href="{{ route('social.messages.show', $conv['user']->username) }}"
                               class="flex items-center gap-4 p-4 hover:bg-[#152D54] transition-colors duration-200 group {{ $conv['unread_count'] > 0 ? 'bg-[#1E3A8A]/20' : '' }}">
                                
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full p-[2px] bg-gradient-to-tr from-[#22D3EE] to-[#3B82F6] group-hover:rotate-6 transition-transform">
                                        <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#0F1F3D]">
                                            <x-avatar :avatar="$conv['user']->avatar" :user="$conv['user']" size="w-full h-full" />
                                        </div>
                                    </div>
                                    @if ($conv['unread_count'] > 0)
                                        <span class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1.5 bg-[#F43F5E] text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-[#0F1F3D] shadow-sm animate-bounce">
                                            {{ $conv['unread_count'] > 9 ? '9+' : $conv['unread_count'] }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-0.5">
                                        <p class="font-bold text-white truncate text-sm group-hover:text-[#FACC15] transition-colors">
                                            {{ $conv['user']->name }}
                                        </p>
                                        @if ($conv['last_message_at'])
                                            <span class="text-[11px] font-semibold text-slate-400 whitespace-nowrap">
                                                {{ $conv['last_message_at']->diffForHumans(null, true, true) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if ($conv['last_message'])
                                        <p class="text-xs text-slate-300 truncate font-medium">
                                            @if ($conv['is_mine'])
                                                <span class="text-[#60A5FA] font-bold">Kamu: </span>
                                            @endif
                                            {{ $conv['last_message'] }}
                                        </p>
                                    @else
                                        <p class="text-xs text-slate-500 italic mt-0.5">Belum ada pesan</p>
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
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm p-5 sticky top-24"
                 x-data="newChatSearch()">
                <h2 class="font-bold text-white text-sm mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                    </svg>
                    Mulai Chat Baru
                </h2>

                {{-- Search --}}
                <div class="relative mb-4">
                    <input type="text" x-model="q"
                           @input.debounce.300ms="search()"
                           placeholder="Cari username atau nama..."
                           autocomplete="off"
                           class="w-full px-4 py-2.5 pl-10 text-sm border-2 border-[#1E3A8A] rounded-xl focus:ring-0 focus:border-[#3B82F6] outline-none text-white font-medium placeholder-slate-500 transition-all bg-[#152D54]">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    {{-- Loading Spinner --}}
                    <div x-show="loading" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                        <svg class="w-4 h-4 animate-spin text-[#60A5FA]" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                    </div>
                </div>

                {{-- Search Results --}}
                <template x-if="q.length >= 2">
                    <div>
                        <template x-if="results.length > 0">
                            <div class="space-y-1">
                                <template x-for="u in results" :key="u.username">
                                    <a :href="'/messages/' + u.username"
                                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-[#152D54] transition-colors border-2 border-transparent hover:border-[#1E3A8A] group">
                                        <div class="w-9 h-9 rounded-full bg-[#1E3A8A] border border-[#3B82F6] flex items-center justify-center text-[#60A5FA] text-xs font-black shadow-sm group-hover:scale-105 transition-transform"
                                             x-text="u.name.charAt(0).toUpperCase()"></div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-white truncate group-hover:text-[#FACC15] transition-colors" x-text="u.name"></p>
                                            <p class="text-[11px] text-slate-400 font-medium truncate" x-text="'@' + u.username"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="results.length === 0 && !loading">
                            <div class="text-center py-6">
                                <p class="text-xs text-slate-400 font-medium">User tidak ditemukan</p>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Suggested Users --}}
                <template x-if="q.length < 2">
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Saran Untukmu:</p>
                        <div class="space-y-1">
                            @foreach ($suggested as $s)
                                <a href="{{ route('social.messages.show', $s->username) }}"
                                   class="flex items-center gap-3 p-2 rounded-xl hover:bg-[#152D54] transition-colors border-2 border-transparent hover:border-[#1E3A8A] group">
                                    <div class="w-9 h-9 rounded-full border-2 border-[#1E3A8A] overflow-hidden">
                                        <x-avatar :avatar="$s->avatar" :user="$s" size="w-full h-full" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-white truncate group-hover:text-[#FACC15] transition-colors">{{ $s->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-medium truncate">{{ '@' . $s->username }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </template>
            </div>
        </aside>
    </div>
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