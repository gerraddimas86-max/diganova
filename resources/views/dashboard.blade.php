<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — DIGANOVA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="text-2xl font-bold bg-gradient-to-r from-nova-600 via-violet-500 to-crystal-400 bg-clip-text text-transparent">
                    DIGANOVA
                </a>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-700 hidden sm:inline">
                        Hi, <strong>{{ auth()->user()->name }}</strong>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 py-10">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                Selamat datang, {{ auth()->user()->name }}! 🦊
            </h1>
            <p class="text-gray-600">Ini dashboard sementara. Nanti diisi modul Event, Social, Playground.</p>
        </div>
    </main>

</body>
</html>