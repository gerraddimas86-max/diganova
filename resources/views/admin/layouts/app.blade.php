<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — DIGANOVA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                {{-- Logo & Menu --}}
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.events.index') }}" class="text-xl font-bold bg-gradient-to-r from-nova-600 via-violet-500 to-crystal-400 bg-clip-text text-transparent">
                        DIGANOVA
                    </a>
                    <span class="hidden sm:inline text-xs bg-red-100 text-red-700 px-2 py-1 rounded font-semibold">
                        ADMIN PANEL
                    </span>

                    <div class="hidden md:flex items-center gap-1">
                        {{-- Events --}}
                        <a href="{{ route('admin.events.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.events.*') ? 'bg-nova-50 text-nova-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            Events
                        </a>

                        {{-- Registrations (dengan badge pending) --}}
                        @php
                            $pendingAdminCount = \App\Models\EventRegistration::where('payment_status', 'pending')->count();
                        @endphp
                        <a href="{{ route('admin.registrations.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition flex items-center gap-2 {{ request()->routeIs('admin.registrations.*') ? 'bg-nova-50 text-nova-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            Registrations
                            @if ($pendingAdminCount > 0)
                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-red-500 rounded-full">
                                    {{ $pendingAdminCount > 99 ? '99+' : $pendingAdminCount }}
                                </span>
                            @endif
                        </a>

                        {{-- Check-in --}}
                        <a href="{{ route('admin.checkin.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.checkin.*') ? 'bg-nova-50 text-nova-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            Check-in
                        </a>
                    </div>
                </div>

                {{-- User --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('event.home') }}" class="text-sm text-gray-500 hover:text-nova-600 hidden sm:inline">
                        ← Public Site
                    </a>
                    <span class="text-sm text-gray-700 hidden sm:inline">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-600">✕</button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

</body>
</html>