<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'Playground — DIGANOVA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#050505] text-white font-mono selection:bg-cyan-500/30">
    @php $pointsDisplay = auth()->check() ? auth()->user()->total_points : 0; @endphp

    <nav class="bg-[#0a0a0a]/90 backdrop-blur-md border-b border-cyan-500/20 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('hub') }}" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-cyan-400 hover:bg-slate-800 px-2.5 py-2 rounded-lg font-bold transition font-sans uppercase tracking-wider" title="Kembali ke Hub">
                            <span>🏠</span><span class="hidden md:inline">Hub</span>
                        </a>
                    @endauth
                    <a href="{{ route('play.home') }}" class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-fuchsia-500 tracking-widest font-sans">DIGANOVA</a>
                    <span class="hidden sm:inline text-[10px] bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 px-2 py-0.5 rounded uppercase font-bold tracking-wider">Playground</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-4 font-sans">
                    @auth
                        <a href="{{ route('play.home') }}" class="hidden sm:inline text-xs font-bold uppercase {{ request()->routeIs('play.home') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }}">Lobby</a>
                        <a href="{{ route('play.decks.index') }}" class="hidden md:inline text-xs font-bold uppercase {{ request()->routeIs('play.decks.*') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }}">Deck</a>
                        <a href="{{ route('play.battle.pve') }}" class="hidden md:inline text-xs font-bold uppercase {{ request()->routeIs('play.battle.*') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }}">Battle</a>
                        <a href="{{ route('play.leaderboard') }}" class="hidden md:inline text-xs font-bold uppercase {{ request()->routeIs('play.leaderboard') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }}">Rank</a>
                        
                        <div class="hidden sm:flex items-center gap-1.5 bg-cyan-500/10 px-3 py-1.5 rounded-lg border border-cyan-500/30">
                            <span class="text-cyan-400 text-xs">⭐</span><span class="text-sm font-black text-cyan-300">{{ $pointsDisplay }}</span>
                        </div>
                        
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-slate-800 transition">
                            <x-navo :skin="auth()->user()->navoProfile?->equippedSkin" size="w-8 h-8" textSize="text-base" />
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-xs text-red-400 hover:text-red-300 font-bold uppercase">Logout</button></form>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase text-slate-300 hover:text-cyan-400">Login</a>
                        <a href="{{ route('register') }}" class="bg-cyan-500 hover:bg-cyan-400 text-black text-xs font-bold uppercase px-4 py-2 rounded-lg transition shadow-[0_0_10px_rgba(34,211,238,0.3)]">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-6xl mx-auto px-4 py-8">@yield('content')</main>
</body>
</html>