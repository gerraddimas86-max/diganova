@extends('layouts.profile')

@section('title', 'Profil ' . $user->name . ' — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'at-sign'        => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>',
        'award'          => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
        'star'           => '<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>',
        'trophy'         => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>',
        'edit'           => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/>',
        'layout'         => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'message-circle' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
        'gamepad'        => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
        'swords'         => '<path d="M14.5 17.5 3 6V3h3l11.5 11.5M13 19l6-6M16 16l4 4M19 21l2-2M5 14l-2 2v3h3l2-2"/>',
        'arrow-right'    => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'check-circle'   => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'clock'          => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'external-link'  => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
    ];
    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#profile-show) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
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

    .hov-pop {
        transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s;
    }
    .hov-pop:hover { scale: 1.04; rotate: -1deg; z-index: 5; }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
    }
    .reveal.in { opacity: 1; transform: none; }

    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="profile-show" x-data="{ tab: 'overview' }" class="space-y-6">

    {{-- ============================ HERO ============================ --}}
    <div class="reveal relative bg-gradient-to-br from-[#4647AE] via-[#4382DF] to-[#112E81] border-2 border-white rounded-[2rem] extrude ex-k p-6 sm:p-8 overflow-hidden">
        {{-- Decorative orb --}}
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full border-[30px] border-white/10 pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row gap-6 items-center md:items-start">

            {{-- Avatar --}}
            <div class="relative shrink-0">
                <div class="absolute inset-0 translate-x-2 translate-y-2 rounded-full bg-[#090E39]"></div>
                <div class="absolute inset-0 translate-x-3 translate-y-3 rounded-full bg-[#4382DF]"></div>
                <div class="relative rounded-full border-3 border-white extrude-sm ex-k overflow-hidden">
                    <x-avatar :avatar="$user->avatar" :user="$user" size="w-28 h-28" textSize="text-5xl" />
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1 text-center md:text-left min-w-0">
                <div class="flex flex-col md:flex-row md:items-center gap-2 mb-2 justify-center md:justify-start">
                    <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                        {{ $user->name }}
                    </h1>
                    <span class="inline-flex items-center justify-center gap-1 px-3 py-1 rounded-full bg-white/15 border border-white/30 text-white text-sm font-extrabold self-center">
                        {!! $icon('at-sign', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        {{ $user->username }}
                    </span>
                </div>

                <p class="text-white/80 max-w-2xl leading-relaxed">
                    {{ $user->bio ?? 'Belum ada bio.' }}
                </p>

                {{-- Stat chips --}}
                <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-2.5 text-sm">
                    <span class="inline-flex items-center gap-1.5 bg-white text-[#090E39] px-3 py-1.5 rounded-full border-2 border-[#090E39] font-extrabold capitalize">
                        {!! $icon('trophy', 'w-3.5 h-3.5 text-[#4647AE]') !!}
                        {{ $user->rank }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-[#090E39] text-white px-3 py-1.5 rounded-full border-2 border-white font-extrabold">
                        {!! $icon('star', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        {{ $user->total_points }} Poin
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-white/15 text-white px-3 py-1.5 rounded-full border border-white/30 font-extrabold">
                        {!! $icon('award', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        {{ $user->achievements->count() }} Achievement
                    </span>
                </div>
            </div>

            {{-- Edit button --}}
            <div class="shrink-0">
                <a href="{{ route('profile.edit') }}" data-cursor="Edit"
                   class="btn-3d ex-b inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-white whitespace-nowrap">
                    {!! $icon('edit', 'w-4 h-4') !!}
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    {{-- ============================ TABS ============================ --}}
    <div class="reveal flex overflow-x-auto gap-2 hide-scrollbar pb-1">
        <button @click="tab = 'overview'"
                :class="tab === 'overview'
                    ? 'bg-[#4382DF] text-[#090E39] border-[#4382DF] shadow-[4px_4px_0_#090E39]'
                    : 'bg-white/5 text-white/70 border-white/20 hover:bg-white/10 hover:text-white'"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 font-extrabold text-sm whitespace-nowrap transition-all">
            {!! $icon('layout', 'w-4 h-4') !!}
            Overview
        </button>
        <button @click="tab = 'event'"
                :class="tab === 'event'
                    ? 'bg-[#4382DF] text-[#090E39] border-[#4382DF] shadow-[4px_4px_0_#090E39]'
                    : 'bg-white/5 text-white/70 border-white/20 hover:bg-white/10 hover:text-white'"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 font-extrabold text-sm whitespace-nowrap transition-all">
            {!! $icon('ticket', 'w-4 h-4') !!}
            Event
        </button>
        <button @click="tab = 'social'"
                :class="tab === 'social'
                    ? 'bg-[#4382DF] text-[#090E39] border-[#4382DF] shadow-[4px_4px_0_#090E39]'
                    : 'bg-white/5 text-white/70 border-white/20 hover:bg-white/10 hover:text-white'"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 font-extrabold text-sm whitespace-nowrap transition-all">
            {!! $icon('message-circle', 'w-4 h-4') !!}
            Social
        </button>
        <button @click="tab = 'play'"
                :class="tab === 'play'
                    ? 'bg-[#4382DF] text-[#090E39] border-[#4382DF] shadow-[4px_4px_0_#090E39]'
                    : 'bg-white/5 text-white/70 border-white/20 hover:bg-white/10 hover:text-white'"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 font-extrabold text-sm whitespace-nowrap transition-all">
            {!! $icon('gamepad', 'w-4 h-4') !!}
            Playground
        </button>
    </div>

    {{-- ============================ OVERVIEW ============================ --}}
    <div x-show="tab === 'overview'" x-transition.opacity>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Achievement --}}
            <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6">
                <h2 class="inline-flex items-center gap-2 font-display font-extrabold text-lg mb-4">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('award', 'w-4 h-4') !!}
                    </span>
                    Achievement Terbaru
                </h2>

                @if ($user->achievements->isEmpty())
                    <p class="text-white/50 text-sm">Belum ada achievement.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($user->achievements->take(4) as $ach)
                            <div class="flex items-center gap-3 bg-white/5 border-2 border-white/10 hover:border-[#4382DF]/50 rounded-2xl p-3 transition-colors">
                                <div class="shrink-0 w-11 h-11 grid place-items-center rounded-xl bg-white text-[#4647AE] text-2xl border-2 border-white">
                                    {{ $ach->icon ?? '🏅' }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-extrabold text-white text-sm truncate">{{ $ach->name }}</p>
                                    <p class="text-xs text-white/60 line-clamp-1">{{ $ach->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Activity --}}
            <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6">
                <h2 class="inline-flex items-center gap-2 font-display font-extrabold text-lg mb-4">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('sparkles', 'w-4 h-4') !!}
                    </span>
                    Aktivitas Terakhir
                </h2>

                <div class="space-y-3">
                    @if ($recentPosts->isNotEmpty())
                        <div class="flex items-start gap-3 bg-white/5 border-2 border-white/10 rounded-2xl p-3">
                            <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl bg-[#4647AE] text-white border-2 border-white">
                                {!! $icon('message-circle', 'w-4 h-4') !!}
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm text-white font-semibold">Kamu membuat post di Social</p>
                                <p class="text-xs text-white/50 mt-0.5 font-semibold">{{ $recentPosts->first()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($eventRegistrations->isNotEmpty())
                        <div class="flex items-start gap-3 bg-white/5 border-2 border-white/10 rounded-2xl p-3">
                            <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl bg-[#4382DF] text-[#090E39] border-2 border-white">
                                {!! $icon('ticket', 'w-4 h-4') !!}
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm text-white font-semibold">Mendaftar event {{ $eventRegistrations->first()->event->title }}</p>
                                <p class="text-xs text-white/50 mt-0.5 font-semibold">{{ $eventRegistrations->first()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($recentBattles->isNotEmpty())
                        <div class="flex items-start gap-3 bg-white/5 border-2 border-white/10 rounded-2xl p-3">
                            <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl bg-[#112E81] text-white border-2 border-white">
                                {!! $icon('swords', 'w-4 h-4') !!}
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm text-white font-semibold">Menyelesaikan pertempuran Navo Clash</p>
                                <p class="text-xs text-white/50 mt-0.5 font-semibold">{{ $recentBattles->first()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================ EVENT ============================ --}}
    <div x-show="tab === 'event'" x-transition.opacity>
        <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] overflow-hidden">

            <div class="px-5 sm:px-6 py-4 border-b-2 border-white/10 flex items-center gap-2">
                <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                    {!! $icon('ticket', 'w-4 h-4') !!}
                </span>
                <h2 class="font-display font-extrabold text-lg">Riwayat Event</h2>
            </div>

            @if ($eventRegistrations->isEmpty())
                <div class="p-8 text-center">
                    <div class="mx-auto mb-3 w-14 h-14 grid place-items-center rounded-2xl bg-white/5 border border-white/15 text-white/60">
                        {!! $icon('ticket', 'w-7 h-7') !!}
                    </div>
                    <p class="text-white/60 text-sm font-semibold">Belum pernah mendaftar event.</p>
                </div>
            @else
                <div class="divide-y-2 divide-dashed divide-white/10">
                    @foreach ($eventRegistrations as $reg)
                        <div class="p-4 sm:px-6 flex items-center justify-between gap-4 hover:bg-white/5 transition-colors">
                            <div class="min-w-0">
                                <p class="font-extrabold text-white truncate">{{ $reg->event->title }}</p>
                                <p class="text-xs text-white/60 mt-0.5 font-semibold inline-flex items-center gap-1.5">
                                    {!! $icon('calendar', 'w-3 h-3 text-[#4382DF]') !!}
                                    {{ $reg->ticketCategory->name }} · {{ $reg->event->start_date->format('d M Y') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border-2 shrink-0
                                {{ $reg->payment_status === 'verified'
                                    ? 'bg-emerald-500/20 text-emerald-200 border-emerald-400'
                                    : 'bg-amber-500/20 text-amber-200 border-amber-400' }}">
                                {!! $reg->payment_status === 'verified' ? $icon('check-circle', 'w-3 h-3') : $icon('clock', 'w-3 h-3') !!}
                                {{ ucfirst($reg->payment_status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ============================ SOCIAL ============================ --}}
    <div x-show="tab === 'social'" x-transition.opacity>
        <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-6">
            <div class="reveal bg-white text-[#090E39] border-2 border-white rounded-[1.5rem] extrude-sm ex-b p-5 text-center">
                <div class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/60 mb-2">
                    {!! $icon('message-circle', 'w-3 h-3 text-[#4382DF]') !!}
                    Posts
                </div>
                <p class="font-display font-extrabold text-3xl text-[#4647AE]">{{ $socialStats['posts'] }}</p>
            </div>
            <div class="reveal bg-[#4382DF] text-[#090E39] border-2 border-white rounded-[1.5rem] extrude-sm ex-i p-5 text-center">
                <div class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/70 mb-2">
                    {!! $icon('users', 'w-3 h-3') !!}
                    Followers
                </div>
                <p class="font-display font-extrabold text-3xl text-[#090E39]">{{ $socialStats['followers'] }}</p>
            </div>
            <div class="reveal bg-[#4647AE] text-white border-2 border-white rounded-[1.5rem] extrude-sm ex-k p-5 text-center">
                <div class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-widest text-white/70 mb-2">
                    {!! $icon('users', 'w-3 h-3 text-[#4382DF]') !!}
                    Following
                </div>
                <p class="font-display font-extrabold text-3xl text-white">{{ $socialStats['following'] }}</p>
            </div>
        </div>

        <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-6 sm:p-8 text-center">
            <div class="mx-auto mb-4 w-14 h-14 grid place-items-center rounded-2xl bg-white text-[#4647AE] border-2 border-white extrude-sm ex-b">
                {!! $icon('message-circle', 'w-7 h-7') !!}
            </div>
            <p class="text-white/70 mb-5 max-w-md mx-auto font-semibold">
                Lihat profil sosial lengkapmu di domain Social.
            </p>
            <a href="{{ route('social.profile.show', $user->username) }}"
               data-cursor="Buka"
               class="btn-3d ex-k inline-flex items-center gap-2 bg-white text-[#090E39] px-6 py-3 rounded-2xl font-extrabold text-sm border-2 border-white">
                {!! $icon('external-link', 'w-4 h-4') !!}
                Buka Profil Social
            </a>
        </div>
    </div>

    {{-- ============================ PLAYGROUND ============================ --}}
    <div x-show="tab === 'play'" x-transition.opacity>
        <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6">

            <div class="flex flex-col sm:flex-row items-center gap-5 mb-6 pb-6 border-b-2 border-dashed border-white/10">
                <div class="shrink-0 rounded-2xl bg-white p-3 border-2 border-white extrude-sm ex-k">
                    <x-navo :skin="$navoProfile?->equippedSkin" size="w-20 h-20" textSize="text-4xl" />
                </div>
                <div class="text-center sm:text-left">
                    <h2 class="font-display font-extrabold text-2xl text-white capitalize">{{ $user->rank }}</h2>
                    <div class="mt-2 flex flex-wrap justify-center sm:justify-start gap-2 text-xs">
                        <span class="inline-flex items-center gap-1 bg-emerald-500/20 text-emerald-200 border border-emerald-400 rounded-full px-2.5 py-1 font-extrabold">
                            {!! $icon('trophy', 'w-3 h-3') !!}
                            {{ $navoProfile?->wins ?? 0 }} Wins
                        </span>
                        <span class="inline-flex items-center gap-1 bg-red-500/20 text-red-200 border border-red-400 rounded-full px-2.5 py-1 font-extrabold">
                            {!! $icon('swords', 'w-3 h-3') !!}
                            {{ $navoProfile?->losses ?? 0 }} Losses
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center bg-white/5 border-2 border-dashed border-[#4382DF]/50 rounded-2xl p-6">
                <p class="text-white/70 mb-5 max-w-md mx-auto font-semibold">
                    Kelola deck dan profil Navo-mu di Playground.
                </p>
                <a href="{{ route('play.profile.show') }}"
                   data-cursor="Buka"
                   class="btn-3d ex-b inline-flex items-center gap-2 bg-white text-[#090E39] px-6 py-3 rounded-2xl font-extrabold text-sm border-2 border-white">
                    {!! $icon('gamepad', 'w-4 h-4') !!}
                    Buka Profil Navo
                </a>
            </div>
        </div>
    </div>

</div>

{{-- ============ REVEAL ============ --}}
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