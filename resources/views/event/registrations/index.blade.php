@extends('layouts.public')

@section('title', 'Registrasi Saya — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'check-circle'   => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'clock'          => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'x-circle'       => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'help-circle'    => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'hash'           => '<path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/>',
        'arrow-right'    => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
        'chevron-left'   => '<path d="m15 18-6-6 6-6"/>',
        'chevron-right'  => '<path d="m9 18 6-6-6-6"/>',
    ];

    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#registrations-page) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
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
        transform: translateY(20px);
        transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
    }
    .reveal.in { opacity: 1; transform: none; }

    /* Custom pagination (Laravel links()) */
    #registrations-page nav[role="navigation"] { display: flex; justify-content: center; }
    #registrations-page nav[role="navigation"] > div:first-child { display: none; }
    #registrations-page nav[role="navigation"] > div:last-child {
        display: inline-flex; flex-wrap: wrap; gap: .5rem;
        background: #fff;
        border: 2px solid #fff;
        border-radius: 1.25rem;
        padding: .5rem;
        box-shadow: 4px 4px 0 #4382DF, 8px 12px 18px rgba(0,0,0,.35);
    }
    #registrations-page nav[role="navigation"] span,
    #registrations-page nav[role="navigation"] a {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.5rem; height: 2.5rem; padding: 0 .75rem;
        border-radius: .75rem;
        font-weight: 800;
        font-size: .875rem;
        color: #112E81;
        text-decoration: none;
        transition: background .2s, color .2s, transform .2s;
    }
    #registrations-page nav[role="navigation"] a:hover {
        background: #4382DF; color: #fff;
        transform: translateY(-2px);
    }
    #registrations-page nav[role="navigation"] [aria-current="page"] span {
        background: #090E39; color: #fff;
        box-shadow: 2px 2px 0 #4382DF;
    }
    #registrations-page nav[role="navigation"] span[aria-disabled="true"],
    #registrations-page nav[role="navigation"] .disabled span {
        color: rgba(17,46,129,.35);
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="registrations-page" class="relative bg-[#090E39] text-white min-h-screen overflow-hidden">
    {{-- Background decor --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

        {{-- ============ HEADER ============ --}}
        <div class="reveal flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <span class="hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-xs font-extrabold uppercase tracking-widest extrude-sm ex-b mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4647AE]"></span>
                    </span>
                    E-Ticket
                </span>
                <h1 class="font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                    Registrasi
                    <span class="relative inline-block mx-1 my-1">
                        <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                        <span class="relative px-3">Saya</span>
                    </span>
                </h1>
                <p class="text-white/70 mt-3 flex flex-wrap items-center gap-x-2 gap-y-1">
                    <span>Total</span>
                    <span class="inline-flex items-center gap-1 font-extrabold text-white">
                        {!! $icon('ticket', 'w-4 h-4 text-[#4382DF]') !!}
                        {{ $registrations->total() }} registrasi
                    </span>
                </p>
            </div>

            <a href="{{ route('event.events.index') }}"
               data-cursor="Jelajah"
               class="btn-3d ex-k self-start sm:self-auto inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-2xl font-extrabold text-sm">
                {!! $icon('sparkles', 'w-4 h-4') !!}
                Cari Event
            </a>
        </div>

        {{-- ============ CONTENT ============ --}}
        @if ($registrations->isEmpty())
            {{-- Empty state --}}
            <div class="reveal bg-[#112E81]/60 backdrop-blur border-2 border-dashed border-white/40 rounded-[2rem] p-10 sm:p-16 text-center">
                <div class="mx-auto mb-5 w-20 h-20 grid place-items-center rounded-2xl bg-white text-[#4647AE] border-2 border-white extrude-sm ex-b">
                    {!! $icon('ticket', 'w-10 h-10') !!}
                </div>
                <h3 class="font-display text-2xl sm:text-3xl font-extrabold text-white">
                    Belum ada registrasi
                </h3>
                <p class="text-white/60 mt-2 max-w-md mx-auto">
                    Yuk ikut event pertamamu dan mulai kumpulin e-ticket & badge!
                </p>
                <a href="{{ route('event.events.index') }}"
                   class="btn-3d ex-b inline-flex items-center gap-2 mt-8 bg-white text-[#090E39] px-6 py-3 rounded-2xl font-extrabold text-sm">
                    {!! $icon('sparkles', 'w-4 h-4') !!}
                    Lihat Events
                </a>
            </div>
        @else
            <div class="space-y-4 sm:space-y-5">
                @foreach ($registrations as $reg)
                    @php
                        $statusMeta = match($reg->payment_status) {
                            'verified' => [
                                'class' => 'bg-emerald-500/20 text-emerald-200 border-emerald-400',
                                'icon'  => 'check-circle',
                                'label' => 'Verified',
                            ],
                            'pending'  => [
                                'class' => 'bg-amber-500/20 text-amber-200 border-amber-400',
                                'icon'  => 'clock',
                                'label' => 'Pending',
                            ],
                            'rejected' => [
                                'class' => 'bg-red-500/20 text-red-200 border-red-400',
                                'icon'  => 'x-circle',
                                'label' => 'Rejected',
                            ],
                            default    => [
                                'class' => 'bg-white/15 text-white/80 border-white/40',
                                'icon'  => 'help-circle',
                                'label' => ucfirst($reg->payment_status),
                            ],
                        };
                    @endphp

                    <div class="reveal" style="transition-delay: {{ ($loop->index % 3) * 70 }}ms">
                        <a href="{{ route('event.registrations.show', $reg->order_code) }}"
                           data-tilt
                           data-rot="{{ $loop->index % 2 === 0 ? '-0.8' : '0.8' }}"
                           data-cursor="Detail"
                           class="group relative block bg-white text-[#090E39] rounded-[2rem] border-2 border-white extrude ex-b p-5 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                                {{-- Left column --}}
                                <div class="flex-1 min-w-0">
                                    {{-- Order code + status badge --}}
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="inline-flex items-center gap-1.5 bg-[#112E81]/10 text-[#112E81] text-[11px] font-mono font-extrabold px-2.5 py-1 rounded-lg border border-[#112E81]/25">
                                            {!! $icon('hash', 'w-3 h-3') !!}
                                            {{ $reg->order_code }}
                                        </span>

                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full border-2 {{ $statusMeta['class'] }}">
                                            {!! $icon($statusMeta['icon'], 'w-3 h-3') !!}
                                            {{ $statusMeta['label'] }}
                                        </span>
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="font-display font-extrabold text-lg leading-snug line-clamp-2 group-hover:text-[#4382DF] transition-colors">
                                        {{ $reg->event->title }}
                                    </h3>

                                    {{-- Ticket + date --}}
                                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                        <span class="inline-flex items-center gap-1.5 bg-[#4647AE]/10 text-[#4647AE] px-2.5 py-1 rounded-full border border-[#4647AE]/30 font-extrabold uppercase tracking-wide">
                                            {!! $icon('ticket', 'w-3 h-3') !!}
                                            {{ $reg->ticketCategory->name }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 bg-[#112E81]/5 text-[#112E81]/80 px-2.5 py-1 rounded-full border border-[#112E81]/15 font-extrabold uppercase tracking-wide">
                                            {!! $icon('calendar', 'w-3 h-3') !!}
                                            {{ $reg->event->start_date->format('d M Y') }}
                                        </span>
                                    </div>

                                    {{-- Location --}}
                                    <p class="mt-2 inline-flex items-center gap-1.5 text-xs text-[#112E81]/70 font-semibold min-w-0">
                                        {!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4382DF] shrink-0') !!}
                                        <span class="truncate">{{ $reg->event->location }}</span>
                                    </p>
                                </div>

                                {{-- Right column --}}
                                <div class="sm:text-right flex-shrink-0 flex sm:flex-col items-center sm:items-end justify-between gap-3 sm:gap-2 pt-3 sm:pt-0 sm:pl-4 border-t-2 sm:border-t-0 sm:border-l-2 border-dashed border-[#090E39]/15">
                                    <div>
                                        <p class="text-[10px] text-[#112E81]/60 uppercase tracking-widest font-extrabold">Didaftarkan</p>
                                        <p class="font-display font-extrabold text-sm text-[#090E39]">{{ $reg->created_at->format('d M Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-[#4647AE] font-extrabold text-sm group-hover:gap-2 transition-all">
                                        Detail
                                        {!! $icon('arrow-right', 'w-4 h-4') !!}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Reveal + Tilt --}}
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
                ' rotateX(' + (-y * 7).toFixed(2) + 'deg)' +
                ' rotateY(' + ( x * 7).toFixed(2) + 'deg)' +
                ' translateY(-3px)';
            glare.style.setProperty('--gx', ((x + 0.5) * 100).toFixed(1) + '%');
            glare.style.setProperty('--gy', ((y + 0.5) * 100).toFixed(1) + '%');
        });
        el.addEventListener('mouseleave', () => { el.style.transform = base; });
    });
})();
</script>

@endsection