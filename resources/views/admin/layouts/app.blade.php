<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — DIGANOVA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;600;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $icons = [
            'shield'       => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
            'calendar'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
            'ticket'       => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
            'scan'         => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/>',
            'home'         => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'log-out'      => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
            'user'         => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
            'alert-circle' => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>',
            'x'            => '<path d="M18 6 6 18M6 6l12 12"/>',
            'zap'          => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
            'external-link'=> '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        ];
        $icon = fn (string $name, string $class = 'w-5 h-5') =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

        $logo = fn (string $class = 'w-9 h-9') =>
            '<img src="' . asset('images/logo.png') . '" alt="Logo DIGANOVA" class="' . $class . ' object-contain" draggable="false">';
    @endphp

    <style>
        :root {
            --c-white: #FFFFFF; --c-indigo: #4647AE; --c-blue: #4382DF;
            --c-deep: #112E81; --c-navy: #090E39; --c-black: #000000;
        }
        body { font-family: 'Space Grotesk', system-ui, sans-serif; }
        .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

        /* Extrude */
        .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-k { --ec: #000000; } .ex-g { --ec: #10B981; } .ex-r { --ec: #EF4444; }
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
            transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s, background .2s, color .2s;
        }
        .hov-pop:hover { scale: 1.04; rotate: -1deg; z-index: 5; }

        /* Pending badge pulse */
        @keyframes badge-ping {
            0%   { box-shadow: 0 0 0 0 rgba(239, 68, 68, .55); }
            70%  { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .badge-ping { animation: badge-ping 2s cubic-bezier(0, 0, .2, 1) infinite; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#090E39] text-white flex flex-col relative overflow-x-hidden selection:bg-[#4382DF] selection:text-white">

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

            {{-- LEFT: Brand + Admin badge + Menu --}}
            <div class="flex items-center gap-1.5 shrink-0 min-w-0">

                {{-- Brand --}}
                <a href="{{ route('admin.events.index') }}" data-cursor="Admin"
                   class="flex items-center gap-2 shrink-0 px-1">
                    <span class="shrink-0">{!! $logo('w-9 h-9') !!}</span>
                    <span class="flex flex-col leading-none">
                        <span class="font-display font-extrabold tracking-tight text-base sm:text-lg">
                            DIGANOVA
                        </span>
                        <span class="hidden sm:block mt-0.5 text-[9px] font-semibold tracking-wide text-white/60 whitespace-nowrap">
                            Admin Panel
                        </span>
                    </span>
                    <span class="hidden md:inline-flex items-center gap-1 ml-1 px-2.5 py-1 rounded-full bg-red-500 text-white text-[10px] font-extrabold uppercase tracking-widest border-2 border-white">
                        {!! $icon('shield', 'w-3 h-3') !!}
                        Admin
                    </span>
                </a>

                {{-- Desktop menu --}}
                <div class="hidden md:flex items-center gap-0.5 ml-2 pl-2 border-l-2 border-white/15">
                    {{-- Events --}}
                    <a href="{{ route('admin.events.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-bold transition-colors
                              {{ request()->routeIs('admin.events.*')
                                  ? 'bg-[#4382DF] text-[#090E39]'
                                  : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        {!! $icon('calendar', 'w-3.5 h-3.5') !!}
                        Events
                    </a>

                    {{-- Registrations --}}
                    @php
                        $pendingAdminCount = \App\Models\EventRegistration::where('payment_status', 'pending')->count();
                    @endphp
                    <a href="{{ route('admin.registrations.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-bold transition-colors
                              {{ request()->routeIs('admin.registrations.*')
                                  ? 'bg-[#4382DF] text-[#090E39]'
                                  : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        {!! $icon('ticket', 'w-3.5 h-3.5') !!}
                        Registrations
                        @if ($pendingAdminCount > 0)
                            <span class="badge-ping inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-extrabold text-white bg-red-500 rounded-full border border-white">
                                {{ $pendingAdminCount > 99 ? '99+' : $pendingAdminCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Check-in --}}
                    <a href="{{ route('admin.checkin.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-bold transition-colors
                              {{ request()->routeIs('admin.checkin.*')
                                  ? 'bg-[#4382DF] text-[#090E39]'
                                  : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        {!! $icon('scan', 'w-3.5 h-3.5') !!}
                        Check-in
                    </a>
                </div>
            </div>

            {{-- RIGHT: Public site + User + Logout --}}
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">

                {{-- Public site link --}}
                <a href="{{ route('event.home') }}" data-cursor="Public"
                   class="hov-pop hidden sm:inline-flex items-center gap-1.5 bg-white/10 hover:bg-white text-white hover:text-[#090E39] border-2 border-white/25 hover:border-white px-3 py-2 rounded-full text-xs font-extrabold transition-colors">
                    {!! $icon('external-link', 'w-3.5 h-3.5') !!}
                    <span class="hidden lg:inline">Public Site</span>
                </a>

                {{-- User pill --}}
                <span class="hidden sm:inline-flex items-center gap-2 bg-white/10 border-2 border-white/25 px-3 py-1.5 rounded-full">
                    <span class="w-6 h-6 rounded-full bg-gradient-to-br from-[#4382DF] to-[#4647AE] grid place-items-center text-white text-[10px] font-extrabold border-2 border-white shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="text-xs font-extrabold text-white/80 max-w-[8rem] truncate">
                        {{ auth()->user()->name }}
                    </span>
                </span>

                {{-- Mobile avatar --}}
                <span class="sm:hidden inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-[#4382DF] to-[#4647AE] text-white text-xs font-extrabold border-2 border-white shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" data-cursor="Keluar"
                            class="hov-pop inline-flex items-center justify-center gap-1.5 bg-white/10 hover:bg-red-500 text-white border-2 border-white/25 hover:border-red-500 w-9 h-9 sm:w-auto sm:h-auto sm:px-3 sm:py-2 rounded-full text-sm font-extrabold transition-colors">
                        {!! $icon('log-out', 'w-4 h-4') !!}
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        {{-- Mobile menu row --}}
        <div class="md:hidden max-w-7xl mx-auto mt-2 flex items-center gap-1.5 overflow-x-auto pb-1 px-1">
            <a href="{{ route('admin.events.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold whitespace-nowrap transition-colors
                      {{ request()->routeIs('admin.events.*')
                          ? 'bg-[#4382DF] text-[#090E39] border-2 border-[#4382DF]'
                          : 'bg-white/5 border border-white/15 text-white/80 hover:bg-[#4382DF] hover:text-[#090E39] hover:border-[#4382DF]' }}">
                {!! $icon('calendar', 'w-3 h-3') !!}
                Events
            </a>

            <a href="{{ route('admin.registrations.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold whitespace-nowrap transition-colors
                      {{ request()->routeIs('admin.registrations.*')
                          ? 'bg-[#4382DF] text-[#090E39] border-2 border-[#4382DF]'
                          : 'bg-white/5 border border-white/15 text-white/80 hover:bg-[#4382DF] hover:text-[#090E39] hover:border-[#4382DF]' }}">
                {!! $icon('ticket', 'w-3 h-3') !!}
                Registrations
                @if ($pendingAdminCount > 0)
                    <span class="inline-flex items-center justify-center min-w-[18px] h-4 px-1 text-[9px] font-extrabold text-white bg-red-500 rounded-full">
                        {{ $pendingAdminCount > 99 ? '99+' : $pendingAdminCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.checkin.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold whitespace-nowrap transition-colors
                      {{ request()->routeIs('admin.checkin.*')
                          ? 'bg-[#4382DF] text-[#090E39] border-2 border-[#4382DF]'
                          : 'bg-white/5 border border-white/15 text-white/80 hover:bg-[#4382DF] hover:text-[#090E39] hover:border-[#4382DF]' }}">
                {!! $icon('scan', 'w-3 h-3') !!}
                Check-in
            </a>

            <a href="{{ route('event.home') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/15 text-xs font-extrabold text-white/80 hover:bg-white hover:text-[#090E39] transition-colors whitespace-nowrap">
                {!! $icon('home', 'w-3 h-3') !!}
                Public Site
            </a>
        </div>
    </header>

    {{-- ============================ FLASH ============================ --}}
    @if (session('success'))
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="flex items-start gap-3 bg-emerald-500/15 backdrop-blur border-2 border-emerald-400 rounded-2xl p-4 extrude-sm ex-g">
                <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-emerald-400 text-[#090E39] border-2 border-[#090E39]">
                    {!! $icon('check-circle', 'w-4 h-4') !!}
                </span>
                <p class="flex-1 text-sm font-extrabold text-emerald-100 self-center leading-snug">
                    {{ session('success') }}
                </p>
                <button type="button" onclick="this.parentElement.remove()"
                        aria-label="Tutup"
                        class="shrink-0 w-7 h-7 grid place-items-center rounded-lg bg-emerald-500/30 hover:bg-emerald-500 text-emerald-100 hover:text-white transition-colors">
                    {!! $icon('x', 'w-3.5 h-3.5') !!}
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="flex items-start gap-3 bg-red-500/15 backdrop-blur border-2 border-red-400 rounded-2xl p-4 extrude-sm ex-r">
                <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-red-500 text-white border-2 border-[#090E39]">
                    {!! $icon('alert-circle', 'w-4 h-4') !!}
                </span>
                <p class="flex-1 text-sm font-extrabold text-red-100 self-center leading-snug">
                    {{ session('error') }}
                </p>
                <button type="button" onclick="this.parentElement.remove()"
                        aria-label="Tutup"
                        class="shrink-0 w-7 h-7 grid place-items-center rounded-lg bg-red-500/30 hover:bg-red-500 text-red-100 hover:text-white transition-colors">
                    {!! $icon('x', 'w-3.5 h-3.5') !!}
                </button>
            </div>
        </div>
    @endif

    {{-- ============================ MAIN ============================ --}}
    <main class="relative z-10 flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @yield('content')
    </main>

</body>
</html>