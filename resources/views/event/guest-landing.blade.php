<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin">
    <title>DIGANOVA Events — Plan It. Join It. Enjoy It.</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0a0e1a] text-white overflow-x-hidden selection:bg-blue-500/30">

    {{-- Background Glow --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -left-40 w-[40rem] h-[40rem] bg-blue-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute top-1/3 -right-40 w-[40rem] h-[40rem] bg-violet-600/20 rounded-full blur-[100px]"></div>
    </div>

    {{-- Navbar Guest --}}
    <nav class="relative z-50 bg-white/5 backdrop-blur-xl border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🦊</span>
                    <span class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-violet-500 bg-clip-text text-transparent">DIGANOVA</span>
                    <span class="hidden sm:inline text-xs bg-white/10 px-2 py-1 rounded-full border border-white/10">EVENT</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-slate-900 hover:bg-gray-100 px-6 py-2.5 rounded-full font-bold transition shadow-lg shadow-white/10">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-300 text-sm mb-6 animate-pulse">
            <span class="w-2 h-2 rounded-full bg-blue-400"></span> Platform Event Masa Depan
        </div>
        
        <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tight leading-tight">
            Temukan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-400 to-violet-500">Festival Impianmu</span><br>
            di DIGANOVA.
        </h1>
        
        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10">
            Satu platform untuk semua event musik, cosplay, seni, dan turnamen game. <br class="hidden md:inline"> Gabung komunitas, dapatkan e-ticket, dan raih pencapaianmu.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 text-white px-8 py-4 rounded-xl font-bold text-lg transition shadow-xl shadow-blue-500/30 flex items-center justify-center gap-2 group">
                Mulai Sekarang <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
            <a href="{{ route('login') }}" class="w-full sm:w-auto bg-white/5 hover:bg-white/10 border border-white/10 text-white px-8 py-4 rounded-xl font-bold text-lg transition flex items-center justify-center gap-2">
                Masuk ke Akun
            </a>
        </div>

        {{-- Preview Cards (Blurred) --}}
        <div class="mt-24 relative max-w-4xl mx-auto">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e1a] via-transparent to-transparent z-10"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-40 blur-sm pointer-events-none select-none">
                {{-- Card 1 --}}
                <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 text-left">
                    <div class="h-40 bg-slate-700 rounded-xl mb-4"></div>
                    <div class="h-6 bg-slate-600 rounded w-3/4 mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded w-1/2"></div>
                </div>
                {{-- Card 2 --}}
                <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 text-left">
                    <div class="h-40 bg-slate-700 rounded-xl mb-4"></div>
                    <div class="h-6 bg-slate-600 rounded w-3/4 mb-2"></div>
                    <div class="h-4 bg-slate-700 rounded w-1/2"></div>
                </div>
            </div>

            <div class="absolute inset-0 z-20 flex flex-col items-center justify-center">
                <div class="bg-slate-900/80 backdrop-blur-md border border-slate-700 p-6 rounded-2xl shadow-2xl">
                    <span class="text-4xl mb-3 block">🔒</span>
                    <h3 class="text-xl font-bold mb-2">Eksklusif untuk Member</h3>
                    <p class="text-slate-400 text-sm mb-4">Login untuk melihat detail event & tiket.</p>
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg font-medium transition">Buka Akses</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>