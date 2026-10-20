<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'DIGANOVA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('hub') }}" class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-nova-600 hover:bg-gray-100 px-3 py-2 rounded-lg font-medium transition" title="Kembali ke Hub">
                            <span>🏠</span><span class="hidden sm:inline">Hub</span>
                        </a>
                    @endauth
                    <a href="{{ route('event.home') }}" class="text-2xl font-bold bg-gradient-to-r from-nova-600 via-violet-500 to-crystal-400 bg-clip-text text-transparent">DIGANOVA</a>
                    <span class="hidden sm:inline text-xs bg-nova-100 text-nova-700 px-2 py-1 rounded font-semibold">EVENT</span>
                    <a href="{{ route('event.events.index') }}" class="hidden sm:inline text-sm text-gray-700 hover:text-nova-600 font-medium">Events</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('event.registrations.index') }}" class="hidden sm:inline text-sm text-gray-700 hover:text-nova-600 font-medium">Registrasi</a>
                        <a href="{{ route('event.groups.index') }}" class="hidden sm:inline text-sm text-gray-700 hover:text-nova-600 font-medium">Grup</a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.events.index') }}" class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded font-semibold">ADMIN</a>
                        @endif
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-nova-500 to-violet-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:inline text-sm text-gray-700">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button></form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-nova-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-nova-600 hover:bg-nova-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full"><div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div></div>
    @endif
    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full"><div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div></div>
    @endif

    <main class="flex-1">@yield('content')</main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xl font-bold">DIGANOVA</p>
            <p class="text-sm text-gray-400 mt-1 italic">"Plan It. Join It. Enjoy It."</p>
        </div>
    </footer>
</body>
</html>