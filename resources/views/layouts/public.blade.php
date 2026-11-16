<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'DIGANOVA')</title>
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
            transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s, background .2s;
        }
        .hov-pop:hover { scale: 1.04; rotate: -1deg; z-index: 5; }

        /* Nav link underline */
        .nav-link {
            position: relative;
            transition: background .2s, color .2s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: .35rem;
            height: 2px;
            border-radius: 9999px;
            background: #4382DF;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s cubic-bezier(.3,1.4,.5,1);
        }
        .nav-link:hover::after { transform: scaleX(1); }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#090E39] text-white flex flex-col relative overflow-x-hidden selection:bg-[#4382DF] selection:text-white">

    @php
        /* ====== Icon library ====== */
        $icons = [
            'home'           => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
            'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
            'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'settings'       => '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>',
            'log-in'         => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/>',
            'log-out'        => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
            'user-plus'      => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/>',
            'check-circle'   => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
            'alert-circle'   => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>',
            'zap'            => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
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
        <nav class="max-w-7xl mx-auto flex items-center justify-between gap-2 bg-[#090E39]/85 backdrop-blur-xl border-2 border-white rounded-full px-2 sm:px-3 py-2 extrude-sm ex-i">

            {{-- ============ LEFT: Brand + Nav ============ --}}
            <div class="flex items-center gap-1.5 shrink-0 min-w-0">

                {{-- Hub link (auth only) --}}
                @auth
                    <a href="{{ route('hub') }}" title="Kembali ke Hub" data-cursor="Hub"
                       class="hov-pop hidden sm:inline-flex items-center gap-1.5 bg-white/10 hover:bg-[#4382DF] text-white border-2 border-white/25 hover:border-[#4382DF] px-3 py-2 rounded-full font-extrabold text-sm transition-colors">
                        {!! $icon('home', 'w-4 h-4') !!}
                        <span class="hidden lg:inline">Hub</span>
                    </a>
                @endauth

                {{-- Brand --}}
                <a href="{{ route('event.home') }}" data-cursor="DIGANOVA"
                   class="flex items-center gap-2 shrink-0 px-1">
                    <span class="shrink-0">{!! $logo('w-9 h-9') !!}</span>
                    <span class="flex flex-col leading-none">
                        <span class="font-display font-extrabold tracking-tight text-base sm:text-lg">
                            DIGANOVA
                        </span>
                        <span class="hidden sm:block mt-0.5 text-[9px] font-semibold tracking-wide text-white/60 whitespace-nowrap">
                            Plan It. Join It. Enjoy It.
                        </span>
                    </span>
                    <span class="hidden md:inline-flex items-center gap-1 ml-1 px-2.5 py-1 rounded-full bg-[#4382DF] text-[#090E39] text-[10px] font-extrabold uppercase tracking-widest border-2 border-white">
                        {!! $icon('zap', 'w-3 h-3') !!}
                        Event
                    </span>
                </a>

                {{-- Nav links (desktop) --}}
                <div class="hidden lg:flex items-center gap-0.5 ml-2 pl-2 border-l-2 border-white/15">
                    <a href="{{ route('event.events.index') }}"
                       class="nav-link px-3 py-2 rounded-full text-sm font-bold text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                        Events
                    </a>

                    @auth
                        <a href="{{ route('event.registrations.index') }}"
                           class="nav-link px-3 py-2 rounded-full text-sm font-bold text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                            Registrasi
                        </a>
                        <a href="{{ route('event.groups.index') }}"
                           class="nav-link px-3 py-2 rounded-full text-sm font-bold text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                            Grup
                        </a>
                    @endauth
                </div>
            </div>

            {{-- ============ RIGHT: Actions ============ --}}
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">

                @auth
                    {{-- Admin badge --}}
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.events.index') }}" data-cursor="Admin"
                           class="hov-pop hidden sm:inline-flex items-center gap-1 bg-red-500 text-white border-2 border-white px-2.5 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest">
                            {!! $icon('settings', 'w-3 h-3') !!}
                            Admin
                        </a>
                    @endif

                    {{-- Profile --}}
                    <a href="{{ route('profile.show') }}" data-cursor="Profil"
                       class="hov-pop inline-flex items-center gap-2 px-1 sm:px-1.5 py-1 pr-2 sm:pr-3 rounded-full bg-white/10 hover:bg-white border-2 border-white/25 hover:border-white transition-colors group">
                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-[#4382DF] to-[#4647AE] grid place-items-center text-white text-xs font-extrabold border-2 border-white shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="hidden md:inline text-sm font-extrabold text-white group-hover:text-[#090E39] transition-colors truncate max-w-[8rem]">
                            {{ auth()->user()->name }}
                        </span>
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-cursor="Keluar"
                                class="hov-pop inline-flex items-center justify-center gap-1.5 bg-white/10 hover:bg-red-500 text-white border-2 border-white/25 hover:border-red-500 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-extrabold transition-colors">
                            {!! $icon('log-out', 'w-4 h-4') !!}
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" data-cursor="Masuk"
                       class="nav-link hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-bold text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                        {!! $icon('log-in', 'w-4 h-4') !!}
                        Login
                    </a>
                    <a href="{{ route('register') }}" data-cursor="Daftar"
                       class="hov-pop inline-flex items-center gap-1.5 bg-white text-[#090E39] px-3 sm:px-4 py-2 rounded-full text-sm font-extrabold hover:bg-[#4382DF] hover:text-white transition-colors">
                        {!! $icon('user-plus', 'w-4 h-4') !!}
                        <span class="hidden sm:inline">Daftar</span>
                    </a>
                @endauth
            </div>
        </nav>

        {{-- Mobile nav row (Events / Registrasi / Grup) --}}
        <div class="lg:hidden max-w-7xl mx-auto mt-2 flex items-center gap-1.5 overflow-x-auto pb-1 px-1">
            <a href="{{ route('event.events.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/15 text-xs font-extrabold text-white/80 hover:bg-[#4382DF] hover:text-[#090E39] hover:border-[#4382DF] transition-colors whitespace-nowrap">
                {!! $icon('ticket', 'w-3 h-3') !!}
                Events
            </a>

            @auth
                <a href="{{ route('event.registrations.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/15 text-xs font-extrabold text-white/80 hover:bg-[#4382DF] hover:text-[#090E39] hover:border-[#4382DF] transition-colors whitespace-nowrap">
                    {!! $icon('ticket', 'w-3 h-3') !!}
                    Registrasi
                </a>
                <a href="{{ route('event.groups.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/15 text-xs font-extrabold text-white/80 hover:bg-[#4382DF] hover:text-[#090E39] hover:border-[#4382DF] transition-colors whitespace-nowrap">
                    {!! $icon('users', 'w-3 h-3') !!}
                    Grup
                </a>
                <a href="{{ route('hub') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/15 text-xs font-extrabold text-white/80 hover:bg-[#4382DF] hover:text-[#090E39] hover:border-[#4382DF] transition-colors whitespace-nowrap">
                    {!! $icon('home', 'w-3 h-3') !!}
                    Hub
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.events.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-500/30 border border-red-400 text-xs font-extrabold text-red-100 hover:bg-red-500 hover:text-white transition-colors whitespace-nowrap">
                        {!! $icon('settings', 'w-3 h-3') !!}
                        Admin
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/15 text-xs font-extrabold text-white/80 hover:bg-white hover:text-[#090E39] transition-colors whitespace-nowrap">
                    {!! $icon('log-in', 'w-3 h-3') !!}
                    Login
                </a>
            @endauth
        </div>
    </header>

    {{-- ============================ FLASH MESSAGES ============================ --}}
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

    @if (session('error'))
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="flex items-start gap-3 bg-red-500/15 backdrop-blur border-2 border-red-400 rounded-2xl p-4 extrude-sm ex-r">
                <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-red-500 text-white border-2 border-[#090E39]">
                    {!! $icon('alert-circle', 'w-4 h-4') !!}
                </span>
                <p class="text-sm font-extrabold text-red-100 self-center leading-snug">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    @endif

    {{-- ============================ MAIN ============================ --}}
    <main class="relative z-10 flex-1">@yield('content')</main>

    {{-- ============================ FOOTER ============================ --}}
    <footer class="relative z-10 border-t-2 border-white/10 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center">
            <div class="inline-flex items-center gap-2.5 mb-2">
                <span class="shrink-0">{!! $logo('w-9 h-9') !!}</span>
                <span class="font-display font-extrabold text-xl tracking-tight">DIGANOVA</span>
            </div>
            <p class="text-sm text-white/50 italic font-semibold">"Plan It. Join It. Enjoy It."</p>
            <p class="text-[11px] text-white/30 mt-4 font-semibold uppercase tracking-widest">
                © {{ date('Y') }} DIGANOVA · Event Platform
            </p>
        </div>
    </footer>

</body>
</html>