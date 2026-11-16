<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'Profil — DIGANOVA')</title>
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
        .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-d { --ec: #112E81; } .ex-k { --ec: #000000; } .ex-g { --ec: #10B981; } .ex-r { --ec: #EF4444; }
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

        /* Grid background */
        .bg-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        }

        /* Hover pop */
        .hov-pop {
            transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s;
        }
        .hov-pop:hover { scale: 1.04; rotate: -1deg; z-index: 5; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#090E39] text-white flex flex-col relative overflow-x-hidden selection:bg-[#4382DF] selection:text-white">

    @php
        /* ====== Icon library ====== */
        $icons = [
            'home'      => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'log-out'   => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
            'user'      => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        ];
        $icon = fn (string $name, string $class = 'w-6 h-6') =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

        $logo = fn (string $class = 'w-10 h-10') =>
            '<img src="' . asset('images/logo.png') . '" alt="Logo DIGANOVA" class="' . $class . ' object-contain" draggable="false">';
    @endphp

    {{-- Background decor --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-grid opacity-50"></div>
        <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-32 left-1/3 w-[30rem] h-[30rem] bg-[#112E81]/70 rounded-full blur-[120px]"></div>
    </div>

    {{-- ============================ NAVBAR ============================ --}}
    <header class="sticky top-0 z-40 px-4 pt-3">
        <nav class="max-w-5xl mx-auto flex items-center justify-between gap-3 bg-[#090E39]/85 backdrop-blur-xl border-2 border-white rounded-full px-3 py-2 extrude-sm ex-i">

            {{-- Brand + Hub link --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('hub') }}"
                   data-cursor="Hub"
                   class="hov-pop inline-flex items-center gap-2 bg-white/10 hover:bg-[#4382DF] text-white border-2 border-white/25 hover:border-[#4382DF] px-3 py-2 rounded-full font-extrabold text-sm transition-colors">
                    {!! $icon('home', 'w-4 h-4') !!}
                    <span class="hidden sm:inline">Hub</span>
                </a>

                <span class="hidden sm:flex items-center gap-2 pl-3 ml-1 border-l-2 border-white/15">
                    <span class="shrink-0">{!! $logo('w-9 h-9') !!}</span>
                    <span class="flex flex-col leading-none">
                        <span class="font-display font-extrabold tracking-tight text-base">Profil Terpadu</span>
                        <span class="mt-0.5 text-[9px] font-semibold tracking-wide text-white/60 whitespace-nowrap">DIGANOVA</span>
                    </span>
                </span>
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-2 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            data-cursor="Keluar"
                            class="hov-pop inline-flex items-center gap-2 bg-white/10 hover:bg-red-500 text-white border-2 border-white/25 hover:border-red-500 px-4 py-2 rounded-full text-sm font-extrabold transition-colors">
                        {!! $icon('log-out', 'w-4 h-4') !!}
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        {{-- Mobile label under navbar --}}
        <div class="sm:hidden max-w-5xl mx-auto mt-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#4647AE] text-white text-[10px] font-extrabold uppercase tracking-widest border-2 border-white">
                {!! $icon('user', 'w-3 h-3') !!}
                Profil Terpadu
            </span>
        </div>
    </header>

    {{-- ============================ FLASH SUCCESS ============================ --}}
    @if (session('success'))
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
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
    <main class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 flex-1 w-full">
        @yield('content')
    </main>

    {{-- ============================ FOOTER ============================ --}}
    <footer class="relative z-10 border-t-2 border-white/10 mt-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/50 font-semibold">
            <p>
                © {{ date('Y') }} <span class="text-white font-extrabold">DIGANOVA</span>
                · Plan It. Join It. Enjoy It.
            </p>
            <p class="uppercase tracking-widest text-[10px] font-extrabold text-[#4382DF]">Profil Terpadu</p>
        </div>
    </footer>

</body>
</html>