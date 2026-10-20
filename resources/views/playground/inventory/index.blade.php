@extends('playground.layouts.app')

@section('title', 'Inventory — DIGANOVA Playground')

@section('content')

<div class="mb-6">
    <div class="flex justify-between items-start flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-white">🎒 Inventory</h1>
            <p class="text-sm text-slate-400 mt-1">Koleksi kosmetik kamu dari gacha</p>
        </div>
        <a href="{{ route('play.gacha.index') }}"
           class="bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm transition">
            🎲 Pull Gacha
        </a>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl p-2 flex gap-1 mb-6 overflow-x-auto">
    @foreach (['all' => '📦 Semua', 'skin' => '👕 Skin', 'emote' => '😊 Emote', 'badge' => '🏅 Badge'] as $key => $label)
        <a href="{{ route('play.inventory.index') }}?type={{ $key }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap
                  {{ $filter === $key ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/50' : 'text-slate-400 hover:bg-slate-800' }}">
            {{ $label }}
            <span class="text-xs opacity-75">({{ $counts[$key] }})</span>
        </a>
    @endforeach
</div>

{{-- Items Grid --}}
@if ($items->isEmpty())
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-12 text-center">
        <div class="text-5xl mb-3">🎒</div>
        <p class="text-white font-medium">
            @if ($filter === 'all')
                Inventory kosong
            @else
                Belum punya {{ $filter }}
            @endif
        </p>
        <p class="text-sm text-slate-500 mt-1">Coba pull gacha untuk mengumpulkan item!</p>
        <a href="{{ route('play.gacha.index') }}"
           class="inline-block mt-4 bg-gradient-to-r from-cyan-500 to-violet-600 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition">
            Buka Gacha
        </a>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach ($items as $item)
            @php
                $isEquipped = $item->pivot->is_equipped;
                $userItemId = $item->pivot->id;
                $rarityColor = match($item->rarity) {
                    'common' => 'slate',
                    'rare' => 'blue',
                    'epic' => 'purple',
                    'legendary' => 'yellow',
                    default => 'slate',
                };
            @endphp

            <div class="bg-slate-900 border {{ $isEquipped ? 'border-cyan-500 ring-2 ring-cyan-500/30' : 'border-slate-800' }} rounded-xl p-3 flex flex-col">

                {{-- Item Display --}}
                <div class="relative aspect-square rounded-lg bg-gradient-to-br border-2 flex items-center justify-center
                    @if ($item->rarity === 'common') from-slate-500/30 to-slate-700/30 border-slate-500/40
                    @elseif ($item->rarity === 'rare') from-blue-500/30 to-cyan-500/30 border-blue-500/50
                    @elseif ($item->rarity === 'epic') from-purple-500/30 to-violet-600/30 border-purple-500/50
                    @else from-yellow-400/40 via-pink-500/30 to-violet-600/40 border-yellow-400/60
                    @endif">
                    <span class="text-5xl">
                        @if ($item->type === 'skin') 👕
                        @elseif ($item->type === 'emote') 😊
                        @else 🏅
                        @endif
                    </span>

                    @if ($isEquipped)
                        <div class="absolute -top-1 -right-1 bg-cyan-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            ✓ EQUIPPED
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="mt-2 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ $item->name }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="text-[10px] bg-{{ $rarityColor }}-500/20 text-{{ $rarityColor }}-300 px-1.5 py-0.5 rounded capitalize">
                            {{ $item->rarity }}
                        </span>
                        <span class="text-[10px] text-slate-500 capitalize">{{ $item->type }}</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">
                        Didapat {{ \Carbon\Carbon::parse($item->pivot->obtained_at)->diffForHumans() }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="mt-2">
                    @if ($isEquipped)
                        <form method="POST" action="{{ route('play.inventory.unequip', $userItemId) }}">
                            @csrf
                            <button class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-medium transition border border-slate-700">
                                Lepas
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('play.inventory.equip', $userItemId) }}">
                            @csrf
                            <button class="w-full bg-cyan-600 hover:bg-cyan-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                Pakai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Info --}}
    <div class="mt-6 bg-blue-500/10 border border-blue-500/30 rounded-xl p-4 text-sm text-blue-300">
        <p class="font-semibold mb-1">💡 Info</p>
        <ul class="text-xs space-y-1 ml-4 list-disc">
            <li>Hanya 1 item per tipe bisa dipakai sekaligus</li>
            <li>Badge & Emote akan ditampilkan di profil publik</li>
            <li>Skin gacha hanya untuk koleksi (Navo skin utama tetap dari menu Ganti Skin)</li>
        </ul>
    </div>
@endif

@endsection