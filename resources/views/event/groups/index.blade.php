@extends('layouts.public')

@section('title', 'Grup Saya — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'message-circle' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
        'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'arrow-right'    => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
    ];

    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#groups-page) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
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

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="groups-page" class="relative bg-[#090E39] text-white min-h-screen overflow-hidden">
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
                    Komunitas
                </span>
                <h1 class="font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                    Grup
                    <span class="relative inline-block mx-1 my-1">
                        <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                        <span class="relative px-3">Saya</span>
                    </span>
                </h1>
                <p class="text-white/70 mt-3 max-w-xl">
                    Komunitas event yang kamu ikuti — ketemu teman baru dengan hobi yang sama.
                </p>
            </div>

            <a href="{{ route('event.events.index') }}"
               data-cursor="Jelajah"
               class="btn-3d ex-k self-start sm:self-auto inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-2xl font-extrabold text-sm">
                {!! $icon('ticket', 'w-4 h-4') !!}
                Jelajahi Events
            </a>
        </div>

        {{-- ============ CONTENT ============ --}}
        @if ($groups->isEmpty())
            {{-- Empty state --}}
            <div class="reveal bg-[#112E81]/60 backdrop-blur border-2 border-dashed border-white/40 rounded-[2rem] p-10 sm:p-16 text-center">
                <div class="mx-auto mb-5 w-20 h-20 grid place-items-center rounded-2xl bg-white text-[#4647AE] border-2 border-white extrude-sm ex-b">
                    {!! $icon('message-circle', 'w-10 h-10') !!}
                </div>
                <h3 class="font-display text-2xl sm:text-3xl font-extrabold text-white">
                    Belum ada grup
                </h3>
                <p class="text-white/60 mt-3 max-w-md mx-auto">
                    Kamu akan otomatis bergabung grup setelah mendaftar event dan pembayaran terverifikasi.
                </p>
                <a href="{{ route('event.events.index') }}"
                   class="btn-3d ex-b inline-flex items-center gap-2 mt-8 bg-white text-[#090E39] px-6 py-3 rounded-2xl font-extrabold text-sm">
                    {!! $icon('ticket', 'w-4 h-4') !!}
                    Jelajahi Events
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                @foreach ($groups as $group)
                    <div class="reveal" style="transition-delay: {{ ($loop->index % 2) * 90 }}ms">
                        <a href="{{ route('event.groups.show', $group) }}"
                           data-tilt
                           data-rot="{{ $loop->index % 2 === 0 ? '-1' : '1' }}"
                           data-cursor="Buka!"
                           class="group relative block bg-white text-[#090E39] rounded-[2rem] border-2 border-white extrude ex-b p-5 sm:p-6 h-full">

                            {{-- Top row --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="relative shrink-0">
                                    {{-- Icon tile with mini stacked shadow --}}
                                    <div class="absolute inset-0 translate-x-1 translate-y-1 rounded-2xl bg-[#4647AE]"></div>
                                    <div class="relative w-12 h-12 rounded-2xl bg-gradient-to-br from-[#4382DF] to-[#4647AE] text-white grid place-items-center border-2 border-[#090E39]">
                                        {!! $icon('message-circle', 'w-6 h-6') !!}
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1.5 bg-[#4647AE] text-white text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full border-2 border-[#090E39]">
                                    {!! $icon('users', 'w-3 h-3') !!}
                                    {{ $group->members_count }} anggota
                                </span>
                            </div>

                            {{-- Name --}}
                            <h3 class="font-display font-extrabold text-lg leading-snug line-clamp-1 group-hover:text-[#4382DF] transition-colors">
                                {{ $group->name }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-sm text-[#112E81]/70 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $group->description ?? 'Grup komunitas event' }}
                            </p>

                            {{-- Footer --}}
                            <div class="mt-4 pt-4 border-t-2 border-dashed border-[#090E39]/15 flex justify-between items-center gap-3">
                                <span class="inline-flex items-center gap-1.5 text-xs text-[#112E81]/70 font-semibold min-w-0">
                                    {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4647AE] shrink-0') !!}
                                    <span class="truncate">{{ $group->event->title }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1 text-[#4647AE] font-extrabold text-sm shrink-0 group-hover:gap-2 transition-all">
                                    Buka
                                    {!! $icon('arrow-right', 'w-4 h-4') !!}
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
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