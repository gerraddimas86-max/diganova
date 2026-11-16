@extends('layouts.public')

@section('title', 'Events — DIGANOVA')

@section('content')

@php
    /* ====== Icon library (Lucide-style stroke) ====== */
    $icons = [
        'search'         => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'mic'            => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"/>',
        'gamepad'        => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
        'palette'        => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.93 0 1.5-.75 1.5-1.5 0-.39-.15-.73-.39-1-.23-.27-.38-.62-.38-1 0-.83.67-1.5 1.5-1.5H16c3.31 0 6-2.69 6-6 0-4.96-4.49-9-10-9z"/>',
        'shirt'          => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
        'party'          => '<path d="M5.8 11.3 2 22l10.7-3.79"/><path d="M4 3h.01M22 8h.01M15 2h.01M22 20h.01"/><path d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10"/><path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11-.11.7-.72 1.22-1.43 1.22H17"/><path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7"/><path d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2z"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
        'alert'          => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>',
        'search-x'       => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3M8.5 8.5l5 5M13.5 8.5l-5 5"/>',
        'filter'         => '<path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
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
@endphp

<style>
    :where(#events-page) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

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
    .z-1 { transform: translateZ(26px); }
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

    .input-brutal {
        background: #fff;
        color: #090E39;
        border: 2px solid #fff;
        border-radius: 1rem;
        padding: .85rem 1rem;
        font-weight: 600;
        transition: box-shadow .2s, transform .2s;
        outline: none;
    }
    .input-brutal::placeholder { color: rgba(17,46,129,.5); font-weight: 500; }
    .input-brutal:focus {
        box-shadow: 4px 4px 0 #4382DF, 8px 12px 18px rgba(0,0,0,.35);
        transform: translateY(-1px);
    }
    select.input-brutal {
        appearance: none;
        background-image:
            linear-gradient(45deg, transparent 50%, #112E81 50%),
            linear-gradient(135deg, #112E81 50%, transparent 50%);
        background-position:
            calc(100% - 22px) calc(50% - 3px),
            calc(100% - 16px) calc(50% - 3px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
        padding-right: 3rem;
    }

    /* Custom pagination (Laravel links()) */
    #events-page nav[role="navigation"] { display: flex; justify-content: center; }
    #events-page nav[role="navigation"] > div:first-child { display: none; }
    #events-page nav[role="navigation"] > div:last-child {
        display: inline-flex; flex-wrap: wrap; gap: .5rem;
        background: #fff;
        border: 2px solid #fff;
        border-radius: 1.25rem;
        padding: .5rem;
        box-shadow: 4px 4px 0 #4382DF, 8px 12px 18px rgba(0,0,0,.35);
    }
    #events-page nav[role="navigation"] span,
    #events-page nav[role="navigation"] a {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.5rem; height: 2.5rem; padding: 0 .75rem;
        border-radius: .75rem;
        font-weight: 800;
        font-size: .875rem;
        color: #112E81;
        text-decoration: none;
        transition: background .2s, color .2s, transform .2s;
    }
    #events-page nav[role="navigation"] a:hover {
        background: #4382DF; color: #fff;
        transform: translateY(-2px);
    }
    #events-page nav[role="navigation"] [aria-current="page"] span {
        background: #090E39; color: #fff;
        box-shadow: 2px 2px 0 #4382DF;
    }
    #events-page nav[role="navigation"] span[aria-disabled="true"],
    #events-page nav[role="navigation"] .disabled span {
        color: rgba(17,46,129,.35);
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="events-page" class="relative bg-[#090E39] text-white min-h-screen overflow-hidden">
    {{-- Background decor --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

        {{-- ============ HEADER ============ --}}
        <div class="reveal flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <span class="hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-xs font-extrabold uppercase tracking-widest extrude-sm ex-b mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4647AE]"></span>
                    </span>
                    Events
                </span>
                <h1 class="font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                    Jelajahi
                    <span class="relative inline-block mx-1 my-1">
                        <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                        <span class="relative px-3">Events</span>
                    </span>
                </h1>
                <p class="text-white/70 mt-3 max-w-xl">
                    Temukan festival, konser, cosplay, dan turnamen game terbaik di sekitarmu.
                </p>
            </div>

            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}"
               data-cursor="Kembali"
               class="btn-3d ex-k self-start sm:self-auto inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-2xl font-extrabold text-sm">
                {!! $icon('arrow-left', 'w-4 h-4') !!}
                Kembali
            </a>
        </div>

        {{-- ============ FILTER BAR ============ --}}
        <form method="GET" action="{{ route('event.events.index') }}"
              class="reveal relative bg-white/95 border-2 border-white rounded-3xl extrude-sm ex-b p-4 sm:p-5 mb-8 flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#112E81]">
                    {!! $icon('search', 'w-5 h-5') !!}
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari event berdasarkan judul..."
                       class="input-brutal w-full pl-12">
            </div>

            <select name="category" class="input-brutal sm:w-56">
                <option value="">Semua Kategori</option>
                @foreach (['music' => 'Musik', 'cosplay' => 'Cosplay', 'art' => 'Seni', 'game_tournament' => 'Turnamen Game', 'festival' => 'Festival', 'other' => 'Lainnya'] as $val => $label)
                    <option value="{{ $val }}" @selected(request('category') === $val)>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit"
                    class="btn-3d ex-k inline-flex items-center justify-center gap-2 bg-[#4647AE] text-white px-6 py-3 rounded-2xl font-extrabold text-sm border-2 border-[#4647AE] hover:bg-[#4382DF] hover:border-[#4382DF] transition">
                {!! $icon('filter', 'w-4 h-4') !!}
                Cari
            </button>

            @if (request()->hasAny(['search', 'category']))
                <a href="{{ route('event.events.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-[#090E39]/10 hover:bg-[#090E39]/20 text-[#090E39] px-4 py-3 rounded-2xl font-extrabold text-sm border-2 border-[#090E39]/20 transition">
                    Reset
                </a>
            @endif
        </form>

        {{-- ============ RESULT COUNT ============ --}}
        <div class="reveal mb-5 flex items-center gap-2 text-sm text-white/60">
            {!! $icon('ticket', 'w-4 h-4 text-[#4382DF]') !!}
            <span>
                Menampilkan <b class="text-white font-extrabold">{{ $events->count() }}</b>
                dari <b class="text-white font-extrabold">{{ $events->total() }}</b> event
            </span>
            @if (request('search'))
                <span class="hidden sm:inline-flex items-center gap-1 ml-2 px-2.5 py-0.5 rounded-full bg-[#4382DF]/20 text-[#4382DF] border border-[#4382DF]/40 text-xs font-bold">
                    “{{ request('search') }}”
                </span>
            @endif
            @if (request('category'))
                <span class="hidden sm:inline-flex items-center gap-1 ml-1 px-2.5 py-0.5 rounded-full bg-[#4647AE]/30 text-white border border-[#4647AE]/50 text-xs font-bold capitalize">
                    {{ str_replace('_', ' ', request('category')) }}
                </span>
            @endif
        </div>

        {{-- ============ GRID ============ --}}
        @if ($events->isEmpty())
            <div class="reveal bg-[#112E81]/60 backdrop-blur border-2 border-dashed border-white/40 rounded-[2rem] p-10 sm:p-16 text-center">
                <div class="mx-auto mb-5 w-20 h-20 grid place-items-center rounded-2xl bg-white text-[#4647AE] border-2 border-white extrude-sm ex-b">
                    {!! $icon('search-x', 'w-10 h-10') !!}
                </div>
                <h3 class="font-display text-2xl sm:text-3xl font-extrabold text-white">
                    Belum ada event dengan filter ini
                </h3>
                <p class="text-white/60 mt-2 max-w-md mx-auto">
                    Coba ubah kata kunci atau kategori, atau lihat semua event yang tersedia.
                </p>
                @if (request()->hasAny(['search', 'category']))
                    <a href="{{ route('event.events.index') }}"
                       class="btn-3d ex-b inline-flex items-center gap-2 mt-6 bg-white text-[#090E39] px-6 py-3 rounded-2xl font-extrabold text-sm">
                        Reset filter & lihat semua event
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($events as $event)
                    @php
                        $minPrice = $event->ticketCategories->min('price');
                        $isAlmostSold = $event->registration_deadline
                            && $event->registration_deadline->diffInDays(now()) <= 7
                            && $event->registration_deadline->isFuture();
                        $bannerIcon = $catIconName($event->category);
                    @endphp

                    <div class="reveal" style="transition-delay: {{ ($loop->index % 3) * 90 }}ms">
                        <a href="{{ route('event.events.show', $event->slug) }}"
                           data-tilt
                           data-rot="{{ $loop->index % 2 === 0 ? '-1' : '1' }}"
                           data-cursor="Detail!"
                           class="group relative bg-white text-[#090E39] rounded-3xl overflow-hidden border-2 border-white extrude ex-b flex flex-col h-full">

                            {{-- Banner --}}
                            <div class="relative h-44 sm:h-48 overflow-hidden bg-[#4647AE]">
                                @if ($event->banner_image)
                                    <img src="{{ Storage::url($event->banner_image) }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         alt="{{ $event->title }}">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#4382DF] via-[#4647AE] to-[#112E81] flex items-center justify-center text-white">
                                        {!! $icon($bannerIcon, 'w-20 h-20') !!}
                                    </div>
                                @endif

                                {{-- Category pill --}}
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 bg-white text-[#090E39] text-xs font-extrabold px-3 py-1 rounded-full capitalize border-2 border-[#090E39] z-10">
                                    {!! $icon($bannerIcon, 'w-3.5 h-3.5') !!}
                                    {{ str_replace('_', ' ', $event->category) }}
                                </span>

                                {{-- Almost sold --}}
                                @if ($isAlmostSold)
                                    <span class="absolute top-3 right-3 inline-flex items-center gap-1 bg-red-500 text-white text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full border-2 border-white shadow-lg animate-pulse z-10">
                                        {!! $icon('alert', 'w-3 h-3') !!}
                                        Segera Tutup
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-5 flex-1 flex flex-col">
                                <h3 class="font-display font-extrabold text-lg leading-snug line-clamp-2 group-hover:text-[#4382DF] transition-colors">
                                    {{ $event->title }}
                                </h3>

                                <div class="mt-3 space-y-2 text-sm text-[#112E81] flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="shrink-0 w-6 h-6 grid place-items-center rounded-md bg-[#4382DF]/20 text-[#112E81]">
                                            {!! $icon('map-pin', 'w-3.5 h-3.5') !!}
                                        </span>
                                        <span class="truncate font-medium">{{ $event->location }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="shrink-0 w-6 h-6 grid place-items-center rounded-md bg-[#4382DF]/20 text-[#112E81]">
                                            {!! $icon('calendar', 'w-3.5 h-3.5') !!}
                                        </span>
                                        <span class="font-medium">{{ $event->start_date->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t-2 border-dashed border-[#090E39]/15 flex justify-between items-center">
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/70">
                                        {!! $icon('ticket', 'w-3.5 h-3.5') !!}
                                        {{ $event->ticketCategories->count() }} tiket
                                    </span>

                                    @if ($minPrice !== null)
                                        <span class="font-display font-extrabold text-lg text-[#4647AE]">
                                            @if ($minPrice == 0)
                                                GRATIS
                                            @else
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-xs text-[#112E81]/60 font-semibold">Tiket belum tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ============ INTERACTION JS ============ --}}
<script>
(function () {
    const reduce   = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    /* Reveal */
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

    /* Tilt */
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
                ' translateY(-4px)';
            glare.style.setProperty('--gx', ((x + 0.5) * 100).toFixed(1) + '%');
            glare.style.setProperty('--gy', ((y + 0.5) * 100).toFixed(1) + '%');
        });
        el.addEventListener('mouseleave', () => { el.style.transform = base; });
    });
})();
</script>

@endsection