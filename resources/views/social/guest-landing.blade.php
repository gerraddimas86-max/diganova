<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin">
    <title>DIGANOVA Social — Connect & Share</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0a0a0f] text-white overflow-x-hidden selection:bg-violet-500/30">

    {{-- Background Glow --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -left-40 w-[40rem] h-[40rem] bg-violet-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 -right-40 w-[40rem] h-[40rem] bg-fuchsia-600/20 rounded-full blur-[100px]"></div>
    </div>

    {{-- Navbar Guest --}}
    <nav class="relative z-50 bg-white/5 backdrop-blur-xl border-b border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🦊</span>
                    <span class="text-2xl font-bold bg-gradient-to-r from-violet-400 to-fuchsia-500 bg-clip-text text-transparent">DIGANOVA</span>
                    <span class="hidden sm:inline text-xs bg-white/10 px-2 py-1 rounded-full border border-white/10">SOCIAL</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-slate-900 hover:bg-gray-100 px-6 py-2.5 rounded-full font-bold transition shadow-lg shadow-white/10">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <main class="relative z-10 max-w-4xl mx-auto px-4 pt-24 pb-32 text-center">
        <h1 class="text-5xl md:text-6xl font-black mb-6 tracking-tight leading-tight">
            Bagikan Momen,<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">Temukan Komunitas.</span>
        </h1>
        
        <p class="text-lg text-slate-400 max-w-xl mx-auto mb-10">
            Tempat berkumpulnya para kreator, gamer, dan penikmat festival. Nyatakan pendapatmu, ikuti tren, dan terhubung dengan teman baru.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500 text-white px-8 py-4 rounded-xl font-bold text-lg transition shadow-xl shadow-violet-500/30">
                Gabung Sekarang
            </a>
        </div>

        {{-- Mockup Feed Preview --}}
        <div class="mt-20 relative max-w-lg mx-auto">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0f] via-transparent to-transparent z-10"></div>
            
            <div class="space-y-4 opacity-40 blur-[3px] pointer-events-none select-none">
                {{-- Fake Post 1 --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 text-left flex gap-4">
                    <div class="w-12 h-12 rounded-full bg-slate-700 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="h-4 bg-slate-700 rounded w-1/3 mb-3"></div>
                        <div class="h-3 bg-slate-800 rounded w-full mb-2"></div>
                        <div class="h-3 bg-slate-800 rounded w-5/6"></div>
                    </div>
                </div>
                {{-- Fake Post 2 --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 text-left flex gap-4">
                    <div class="w-12 h-12 rounded-full bg-slate-700 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="h-4 bg-slate-700 rounded w-1/4 mb-3"></div>
                        <div class="h-3 bg-slate-800 rounded w-full mb-2"></div>
                    </div>
                </div>
            </div>

            <div class="absolute inset-0 z-20 flex flex-col items-center justify-center">
                <div class="bg-slate-900/90 backdrop-blur-md border border-slate-700 p-6 rounded-2xl shadow-2xl">
                    <span class="text-4xl mb-3 block">💬</span>
                    <h3 class="text-xl font-bold mb-2">Penasaran obrolannya?</h3>
                    <p class="text-slate-400 text-sm mb-4">Login untuk membaca feed & ikut berdiskusi.</p>
                    <a href="{{ route('login') }}" class="bg-violet-600 hover:bg-violet-500 text-white px-6 py-2 rounded-lg font-medium transition">Buka Akses</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>