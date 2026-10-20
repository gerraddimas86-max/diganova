@extends('playground.layouts.app')

@section('title', 'Profil Navo — DIGANOVA Playground')

@section('content')

<div class="mb-4">
    <a href="{{ route('play.home') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
        ← Kembali ke Lobby
    </a>
</div>

{{-- Header Profile --}}
<div class="bg-gradient-to-br from-cyan-500/20 via-blue-500/10 to-violet-600/20 border border-cyan-500/30 rounded-2xl p-6 mb-6">
    <div class="flex flex-col md:flex-row items-center gap-6">
        <x-navo :skin="$profile->equippedSkin" size="w-32 h-32" textSize="text-7xl" />

        <div class="flex-1 text-center md:text-left">
            <p class="text-xs text-cyan-300 uppercase tracking-wider font-semibold">Navo Profile</p>
            <h1 class="text-3xl font-bold text-white mt-1">{{ $profile->equippedSkin->name ?? 'Navo Original' }}</h1>
            <p class="text-sm text-slate-400 mt-1">
                {{ $user->name }} · {{ '@' . $user->username }} · Rank <strong class="capitalize text-white">{{ $user->rank }}</strong>
            </p>

            @if ($profile->equippedSkin)
                <div class="mt-2 inline-flex gap-1.5 items-center text-xs">
                    <span class="bg-{{ $profile->equippedSkin->rarity === 'legendary' ? 'yellow' : ($profile->equippedSkin->rarity === 'epic' ? 'purple' : ($profile->equippedSkin->rarity === 'rare' ? 'blue' : 'gray')) }}-500/20 text-{{ $profile->equippedSkin->rarity === 'legendary' ? 'yellow' : ($profile->equippedSkin->rarity === 'epic' ? 'purple' : ($profile->equippedSkin->rarity === 'rare' ? 'blue' : 'gray')) }}-300 px-2 py-0.5 rounded capitalize border border-current/30">
                        {{ $profile->equippedSkin->rarity }}
                    </span>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('play.profile.skins') }}"
                   class="bg-slate-800 hover:bg-slate-700 text-cyan-300 px-5 py-2 rounded-lg font-medium text-sm transition border border-cyan-500/30">
                    🎨 Ganti Skin
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wide">Total Battle</p>
        <p class="text-3xl font-bold text-white mt-1">{{ $profile->total_battles }}</p>
    </div>
    <div class="bg-slate-900 border border-green-500/30 rounded-xl p-4">
        <p class="text-xs text-green-400 uppercase tracking-wide">Wins</p>
        <p class="text-3xl font-bold text-green-400 mt-1">{{ $profile->wins }}</p>
    </div>
    <div class="bg-slate-900 border border-red-500/30 rounded-xl p-4">
        <p class="text-xs text-red-400 uppercase tracking-wide">Losses</p>
        <p class="text-3xl font-bold text-red-400 mt-1">{{ $profile->losses }}</p>
    </div>
    <div class="bg-slate-900 border border-cyan-500/30 rounded-xl p-4">
        <p class="text-xs text-cyan-400 uppercase tracking-wide">Win Rate</p>
        <p class="text-3xl font-bold text-cyan-400 mt-1">{{ $profile->winRate() }}%</p>
    </div>
    <div class="bg-slate-900 border border-yellow-500/30 rounded-xl p-4">
        <p class="text-xs text-yellow-400 uppercase tracking-wide">Best Streak</p>
        <p class="text-3xl font-bold text-yellow-400 mt-1">{{ $profile->best_streak }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Battle History --}}
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 class="font-bold text-white mb-3">⚔️ Battle History</h3>

        @if ($battleHistory->isEmpty())
            <div class="text-center py-8">
                <p class="text-slate-400 text-sm">Belum ada battle</p>
                <a href="{{ route('play.battle.pve') }}" class="inline-block mt-2 text-cyan-400 hover:text-cyan-300 text-sm">
                    Mulai battle pertama →
                </a>
            </div>
        @else
            <div class="space-y-1.5">
                @foreach ($battleHistory as $battle)
                    @php
                        $isWinner = $battle->winner_id === $user->id;
                        $isDraw = !$battle->winner_id;
                        $opponent = $battle->player1_id === $user->id ? $battle->player2 : $battle->player1;
                        $isPve = $battle->mode === 'pve';
                    @endphp
                    <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-800/50 hover:bg-slate-800 transition">
                        <div class="w-9 h-9 rounded-full {{ $isDraw ? 'bg-slate-700 text-slate-300' : ($isWinner ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400') }} flex items-center justify-center text-sm font-bold flex-shrink-0">
                            {{ $isDraw ? '–' : ($isWinner ? 'W' : 'L') }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white truncate">
                                vs <strong>{{ $opponent->name ?? ($isPve ? 'AI' : 'Unknown') }}</strong>
                                @if ($isPve)
                                    <span class="text-xs bg-blue-500/20 text-blue-300 px-1.5 py-0.5 rounded ml-1">PvE</span>
                                @else
                                    <span class="text-xs bg-violet-500/20 text-violet-300 px-1.5 py-0.5 rounded ml-1">PvP</span>
                                @endif
                            </p>
                            <p class="text-xs text-slate-500">{{ $battle->created_at->diffForHumans() }}</p>
                        </div>
                        @if ($battle->player1_points_change !== 0 || $battle->player2_points_change !== 0)
                            @php
                                $myPointsChange = $battle->player1_id === $user->id ? $battle->player1_points_change : $battle->player2_points_change;
                            @endphp
                            <span class="text-xs font-semibold {{ $myPointsChange >= 0 ? 'text-green-400' : 'text-red-400' }} whitespace-nowrap">
                                {{ $myPointsChange >= 0 ? '+' : '' }}{{ $myPointsChange }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">

        {{-- Active Deck --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-white text-sm">🎴 Deck Aktif</h3>
                <a href="{{ route('play.decks.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300">Kelola</a>
            </div>
            @if ($activeDeck)
                <p class="font-semibold text-white text-sm">{{ $activeDeck->name }}</p>
                <p class="text-xs text-slate-500">{{ $activeDeck->cards->sum('pivot.quantity') }} kartu</p>
            @else
                <p class="text-sm text-slate-400">Belum ada deck aktif</p>
            @endif
            <p class="text-xs text-slate-500 mt-2">{{ $decksCount }} / 3 slot terpakai</p>
        </div>

        {{-- Favorite Card --}}
        @if ($profile->favoriteCard)
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <h3 class="font-bold text-white text-sm mb-2">⭐ Kartu Favorit</h3>
                <p class="text-white font-semibold">{{ $profile->favoriteCard->name }}</p>
                <p class="text-xs text-slate-500 capitalize">{{ $profile->favoriteCard->type }}</p>
            </div>
        @endif

        {{-- Streak --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
            <h3 class="font-bold text-white text-sm mb-3">🔥 Streak</h3>
            <div class="flex justify-between text-sm">
                <span class="text-slate-400">Current</span>
                <span class="font-bold text-cyan-400">{{ $profile->current_streak }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-slate-400">Best</span>
                <span class="font-bold text-yellow-400">{{ $profile->best_streak }}</span>
            </div>
        </div>
    </div>
</div>

@endsection