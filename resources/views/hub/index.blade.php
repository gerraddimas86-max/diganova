<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>Hub — DIGANOVA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0a0e1a] text-white relative overflow-x-hidden">

    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 -left-32 w-[32rem] h-[32rem] bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/4 -right-32 w-[32rem] h-[32rem] bg-violet-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute -bottom-32 left-1/3 w-[32rem] h-[32rem] bg-pink-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <nav class="relative z-10 bg-white/5 backdrop-blur-xl border-b border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🦊</span>
                    <span class="text-xl font-bold bg-gradient-to-r from-cyan-400 via-violet-400 to-pink-400 bg-clip-text text-transparent">
                        DIGANOVA
                    </span>
                    <span class="hidden sm:inline text-xs bg-white/10 text-slate-300 px-2 py-1 rounded-full border border-white/10">HUB</span>
                </div>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-red-400 hover:text-red-300 font-medium transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <div class="text-center mb-12">
            <div class="inline-block relative mb-5">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-400 via-violet-500 to-pink-500 flex items-center justify-center text-3xl font-bold text-white ring-4 ring-white/10 shadow-2xl shadow-violet-500/30">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-[#0a0e1a]"></div>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2">
                <span class="bg-gradient-to-r from-cyan-400 via-violet-400 to-pink-400 bg-clip-text text-transparent">Halo, {{ $user->name }}!</span>
                <span class="inline-block animate-wave origin-[70%_70%]">👋</span>
            </h1>
            <p class="text-slate-400 text-base sm:text-lg">Mau ke mana hari ini?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 mb-10">
            {{-- EVENT CARD --}}
            <a href="{{ route('event.home') }}" style="view-transition-name: card-event;"
               class="group relative rounded-2xl p-6 border-2 border-blue-500/30 bg-gradient-to-br from-blue-500/10 via-cyan-500/10 to-blue-500/5 hover:border-blue-400/60 hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-500/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300 origin-left">🎟️</div>
                    <h2 class="text-2xl font-bold text-white mb-1">Event</h2>
                    <p class="text-sm text-slate-400 mb-4">Festival, konser, cosplay, dan turnamen</p>
                    <div class="space-y-1.5 text-xs text-slate-400 mb-5">
                        <div class="flex items-center gap-2"><span class="text-blue-400">📅</span><span>{{ $eventStats['active_events'] }} event aktif</span></div>
                        <div class="flex items-center gap-2"><span class="text-cyan-400">🎫</span><span>{{ $eventStats['my_registrations'] }} registrasi kamu</span></div>
                    </div>
                    <div class="flex items-center justify-between text-sm font-semibold text-blue-300">
                        <span>Masuk</span><span class="group-hover:translate-x-1 transition-transform duration-300">→</span>
                    </div>
                </div>
            </a>

            {{-- SOCIAL CARD --}}
            <a href="{{ route('social.home') }}" style="view-transition-name: card-social;"
               class="group relative rounded-2xl p-6 border-2 border-violet-500/30 bg-gradient-to-br from-violet-500/10 via-purple-500/10 to-violet-500/5 hover:border-violet-400/60 hover:scale-[1.02] hover:shadow-2xl hover:shadow-violet-500/20 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-violet-500/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300 origin-left">💬</div>
                    <h2 class="text-2xl font-bold text-white mb-1">Social</h2>
                    <p class="text-sm text-slate-400 mb-4">Ngobrol, sharing, dan temukan komunitas</p>
                    <div class="space-y-1.5 text-xs text-slate-400 mb-5">
                        <div class="flex items-center gap-2"><span class="text-violet-400">📝</span><span>{{ $socialStats['my_posts'] }} post kamu</span></div>
                        <div class="flex items-center gap-2"><span class="text-purple-400">👥</span><span>{{ $socialStats['followers'] }} followers</span></div>
                        @if ($socialStats['unread_messages'] > 0)
                            <div class="flex items-center gap-2 text-pink-400 font-medium"><span>💌</span><span>{{ $socialStats['unread_messages'] }} pesan baru</span></div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-sm font-semibold text-violet-300">
                        <span>Masuk</span><span class="group-hover:translate-x-1 transition-transform duration-300">→</span>
                    </div>
                </div>
            </a>

            {{-- PLAYGROUND CARD --}}
            <a href="{{ route('play.home') }}" style="view-transition-name: card-playground;"
               class="group relative rounded-2xl p-6 border-2 border-cyan-500/30 bg-gradient-to-br from-cyan-500/10 via-pink-500/10 to-cyan-500/5 hover:border-cyan-400/60 hover:scale-[1.02] hover:shadow-2xl hover:shadow-cyan-500/20 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-cyan-500/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300 origin-left">🎮</div>
                    <h2 class="text-2xl font-bold text-white mb-1">Playground</h2>
                    <p class="text-sm text-slate-400 mb-4">Battle, deck building, dan naik rank</p>
                    <div class="space-y-1.5 text-xs text-slate-400 mb-5">
                        <div class="flex items-center gap-2"><span class="text-cyan-400">🏅</span><span class="capitalize">{{ $playgroundStats['rank'] }} · {{ $playgroundStats['points'] }} poin</span></div>
                        <div class="flex items-center gap-2"><span class="text-pink-400">⚔️</span><span>{{ $playgroundStats['battles'] }} battle · {{ $playgroundStats['wins'] }} menang</span></div>
                    </div>
                    <div class="flex items-center justify-between text-sm font-semibold text-cyan-300">
                        <span>Masuk</span><span class="group-hover:translate-x-1 transition-transform duration-300">→</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm uppercase tracking-wider text-slate-500 font-semibold">Progress Kamu</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-bold bg-gradient-to-br from-cyan-400 to-blue-500 bg-clip-text text-transparent">{{ $user->total_points }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mt-1">Total Poin</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-bold bg-gradient-to-br from-yellow-400 to-amber-500 bg-clip-text text-transparent capitalize">{{ $user->rank }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mt-1">Rank</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-bold bg-gradient-to-br from-violet-400 to-purple-500 bg-clip-text text-transparent">{{ $globalStats['achievements'] }}<span class="text-lg text-slate-600">/{{ $globalStats['total_achievements'] }}</span></p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mt-1">Achievement</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-bold bg-gradient-to-br from-pink-400 to-rose-500 bg-clip-text text-transparent">{{ $eventStats['my_registrations'] + $socialStats['my_posts'] + $playgroundStats['battles'] }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mt-1">Total Aktivitas</p>
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes wave { 0%, 100% { transform: rotate(0deg); } 10%, 30% { transform: rotate(-14deg); } 20%, 40% { transform: rotate(14deg); } 50% { transform: rotate(0deg); } }
        .animate-wave { animation: wave 2s ease-in-out infinite; }
    </style>
</body>
</html>