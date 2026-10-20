<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'Social — DIGANOVA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">
    @php
        $unreadDmCount = 0;
        if (auth()->check()) {
            $me = auth()->id();
            $unreadDmCount = \App\Models\DirectMessage::whereHas('conversation', function ($q) use ($me) {
                $q->where('user_one_id', $me)->orWhere('user_two_id', $me);
            })->where('sender_id', '!=', $me)->where('is_read', false)->count();
        }
    @endphp

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center gap-3">
                <div class="flex items-center gap-3 flex-shrink-0">
                    @auth
                        <a href="{{ route('hub') }}" class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-violet-600 hover:bg-gray-100 px-2.5 py-2 rounded-lg font-medium transition" title="Kembali ke Hub">
                            <span>🏠</span><span class="hidden md:inline">Hub</span>
                        </a>
                    @endauth
                    <a href="{{ route('social.home') }}" class="text-2xl font-bold bg-gradient-to-r from-violet-600 via-nova-500 to-crystal-400 bg-clip-text text-transparent">DIGANOVA</a>
                    
                    <div class="hidden md:flex items-center gap-1 ml-2">
                        <a href="{{ route('social.home') }}" class="px-3 py-1.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('social.home') ? 'bg-violet-50 text-violet-700' : 'text-gray-700 hover:bg-gray-100' }}">Beranda</a>
                        <a href="{{ route('social.posts.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('social.posts.*') ? 'bg-violet-50 text-violet-700' : 'text-gray-700 hover:bg-gray-100' }}">Feed</a>
                        <a href="{{ route('social.trending') }}" class="px-3 py-1.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('social.trending') ? 'bg-violet-50 text-violet-700' : 'text-gray-700 hover:bg-gray-100' }}">Trending</a>
                        <a href="{{ route('social.groups.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('social.groups.*') ? 'bg-violet-50 text-violet-700' : 'text-gray-700 hover:bg-gray-100' }}">Grup</a>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @auth
                        <a href="{{ route('social.messages.index') }}" class="relative p-2 rounded-full hover:bg-gray-100 transition">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @if ($unreadDmCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadDmCount > 9 ? '9+' : $unreadDmCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button></form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-violet-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-violet-600 hover:bg-violet-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-5xl mx-auto px-4 py-6">@yield('content')</main>
</body>
</html>