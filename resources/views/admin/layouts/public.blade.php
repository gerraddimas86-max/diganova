<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DIGANOVA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <div class="flex items-center gap-6">
                    <a href="{{ route('event.home') }}"
                       class="text-2xl font-bold bg-gradient-to-r from-nova-600 via-violet-500 to-crystal-400 bg-clip-text text-transparent">
                        DIGANOVA
                    </a>
                    <a href="{{ route('event.events.index') }}"
                       class="hidden sm:inline text-sm text-gray-700 hover:text-nova-600 font-medium">
                        Events
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('event.registrations.index') }}"
                           class="hidden sm:inline text-sm text-gray-700 hover:text-nova-600 font-medium">
                            Registrasi Saya
                        </a>
                        <a href="{{ route('event.groups.index') }}"
                           class="hidden sm:inline text-sm text-gray-700 hover:text-nova-600 font-medium">
                            Grup Saya
                        </a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.events.index') }}"
                               class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded font-semibold">
                                ADMIN
                            </a>
                        @endif
                        <span class="text-sm text-gray-700 hidden md:inline">
                            Hi, <strong>{{ auth()->user()->name }}</strong>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-nova-600">Login</a>
                        <a href="{{ route('register') }}"
                           class="bg-nova-600 hover:bg-nova-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-10 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-2xl font-bold">DIGANOVA</p>
            <p class="text-sm text-gray-400 mt-1 italic">"Plan It. Join It. Enjoy It."</p>
            <div class="mt-4 flex justify-center gap-4 text-xs text-gray-400">
                <a href="{{ route('event.events.index') }}" class="hover:text-white">Events</a>
                <span>·</span>
                <a href="{{ config('app.social_domain') }}" class="hover:text-white">Social</a>
                <span>·</span>
                <a href="{{ config('app.play_domain') }}" class="hover:text-white">Playground</a>
            </div>
            <p class="text-xs text-gray-500 mt-6">&copy; {{ date('Y') }} DIGANOVA. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>