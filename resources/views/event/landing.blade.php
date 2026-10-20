<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIGANOVA — Event</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-nova-900 via-violet-700 to-crystal-600 flex items-center justify-center text-white">
    <div class="text-center px-4 py-12">
        <h1 class="text-5xl md:text-7xl font-bold mb-4 tracking-tight">DIGANOVA</h1>
        <p class="text-lg md:text-2xl italic opacity-90">"Plan It. Join It. Enjoy It."</p>
        <p class="mt-3 text-sm md:text-base opacity-75 max-w-xl mx-auto">
            Satu akun, tiga pengalaman — Event, Social, dan Playground.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center flex-wrap">

            {{-- Selalu tampil: Lihat Events --}}
            <a href="{{ route('event.events.index') }}"
               class="bg-nova-600 text-white font-semibold px-8 py-3 rounded-lg hover:bg-nova-700 transition shadow-lg">
                🎉 Lihat Events
            </a>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.events.index') }}"
                       class="bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                        ⚙️ Admin Panel
                    </a>
                @endif
                <a href="{{ route('dashboard') }}"
                   class="bg-white text-nova-700 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="bg-white text-nova-700 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="bg-crystal-500 hover:bg-crystal-600 text-white font-semibold px-8 py-3 rounded-lg transition">
                    Daftar
                </a>
            @endauth
        </div>

        <p class="mt-16 text-sm text-crystal-300 opacity-80">🌐 Event Domain — diganova.test</p>
    </div>
</body>
</html>