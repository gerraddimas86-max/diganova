<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>Hub — DIGANOVA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;600;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $icons = [
            'zap'            => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
            'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
            'message-circle' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
            'gamepad'        => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
            'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
            'pen-line'       => '<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
            'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
            'mail'           => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
            'award'          => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
            'swords'         => '<path d="M14.5 17.5 3 6V3h3l11.5 11.5M13 19l6-6M16 16l4 4M19 21l2-2M5 14l-2 2v3h3l2-2"/>',
            'arrow-right'    => '<path d="M5 12h14M12 5l7 7-7 7"/>',
            'log-out'        => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
            'hand'           => '<path d="M18 11V6a2 2 0 0 0-4 0v5"/><path d="M14 10V4a2 2 0 0 0-4 0v6"/><path d="M10 10.5V6a2 2 0 0 0-4 0v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>',
            'activity'       => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
            'trophy'         => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>',
            'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
        ];
        $icon = fn (string $name, string $class = 'w-6 h-6') =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

        $logo = fn (string $class = 'w-10 h-10') =>
            '<img src="' . asset('images/logo.png') . '" alt="Logo DIGANOVA" class="' . $class . ' object-contain" draggable="false">';

        $totalActivity = $eventStats['my_registrations'] + $socialStats['my_posts'] + $playgroundStats['battles'];
    @endphp

    <style>
        :root {
            --c-white: #FFFFFF; --c-indigo: #4647AE; --c-blue: #4382DF;
            --c-deep: #112E81; --c-navy: #090E39; --c-black: #000000;
        }
        body { font-family: 'Space Grotesk', system-ui, sans-serif; }
        .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

        /* Extrude */
        .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-d { --ec: #112E81; } .ex-k { --ec: #000000; } .ex-g { --ec: #10B981; }
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

        /* Button 3D */
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

        /* Background grid */
        .bg-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        }

        /* Orb */
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

        /* Hover pop */
        .hov-pop {
            transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s;
        }
        .hov-pop:hover { scale: 1.04; rotate: -1deg; z-index: 5; }

        /* Tilt */
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

        /* Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(24px) scale(.98);
            transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
        }
        .reveal.in { opacity: 1; transform: none; }

        /* Floating anims */
        @keyframes floaty { 0%,100% { transform: translateY(0) rotate(var(--rot,0deg)); } 50% { transform: translateY(-14px) rotate(calc(var(--rot,0deg) + 2deg)); } }
        @keyframes breathe { 0%,100% { transform: scale(1); } 50% { transform: scale(1.08); } }
        @keyframes wave { 0%, 100% { transform: rotate(0deg); } 10%, 30% { transform: rotate(-14deg); } 20%, 40% { transform: rotate(14deg); } 50% { transform: rotate(0deg); } }
        .floaty { animation: floaty 6s ease-in-out infinite; }
        .floaty-2 { animation: floaty 7.5s ease-in-out infinite reverse; }
        .breathe { animation: breathe 4s ease-in-out infinite; }
        .animate-wave { animation: wave 2s ease-in-out infinite; transform-origin: 70% 70%; display: inline-block; }

        /* Progress bar shimmer */
        @keyframes shimmer { from { background-position: 0% 0; } to { background-position: 200% 0; } }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
            .reveal { opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#090E39] text-white relative overflow-x-hidden selection:bg-[#4382DF] selection:text-white">

    {{-- Background decor --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-grid opacity-50"></div>
        <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-32 left-1/3 w-[30rem] h-[30rem] bg-[#112E81]/70 rounded-full blur-[120px]"></div>
    </div>

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 px-4 pt-3">
        <nav class="max-w-6xl mx-auto flex items-center justify-between gap-3 bg-[#090E39]/85 backdrop-blur-xl border-2 border-white rounded-full px-3 py-2 extrude-sm ex-i">
            <a href="{{ route('hub') ?? '/' }}" class="flex items-center gap-2.5 shrink-0">
                <span class="shrink-0">{!! $logo('w-10 h-10') !!}</span>
                <span class="flex flex-col leading-none">
                    <span class="font-display font-extrabold tracking-tight text-lg">DIGANOVA</span>
                    <span class="mt-0.5 text-[9px] font-semibold tracking-wide text-white/60 whitespace-nowrap hidden sm:block">Plan It. Join It. Enjoy It.</span>
                </span>
                <span class="hidden sm:inline-flex items-center gap-1 ml-1 px-2.5 py-1 rounded-full bg-[#4382DF] text-[#090E39] text-[10px] font-extrabold uppercase tracking-widest border-2 border-white">
                    {!! $icon('zap', 'w-3 h-3') !!}
                    Hub
                </span>
            </a>

            <div class="flex items-center gap-2 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="hov-pop inline-flex items-center gap-2 bg-white/10 hover:bg-red-500 text-white border-2 border-white/25 hover:border-red-500 px-4 py-2 rounded-full text-sm font-extrabold transition-colors">
                        {!! $icon('log-out', 'w-4 h-4') !!}
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </header>

    {{-- Main --}}
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

        {{-- ============ GREETING ============ --}}
        <section class="text-center mb-12 sm:mb-16">
            {{-- Avatar --}}
            <div class="relative inline-block mb-6">
                <div class="absolute inset-0 translate-x-2 translate-y-2 rounded-full bg-[#4382DF]"></div>
                <div class="absolute inset-0 translate-x-3 translate-y-3 rounded-full bg-[#4647AE]"></div>
                <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-[#4382DF] via-[#4647AE] to-[#112E81] flex items-center justify-center text-3xl sm:text-4xl font-display font-extrabold text-white border-3 border-white extrude-sm ex-k">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                {{-- Online indicator --}}
                <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-emerald-400 rounded-full border-3 border-[#090E39] grid place-items-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                </div>
            </div>

            <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight mb-3 flex flex-wrap items-center justify-center gap-2">
                <span>Halo,</span>
                <span class="relative inline-block">
                    <span class="absolute inset-0 bg-[#4647AE] -rotate-1 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                    <span class="relative px-3">{{ $user->name }}</span>
                </span>
                <span class="animate-wave text-[#4382DF] inline-flex items-center">
                    {!! $icon('hand', 'w-8 h-8 sm:w-10 sm:h-10') !!}
                </span>
            </h1>

            <p class="text-white/70 text-base sm:text-lg max-w-lg mx-auto">
                Mau ke mana hari ini? Pilih petualanganmu.
            </p>
        </section>

        {{-- ============ THREE PILLARS ============ --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-7 mb-10">

            {{-- EVENT CARD --}}
            <div class="reveal">
                <a href="{{ route('event.home') }}" style="view-transition-name: card-event;"
                   data-tilt data-rot="-2" data-cursor="Masuk"
                   class="group relative block rounded-[2rem] p-6 sm:p-7 bg-gradient-to-br from-[#4382DF] via-[#4647AE] to-[#112E81] text-white border-2 border-white extrude ex-k h-full overflow-hidden">
                    {{-- Decorative orb --}}
                    <div class="absolute -right-14 -bottom-14 w-48 h-48 orb orb-lg breathe pointer-events-none opacity-40 group-hover:opacity-60 transition-opacity"></div>

                    <div class="relative z-10">
                        {{-- Icon --}}
                        <div class="relative w-16 h-16 mb-5">
                            <div class="absolute inset-0 translate-x-1.5 translate-y-1.5 rounded-2xl bg-[#090E39]"></div>
                            <div class="relative w-full h-full rounded-2xl bg-white text-[#4647AE] grid place-items-center border-2 border-[#090E39] group-hover:-rotate-6 transition-transform">
                                {!! $icon('ticket', 'w-8 h-8') !!}
                            </div>
                        </div>

                        <h2 class="font-display text-2xl font-extrabold mb-1 leading-tight">Event</h2>
                        <p class="text-sm text-white/75 mb-4 leading-relaxed">Festival, konser, cosplay, dan turnamen</p>

                        <div class="space-y-2 text-xs mb-5">
                            <div class="inline-flex items-center gap-2 bg-[#090E39]/60 border border-white/25 rounded-full px-3 py-1.5 font-extrabold">
                                {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                <span>{{ $eventStats['active_events'] }} event aktif</span>
                            </div>
                            <div class="inline-flex items-center gap-2 bg-[#090E39]/60 border border-white/25 rounded-full px-3 py-1.5 font-extrabold">
                                {!! $icon('ticket', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                <span>{{ $eventStats['my_registrations'] }} registrasi kamu</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between bg-white text-[#090E39] rounded-xl px-4 py-2.5 font-extrabold text-sm border-2 border-[#090E39] group-hover:bg-[#4382DF] group-hover:text-white transition-colors">
                            <span>Masuk</span>
                            <span class="group-hover:translate-x-1 transition-transform inline-flex">
                                {!! $icon('arrow-right', 'w-4 h-4') !!}
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- SOCIAL CARD --}}
            <div class="reveal" style="transition-delay: 100ms">
                <a href="{{ route('social.home') }}" style="view-transition-name: card-social;"
                   data-tilt data-rot="2" data-cursor="Masuk"
                   class="group relative block rounded-[2rem] p-6 sm:p-7 bg-gradient-to-br from-[#4647AE] via-[#4382DF] to-[#090E39] text-white border-2 border-white extrude ex-k h-full overflow-hidden">
                    <div class="absolute -right-14 -bottom-14 w-48 h-48 orb orb-lg breathe pointer-events-none opacity-40 group-hover:opacity-60 transition-opacity" style="--oc: #fff; --oe: #090E39;"></div>

                    <div class="relative z-10">
                        <div class="relative w-16 h-16 mb-5">
                            <div class="absolute inset-0 translate-x-1.5 translate-y-1.5 rounded-2xl bg-[#090E39]"></div>
                            <div class="relative w-full h-full rounded-2xl bg-white text-[#4647AE] grid place-items-center border-2 border-[#090E39] group-hover:-rotate-6 transition-transform">
                                {!! $icon('message-circle', 'w-8 h-8') !!}
                            </div>
                        </div>

                        <h2 class="font-display text-2xl font-extrabold mb-1 leading-tight">Social</h2>
                        <p class="text-sm text-white/75 mb-4 leading-relaxed">Ngobrol, sharing, dan temukan komunitas</p>

                        <div class="space-y-2 text-xs mb-5">
                            <div class="inline-flex items-center gap-2 bg-[#090E39]/60 border border-white/25 rounded-full px-3 py-1.5 font-extrabold">
                                {!! $icon('pen-line', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                <span>{{ $socialStats['my_posts'] }} post kamu</span>
                            </div>
                            <div class="inline-flex items-center gap-2 bg-[#090E39]/60 border border-white/25 rounded-full px-3 py-1.5 font-extrabold">
                                {!! $icon('users', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                <span>{{ $socialStats['followers'] }} followers</span>
                            </div>
                            @if ($socialStats['unread_messages'] > 0)
                                <div class="inline-flex items-center gap-2 bg-pink-500/30 border border-pink-300/60 rounded-full px-3 py-1.5 font-extrabold text-pink-100">
                                    {!! $icon('mail', 'w-3.5 h-3.5') !!}
                                    <span>{{ $socialStats['unread_messages'] }} pesan baru</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between bg-white text-[#090E39] rounded-xl px-4 py-2.5 font-extrabold text-sm border-2 border-[#090E39] group-hover:bg-[#4382DF] group-hover:text-white transition-colors">
                            <span>Masuk</span>
                            <span class="group-hover:translate-x-1 transition-transform inline-flex">
                                {!! $icon('arrow-right', 'w-4 h-4') !!}
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- PLAYGROUND CARD --}}
            <div class="reveal" style="transition-delay: 200ms">
                <a href="{{ route('play.home') }}" style="view-transition-name: card-playground;"
                   data-tilt data-rot="-2" data-cursor="Masuk"
                   class="group relative block rounded-[2rem] p-6 sm:p-7 bg-gradient-to-br from-[#112E81] via-[#4647AE] to-[#090E39] text-white border-2 border-white extrude ex-k h-full overflow-hidden">
                    <div class="absolute -right-14 -bottom-14 w-48 h-48 orb orb-lg breathe pointer-events-none opacity-40 group-hover:opacity-60 transition-opacity" style="--oc: #4382DF; --oe: #090E39;"></div>

                    <div class="relative z-10">
                        <div class="relative w-16 h-16 mb-5">
                            <div class="absolute inset-0 translate-x-1.5 translate-y-1.5 rounded-2xl bg-[#090E39]"></div>
                            <div class="relative w-full h-full rounded-2xl bg-white text-[#4647AE] grid place-items-center border-2 border-[#090E39] group-hover:-rotate-6 transition-transform">
                                {!! $icon('gamepad', 'w-8 h-8') !!}
                            </div>
                        </div>

                        <h2 class="font-display text-2xl font-extrabold mb-1 leading-tight">Playground</h2>
                        <p class="text-sm text-white/75 mb-4 leading-relaxed">Battle, deck building, dan naik rank</p>

                        <div class="space-y-2 text-xs mb-5">
                            <div class="inline-flex items-center gap-2 bg-[#090E39]/60 border border-white/25 rounded-full px-3 py-1.5 font-extrabold">
                                {!! $icon('award', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                <span class="capitalize">{{ $playgroundStats['rank'] }} · {{ $playgroundStats['points'] }} poin</span>
                            </div>
                            <div class="inline-flex items-center gap-2 bg-[#090E39]/60 border border-white/25 rounded-full px-3 py-1.5 font-extrabold">
                                {!! $icon('swords', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                <span>{{ $playgroundStats['battles'] }} battle · {{ $playgroundStats['wins'] }} menang</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between bg-white text-[#090E39] rounded-xl px-4 py-2.5 font-extrabold text-sm border-2 border-[#090E39] group-hover:bg-[#4382DF] group-hover:text-white transition-colors">
                            <span>Masuk</span>
                            <span class="group-hover:translate-x-1 transition-transform inline-flex">
                                {!! $icon('arrow-right', 'w-4 h-4') !!}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        {{-- ============ PROGRESS ============ --}}
        <section class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                <h3 class="inline-flex items-center gap-2 font-display font-extrabold text-lg">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('sparkles', 'w-4 h-4') !!}
                    </span>
                    Progress Kamu
                </h3>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#4382DF] bg-[#4382DF]/15 border border-[#4382DF]/40 rounded-full px-3 py-1">
                    Live Stats
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">

                {{-- Total Poin --}}
                <div class="bg-white text-[#090E39] border-2 border-white rounded-2xl p-4 extrude-sm ex-b hover:-translate-y-1 transition-transform">
                    <div class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/70 mb-1">
                        {!! $icon('zap', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        Total Poin
                    </div>
                    <p class="font-display font-extrabold text-3xl sm:text-4xl text-[#4647AE] leading-none">{{ $user->total_points }}</p>
                </div>

                {{-- Rank --}}
                <div class="bg-[#4382DF] text-[#090E39] border-2 border-white rounded-2xl p-4 extrude-sm ex-i hover:-translate-y-1 transition-transform">
                    <div class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/70 mb-1">
                        {!! $icon('trophy', 'w-3.5 h-3.5 text-[#090E39]') !!}
                        Rank
                    </div>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl text-[#090E39] leading-none capitalize">{{ $user->rank }}</p>
                </div>

                {{-- Achievement --}}
                <div class="bg-white text-[#090E39] border-2 border-white rounded-2xl p-4 extrude-sm ex-b hover:-translate-y-1 transition-transform">
                    <div class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/70 mb-1">
                        {!! $icon('award', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        Achievement
                    </div>
                    <p class="font-display font-extrabold text-3xl sm:text-4xl text-[#4647AE] leading-none">
                        {{ $globalStats['achievements'] }}<span class="text-base text-[#112E81]/50">/{{ $globalStats['total_achievements'] }}</span>
                    </p>
                </div>

                {{-- Total Aktivitas --}}
                <div class="bg-[#4647AE] text-white border-2 border-white rounded-2xl p-4 extrude-sm ex-k hover:-translate-y-1 transition-transform">
                    <div class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest text-white/70 mb-1">
                        {!! $icon('activity', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        Total Aktivitas
                    </div>
                    <p class="font-display font-extrabold text-3xl sm:text-4xl text-white leading-none">{{ $totalActivity }}</p>
                </div>
            </div>
        </section>

        {{-- Footer note --}}
        <p class="text-center text-xs text-white/40 mt-10 font-semibold">
            © {{ date('Y') }} DIGANOVA · Plan It. Join It. Enjoy It.
        </p>
    </main>

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
</body>
</html>