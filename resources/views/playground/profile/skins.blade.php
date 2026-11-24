@extends('playground.layouts.app')

@section('title', 'Skin Navo — DIGANOVA Playground')

@section('content')

<div class="sk-page">

    <div class="sk-back-wrap">
        <a href="{{ route('play.profile.show') }}" class="sk-back">
            ← Kembali ke Profil
        </a>
    </div>

    <div class="sk-head">
        <h1 class="sk-title">🎨 Pilih Skin Navo</h1>
        <p class="sk-sub">
            Semua skin gratis! Free-to-win — tidak ada pay-to-win.
        </p>
    </div>

    <form method="POST" action="{{ route('play.profile.skins.update') }}"
          x-data="{ selectedId: {{ $profile->equipped_skin_id ?? 'null' }} }">
        @csrf

        <div class="sk-grid">
            @foreach ($skins as $skin)
                @php
                    $rarityClass = match($skin->rarity) {
                        'common' => 'r-common',
                        'rare' => 'r-rare',
                        'epic' => 'r-epic',
                        'legendary' => 'r-legendary',
                        default => 'r-common',
                    };
                    $isEquipped = $profile->equipped_skin_id === $skin->id;
                @endphp

                <label class="sk-label">
                    <input type="radio" name="skin_id" value="{{ $skin->id }}"
                           x-model.number="selectedId"
                           class="sk-radio"
                           @checked($isEquipped)>

                    <div class="sk-card {{ $rarityClass }} {{ $isEquipped ? 'is-equipped' : '' }}">

                        {{-- Equipped badge --}}
                        @if ($isEquipped)
                            <div class="sk-active">AKTIF</div>
                        @endif

                        {{-- Rarity badge --}}
                        <div class="sk-rarity">{{ $skin->rarity }}</div>

                        <div class="sk-art">
                            <x-navo :skin="$skin" size="w-24 h-24" textSize="text-5xl" />
                        </div>

                        <p class="sk-name">{{ $skin->name }}</p>
                        @if ($skin->is_default)
                            <p class="sk-default">DEFAULT</p>
                        @endif

                        <p class="sk-desc">
                            {{ $skin->description }}
                        </p>

                        {{-- Selected check --}}
                        <div x-show="selectedId === {{ $skin->id }}"
                             x-cloak
                             class="sk-selected">
                            ✓ Dipilih
                        </div>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="sk-actions">
            <a href="{{ route('play.profile.show') }}" class="sk-btn sk-btn-ghost">
                Batal
            </a>
            <button type="submit" class="sk-btn sk-btn-primary">
                Pakai Skin Ini
            </button>
        </div>
    </form>

</div>

<style>
[x-cloak] { display: none !important; }

/* =========================================================
   TOKENS — mengikuti landing page & layout Playground
   ========================================================= */
.sk-page {
    --ct:   var(--accent-text);
    --ln:   var(--line);
    --ln2:  var(--line-strong);
    --gold: #FFD76A;
    --epic: #8A8CFF;
    color: var(--text);
    font-family: 'Anta', system-ui, sans-serif;
    font-weight: 400;
}
[data-theme="light"] .sk-page { --gold: #B7791F; --epic: #6D5BD0; }
.sk-page, .sk-page * { box-sizing: border-box; }

/* Back link */
.sk-back-wrap { margin-bottom: 1rem; }
.sk-back {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .55rem .9rem; border: 1px solid var(--ln2); border-radius: 2px;
    color: var(--muted); font-size: .66rem; letter-spacing: .14em; text-transform: uppercase; text-decoration: none;
    transition: transform .18s, border-color .18s, color .18s;
}
[data-theme="light"] .sk-back { background: #FFFFFF; }
.sk-back:hover { transform: translateY(-2px); border-color: var(--cyan); color: var(--ct); }

/* Heading */
.sk-head { margin-bottom: 1.5rem; }
.sk-title {
    margin: 0; font-family: 'Iceberg', serif; font-weight: 400; -webkit-text-stroke: .4px currentColor;
    font-size: clamp(1.9rem, 4vw, 2.7rem); line-height: 1.05; letter-spacing: -.015em; color: var(--text);
}
.sk-sub { margin: .7rem 0 0; font-size: .95rem; line-height: 1.75; color: var(--muted); }

/* Grid */
.sk-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
@media (min-width: 768px)  { .sk-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
@media (min-width: 1024px) { .sk-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); } }

.sk-label { display: block; cursor: pointer; }
.sk-radio { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }

/* Card */
.sk-card {
    --rc: var(--muted);
    position: relative; height: 100%; padding: 1.1rem 1rem 1rem; overflow: hidden;
    background: var(--panel); border: 1px solid var(--ln); border-radius: 6px; box-shadow: var(--shadow);
    transition: transform .25s cubic-bezier(.22, 1, .36, 1), border-color .2s, box-shadow .25s, background-color .2s;
}
.sk-card::before {
    content: ''; position: absolute; top: 0; left: 1rem; right: 1rem; height: 2px;
    background: linear-gradient(90deg, transparent, var(--rc), transparent); opacity: .8;
}
.sk-card:hover { transform: translateY(-3px); border-color: var(--rc); }
.sk-radio:focus-visible + .sk-card { outline: 2px solid var(--cyan); outline-offset: 3px; }
.sk-radio:checked + .sk-card {
    border-color: var(--cyan);
    background: radial-gradient(ellipse at 50% 0%, var(--glow), transparent 70%), var(--panel);
    box-shadow: 0 0 0 1px var(--cyan), 0 18px 40px -22px var(--cyan);
}
.sk-card.is-equipped { border-color: var(--cyan); }

.r-common    { --rc: var(--muted); }
.r-rare      { --rc: var(--ct); }
.r-epic      { --rc: var(--epic); }
.r-legendary { --rc: var(--gold); }

/* Badges */
.sk-rarity {
    position: absolute; top: .6rem; left: .6rem; z-index: 2;
    display: inline-flex; align-items: center; gap: .4rem; padding: .25rem .6rem;
    border: 1px solid color-mix(in srgb, var(--rc) 45%, transparent); border-radius: 999px;
    background: color-mix(in srgb, var(--rc) 12%, transparent);
    color: var(--rc); font-size: .55rem; letter-spacing: .16em; text-transform: uppercase;
}
.sk-rarity::before {
    content: ''; width: 5px; height: 5px; flex-shrink: 0; transform: rotate(45deg);
    background: var(--rc); box-shadow: 0 0 6px var(--rc);
}
.sk-active {
    position: absolute; top: .6rem; right: .6rem; z-index: 2;
    padding: .25rem .6rem; border-radius: 2px; background: var(--cyan); color: var(--cyan-ink);
    font-size: .55rem; letter-spacing: .16em;
}

/* Art */
.sk-art { display: flex; justify-content: center; margin-top: 2rem; }

/* Text */
.sk-name {
    margin: .9rem 0 0; text-align: center; font-family: 'Iceberg', serif; -webkit-text-stroke: .35px currentColor;
    font-size: 1.15rem; line-height: 1.1; color: var(--text);
}
.sk-default { margin: .4rem 0 0; text-align: center; font-size: .55rem; letter-spacing: .2em; color: var(--ct); }
.sk-desc {
    margin: .7rem 0 0; min-height: 2.2rem; text-align: center; font-size: .7rem; line-height: 1.55; color: var(--muted);
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.sk-selected {
    margin-top: .7rem; padding-top: .7rem; text-align: center; border-top: 1px dashed var(--ln2);
    font-size: .65rem; letter-spacing: .14em; text-transform: uppercase; color: var(--ct);
}

/* Actions */
.sk-actions { display: flex; justify-content: flex-end; gap: .6rem; flex-wrap: wrap; }
.sk-btn {
    position: relative; overflow: hidden; display: inline-flex; align-items: center; justify-content: center;
    padding: .95rem 1.7rem; border: 1px solid transparent; border-radius: 2px; cursor: pointer; text-decoration: none;
    font-family: 'Anta', sans-serif; font-size: .76rem; letter-spacing: .1em; text-transform: uppercase;
    transition: transform .18s, box-shadow .18s, border-color .18s, color .18s;
}
.sk-btn:hover { transform: translateY(-2px); }
.sk-btn:active { transform: translateY(0); }
.sk-btn-ghost { background: transparent; color: var(--text); border-color: var(--ln2); }
[data-theme="light"] .sk-btn-ghost { background: #FFFFFF; }
.sk-btn-ghost:hover { border-color: var(--cyan); color: var(--ct); }
.sk-btn-primary { background: var(--cyan); color: var(--cyan-ink); border-color: var(--cyan); }
.sk-btn-primary:hover { box-shadow: 0 14px 34px -14px var(--cyan); }

@media (max-width: 639px) {
    .sk-actions { flex-direction: column-reverse; }
    .sk-btn { width: 100%; }
}
@media (prefers-reduced-motion: reduce) {
    .sk-page *, .sk-page *::before, .sk-page *::after { transition-duration: .01ms !important; }
}
</style>

@endsection