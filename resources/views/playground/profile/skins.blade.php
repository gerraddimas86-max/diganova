@extends('playground.layouts.app')

@section('title', 'Skin Navo — DIGANOVA Playground')

@section('content')

<div class="mb-4">
    <a href="{{ route('play.profile.show') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
        ← Kembali ke Profil
    </a>
</div>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">🎨 Pilih Skin Navo</h1>
    <p class="text-sm text-slate-400 mt-1">
        Semua skin gratis! Free-to-win — tidak ada pay-to-win.
    </p>
</div>

<form method="POST" action="{{ route('play.profile.skins.update') }}"
      x-data="{ selectedId: {{ $profile->equipped_skin_id ?? 'null' }} }">
    @csrf

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        @foreach ($skins as $skin)
            @php
                $rarityColor = match($skin->rarity) {
                    'common' => 'gray',
                    'rare' => 'blue',
                    'epic' => 'purple',
                    'legendary' => 'yellow',
                    default => 'gray',
                };
                $isEquipped = $profile->equipped_skin_id === $skin->id;
            @endphp

            <label class="cursor-pointer">
                <input type="radio" name="skin_id" value="{{ $skin->id }}"
                       x-model.number="selectedId"
                       class="peer sr-only"
                       @checked($isEquipped)>

                <div class="bg-slate-900 border-2 rounded-xl p-4 transition relative
                            {{ $isEquipped ? 'border-cyan-500' : 'border-slate-800 hover:border-slate-600' }}
                            peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 peer-checked:ring-2 peer-checked:ring-cyan-500/30">

                    {{-- Equipped badge --}}
                    @if ($isEquipped)
                        <div class="absolute top-2 right-2 bg-cyan-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            AKTIF
                        </div>
                    @endif

                    {{-- Rarity badge --}}
                    <div class="absolute top-2 left-2 text-[10px] bg-{{ $rarityColor }}-500/20 text-{{ $rarityColor }}-300 px-2 py-0.5 rounded-full capitalize border border-{{ $rarityColor }}-500/30">
                        {{ $skin->rarity }}
                    </div>

                    <div class="flex justify-center mt-4">
                        <x-navo :skin="$skin" size="w-24 h-24" textSize="text-5xl" />
                    </div>

                    <p class="text-center font-bold text-white mt-3 text-sm">{{ $skin->name }}</p>
                    @if ($skin->is_default)
                        <p class="text-center text-[10px] text-green-400 mt-0.5 font-semibold">DEFAULT</p>
                    @endif

                    <p class="text-center text-[10px] text-slate-500 mt-2 line-clamp-2 min-h-[2rem]">
                        {{ $skin->description }}
                    </p>

                    {{-- Selected check --}}
                    <div x-show="selectedId === {{ $skin->id }}"
                         x-cloak
                         class="mt-2 text-center text-xs text-cyan-400 font-semibold">
                        ✓ Dipilih
                    </div>
                </div>
            </label>
        @endforeach
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('play.profile.show') }}"
           class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-medium text-sm transition">
            Batal
        </a>
        <button type="submit"
                class="bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white px-8 py-3 rounded-lg font-bold text-sm transition">
            Pakai Skin Ini
        </button>
    </div>
</form>

@endsection