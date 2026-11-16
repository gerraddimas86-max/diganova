<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — DIGANOVA</title>
    
    <!-- Favicon SVG (Menggunakan logo kustom) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg viewBox='0 0 100 100' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='4' y='4' width='92' height='92' rx='24' fill='%234382DF' stroke='%23FFFFFF' stroke-width='8'/%3E%3Cpath d='M30 22H52C73 22 86 37 86 56C86 75 73 90 52 90H30V22Z' fill='%234647AE' stroke='%23FFFFFF' stroke-width='8' stroke-linejoin='round'/%3E%3Ccircle cx='54' cy='56' r='12' fill='%23FFFFFF'/%3E%3Cpath d='M20 80L26 66L32 80L26 94L20 80Z' fill='%23FFFFFF' stroke='%23090E39' stroke-width='3'/%3E%3C/svg%3E">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;600;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        /* Hover pop */
        .hov-pop {
            transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s;
        }
        .hov-pop:hover { scale: 1.04; rotate: -1deg; z-index: 5; }

        /* Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
        }
        .reveal.in { opacity: 1; transform: none; }

        /* Floating */
        @keyframes floaty { 0%,100% { transform: translateY(0) rotate(var(--rot,0deg)); } 50% { transform: translateY(-12px) rotate(calc(var(--rot,0deg) + 2deg)); } }
        @keyframes breathe { 0%,100% { transform: scale(1); } 50% { transform: scale(1.06); } }
        .floaty { animation: floaty 6s ease-in-out infinite; }
        .floaty-2 { animation: floaty 7.5s ease-in-out infinite reverse; }
        .breathe { animation: breathe 4s ease-in-out infinite; }

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

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
            .reveal { opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#090E39] text-white relative overflow-x-hidden selection:bg-[#4382DF] selection:text-white">

    @php
        /* ====== Icon library ====== */
        $icons = [
            'layout-dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>',
            'log-out'          => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
            'sparkles'         => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
            'check-circle'     => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
            'ticket'           => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
            'message-circle'   => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
            'gamepad'          => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
            'construction'     => '<rect x="2" y="6" width="20" height="8" rx="1"/><path d="M17 14v7M7 14v7M17 3v3M7 3v3M10 14 2.3 6.3M14 6l7.7 7.7M8 6l8 8"/>',
            'arrow-right'      => '<path d="M5 12h14M12 5l7 7-7 7"/>',
            'zap'              => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
        ];
        $icon = fn (string $name, string $class = 'w-6 h-6') =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

        /* ====== Logo Kustom (SVG) ====== */
        // Jika ingin kembali menggunakan gambar dari folder public/images/logo.png,
        // silakan hapus blok SVG di bawah dan gunakan:
        // $logo = fn (string $class = 'w-10 h-10') => '<img src="' . asset('images/logo.png') . '" alt="Logo DIGANOVA" class="' . $class . ' object-contain" draggable="false">';
        
        $logo = fn (string $class = 'w-10 h-10') =>
            '<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="' . $class . '">
                <rect x="4" y="4" width="92" height="92" rx="24" fill="#4382DF" stroke="#FFFFFF" stroke-width="8"/>
                <path d="M30 22H52C73 22 86 37 86 56C86 75 73 90 52 90H30V22Z" fill="#4647AE" stroke="#FFFFFF" stroke-width="8" stroke-linejoin="round"/>
                <circle cx="54" cy="56" r="12" fill="#FFFFFF"/>
                <path d="M20 80L26 66L32 80L26 94L20 80Z" fill="#FFFFFF" stroke="#090E39" stroke-width="3"/>
            </svg>';
    @endphp

    {{-- ============================ NAVBAR ============================ --}}
    <header class="sticky top-0 z-40 px-4 pt-3">
        <nav class="max-w-7xl mx-auto flex items-center justify-between gap-3 bg-[#090E39]/85 backdrop-blur-xl border-2 border-white rounded-full px-3 py-2 extrude-sm ex-i">

            <a href="/" class="flex items-center gap-2.5 shrink-0 hov-pop">
                <span class="shrink-0">{!! $logo('w-10 h-10') !!}</span>
                <span class="flex flex-col leading-none">
                    <span class="font-display font-extrabold tracking-tight text-lg">DIGANOVA</span>
                    <span class="mt-0.5 text-[9px] font-semibold tracking-wide text-white/60 whitespace-nowrap hidden sm:block">Plan It. Join It. Enjoy It.</span>
                </span>
                <span class="hidden sm:inline-flex items-center gap-1 ml-1 px-2.5 py-1 rounded-full bg-[#4382DF] text-[#090E39] text-[10px] font-extrabold uppercase tracking-widest border-2 border-white">
                    {!! $icon('layout-dashboard', 'w-3 h-3') !!}
                    Dashboard
                </span>
            </a>

            <div class="flex items-center gap-2 shrink-0">
                <span class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-full bg-white/10 border-2 border-white/25 text-white text-sm font-extrabold">
                    {!! $icon('zap', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    <span>Hi, <span class="text-[#4382DF]">{{ auth()->user()->name }}</span></span>
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" data-cursor="Keluar"
                            class="hov-pop inline-flex items-center gap-2 bg-white/10 hover:bg-red-500 text-white border-2 border-white/25 hover:border-red-500 px-3 sm:px-4 py-2 rounded-full text-sm font-extrabold transition-colors">
                        {!! $icon('log-out', 'w-4 h-4') !!}
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </header>

    {{-- ============================ FLASH SUCCESS ============================ --}}
    @if (session('success'))
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="flex items-start gap-3 bg-emerald-500/15 backdrop-blur border-2 border-emerald-400 rounded-2xl p-4 extrude-sm ex-g">
                <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-emerald-400 text-[#090E39] border-2 border-[#090E39]">
                    {!! $icon('check-circle', 'w-4 h-4') !!}
                </span>
                <p class="text-sm font-extrabold text-emerald-100 self-center leading-snug">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif

    {{-- ============================ MAIN ============================ --}}
    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

        {{-- ============ HERO / WELCOME CARD (SOLID) ============ --}}
        <div class="reveal relative bg-[#4647AE] border-2 border-white rounded-[2.5rem] extrude ex-k p-6 sm:p-10 lg:p-14 overflow-hidden">

            {{-- Watermark Logo di Kanan Bawah --}}
            <div class="absolute -right-8 -bottom-8 w-48 h-48 sm:w-64 sm:h-64 opacity-10 pointer-events-none rotate-12">
                {!! $logo('w-full h-full') !!}
            </div>

            {{-- Decorative elements --}}
            <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full border-[30px] border-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-16 w-56 h-56 pointer-events-none">
                <div class="orb orb-lg breathe w-full h-full opacity-60"></div>
            </div>
            <div class="absolute top-1/3 right-1/4 w-12 h-12 pointer-events-none floaty-2">
                <div class="orb orb-w w-full h-full"></div>
            </div>
            <div class="absolute bottom-16 right-1/3 w-8 h-8 pointer-events-none floaty">
                <div class="orb orb-i w-full h-full"></div>
            </div>

            <div class="relative z-10 max-w-2xl">
                {{-- Small badge --}}
                <span class="hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-xs font-extrabold uppercase tracking-widest border-2 border-[#090E39] shadow-[3px_3px_0_#090E39] mb-5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4647AE]"></span>
                    </span>
                    Dashboard
                </span>

                {{-- Welcome heading --}}
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight mb-4">
                    <span class="text-white">Selamat datang,</span><br>
                    <span class="relative inline-block my-1">
                        <span class="absolute inset-0 bg-[#090E39] -rotate-1 rounded-2xl" aria-hidden="true"></span>
                        <span class="relative px-3 text-white">{{ auth()->user()->name }}</span>
                    </span>
                    <span class="inline-flex items-center ml-1 align-middle text-[#4382DF]">
                        {!! $icon('sparkles', 'w-8 h-8 sm:w-10 sm:h-10') !!}
                    </span>
                </h1>

                {{-- Description --}}
                <p class="text-white/80 text-base sm:text-lg leading-relaxed mb-6">
                    Ini dashboard sementara. Modul <strong class="text-white">Event</strong>,
                    <strong class="text-white">Social</strong>, dan
                    <strong class="text-white">Playground</strong> sedang dalam pengembangan.
                </p>

                {{-- Coming soon chips --}}
                <div class="flex flex-wrap gap-2.5 mb-6">
                    <span class="inline-flex items-center gap-2 bg-white/15 border-2 border-white/30 rounded-full px-3.5 py-1.5 text-xs font-extrabold text-white">
                        {!! $icon('ticket', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        Event
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4382DF] animate-pulse"></span>
                    </span>
                    <span class="inline-flex items-center gap-2 bg-white/15 border-2 border-white/30 rounded-full px-3.5 py-1.5 text-xs font-extrabold text-white">
                        {!! $icon('message-circle', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        Social
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4382DF] animate-pulse"></span>
                    </span>
                    <span class="inline-flex items-center gap-2 bg-white/15 border-2 border-white/30 rounded-full px-3.5 py-1.5 text-xs font-extrabold text-white">
                        {!! $icon('gamepad', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        Playground
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4382DF] animate-pulse"></span>
                    </span>
                </div>

                {{-- "Under construction" note --}}
                <div class="inline-flex items-start gap-2.5 bg-[#090E39]/60 backdrop-blur border-2 border-dashed border-white/30 rounded-2xl px-4 py-3 max-w-md">
                    <span class="shrink-0 w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('construction', 'w-4 h-4') !!}
                    </span>
                    <p class="text-xs text-white/70 font-semibold leading-relaxed self-center">
                        Halaman ini akan segera menampilkan ringkasan aktivitas dari semua modul.
                    </p>
                </div>
            </div>
        </div>

        {{-- ============ QUICK PREVIEW CARDS ============ --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
            <div class="reveal" style="transition-delay: 0ms">
                <div class="bg-white text-[#090E39] border-2 border-white rounded-[2rem] extrude-sm ex-b p-5 hover:-translate-y-1 transition-transform h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="shrink-0 w-11 h-11 grid place-items-center rounded-xl bg-[#4382DF] text-white border-2 border-[#090E39]">
                            {!! $icon('ticket', 'w-5 h-5') !!}
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/60">Modul</p>
                            <p class="font-display font-extrabold text-lg leading-none">Event</p>
                        </div>
                    </div>
                    <p class="text-sm text-[#112E81]/70 font-semibold leading-relaxed mb-3">
                        Festival, konser, cosplay, dan turnamen game.
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs font-extrabold uppercase tracking-widest text-[#4647AE]">
                        Coming Soon
                        {!! $icon('arrow-right', 'w-3.5 h-3.5') !!}
                    </span>
                </div>
            </div>

            <div class="reveal" style="transition-delay: 100ms">
                <div class="bg-[#4382DF] text-[#090E39] border-2 border-white rounded-[2rem] extrude-sm ex-w p-5 hover:-translate-y-1 transition-transform h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="shrink-0 w-11 h-11 grid place-items-center rounded-xl bg-white text-[#4647AE] border-2 border-[#090E39]">
                            {!! $icon('message-circle', 'w-5 h-5') !!}
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/70">Modul</p>
                            <p class="font-display font-extrabold text-lg leading-none">Social</p>
                        </div>
                    </div>
                    <p class="text-sm font-semibold leading-relaxed mb-3 text-[#112E81]/80">
                        Ngobrol, sharing, dan temukan komunitas.
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs font-extrabold uppercase tracking-widest text-[#090E39]">
                        Coming Soon
                        {!! $icon('arrow-right', 'w-3.5 h-3.5') !!}
                    </span>
                </div>
            </div>

            <div class="reveal" style="transition-delay: 200ms">
                <div class="bg-[#4647AE] text-white border-2 border-white rounded-[2rem] extrude-sm ex-k p-5 hover:-translate-y-1 transition-transform h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="shrink-0 w-11 h-11 grid place-items-center rounded-xl bg-white text-[#4647AE] border-2 border-[#090E39]">
                            {!! $icon('gamepad', 'w-5 h-5') !!}
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-white/60">Modul</p>
                            <p class="font-display font-extrabold text-lg leading-none">Playground</p>
                        </div>
                    </div>
                    <p class="text-sm text-white/80 font-semibold leading-relaxed mb-3">
                        Battle, deck building, dan naik rank.
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs font-extrabold uppercase tracking-widest text-white">
                        Coming Soon
                        {!! $icon('arrow-right', 'w-3.5 h-3.5') !!}
                    </span>
                </div>
            </div>
        </div>
    </main>

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

</body>
</html>