@extends('layouts.public')

@section('title', $event->title . ' — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'clock'          => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'activity'       => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'info'           => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
        'align-left'     => '<path d="M21 6H3M15 12H3M17 18H3"/>',
        'calendar-clock' => '<path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4M8 2v4M3 10h5"/><path d="M17.5 17.5 16 16.3V14"/><circle cx="16" cy="16" r="6"/>',
        'map'            => '<path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15M9 3.236v15"/>',
        'award'          => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
        'check-circle'   => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'alert-circle'   => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>',
        'x-circle'       => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'lock'           => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'log-in'         => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/>',
        'message-circle' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
        'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'chevron-right'  => '<path d="m9 18 6-6-6-6"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>',
    ];

    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    $catIconName = function (string $slug) {
        return match ($slug) {
            'music'           => 'mic',
            'cosplay'         => 'shirt',
            'art'             => 'palette',
            'game_tournament' => 'gamepad',
            'festival'        => 'party',
            default           => 'sparkles',
        };
    };

    // additional category icons
    $catIcons = [
        'mic'      => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"/>',
        'shirt'    => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
        'palette'  => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.93 0 1.5-.75 1.5-1.5 0-.39-.15-.73-.39-1-.23-.27-.38-.62-.38-1 0-.83.67-1.5 1.5-1.5H16c3.31 0 6-2.69 6-6 0-4.96-4.49-9-10-9z"/>',
        'gamepad'  => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
        'party'    => '<path d="M5.8 11.3 2 22l10.7-3.79"/><path d="M4 3h.01M22 8h.01M15 2h.01M22 20h.01"/><path d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10"/><path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11-.11.7-.72 1.22-1.43 1.22H17"/><path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7"/><path d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2z"/>',
    ];

    $allIcons = array_merge($icons, $catIcons);
    $iconAny = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($allIcons[$name] ?? $allIcons['sparkles']) . '</svg>';

    $bannerIcon = $catIconName($event->category);

    // status color mapping
    $statusMeta = match ($event->status) {
        'published', 'active', 'ongoing' => ['class' => 'bg-[#4382DF] text-[#090E39]', 'label' => ucfirst($event->status)],
        'completed'                      => ['class' => 'bg-white text-[#090E39]',      'label' => 'Selesai'],
        'cancelled'                      => ['class' => 'bg-red-500 text-white',        'label' => 'Dibatalkan'],
        'draft'                          => ['class' => 'bg-[#4647AE] text-white',      'label' => 'Draft'],
        default                          => ['class' => 'bg-[#112E81] text-white',      'label' => ucfirst($event->status)],
    };
@endphp

<style>
    :where(#event-detail) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
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

    [data-tilt] {
        position: relative;
        transition: transform .15s ease-out;
        transform-style: preserve-3d;
        will-change: transform;
    }
    .glare {
        position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
        opacity: 0; transition: opacity .3s; z-index: 5;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(255,255,255,.3), transparent 50%);
    }
    [data-tilt]:hover > .glare { opacity: 1; }

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
        transform: translateY(28px) scale(.98);
        transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
    }
    .reveal.in { opacity: 1; transform: none; }

    /* Section card style */
    .info-card {
        background: rgba(17, 46, 129, 0.35);
        backdrop-filter: blur(8px);
        border: 2px solid rgba(255,255,255,.15);
        border-radius: 1.75rem;
        padding: 1.5rem;
    }
    @media (min-width: 640px) { .info-card { padding: 1.75rem; } }

    /* Ticket row card */
    .ticket-row {
        background: #fff;
        color: #090E39;
        border: 2px solid #fff;
        border-radius: 1rem;
        padding: .85rem 1rem;
        transition: transform .2s, box-shadow .2s;
    }
    .ticket-row.is-soldout {
        background: rgba(255,255,255,.8);
        border-color: rgba(9,14,57,.15);
        opacity: .7;
    }
    .ticket-row:not(.is-soldout):hover {
        transform: translateY(-2px);
        box-shadow: 3px 3px 0 #4382DF;
    }

    /* Sidebar sticky */
    @media (min-width: 1024px) {
        .sidebar-sticky { position: sticky; top: 5.5rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="event-detail" class="relative bg-[#090E39] text-white min-h-screen overflow-hidden">
    {{-- Background decor --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

    {{-- ============================ HERO BANNER ============================ --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10">
        <div class="relative rounded-[2rem] overflow-hidden border-2 border-white extrude ex-b">
            <div class="relative h-64 sm:h-80 lg:h-96 bg-[#4647AE]">
                @if ($event->banner_image)
                    <img src="{{ Storage::url($event->banner_image) }}"
                         class="w-full h-full object-cover"
                         alt="{{ $event->title }}">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-[#4382DF] via-[#4647AE] to-[#112E81] flex items-center justify-center text-white">
                        {!! $iconAny($bannerIcon, 'w-32 h-32') !!}
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#090E39] via-[#090E39]/30 to-transparent"></div>

                <div class="absolute inset-0 flex items-end p-5 sm:p-8 lg:p-10">
                    <div class="w-full">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 bg-white text-[#090E39] text-xs font-extrabold uppercase tracking-widest px-3 py-1.5 rounded-full border-2 border-[#090E39]">
                                {!! $iconAny($bannerIcon, 'w-3.5 h-3.5') !!}
                                {{ str_replace('_', ' ', $event->category) }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 {{ $statusMeta['class'] }} text-xs font-extrabold uppercase tracking-widest px-3 py-1.5 rounded-full border-2 border-white">
                                {!! $icon('activity', 'w-3.5 h-3.5') !!}
                                {{ $statusMeta['label'] }}
                            </span>
                        </div>

                        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white drop-shadow-lg leading-tight">
                            {{ $event->title }}
                        </h1>

                        <div class="flex flex-wrap gap-3 mt-4 text-sm text-white/90">
                            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur border border-white/25 rounded-full px-3 py-1.5">
                                {!! $icon('map-pin', 'w-4 h-4 text-[#4382DF]') !!}
                                {{ $event->location }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur border border-white/25 rounded-full px-3 py-1.5">
                                {!! $icon('calendar', 'w-4 h-4 text-[#4382DF]') !!}
                                {{ $event->start_date->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Breadcrumb + back --}}
        <div class="flex items-center justify-between gap-4 mt-5 flex-wrap">
            <nav class="flex items-center gap-2 text-sm text-white/50">
                <a href="{{ route('event.events.index') }}" class="hover:text-white transition inline-flex items-center gap-1">
                    {!! $icon('ticket', 'w-4 h-4') !!}
                    Events
                </a>
                {!! $icon('chevron-right', 'w-3.5 h-3.5') !!}
                <span class="text-white truncate max-w-[18rem]">{{ $event->title }}</span>
            </nav>

            <a href="{{ route('event.events.index') }}"
               class="btn-3d ex-k inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-2xl font-extrabold text-sm">
                {!! $icon('arrow-left', 'w-4 h-4') !!}
                Semua Event
            </a>
        </div>
    </section>

    {{-- ============================ MAIN GRID ============================ --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- ==================== MAIN CONTENT (2/3) ==================== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Grid --}}
            <div class="reveal info-card">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="inline-flex items-center gap-1.5 text-[10px] text-[#4382DF] uppercase tracking-widest font-extrabold">
                            {!! $icon('clock', 'w-3.5 h-3.5') !!}
                            Mulai
                        </p>
                        <p class="font-display font-extrabold text-white text-base mt-2">{{ $event->start_date->format('d M Y') }}</p>
                        <p class="text-xs text-white/60">{{ $event->start_date->format('H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="inline-flex items-center gap-1.5 text-[10px] text-[#4382DF] uppercase tracking-widest font-extrabold">
                            {!! $icon('clock', 'w-3.5 h-3.5') !!}
                            Selesai
                        </p>
                        <p class="font-display font-extrabold text-white text-base mt-2">{{ $event->end_date->format('d M Y') }}</p>
                        <p class="text-xs text-white/60">{{ $event->end_date->format('H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="inline-flex items-center gap-1.5 text-[10px] text-[#4382DF] uppercase tracking-widest font-extrabold">
                            {!! $icon('users', 'w-3.5 h-3.5') !!}
                            Peserta
                        </p>
                        <p class="font-display font-extrabold text-white text-base mt-2">
                            {{ $event->registrations()->count() }}
                            @if ($event->max_participants)
                                <span class="text-white/50 text-sm font-bold">/ {{ number_format($event->max_participants, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="inline-flex items-center gap-1.5 text-[10px] text-[#4382DF] uppercase tracking-widest font-extrabold">
                            {!! $icon('activity', 'w-3.5 h-3.5') !!}
                            Status
                        </p>
                        <p class="font-display font-extrabold text-white text-base mt-2 capitalize">{{ $event->status }}</p>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="reveal info-card">
                <h2 class="font-display font-extrabold text-xl mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('info', 'w-4 h-4') !!}
                    </span>
                    Tentang Event
                </h2>
                <div class="text-sm text-white/80 whitespace-pre-line leading-relaxed">{{ $event->description }}</div>

                @if ($event->address)
                    <div class="mt-6 pt-5 border-t-2 border-dashed border-white/15">
                        <p class="inline-flex items-center gap-1.5 text-[10px] text-[#4382DF] uppercase tracking-widest font-extrabold mb-2">
                            {!! $icon('map-pin', 'w-3.5 h-3.5') !!}
                            Alamat Lengkap
                        </p>
                        <p class="text-sm text-white/80">{{ $event->address }}</p>
                    </div>
                @endif
            </div>

            {{-- Schedules --}}
            @if ($event->schedules->isNotEmpty())
                <div class="reveal info-card">
                    <h2 class="font-display font-extrabold text-xl mb-5 inline-flex items-center gap-2">
                        <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                            {!! $icon('calendar-clock', 'w-4 h-4') !!}
                        </span>
                        Jadwal Acara
                    </h2>
                    <div class="space-y-3">
                        @foreach ($event->schedules as $schedule)
                            <div class="flex gap-4 p-3 rounded-xl bg-white/5 border border-white/10 hover:border-[#4382DF]/50 transition-colors">
                                <div class="flex-shrink-0 w-20 text-center">
                                    <p class="font-display font-extrabold text-[#4382DF] text-base">{{ $schedule->start_time->format('H:i') }}</p>
                                    <p class="text-[10px] text-white/50 uppercase tracking-widest font-bold">{{ $schedule->start_time->format('d M') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-white text-sm">{{ $schedule->title }}</p>
                                    @if ($schedule->description)
                                        <p class="text-xs text-white/60 mt-1">{{ $schedule->description }}</p>
                                    @endif
                                    @if ($schedule->location)
                                        <p class="inline-flex items-center gap-1 text-xs text-white/50 mt-1.5">
                                            {!! $icon('map-pin', 'w-3 h-3 text-[#4382DF]') !!}
                                            {{ $schedule->location }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Venue Maps --}}
            @if ($event->venueMaps->isNotEmpty())
                <div class="reveal info-card">
                    <h2 class="font-display font-extrabold text-xl mb-4 inline-flex items-center gap-2">
                        <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                            {!! $icon('map', 'w-4 h-4') !!}
                        </span>
                        Denah Venue
                    </h2>
                    @foreach ($event->venueMaps as $map)
                        <div class="mb-4 last:mb-0">
                            <img src="{{ Storage::url($map->image_path) }}"
                                 class="w-full rounded-xl border-2 border-white/20 bg-white"
                                 alt="Denah venue">
                            @if ($map->description)
                                <p class="text-xs text-white/60 mt-2">{{ $map->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Sponsors --}}
            @if ($event->sponsors->isNotEmpty())
                <div class="reveal info-card">
                    <h2 class="font-display font-extrabold text-xl mb-4 inline-flex items-center gap-2">
                        <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                            {!! $icon('award', 'w-4 h-4') !!}
                        </span>
                        Sponsor & Partner
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach ($event->sponsors as $sponsor)
                            <div class="bg-white text-[#090E39] border-2 border-white rounded-2xl p-3 text-center hover:-translate-y-1 transition-transform">
                                @if ($sponsor->logo_path)
                                    <img src="{{ Storage::url($sponsor->logo_path) }}"
                                         class="h-12 mx-auto object-contain mb-2" alt="{{ $sponsor->name }}">
                                @endif
                                <p class="font-display font-extrabold text-sm">{{ $sponsor->name }}</p>
                                <p class="text-[10px] text-[#112E81]/70 uppercase tracking-widest font-extrabold mt-0.5">{{ $sponsor->tier }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ==================== SIDEBAR (1/3) ==================== --}}
        <aside class="space-y-6">
            <div class="sidebar-sticky space-y-6">

                {{-- Tiket Card --}}
                <div class="reveal bg-white text-[#090E39] border-2 border-white rounded-[2rem] extrude-sm ex-b p-5 sm:p-6">
                    <h2 class="font-display font-extrabold text-xl mb-4 inline-flex items-center gap-2">
                        <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white">
                            {!! $icon('ticket', 'w-4 h-4') !!}
                        </span>
                        Tiket
                    </h2>

                    @if ($event->ticketCategories->isEmpty())
                        <p class="text-sm text-[#112E81]/70 mb-4">Tiket belum tersedia.</p>
                    @else
                        <div class="space-y-2.5 mb-4">
                            @foreach ($event->ticketCategories as $ticket)
                                @php $soldOut = $ticket->sold_count >= $ticket->quota; @endphp
                                <div class="ticket-row {{ $soldOut ? 'is-soldout' : '' }}">
                                    <div class="flex justify-between items-start gap-2">
                                        <div class="min-w-0">
                                            <p class="font-display font-extrabold text-sm">{{ $ticket->name }}</p>
                                            @if ($ticket->description)
                                                <p class="text-xs text-[#112E81]/60 mt-0.5">{{ $ticket->description }}</p>
                                            @endif
                                            <p class="text-[11px] mt-1 font-bold uppercase tracking-wide">
                                                @if ($soldOut)
                                                    <span class="text-red-600">Habis</span>
                                                @else
                                                    <span class="text-[#112E81]/60">Sisa {{ $ticket->quota - $ticket->sold_count }} / {{ $ticket->quota }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        <p class="font-display font-extrabold text-[#4647AE] whitespace-nowrap">
                                            @if ($ticket->price == 0) GRATIS @else Rp {{ number_format($ticket->price, 0, ',', '.') }} @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- State handling --}}
                        @if ($myRegistrations->isNotEmpty())
                            <div class="bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-3 mb-3">
                                <p class="inline-flex items-center gap-1.5 text-sm text-emerald-800 font-extrabold">
                                    {!! $icon('check-circle', 'w-4 h-4') !!}
                                    Kamu sudah terdaftar
                                </p>
                                @foreach ($myRegistrations as $reg)
                                    <p class="text-xs text-emerald-700 mt-1.5">
                                        Kode: <span class="font-mono font-extrabold">{{ $reg->order_code }}</span>
                                        — Status: <span class="capitalize font-bold">{{ $reg->payment_status }}</span>
                                    </p>
                                @endforeach
                            </div>
                            <a href="#" class="flex items-center justify-center gap-2 w-full bg-[#112E81]/10 text-[#112E81]/60 text-center px-4 py-3 rounded-2xl font-extrabold text-sm cursor-not-allowed">
                                {!! $icon('lock', 'w-4 h-4') !!}
                                Lihat E-Ticket (Step 14)
                            </a>

                        @elseif ($event->registration_deadline && $event->registration_deadline->isPast())
                            <div class="bg-red-50 border-2 border-red-300 rounded-2xl p-3 text-center">
                                <p class="inline-flex items-center gap-1.5 text-sm text-red-800 font-extrabold">
                                    {!! $icon('alert-circle', 'w-4 h-4') !!}
                                    Pendaftaran Ditutup
                                </p>
                                <p class="text-xs text-red-600 mt-1 font-semibold">
                                    Deadline: {{ $event->registration_deadline->format('d M Y H:i') }}
                                </p>
                            </div>

                        @elseif ($event->status === 'completed' || $event->status === 'cancelled')
                            <div class="bg-gray-100 border-2 border-gray-300 rounded-2xl p-3 text-center">
                                <p class="inline-flex items-center gap-1.5 text-sm text-gray-700 font-extrabold">
                                    {!! $icon('x-circle', 'w-4 h-4') !!}
                                    Event {{ $event->status }}
                                </p>
                            </div>

                        @else
                            @auth
                                <a href="#"
                                   onclick="event.preventDefault(); alert('Registrasi akan hadir di Step 12!');"
                                   class="btn-3d ex-k flex items-center justify-center gap-2 w-full bg-[#4647AE] text-white text-center px-4 py-3 rounded-2xl font-extrabold text-sm border-2 border-[#4647AE] hover:bg-[#4382DF] hover:border-[#4382DF] transition">
                                    {!! $icon('ticket', 'w-4 h-4') !!}
                                    Daftar Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                                   class="btn-3d ex-k flex items-center justify-center gap-2 w-full bg-[#4647AE] text-white text-center px-4 py-3 rounded-2xl font-extrabold text-sm border-2 border-[#4647AE] hover:bg-[#4382DF] hover:border-[#4382DF] transition">
                                    {!! $icon('log-in', 'w-4 h-4') !!}
                                    Login untuk Daftar
                                </a>
                            @endauth

                            @if ($event->registration_deadline)
                                <p class="text-xs text-center text-[#112E81]/60 font-semibold mt-3">
                                    Deadline: <strong class="text-[#112E81]">{{ $event->registration_deadline->format('d M Y H:i') }}</strong>
                                </p>
                            @endif
                        @endif
                    @endif
                </div>

                {{-- Group Info --}}
                @if ($event->group)
                    <div class="reveal bg-gradient-to-br from-[#4382DF] to-[#4647AE] text-[#090E39] border-2 border-white rounded-[2rem] extrude-sm ex-i p-5 sm:p-6">
                        <h3 class="font-display font-extrabold text-lg mb-2 inline-flex items-center gap-2">
                            <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#090E39] text-white">
                                {!! $icon('message-circle', 'w-4 h-4') !!}
                            </span>
                            Komunitas Event
                        </h3>
                        <p class="font-extrabold text-sm">{{ $event->group->name }}</p>
                        <p class="text-xs text-[#090E39]/70 mt-2 font-medium">
                            Kamu akan otomatis bergabung grup setelah pembayaran terverifikasi.
                        </p>
                    </div>
                @endif

                {{-- Creator Info --}}
                @if ($event->creator)
                    <div class="reveal bg-white text-[#090E39] border-2 border-white rounded-2xl extrude-sm ex-b p-4 flex items-center gap-3">
                        <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl bg-[#4382DF] text-white">
                            {!! $icon('user', 'w-5 h-5') !!}
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] text-[#112E81]/60 uppercase tracking-widest font-extrabold">Diselenggarakan oleh</p>
                            <p class="font-display font-extrabold text-sm truncate">{{ $event->creator->name }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </aside>
    </section>
</div>

{{-- ============ INTERACTION JS ============ --}}
<script>
(function () {
    const reduce   = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

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

    if (reduce || !canHover) return;

    document.querySelectorAll('[data-tilt]').forEach(el => {
        const rot  = parseFloat(el.dataset.rot || 0);
        const base = 'rotate(' + rot + 'deg)';
        el.style.transform = base;

        const glare = document.createElement('span');
        glare.className = 'glare';
        el.appendChild(glare);

        el.addEventListener('mousemove', (e) => {
            const r = el.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width  - 0.5;
            const y = (e.clientY - r.top)  / r.height - 0.5;
            el.style.transform =
                'perspective(800px) ' + base +
                ' rotateX(' + (-y * 8).toFixed(2) + 'deg)' +
                ' rotateY(' + ( x * 8).toFixed(2) + 'deg)' +
                ' translateY(-2px)';
            glare.style.setProperty('--gx', ((x + 0.5) * 100).toFixed(1) + '%');
            glare.style.setProperty('--gy', ((y + 0.5) * 100).toFixed(1) + '%');
        });
        el.addEventListener('mouseleave', () => { el.style.transform = base; });
    });
})();
</script>

@endsection