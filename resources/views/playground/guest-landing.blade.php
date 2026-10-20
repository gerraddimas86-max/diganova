<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin">
    <title>DIGANOVA Playground — Navo Clash</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#050505] text-white overflow-x-hidden selection:bg-cyan-500/30 font-mono">

    {{-- Cyber Grid Background --}}
    <div class="fixed inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(0, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 255, 255, 0.05) 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    {{-- Glow Blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-fuchsia-500/20 rounded-full blur-[120px]"></div>
    </div>

    {{-- Navbar Guest --}}
    <nav class="relative z-50 bg-[#050505]/80 backdrop-blur-md border-b border-cyan-500/30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🦊</span>
                    <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-fuchsia-500 tracking-widest">NAVO CLASH</span>
                </div>
                <div class="flex items-center gap-4 font-sans">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-cyan-400 hover:text-cyan-300 transition">LOGIN</a>
                    <a href="{{ route('register') }}" class="bg-cyan-500/10 border border-cyan-400 text-cyan-400 hover:bg-cyan-400 hover:text-black px-6 py-2 rounded font-bold transition shadow-[0_0_15px_rgba(34,211,238,0.3)]">DAFTAR</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <main class="relative z-10 max-w-6xl mx-auto px-4 pt-20 pb-32 text-center flex flex-col items-center">
        
        <div class="inline-block border border-fuchsia-500/50 bg-fuchsia-500/10 text-fuchsia-400 text-xs px-4 py-1.5 rounded-full mb-6 tracking-widest uppercase">
            Turn-Based Card Battle
        </div>

        <h1 class="text-6xl md:text-8xl font-black mb-6 tracking-tighter uppercase font-sans">
            <span class="text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-500">Kuasai</span><br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-fuchsia-500 drop-shadow-[0_0_30px_rgba(34,211,238,0.5)]">Arena.</span>
        </h1>
        
        <p class="text-lg text-slate-400 max-w-2xl mx-auto mb-10 font-sans">
            Bergabunglah dengan turnamen Navo Clash. Bangun deck epik, lawan pemain lain, dan panjat tangga peringkat. <strong>100% Free-to-Win.</strong>
        </p>

        <a href="{{ route('register') }}" class="bg-gradient-to-r from-cyan-500 to-fuchsia-600 hover:from-cyan-400 hover:to-fuchsia-500 text-white font-sans px-10 py-4 rounded font-black text-lg transition shadow-[0_0_30px_rgba(217,70,239,0.5)] hover:scale-105 transform duration-300">
            MULAI BERTARUNG
        </a>

        {{-- Preview Top Players (Blurred) --}}
        <div class="mt-24 relative w-full max-w-3xl mx-auto">
            <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-transparent to-transparent z-10"></div>
            
            <div class="grid grid-cols-3 gap-4 opacity-30 blur-[4px] pointer-events-none select-none font-sans">
                @foreach ($topPlayers as $p)
                    <div class="bg-[#111] border border-cyan-500/30 rounded-xl p-6 text-center">
                        <div class="text-4xl mb-2">👤</div>
                        <div class="font-bold text-white mb-1">{{ $p->user->name ?? 'Player' }}</div>
                        <div class="text-xs text-cyan-400">{{ $p->wins }} WINS</div>
                    </div>
                @endforeach
            </div>

            <div class="absolute inset-0 z-20 flex flex-col items-center justify-center">
                <div class="bg-[#111]/90 backdrop-blur-md border border-cyan-500/50 p-6 rounded-xl shadow-[0_0_20px_rgba(34,211,238,0.2)] font-sans">
                    <span class="text-4xl mb-2 block">🔒</span>
                    <h3 class="text-lg font-bold text-white mb-2 uppercase tracking-wide">Akses Terbatas</h3>
                    <p class="text-slate-400 text-sm mb-4">Login untuk melihat statistik peringkat global.</p>
                    <a href="{{ route('login') }}" class="bg-white text-black px-6 py-2 rounded font-bold transition hover:bg-gray-200">LOGIN SEKARANG</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>