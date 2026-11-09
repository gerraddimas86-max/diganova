<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — DIGANOVA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Animasi Glow di Background */
        @keyframes float-glow {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.4; }
            50% { transform: translateY(-20px) scale(1.05); opacity: 0.6; }
        }
        .animate-glow { animation: float-glow 10s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-[#000000] text-[#FFFFFF] font-sans antialiased relative selection:bg-[#4382DF]/40 selection:text-white">

    {{-- Background Ambient Glows --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-0 left-1/4 w-[40rem] h-[40rem] bg-[#4382DF]/10 rounded-full blur-[120px] animate-glow"></div>
        <div class="absolute bottom-0 right-1/4 w-[40rem] h-[40rem] bg-[#4647AE]/15 rounded-full blur-[120px] animate-glow" style="animation-delay: 2s;"></div>
        {{-- Grid Pattern Halus --}}
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: linear-gradient(#4382DF 1px, transparent 1px), linear-gradient(90deg, #4382DF 1px, transparent 1px); background-size: 60px 60px;"></div>
    </div>

    {{-- Navbar --}}
    <nav class="bg-[#090E39]/80 backdrop-blur-xl border-b border-[#4647AE]/30 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-tr from-[#112E81] to-[#4382DF] rounded-xl flex items-center justify-center shadow-lg shadow-[#4382DF]/30 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-2xl font-black bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent tracking-tight">
                        DIGANOVA
                    </span>
                </a>
                
                {{-- User Info & Logout --}}
                <div class="flex items-center gap-4 sm:gap-6">
                    <span class="text-sm text-slate-300 hidden sm:flex items-center gap-2">
                        Hi, <strong class="text-white">{{ auth()->user()->name }}</strong>
                        {{-- Verified Icon --}}
                        <svg class="w-4 h-4 text-[#4382DF]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </span>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex items-center gap-2 text-sm text-red-400 hover:text-red-300 bg-red-400/10 hover:bg-red-400/20 border border-red-400/20 px-4 py-2 rounded-full font-medium transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-6 relative z-10">
            <div class="bg-[#112E81]/40 border border-[#4382DF]/50 text-white px-5 py-4 rounded-2xl text-sm flex items-center gap-3 backdrop-blur-md shadow-lg shadow-[#4382DF]/10">
                <div class="w-8 h-8 rounded-full bg-[#4382DF]/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#4382DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 py-12 relative z-10">
        <div class="bg-[#090E39]/80 backdrop-blur-xl border border-[#4647AE]/40 rounded-3xl p-8 sm:p-12 relative overflow-hidden shadow-2xl">
            {{-- Efek Glow di Dalam Card --}}
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#4382DF]/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-[#4647AE]/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-full text-xs font-semibold text-[#4382DF] mb-4">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4382DF]"></span>
                        </span>
                        Pusat Kendali
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-3 tracking-tight flex items-center gap-4 flex-wrap">
                        Selamat datang, {{ auth()->user()->name }}!
                        {{-- Premium Icon menggantikan 🦊 --}}
                        <div class="w-12 h-12 bg-gradient-to-br from-[#4647AE] to-[#4382DF] rounded-full border-2 border-white/20 flex items-center justify-center shadow-lg shadow-[#4382DF]/40 animate-pulse">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                            </svg>
                        </div>
                    </h1>
                    <p class="text-slate-400 text-lg max-w-xl leading-relaxed">
                        Ini adalah dashboard sementara. Silakan jelajahi modul <strong class="text-white">Event</strong>, <strong class="text-white">Social</strong>, dan <strong class="text-white">Playground</strong> yang akan segera hadir.
                    </p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>