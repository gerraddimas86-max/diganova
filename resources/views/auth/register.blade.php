<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>Daftar — DIGANOVA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0f172a] flex items-center justify-center p-4 sm:p-8 relative overflow-hidden">
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 -left-32 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-[900px] flex flex-col md:flex-row bg-white rounded-[32px] shadow-2xl overflow-hidden relative z-10 min-h-[600px]">
        <div class="hidden md:flex w-1/2 bg-[#f0f2f5] items-center justify-center p-10 relative">
            <img src="{{ asset('images/navo-mascot.png') }}" alt="Navo Mascot" class="w-[85%] max-w-[320px] object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
        </div>

        <div style="view-transition-name: auth-form;" class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center bg-white relative">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">CREATE ACCOUNT</h1>
            </div>

            <button type="button" class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-xl py-2.5 hover:bg-gray-50 transition mb-6">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                <span class="text-sm font-medium text-gray-600">Sign up with Google</span>
            </button>

            <div class="relative flex items-center justify-center mb-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <span class="relative bg-white px-3 text-xs text-gray-400">or</span>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf
                <div>
                    <input name="name" type="text" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder:text-gray-400"
                           placeholder="Full Name">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <input name="username" type="text" value="{{ old('username') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder:text-gray-400"
                           placeholder="Username (e.g. novafox123)">
                    @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <input name="email" type="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder:text-gray-400"
                           placeholder="E-mail Address">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ show: false }" class="relative">
                    <input name="password" :type="show ? 'text' : 'password'" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder:text-gray-400 pr-10"
                           placeholder="Password">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <input name="password_confirmation" type="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder:text-gray-400"
                           placeholder="Confirm Password">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-violet-600 hover:from-blue-700 hover:to-violet-700 text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-blue-500/30 mt-2">
                    Create Account
                </button>
            </form>

            <p class="text-center text-xs text-gray-500 mt-6">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Log in</a>
            </p>
        </div>
    </div>
</body>
</html>