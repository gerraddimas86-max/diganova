<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>Daftar — DIGANOVA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;600;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $icons = [
            'eye'            => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
            'eye-off'        => '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><path d="m2 2 20 20"/>',
            'mail'           => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
            'lock'           => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'at-sign'        => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>',
            'arrow-right'    => '<path d="M5 12h14M12 5l7 7-7 7"/>',
            'user-plus'      => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/>',
            'shield-check'   => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
            'alert-circle'   => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>',
            'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
        ];
        $icon = fn (string $name, string $class = 'w-6 h-6') =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

        $logo = fn (string $class = 'w-10 h-10') =>
            '<img src="' . asset('images/logo.png') . '" alt="Logo DIGANOVA" class="' . $class . ' object-contain" draggable="false">';
    @endphp

    <style>
        :root {
            --c-white: #FFFFFF; --c-indigo: #4647AE; --c-blue: #4382DF;
            --c-deep: #112E81; --c-navy: #090E39; --c-black: #000000;
        }
        body { font-family: 'Space Grotesk', system-ui, sans-serif; }
        .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

        .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-k { --ec: #000000; }

        .btn-3d {
            --ec: #4382DF;
            transform: translate(var(--tx, 0px), var(--ty, 0px));
            box-shadow: 5px 5px 0 var(--ec), 5px 12px 20px rgba(0,0,0,.4);
            transition: transform .15s ease, box-shadow .15s ease, background .2s, color .2s;
        }
        .btn-3d:hover {
            transform: translate(calc(var(--tx, 0px) + 3px), calc(var(--ty, 0px) + 3px));
            box-shadow: 2px 2px 0 var(--ec), 2px 6px 12px rgba(0,0,0,.35);
        }
        .btn-3d:active {
            transform: translate(calc(var(--tx, 0px) + 5px), calc(var(--ty, 0px) + 5px));
            box-shadow: 0 0 0 var(--ec);
        }

        .bg-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: rgba(9,14,57,.4); }
        ::-webkit-scrollbar-thumb { background: rgba(67,130,223,.6); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(67,130,223,.9); }

        /* ===== INPUT ===== */
        .input-brutal {
            background: #F5F7FF;
            color: #090E39;
            border: 2px solid rgba(17,46,129,.15);
            border-radius: .9rem;
            padding: 0 1rem 0 3rem;
            min-height: 3.25rem;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: .9375rem;
            line-height: 1.2;
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
            width: 100%;
        }
        .input-brutal::placeholder {
            color: rgba(17,46,129,.4);
            font-weight: 500;
            opacity: 1;
        }
        .input-brutal:hover { border-color: rgba(17,46,129,.3); }
        .input-brutal:focus {
            background: #fff;
            border-color: #4382DF;
            box-shadow: 4px 4px 0 #4382DF;
        }
        .input-brutal:focus-visible { outline: 2px solid #4382DF; outline-offset: 2px; }

        .input-brutal.has-toggle { padding-right: 3rem; }

        .input-brutal.is-error { border-color: #EF4444; }
        .input-brutal.is-error:focus { box-shadow: 4px 4px 0 #EF4444; }

        .input-brutal:-webkit-autofill,
        .input-brutal:-webkit-autofill:hover,
        .input-brutal:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #F5F7FF inset;
            -webkit-text-fill-color: #090E39;
            transition: background-color 9999s ease-in-out 0s;
        }
        .input-brutal:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #fff inset, 4px 4px 0 #4382DF;
        }

        /* ===== LABEL ===== */
        .input-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(17,46,129,.7);
            margin-bottom: .45rem;
        }

        /* ===== ICON ===== */
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #4382DF;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.25rem;
            height: 1.25rem;
            z-index: 1;
        }
        .input-icon svg { width: 100%; height: 100%; }

        /* Card entrance */
        @keyframes enter-card {
            from { opacity: 0; transform: translateY(20px) scale(.97); }
            to   { opacity: 1; transform: none; }
        }
        .auth-card { animation: enter-card .7s cubic-bezier(.2,.8,.2,1) both; }

        /* View Transitions */
        ::view-transition-old(auth-card),
        ::view-transition-new(auth-card) {
            animation-duration: .55s;
            animation-timing-function: cubic-bezier(.3,1.2,.4,1);
            mix-blend-mode: normal;
        }
        ::view-transition-old(mascot),
        ::view-transition-new(mascot) {
            animation-duration: .5s;
            animation-timing-function: cubic-bezier(.3,1.2,.4,1);
        }
        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation-duration: .3s;
            animation-timing-function: ease;
        }

        body.is-leaving {
            opacity: 0;
            transform: scale(.98);
            transition: opacity .22s ease, transform .22s ease;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
            .auth-card { animation: none; }
            ::view-transition-old(root), ::view-transition-new(root) { animation-duration: 0s; }
        }

        /* Google button spinner */
        .g-spinner {
            width: 1rem; height: 1rem;
            border: 2px solid rgba(17,46,129,.2);
            border-top-color: #4382DF;
            border-radius: 9999px;
            animation: spin .7s linear infinite;
            display: none;
        }
        .is-loading .g-spinner { display: inline-block; }
        .is-loading .g-icon { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="min-h-screen bg-[#090E39] text-white relative overflow-x-hidden selection:bg-[#4382DF] selection:text-white">

    {{-- Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-grid opacity-50"></div>
        <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px]"></div>
    </div>

    {{-- Wrapper --}}
    <div class="relative z-10 min-h-screen flex flex-col p-4 sm:p-6 md:p-8">

        {{-- AUTH CARD --}}
        <div class="auth-card my-auto w-full max-w-[920px] mx-auto flex flex-col md:flex-row rounded-[1.5rem] md:rounded-[2rem] border-2 border-white bg-[#112E81] overflow-hidden md:min-h-[580px]"
             style="view-transition-name: auth-card; box-shadow: 4px 4px 0 #4382DF, 16px 24px 40px rgba(0,0,0,.55);">

            {{-- LEFT: MASCOT --}}
            <div class="hidden md:flex md:w-1/2 relative overflow-hidden bg-gradient-to-br from-[#4647AE] via-[#4382DF] to-[#112E81] items-center justify-center p-8"
                 style="view-transition-name: mascot;">

                <div class="absolute top-8 right-8 w-40 h-40 rounded-full border-[20px] border-white/10 pointer-events-none"></div>
                <div class="absolute bottom-12 left-8 w-28 h-28 rounded-full border-[14px] border-white/10 pointer-events-none"></div>

                <div class="relative text-center">
                    <img src="{{ asset('images/navo-mascot.png') }}"
                         alt="Navo Mascot"
                         class="w-[85%] max-w-[280px] mx-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">

                    <div class="mt-5 inline-flex items-center gap-2 bg-white text-[#090E39] px-4 py-2 rounded-full border-2 border-[#090E39] shadow-[3px_3px_0_#090E39]">
                        {!! $logo('w-5 h-5') !!}
                        <span class="font-display font-extrabold tracking-tight">DIGANOVA</span>
                    </div>
                    <p class="mt-2 text-xs text-white/70 italic font-semibold">"Plan It. Join It. Enjoy It."</p>
                </div>
            </div>

            {{-- RIGHT: FORM --}}
            <div class="w-full md:w-1/2 bg-white text-[#090E39] p-5 sm:p-7 md:p-9 flex flex-col justify-center relative">

                {{-- Mobile brand header --}}
                <div class="md:hidden flex items-center justify-center gap-2 mb-4">
                    <span class="shrink-0">{!! $logo('w-10 h-10') !!}</span>
                    <span class="font-display font-extrabold text-xl tracking-tight">DIGANOVA</span>
                </div>

                {{-- Header --}}
                <div class="text-center mb-5">
                    <div class="inline-flex items-center gap-1.5 bg-[#4382DF] text-[#090E39] px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border-2 border-[#090E39] shadow-[2px_2px_0_#090E39] mb-2.5">
                        {!! $icon('user-plus', 'w-3 h-3') !!}
                        Sign Up
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight">
                        Create account
                    </h1>
                    <p class="text-xs sm:text-sm text-[#112E81]/60 mt-1.5 font-semibold">
                        Join and start your adventure
                    </p>
                </div>

                {{-- OAuth / global error banner --}}
                @if (session('error'))
                    <div class="mb-3 flex items-start gap-2 bg-red-50 border-2 border-red-200 rounded-xl px-3 py-2.5">
                        <span class="text-red-600 shrink-0 mt-0.5">{!! $icon('alert-circle', 'w-4 h-4') !!}</span>
                        <p class="text-xs text-red-700 font-bold leading-relaxed">{{ session('error') }}</p>
                    </div>
                @endif

                {{-- Google — diubah menjadi link ke route auth.google --}}
                <a href="{{ route('auth.google') }}"
                   data-cursor="Google"
                   aria-label="Sign up with Google"
                   id="google-btn"
                   class="w-full flex items-center justify-center gap-2 bg-white border-2 border-[#112E81]/20 rounded-xl py-2.5 hover:border-[#112E81]/40 hover:-translate-y-0.5 transition-all mb-3">
                    <span class="g-spinner" aria-hidden="true"></span>
                    <svg class="g-icon w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="text-sm font-extrabold text-[#112E81]/80">Sign up with Google</span>
                </a>

                {{-- Divider --}}
                <div class="relative flex items-center justify-center mb-3">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t-2 border-dashed border-[#112E81]/15"></div></div>
                    <span class="relative bg-white px-3 text-[10px] uppercase tracking-widest font-extrabold text-[#112E81]/40">or</span>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- NAME --}}
                    <div>
                        <label for="name" class="input-label">Full Name</label>
                        <div class="relative">
                            <span class="input-icon">
                                {!! $icon('user', 'w-5 h-5') !!}
                            </span>
                            <input id="name" name="name" type="text"
                                   value="{{ old('name') }}" required autofocus
                                   placeholder="Nama lengkap kamu"
                                   autocomplete="name"
                                   class="input-brutal @error('name') is-error @enderror">
                        </div>
                        @error('name')
                            <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                                {!! $icon('alert-circle', 'w-3.5 h-3.5 shrink-0') !!}
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- USERNAME --}}
                    <div>
                        <label for="username" class="input-label">Username</label>
                        <div class="relative">
                            <span class="input-icon">
                                {!! $icon('at-sign', 'w-5 h-5') !!}
                            </span>
                            <input id="username" name="username" type="text"
                                   value="{{ old('username') }}" required
                                   placeholder="novafox123"
                                   autocomplete="username"
                                   class="input-brutal @error('username') is-error @enderror">
                        </div>
                        @error('username')
                            <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                                {!! $icon('alert-circle', 'w-3.5 h-3.5 shrink-0') !!}
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label for="email" class="input-label">Email Address</label>
                        <div class="relative">
                            <span class="input-icon">
                                {!! $icon('mail', 'w-5 h-5') !!}
                            </span>
                            <input id="email" name="email" type="email"
                                   value="{{ old('email') }}" required
                                   placeholder="nama@email.com"
                                   autocomplete="email"
                                   class="input-brutal @error('email') is-error @enderror">
                        </div>
                        @error('email')
                            <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                                {!! $icon('alert-circle', 'w-3.5 h-3.5 shrink-0') !!}
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label for="password" class="input-label">Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="input-icon">
                                {!! $icon('lock', 'w-5 h-5') !!}
                            </span>
                            <input id="password" name="password"
                                   :type="show ? 'text' : 'password'" required
                                   placeholder="Minimal 8 karakter"
                                   autocomplete="new-password"
                                   class="input-brutal has-toggle @error('password') is-error @enderror">
                            <button type="button" @click="show = !show"
                                    :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'"
                                    class="absolute right-1.5 top-1/2 -translate-y-1/2 w-9 h-9 grid place-items-center rounded-lg text-[#112E81]/60 hover:text-[#4382DF] hover:bg-[#4382DF]/10 transition">
                                <span x-show="!show" x-cloak="false">{!! $icon('eye', 'w-5 h-5') !!}</span>
                                <span x-show="show" x-cloak>{!! $icon('eye-off', 'w-5 h-5') !!}</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                                {!! $icon('alert-circle', 'w-3.5 h-3.5 shrink-0') !!}
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- CONFIRM --}}
                    <div>
                        <label for="password_confirmation" class="input-label">Confirm Password</label>
                        <div class="relative">
                            <span class="input-icon">
                                {!! $icon('shield-check', 'w-5 h-5') !!}
                            </span>
                            <input id="password_confirmation" name="password_confirmation"
                                   type="password" required
                                   placeholder="Ulangi password"
                                   autocomplete="new-password"
                                   class="input-brutal">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" data-cursor="Daftar"
                            onclick="this.disabled=true;this.form.submit();"
                            class="btn-3d ex-k w-full inline-flex items-center justify-center gap-2 bg-[#4647AE] text-white font-extrabold py-3.5 rounded-2xl border-2 border-[#4647AE] hover:bg-[#4382DF] hover:border-[#4382DF] transition mt-2 disabled:opacity-70">
                        {!! $icon('user-plus', 'w-4 h-4') !!}
                        Create Account
                    </button>
                </form>

                {{-- Switch to login --}}
                <p class="text-center text-xs sm:text-sm text-[#112E81]/70 mt-5 font-bold">
                    Already have an account?
                    <a href="{{ route('login') }}"
                       data-transition-link
                       data-cursor="Login"
                       class="inline-flex items-center gap-1 text-[#4382DF] font-extrabold hover:gap-2 transition-all underline underline-offset-4 decoration-2">
                        Log in
                        {!! $icon('arrow-right', 'w-3.5 h-3.5') !!}
                    </a>
                </p>
            </div>
        </div>
    </div>

    <style>[x-cloak]{display:none!important}</style>

    {{-- Fallback transition JS --}}
    <script>
    (function () {
        const supportsVT = 'startViewTransition' in document;

        document.querySelectorAll('[data-transition-link]').forEach(link => {
            link.addEventListener('click', function (e) {
                if (supportsVT) return;
                e.preventDefault();
                document.body.classList.add('is-leaving');
                setTimeout(() => { window.location.href = link.href; }, 200);
            });
        });

        // Spinner saat user klik Google
        const gBtn = document.getElementById('google-btn');
        if (gBtn) {
            gBtn.addEventListener('click', function () {
                gBtn.classList.add('is-loading', 'pointer-events-none', 'opacity-70');
            });
        }
    })();
    </script>
</body>
</html>