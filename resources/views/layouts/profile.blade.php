<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'Profil — DIGANOVA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('hub') }}" class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-nova-600 hover:bg-gray-100 px-3 py-2 rounded-lg font-medium transition">
                        <span>🏠</span> <span class="hidden sm:inline">Hub</span>
                    </a>
                    <span class="text-xl font-bold bg-gradient-to-r from-nova-600 via-violet-500 to-crystal-400 bg-clip-text text-transparent">Profil Terpadu</span>
                </div>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button></form>
                </div>
            </div>
        </div>
    </nav>
    @if (session('success'))
        <div class="max-w-5xl mx-auto px-4 mt-4 w-full"><div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div></div>
    @endif
    <main class="max-w-5xl mx-auto px-4 py-8 flex-1 w-full">
        @yield('content')
    </main>
</body>
</html>