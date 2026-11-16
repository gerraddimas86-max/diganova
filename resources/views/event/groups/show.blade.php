@extends('layouts.public')

@section('title', $group->name . ' — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'message-circle' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
        'megaphone'      => '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
        'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'pin'            => '<path d="M12 17v5"/><path d="M9 10.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1z"/>',
        'pen-line'       => '<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'trash'          => '<path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M10 11v6M14 11v6"/>',
        'send'           => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'shield'         => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
        'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'info'           => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
    ];
    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#group-detail) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

    .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-d { --ec: #112E81; } .ex-k { --ec: #000000; }
    .extrude {
        box-shadow:
            1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
            5px 5px 0 var(--ec), 6px 6px 0 var(--ec), 7px 7px 0 var(--ec), 8px 8px 0 var(--ec),
            14px 20px 30px rgba(0,0,0,.5);
    }
    .extrude-sm {
        box-shadow:
            1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
            8px 12px 18px rgba(0,0,0,.45);
    }

    .btn-3d {
        --ec: #4382DF;
        transform: translate(var(--tx, 0px), var(--ty, 0px));
        box-shadow: 6px 6px 0 var(--ec), 6px 14px 24px rgba(0,0,0,.45);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-3d:hover {
        transform: translate(calc(var(--tx, 0px) + 3px), calc(var(--ty, 0px) + 3px));
        box-shadow: 3px 3px 0 var(--ec), 3px 8px 14px rgba(0,0,0,.4);
    }
    .btn-3d:active {
        transform: translate(calc(var(--tx, 0px) + 6px), calc(var(--ty, 0px) + 6px));
        box-shadow: 0 0 0 var(--ec);
    }

    .home-grid {
        background-image:
            linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
    }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
    }
    .reveal.in { opacity: 1; transform: none; }

    /* Brutal inputs */
    .input-brutal {
        background: #fff;
        color: #090E39;
        border: 2px solid #fff;
        border-radius: .9rem;
        padding: .75rem 1rem;
        font-weight: 500;
        transition: box-shadow .2s;
        outline: none;
    }
    .input-brutal::placeholder { color: rgba(17,46,129,.5); }
    .input-brutal:focus {
        box-shadow: 4px 4px 0 #4382DF;
    }

    /* Chat scrollbar */
    .chat-scroll::-webkit-scrollbar { width: 6px; }
    .chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .chat-scroll::-webkit-scrollbar-thumb { background: rgba(67,130,223,.5); border-radius: 3px; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="group-detail"
     class="relative bg-[#090E39] text-white min-h-screen overflow-hidden"
     x-data="groupApp()"
     x-init="init()">

    {{-- Background --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- ============ BACK LINK ============ --}}
        <a href="{{ route('event.groups.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-extrabold text-white/70 hover:text-white transition mb-4 group">
            <span class="w-7 h-7 grid place-items-center rounded-lg bg-white/10 border border-white/20 group-hover:bg-[#4382DF] group-hover:text-[#090E39] group-hover:border-[#4382DF] transition">
                {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
            </span>
            Semua Grup
        </a>

        {{-- ============ HERO GROUP CARD ============ --}}
        <div class="reveal relative bg-gradient-to-br from-[#4647AE] via-[#4382DF] to-[#112E81] text-white border-2 border-white rounded-[2rem] extrude ex-b p-5 sm:p-7 mb-6">
            <div class="flex items-start gap-4">
                {{-- Icon tile --}}
                <div class="relative shrink-0">
                    <div class="absolute inset-0 translate-x-1 translate-y-1 rounded-2xl bg-[#090E39]"></div>
                    <div class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white text-[#4647AE] grid place-items-center border-2 border-[#090E39]">
                        {!! $icon('message-circle', 'w-7 h-7 sm:w-8 sm:h-8') !!}
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <h1 class="font-display text-xl sm:text-2xl md:text-3xl font-extrabold leading-tight">
                        {{ $group->name }}
                    </h1>

                    <div class="flex flex-wrap gap-2 mt-2.5 text-xs">
                        <span class="inline-flex items-center gap-1.5 bg-[#090E39]/60 text-white px-2.5 py-1 rounded-full border border-white/25 font-extrabold">
                            {!! $icon('calendar', 'w-3 h-3') !!}
                            {{ $group->event->title }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-white text-[#090E39] px-2.5 py-1 rounded-full border border-[#090E39] font-extrabold">
                            {!! $icon('users', 'w-3 h-3') !!}
                            {{ $group->members->count() }} anggota
                        </span>
                    </div>

                    @if ($group->description)
                        <p class="text-sm text-white/85 mt-3 leading-relaxed">{{ $group->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============ TABS ============ --}}
        <div class="reveal flex items-center gap-2 overflow-x-auto pb-1 mb-5">
            <button type="button" @click="switchTab('feed')"
                    :class="tab === 'feed'
                        ? 'bg-[#4382DF] text-[#090E39] border-[#4382DF] shadow-[4px_4px_0_#090E39]'
                        : 'bg-white/5 text-white/70 border-white/20 hover:bg-white/10 hover:text-white'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 font-extrabold text-sm whitespace-nowrap transition-all">
                {!! $icon('megaphone', 'w-4 h-4') !!}
                Feed
            </button>

            <button type="button" @click="switchTab('chat')"
                    :class="tab === 'chat'
                        ? 'bg-[#4382DF] text-[#090E39] border-[#4382DF] shadow-[4px_4px_0_#090E39]'
                        : 'bg-white/5 text-white/70 border-white/20 hover:bg-white/10 hover:text-white'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 font-extrabold text-sm whitespace-nowrap transition-all">
                {!! $icon('message-circle', 'w-4 h-4') !!}
                Chat
            </button>

            <button type="button" @click="switchTab('members')"
                    :class="tab === 'members'
                        ? 'bg-[#4382DF] text-[#090E39] border-[#4382DF] shadow-[4px_4px_0_#090E39]'
                        : 'bg-white/5 text-white/70 border-white/20 hover:bg-white/10 hover:text-white'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 font-extrabold text-sm whitespace-nowrap transition-all">
                {!! $icon('users', 'w-4 h-4') !!}
                Anggota ({{ $group->members->count() }})
            </button>
        </div>

        {{-- ============ PANEL ============ --}}
        <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-4 sm:p-6">

            {{-- ==================================================
                 TAB: FEED
                 ================================================== --}}
            <div x-show="tab === 'feed'">

                @if ($isGroupAdmin)
                    <div class="mb-5 bg-white/5 border-2 border-dashed border-[#4382DF]/50 rounded-2xl p-4">
                        <h3 class="font-display font-extrabold text-base mb-3 inline-flex items-center gap-2">
                            <span class="w-7 h-7 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                                {!! $icon('pen-line', 'w-3.5 h-3.5') !!}
                            </span>
                            Buat Pengumuman
                        </h3>
                        <form method="POST" action="{{ route('event.groups.posts.store', $group) }}" class="space-y-3">
                            @csrf
                            <input type="text" name="title" required maxlength="255"
                                   placeholder="Judul pengumuman..."
                                   value="{{ old('title') }}"
                                   class="input-brutal w-full text-sm">
                            <textarea name="body" required maxlength="3000" rows="3"
                                      placeholder="Isi pengumuman..."
                                      class="input-brutal w-full text-sm resize-none">{{ old('body') }}</textarea>
                            <div class="flex justify-between items-center gap-3 flex-wrap">
                                <label class="inline-flex items-center gap-2 text-sm text-white/80 font-semibold cursor-pointer">
                                    <input type="checkbox" name="is_pinned" value="1"
                                           class="w-4 h-4 accent-[#4382DF] cursor-pointer">
                                    <span class="inline-flex items-center gap-1">
                                        {!! $icon('pin', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                        Pin di atas
                                    </span>
                                </label>
                                <button type="submit"
                                        class="btn-3d ex-k inline-flex items-center gap-1.5 bg-white text-[#090E39] px-4 py-2 rounded-xl font-extrabold text-sm">
                                    {!! $icon('send', 'w-3.5 h-3.5') !!}
                                    Post
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($posts->isEmpty())
                    <div class="text-center py-12">
                        <div class="mx-auto mb-4 w-16 h-16 grid place-items-center rounded-2xl bg-white text-[#4647AE] border-2 border-white extrude-sm ex-b">
                            {!! $icon('megaphone', 'w-8 h-8') !!}
                        </div>
                        <p class="font-display font-extrabold text-white text-lg">Belum ada pengumuman</p>
                        <p class="text-sm text-white/60 mt-1">Pengumuman dari admin akan muncul di sini</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($posts as $post)
                            <div class="border-2 rounded-2xl p-4 sm:p-5 transition-colors
                                        {{ $post->is_pinned
                                            ? 'border-[#4382DF] bg-[#4382DF]/10'
                                            : 'border-white/15 bg-white/5 hover:border-white/30' }}">

                                @if ($post->is_pinned)
                                    <div class="inline-flex items-center gap-1.5 bg-[#4382DF] text-[#090E39] text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full border-2 border-[#090E39] mb-2.5">
                                        {!! $icon('pin', 'w-3 h-3') !!}
                                        Pinned
                                    </div>
                                @endif

                                <h4 class="font-display font-extrabold text-white text-lg leading-snug">{{ $post->title }}</h4>
                                <p class="text-sm text-white/75 mt-2 whitespace-pre-line leading-relaxed">{{ $post->body }}</p>

                                <div class="mt-4 pt-3 border-t-2 border-dashed border-white/10 flex justify-between items-center gap-3 flex-wrap">
                                    <div class="text-xs text-white/50">
                                        <strong class="text-white">{{ $post->author->name }}</strong>
                                        <span class="mx-1">·</span>
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>

                                    @if ($post->user_id === $currentUserId || auth()->user()->isAdmin())
                                        <div class="flex gap-2">
                                            @if ($isGroupAdmin)
                                                <form method="POST" action="{{ route('event.groups.posts.pin', [$group, $post]) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-lg bg-white/10 text-white/70 hover:bg-[#4382DF] hover:text-[#090E39] transition">
                                                        {!! $icon('pin', 'w-3 h-3') !!}
                                                        {{ $post->is_pinned ? 'Unpin' : 'Pin' }}
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST"
                                                  action="{{ route('event.groups.posts.destroy', [$group, $post]) }}"
                                                  onsubmit="return confirm('Hapus pengumuman ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-lg bg-red-500/20 text-red-300 hover:bg-red-500 hover:text-white transition">
                                                    {!! $icon('trash', 'w-3 h-3') !!}
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ==================================================
                 TAB: CHAT
                 ================================================== --}}
            <div x-show="tab === 'chat'" x-cloak>
                <div class="flex flex-col" style="height: 60vh; max-height: 600px;">

                    {{-- Messages area --}}
                    <div x-ref="chatBox"
                         class="flex-1 overflow-y-auto bg-[#090E39]/60 rounded-2xl p-3 sm:p-4 space-y-3 border-2 border-white/10 chat-scroll"
                         style="scroll-behavior: smooth;">

                        <template x-for="msg in messages" :key="msg.id">
                            <div :class="msg.is_mine ? 'flex justify-end' : 'flex justify-start'">
                                <div class="flex gap-2 max-w-[80%]"
                                     :class="msg.is_mine ? 'flex-row-reverse' : 'flex-row'">

                                    {{-- Avatar --}}
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#4382DF] to-[#4647AE] flex items-center justify-center text-white text-xs font-extrabold border-2 border-white flex-shrink-0"
                                         x-text="msg.avatar_initial"></div>

                                    <div class="min-w-0">
                                        <p class="text-[11px] font-extrabold text-white/50 mb-1 px-1"
                                           :class="msg.is_mine ? 'text-right' : 'text-left'"
                                           x-text="msg.is_mine ? 'Kamu' : msg.user"></p>

                                        <div class="px-3.5 py-2.5 text-sm break-words rounded-2xl"
                                             :class="msg.is_mine
                                                 ? 'bg-[#4382DF] text-[#090E39] rounded-tr-none shadow-[3px_3px_0_#090E39] font-semibold'
                                                 : 'bg-white/10 text-white rounded-tl-none border-2 border-white/20'">
                                            <p x-text="msg.body" class="whitespace-pre-line"></p>
                                        </div>

                                        <p class="text-[10px] text-white/40 mt-1 px-1 font-semibold"
                                           :class="msg.is_mine ? 'text-right' : 'text-left'"
                                           x-text="msg.time"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="messages.length === 0" class="text-center py-12">
                            <div class="mx-auto mb-3 w-14 h-14 grid place-items-center rounded-2xl bg-white/5 border border-white/15 text-white/60">
                                {!! $icon('message-circle', 'w-7 h-7') !!}
                            </div>
                            <p class="text-white/60 text-sm font-semibold">Belum ada pesan. Mulai obrolan!</p>
                        </div>
                    </div>

                    {{-- Input --}}
                    <form @submit.prevent="sendMessage()" class="mt-3 flex gap-2">
                        <input type="text" x-model="newMessage" maxlength="1000"
                               placeholder="Tulis pesan..."
                               autocomplete="off"
                               class="input-brutal flex-1 text-sm">
                        <button type="submit" :disabled="sending || !newMessage.trim()"
                                class="btn-3d ex-k inline-flex items-center gap-1.5 bg-[#4382DF] text-[#090E39] px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#4382DF] disabled:opacity-50 disabled:cursor-not-allowed transition">
                            <span x-show="!sending" class="inline-flex items-center gap-1.5">
                                {!! $icon('send', 'w-4 h-4') !!}
                                Kirim
                            </span>
                            <span x-show="sending">...</span>
                        </button>
                    </form>

                    <p class="text-[11px] text-white/40 text-center mt-2 font-semibold inline-flex items-center justify-center gap-1.5 w-full" x-show="polling">
                        <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        Live — update otomatis tiap 5 detik
                    </p>
                </div>
            </div>

            {{-- ==================================================
                 TAB: MEMBERS
                 ================================================== --}}
            <div x-show="tab === 'members'" x-cloak>
                @php
                    $admins = $group->members->filter(fn($m) => $m->pivot->role === 'admin');
                    $regularMembers = $group->members->filter(fn($m) => $m->pivot->role !== 'admin');
                @endphp

                @if ($admins->isNotEmpty())
                    <div class="mb-5">
                        <h3 class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-widest text-[#4382DF] font-extrabold mb-2.5">
                            {!! $icon('shield', 'w-3 h-3') !!}
                            Admin
                        </h3>
                        <div class="space-y-2">
                            @foreach ($admins as $member)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border-2 border-white/10 hover:border-[#4382DF]/40 transition-colors">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#4382DF] to-[#4647AE] flex items-center justify-center text-white text-sm font-extrabold border-2 border-white shrink-0">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-display font-extrabold text-white text-sm truncate">{{ $member->name }}</p>
                                        <p class="text-xs text-white/50 truncate">{{ '@' . $member->username }}</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-[10px] bg-[#4382DF] text-[#090E39] px-2 py-0.5 rounded-full font-extrabold uppercase tracking-widest border border-[#090E39] shrink-0">
                                        {!! $icon('shield', 'w-3 h-3') !!}
                                        Admin
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <h3 class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-widest text-[#4382DF] font-extrabold mb-2.5">
                        {!! $icon('users', 'w-3 h-3') !!}
                        Anggota ({{ $regularMembers->count() }})
                    </h3>
                    <div class="space-y-2">
                        @foreach ($regularMembers as $member)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border-2 border-white/10 hover:border-[#4382DF]/40 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#4647AE] to-[#112E81] flex items-center justify-center text-white text-sm font-extrabold border-2 border-white shrink-0">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-display font-extrabold text-white text-sm truncate">{{ $member->name }}</p>
                                    <p class="text-xs text-white/50 truncate">{{ '@' . $member->username }}</p>
                                </div>
                                <span class="text-[10px] text-white/40 font-semibold shrink-0 text-right">
                                    Bergabung
                                    {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() : '' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>

{{-- ============================================================
     ALPINE COMPONENT — Fixed ParseError
     ============================================================ --}}
<script>
function groupApp() {
    return {
        tab: 'feed',
        // PERBAIKAN: Menggunakan json_encode dan function standar
        messages: {!! json_encode($messages->map(function($m) {
            return [
                'id' => $m->id,
                'user' => $m->user->name,
                'username' => '@' . $m->user->username,
                'avatar_initial' => strtoupper(substr($m->user->name, 0, 1)),
                'body' => $m->message,
                'time' => $m->created_at->format('H:i'),
                'is_mine' => $m->user_id === auth()->id(),
            ];
        })->toArray()) !!},
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

{{-- Reveal on scroll --}}
<script>
(function () {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const reveals = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window) || reduce) {
        reveals.forEach(el => el.classList.add('in'));
    } else {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(en => {
                if (en.isIntersecting) {
                    en.target.classList.add('in');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => io.observe(el));
    }
})();
</script>

@endsection