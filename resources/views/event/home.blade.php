@extends('layouts.public')

@section('title', 'Event Home — DIGANOVA')

@section('content')

@php
    /* ====== Icon library (Lucide-style stroke) ====== */
    $icons = [
        'hand'         => '<path d="M18 11V6a2 2 0 0 0-4 0v5"/><path d="M14 10V4a2 2 0 0 0-4 0v6"/><path d="M10 10.5V6a2 2 0 0 0-4 0v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>',
        'ticket'       => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'party'        => '<path d="M5.8 11.3 2 22l10.7-3.79"/><path d="M4 3h.01M22 8h.01M15 2h.01M22 20h.01"/><path d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10"/><path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11-.11.7-.72 1.22-1.43 1.22H17"/><path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7"/><path d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2z"/>',
        'sparkles'     => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
        'music'        => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
        'calendar'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'calendar-x'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m14 14-4 4M10 14l4 4"/>',
        'map-pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'mic'          => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"/>',
        'shirt'        => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
        'palette'      => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.93 0 1.5-.75 1.5-1.5 0-.39-.15-.73-.39-1-.23-.27-.38-.62-.38-1 0-.83.67-1.5 1.5-1.5H16c3.31 0 6-2.69 6-6 0-4.96-4.49-9-10-9z"/>',
        'gamepad'      => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
    ];

    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* Map kategori ke icon SVG (fallback: sparkles) */
    $catIcon = function (string $slug) use ($icon) {
        $map = [
            'music'           => 'mic',
            'cosplay'         => 'shirt',
            'art'             => 'palette',
            'game_tournament' => 'gamepad',
            'game'            => 'gamepad',
            'festival'        => 'party',
        ];
        return $icon($map[$slug] ?? 'sparkles', 'w-6 h-6');
    };
@endphp

<style>
    /* ===== Design tokens ===== */
    :where(#home-page) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

    /* ===== Extrude (hard-shadow 3D) ===== */
    .ex-w { --ec: #FFFFFF; }
    .ex-b { --ec: #4382DF; }
    .ex-i { --ec: #4647AE; }
    .ex-d { --ec: #112E81; }
    .ex-k { --ec: #000000; }
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

    /* ===== 3D Button ===== */
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

    /* ===== Grid background ===== */
    .home-grid {
        background-image:
            linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
    }

    /* ===== Hover pop ===== */
    .hov-pop {
        transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s;
    }
    .hov-pop:hover { scale: 1.05; rotate: -2deg; z-index: 5; }

    /* ===== Tilt ===== */
    [data-tilt] {
        position: relative;
        transition: transform .15s ease-out;
        transform-style: preserve-3d;
        will-change: transform;
    }
    .z-1 { transform: translateZ(26px); }
    .glare {
        position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
        opacity: 0; transition: opacity .3s; z-index: 5;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(255,255,255,.3), transparent 50%);
    }
    [data-tilt]:hover > .glare { opacity: 1; }

    /* ===== Orbs ===== */
    .orb {
        --oc: #4382DF; --os: rgba(9,14,57,.34); --oh: #fff; --oe: #000;
        border-radius: 9999px; border: 3px solid #fff;
        background:
            radial-gradient(ellipse 13% 8% at 27% 23%, var(--oh) 0 92%, transparent 100%),
            radial-gradient(circle at 32% 30%, transparent 0 60%, var(--os) 61%),
            var(--oc);
        box-shadow: 4px 5px 0 var(--oe), 10px 16px 24px rgba(0,0,0,.35);
    }
    .orb-i { --oc: #4647AE; --oe: #000; }
    .orb-w { --oc: #fff; --os: rgba(67,130,223,.4); --oe: #4382DF; }
    .orb-lg { border-width: 5px; box-shadow: 8px 10px 0 var(--oe), 18px 28px 40px rgba(0,0,0,.35); }

    /* ===== Floating animations ===== */
    .floaty    { animation: floaty 6s ease-in-out infinite; }
    .floaty-2  { animation: floaty 7.5s ease-in-out infinite reverse; }
    .wiggle    { animation: wiggle 2.4s ease-in-out infinite; }
    .breathe   { animation: breathe 4s ease-in-out infinite; }
    @keyframes floaty {
        0%, 100% { transform: translateY(0) rotate(var(--rot, 0deg)); }
        50%      { transform: translateY(-14px) rotate(calc(var(--rot, 0deg) + 2deg)); }
    }
    @keyframes wiggle {
        0%, 100% { transform: rotate(-5deg); }
        50%      { transform: rotate(5deg); }
    }
    @keyframes breathe {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.08); }
    }

    /* ===== Scroll reveal ===== */
    .home-reveal {
        opacity: 0;
        transform: translateY(28px) scale(.98);
        transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
    }
    .home-reveal.in { opacity: 1; transform: none; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        .home-reveal { opacity: 1; transform: none; }
    }
</style>

<div id="home-page" class="relative bg-[#090E39] text-white overflow-hidden">
    {{-- Background decor --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

    {{-- ============================ HERO ============================ --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 lg:pt-16 pb-14 grid lg:grid-cols-2 gap-10 items-center">

        {{-- Left: greeting + stats --}}
        <div class="text-center lg:text-left">
            <span class="hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-sm font-bold extrude-sm ex-b mb-6">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#4647AE]"></span>
                </span>
                <span>Halo, {{ auth()->user()->name }}!</span>
                <span class="text-[#4647AE]">{!! $icon('hand', 'w-4 h-4') !!}</span>
            </span>

            <h1 class="font-display text-4xl sm:text-5xl xl:text-6xl font-extrabold leading-[1.05] tracking-tight mb-5">
                Siap untuk
                <span class="relative inline-block mx-1 my-1">
                    <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                    <span class="relative px-3">petualangan</span>
                </span>
                berikutnya?
            </h1>

            <p class="text-base sm:text-lg text-white/70 max-w-xl mx-auto lg:mx-0 mb-8">
                Jelajahi festival, turnamen, dan event seru lainnya minggu ini.
                Kumpulin badge dan momen tak terlupakan.
            </p>

            {{-- Stats cards --}}
            <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                <div data-tilt data-rot="-2" data-cursor="Tiket"
                     class="extrude-sm ex-b bg-white text-[#090E39] border-2 border-white rounded-2xl px-5 py-4 flex items-center gap-3 min-w-[10.5rem]">
                    <span class="z-1 grid place-items-center w-11 h-11 rounded-xl bg-[#4382DF] text-white shrink-0">
                        {!! $icon('ticket', 'w-5 h-5') !!}
                    </span>
                    <div class="z-1 leading-tight text-left">
                        <p class="font-display font-extrabold text-2xl">{{ $myRegistrationsCount }}</p>
                        <p class="text-[10px] uppercase tracking-widest font-extrabold text-[#112E81]/70">Tiket Saya</p>
                    </div>
                </div>

                <div data-tilt data-rot="2" data-cursor="Event"
                     class="extrude-sm ex-i bg-[#4382DF] text-[#090E39] border-2 border-white rounded-2xl px-5 py-4 flex items-center gap-3 min-w-[10.5rem]">
                    <span class="z-1 grid place-items-center w-11 h-11 rounded-xl bg-[#090E39] text-white shrink-0">
                        {!! $icon('party', 'w-5 h-5') !!}
                    </span>
                    <div class="z-1 leading-tight text-left">
                        <p class="font-display font-extrabold text-2xl">{{ $activeEvents }}</p>
                        <p class="text-[10px] uppercase tracking-widest font-extrabold text-[#112E81]/70">Event Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: mascot + floating decor --}}
        <div class="relative h-[22rem] sm:h-[28rem] max-w-md mx-auto w-full hidden md:block" data-parallax>
            <div data-depth="40" class="absolute top-2 right-6">
                <div class="orb orb-lg breathe w-20 h-20"></div>
            </div>
            <div data-depth="55" class="absolute bottom-6 left-0">
                <div class="orb orb-i floaty-2 w-12 h-12"></div>
            </div>
            <div data-depth="30" class="absolute top-1/4 left-2">
                <div class="orb orb-w floaty w-8 h-8"></div>
            </div>

            {{-- Floating icon chips (menggantikan emoji ✨ & 🎵) --}}
            <div data-depth="45" class="absolute top-4 left-8 wiggle w-16 h-16 rounded-2xl bg-white text-[#4647AE] grid place-items-center border-2 border-white extrude-sm ex-b">
                {!! $icon('sparkles', 'w-8 h-8') !!}
            </div>
            <div data-depth="35" class="absolute bottom-10 right-0 floaty w-14 h-14 rounded-full bg-[#4647AE] text-white border-2 border-white grid place-items-center extrude-sm ex-k">
                {!! $icon('music', 'w-6 h-6') !!}
            </div>

            <div class="relative z-10 w-full h-full flex items-center justify-center">
                <img src="{{ asset('images/navo-mascot.png') }}"
                     alt="Navo mascot"
                     class="max-h-full w-auto hover:scale-105 transition-transform duration-500 drop-shadow-2xl">
            </div>
        </div>
    </section>

    {{-- ============================ CATEGORIES ============================ --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 sm:gap-4">
            @foreach ($categories as $cat)
                <a href="{{ route('event.events.index') }}?category={{ $cat['slug'] }}"
                   data-cursor="Jelajah"
                   class="hov-pop group bg-white text-[#090E39] border-2 border-white rounded-2xl p-4 flex flex-col items-center gap-2 extrude-sm ex-b">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $cat['color'] }} flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:-rotate-6 transition-transform text-[#090E39]">
                        {!! $catIcon($cat['slug']) !!}
                    </div>
                    <span class="font-display font-extrabold text-sm text-center leading-tight">{{ $cat['name'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ============================ FEATURED EVENTS ============================ --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex justify-between items-end mb-8 flex-wrap gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-[#4647AE] text-xs font-bold rotate-2 mb-3 border-2 border-white/80">
                    Event Mendatang
                </span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Jangan sampai ketinggalan
                </h2>
                <p class="text-white/60 mt-1">Event paling seru minggu ini, langsung pilih yang kamu mau.</p>
            </div>

            <a href="{{ route('event.events.index') }}"
               class="btn-3d ex-k inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-2xl font-extrabold text-sm">
                Lihat Semua <span>→</span>
            </a>
        </div>

        @if ($featuredEvents->isEmpty())
            <div class="home-reveal bg-[#112E81]/60 backdrop-blur border-2 border-dashed border-white/40 rounded-3xl p-12 text-center">
                <div class="mx-auto mb-4 w-20 h-20 grid place-items-center rounded-2xl bg-white text-[#4647AE] border-2 border-white extrude-sm ex-b">
                    {!! $icon('calendar-x', 'w-10 h-10') !!}
                </div>
                <h3 class="font-display text-2xl font-extrabold text-white">Belum ada event mendatang</h3>
                <p class="text-white/60 mt-2">Cek kembali nanti untuk update terbaru!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($featuredEvents as $event)
                    @php
                        $minPrice = $event->ticketCategories->min('price');
                        // fallback icon untuk banner tanpa gambar
                        $bannerIcon = match ($event->category) {
                            'music'           => 'mic',
                            'cosplay'         => 'shirt',
                            'art'             => 'palette',
                            'game_tournament' => 'gamepad',
                            'festival'        => 'party',
                            default           => 'sparkles',
                        };
                    @endphp

                    <div class="home-reveal" style="transition-delay: {{ ($loop->index % 3) * 90 }}ms">
                        <a href="{{ route('event.events.show', $event->slug) }}"
                           data-tilt
                           data-rot="{{ $loop->index % 2 === 0 ? '-1' : '1' }}"
                           data-cursor="Detail!"
                           class="group bg-white text-[#090E39] rounded-3xl overflow-hidden border-2 border-white extrude ex-b flex flex-col h-full">

                            {{-- Banner --}}
                            <div class="relative h-48 overflow-hidden bg-[#4647AE]">
                                @if ($event->banner_image)
                                    <img src="{{ Storage::url($event->banner_image) }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         alt="{{ $event->title }}">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#4382DF] via-[#4647AE] to-[#112E81] flex items-center justify-center text-white">
                                        {!! $icon($bannerIcon, 'w-20 h-20') !!}
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3 bg-white text-[#090E39] text-xs font-extrabold px-3 py-1 rounded-full capitalize border-2 border-[#090E39] z-10">
                                    {{ str_replace('_', ' ', $event->category) }}
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-5 flex-1 flex flex-col">
                                <h3 class="font-display font-extrabold text-lg mb-3 line-clamp-2 group-hover:text-[#4382DF] transition-colors">
                                    {{ $event->title }}
                                </h3>

                                <div class="space-y-2 text-sm text-[#112E81] mb-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="shrink-0 w-6 h-6 grid place-items-center rounded-md bg-[#4382DF]/20 text-[#112E81]">
                                            {!! $icon('calendar', 'w-3.5 h-3.5') !!}
                                        </span>
                                        <span class="font-medium">{{ $event->start_date->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="shrink-0 w-6 h-6 grid place-items-center rounded-md bg-[#4382DF]/20 text-[#112E81]">
                                            {!! $icon('map-pin', 'w-3.5 h-3.5') !!}
                                        </span>
                                        <span class="truncate font-medium">{{ $event->location }}</span>
                                    </div>
                                </div>

                                <div class="pt-4 border-t-2 border-dashed border-[#090E39]/15 flex justify-between items-center">
                                    <span class="text-[10px] text-[#112E81]/70 font-extrabold uppercase tracking-widest">Mulai dari</span>
                                    <span class="font-display font-extrabold text-lg text-[#4647AE]">
                                        @if ($minPrice === null)
                                            -
                                        @elseif ($minPrice == 0)
                                            GRATIS
                                        @else
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>

{{-- ============================ INTERACTION JS ============================ --}}
<script>
(function () {
    const reduce   = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    /* -------- Scroll reveal -------- */
    const reveals = document.querySelectorAll('.home-reveal');
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

    /* -------- 3D Tilt -------- */
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
                ' rotateX(' + (-y * 10).toFixed(2) + 'deg)' +
                ' rotateY(' + ( x * 10).toFixed(2) + 'deg)' +
                ' translateY(-4px)';
            glare.style.setProperty('--gx', ((x + 0.5) * 100).toFixed(1) + '%');
            glare.style.setProperty('--gy', ((y + 0.5) * 100).toFixed(1) + '%');
        });
        el.addEventListener('mouseleave', () => { el.style.transform = base; });
    });

    /* -------- Mouse parallax (mascot scene) -------- */
    document.querySelectorAll('[data-parallax]').forEach(scene => {
        const items = [...scene.querySelectorAll('[data-depth]')];
        const area  = scene.closest('section') || scene;
        let tx = 0, ty = 0, cx = 0, cy = 0;

        area.addEventListener('mousemove', (e) => {
            const r = area.getBoundingClientRect();
            tx = (e.clientX - r.left) / r.width  - 0.5;
            ty = (e.clientY - r.top)  / r.height - 0.5;
        });
        area.addEventListener('mouseleave', () => { tx = 0; ty = 0; });

        (function loop() {
            cx += (tx - cx) * 0.08;
            cy += (ty - cy) * 0.08;
            items.forEach(el => {
                const d = +el.dataset.depth;
                el.style.transform = 'translate3d(' + (-cx * d).toFixed(2) + 'px,' + (-cy * d).toFixed(2) + 'px,0)';
            });
            requestAnimationFrame(loop);
        })();
    });
})();
</script>

@endsection