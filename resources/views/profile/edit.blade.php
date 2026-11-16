@extends('layouts.profile')

@section('title', 'Edit Profil — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'image'          => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
        'type'           => '<path d="M4 7V4h16v3M9 20h6M12 4v16"/>',
        'file-text'      => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4M10 9H8M16 13H8M16 17H8"/>',
        'lock'           => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'save'           => '<path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7M7 3v4a1 1 0 0 0 1 1h7"/>',
        'alert'          => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
    ];
    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#profile-edit) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

    .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-d { --ec: #112E81; } .ex-k { --ec: #000000; }
    .extrude {
        box-shadow:
            1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
            5px 5px 0 var(--ec), 6px 6px 0 var(--ec), 7px 7px 0 var(--ec), 8px 8px 0 var(--ec),
            14px 20px 30px rgba(0,0,0,.5);
    }
    .extrude-sm {
        box-shadow:
            1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
            8px 12px 18px rgba(0,0,0,.45);
    }

    .btn-3d {
        --ec: #4382DF;
        transform: translate(var(--tx, 0px), var(--ty, 0px));
        box-shadow: 6px 6px 0 var(--ec), 6px 14px 24px rgba(0,0,0,.45);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-3d:hover {
        transform: translate(calc(var(--tx, 0px) + 3px), calc(var(--ty, 0px) + 3px));
        box-shadow: 3px 3px 0 var(--ec), 3px 8px 14px rgba(0,0,0,.4);
    }
    .btn-3d:active {
        transform: translate(calc(var(--tx, 0px) + 6px), calc(var(--ty, 0px) + 6px));
        box-shadow: 0 0 0 var(--ec);
    }

    .hov-pop {
        transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s;
    }
    .hov-pop:hover { scale: 1.04; rotate: -1deg; z-index: 5; }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
    }
    .reveal.in { opacity: 1; transform: none; }

    /* Brutal inputs */
    .input-brutal {
        background: #fff;
        color: #090E39;
        border: 2px solid #fff;
        border-radius: .9rem;
        padding: .8rem 1rem;
        font-weight: 500;
        transition: box-shadow .2s;
        outline: none;
        width: 100%;
    }
    .input-brutal::placeholder { color: rgba(17,46,129,.5); }
    .input-brutal:focus { box-shadow: 4px 4px 0 #4382DF; }

    /* Avatar picker */
    .avatar-pick {
        cursor: pointer;
        display: block;
        position: relative;
    }
    .avatar-pick input { position: absolute; opacity: 0; pointer-events: none; }
    .avatar-pick .avatar-inner {
        border-radius: 1rem;
        border: 2px solid rgba(255,255,255,.15);
        padding: 4px;
        background: rgba(255,255,255,.05);
        transition: border-color .2s, box-shadow .2s, transform .2s;
    }
    .avatar-pick:hover .avatar-inner {
        border-color: rgba(67,130,223,.6);
        transform: translateY(-2px);
    }
    .avatar-pick input:checked + .avatar-inner {
        border-color: #4382DF;
        box-shadow: 4px 4px 0 #4382DF;
        background: #fff;
    }
    .avatar-pick.is-locked {
        opacity: .55;
        cursor: not-allowed;
    }
    .avatar-pick.is-locked .avatar-inner {
        filter: grayscale(100%);
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="profile-edit" class="max-w-2xl mx-auto">

    {{-- ============ HEADER ============ --}}
    <div class="reveal flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
        <div>
            <span class="hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-xs font-extrabold uppercase tracking-widest extrude-sm ex-b mb-4">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4647AE]"></span>
                </span>
                Pengaturan
            </span>
            <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                Edit
                <span class="relative inline-block mx-1 my-1">
                    <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                    <span class="relative px-3">Profil</span>
                </span>
            </h1>
            <p class="text-white/70 mt-2 max-w-md">
                Perbarui avatar, nama, dan bio kamu. Data langsung tampil di profil publik.
            </p>
        </div>

        <a href="{{ route('profile.show') }}"
           data-cursor="Batal"
           class="btn-3d ex-k self-start sm:self-auto inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-2xl font-extrabold text-sm">
            {!! $icon('arrow-left', 'w-4 h-4') !!}
            Batal
        </a>
    </div>

    {{-- ============ FORM CARD ============ --}}
    <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-7">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- ============ AVATAR ============ --}}
            <div>
                <label class="inline-flex items-center gap-2 font-display font-extrabold text-base mb-3">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('image', 'w-4 h-4') !!}
                    </span>
                    Pilih Avatar
                </label>

                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                    @foreach ($allAvatars as $avatar)
                        @php $isUnlocked = in_array($avatar->id, $unlockedIds); @endphp

                        <label class="avatar-pick {{ !$isUnlocked ? 'is-locked' : '' }}">
                            <input type="radio" name="avatar_id" value="{{ $avatar->id }}"
                                   class="peer sr-only"
                                   {{ $user->avatar_id == $avatar->id ? 'checked' : '' }}
                                   {{ !$isUnlocked ? 'disabled' : '' }}>

                            <div class="avatar-inner relative">
                                <x-avatar :avatar="$avatar" :user="$user" size="w-full aspect-square" textSize="text-4xl" />

                                @if (!$isUnlocked)
                                    <div class="absolute inset-0 flex items-center justify-center bg-[#090E39]/70 rounded-xl text-white">
                                        {!! $icon('lock', 'w-5 h-5') !!}
                                    </div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                <p class="mt-3 inline-flex items-start gap-1.5 text-xs text-white/50 font-semibold">
                    {!! $icon('lock', 'w-3.5 h-3.5 text-[#4382DF] shrink-0 mt-0.5') !!}
                    <span>Avatar dengan simbol gembok harus di-unlock melalui pencapaian.</span>
                </p>
            </div>

            {{-- divider --}}
            <div class="pt-1 border-t-2 border-dashed border-white/10"></div>

            {{-- ============ NAMA ============ --}}
            <div>
                <label for="name" class="inline-flex items-center gap-2 font-display font-extrabold text-base mb-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('type', 'w-4 h-4') !!}
                    </span>
                    Nama Lengkap
                </label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}" required
                       class="input-brutal"
                       placeholder="Masukkan nama lengkap...">
                @error('name')
                    <p class="inline-flex items-center gap-1.5 text-sm text-red-300 mt-2 font-semibold bg-red-500/20 border border-red-400/40 rounded-lg px-3 py-1.5">
                        {!! $icon('alert', 'w-4 h-4') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ============ BIO ============ --}}
            <div>
                <label for="bio" class="inline-flex items-center gap-2 font-display font-extrabold text-base mb-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('file-text', 'w-4 h-4') !!}
                    </span>
                    Bio
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-white/40 ml-1">Maks 160 Karakter</span>
                </label>
                <textarea id="bio" name="bio" rows="3" maxlength="160"
                          class="input-brutal resize-none"
                          placeholder="Ceritakan sedikit tentang kamu...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="inline-flex items-center gap-1.5 text-sm text-red-300 mt-2 font-semibold bg-red-500/20 border border-red-400/40 rounded-lg px-3 py-1.5">
                        {!! $icon('alert', 'w-4 h-4') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ============ SUBMIT ============ --}}
            <div class="pt-4 border-t-2 border-dashed border-white/10 flex justify-end">
                <button type="submit" data-cursor="Simpan"
                        class="btn-3d ex-k inline-flex items-center gap-2 bg-white text-[#090E39] px-8 py-3 rounded-2xl font-extrabold text-sm border-2 border-white">
                    {!! $icon('save', 'w-4 h-4') !!}
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

{{-- ============ REVEAL ============ --}}
<script>
(function () {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const reveals = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window) || reduce) {
        reveals.forEach(el => el.classList.add('in'));
    } else {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(en => {
                if (en.isIntersecting) {
                    en.target.classList.add('in');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => io.observe(el));
    }
})();
</script>

@endsection