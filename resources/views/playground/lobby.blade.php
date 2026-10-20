@extends('playground.layouts.app')

@section('title', 'Cyber Lobby — DIGANOVA Playground')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- KIRI: PROFIL NAVO & STATS --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Profile Card --}}
        <div class="bg-[#111] border border-cyan-500/30 rounded-2xl p-6 relative overflow-hidden group hover:border-cyan-400 transition-colors duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
            
            <div class="text-center relative z-10">
                <div class="inline-block relative mb-4">
                    <x-navo :skin="$profile->equippedSkin" size="w-28 h-28" textSize="text-6xl" class="ring-4 ring-cyan-500/30 shadow-[0_0_20px_rgba(34,211,238,0.4)] rounded-2xl" />
                    <div class="absolute -bottom-2 -right-2 bg-slate-900 border border-cyan-500 text-cyan-400 text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wider">
                        {{ $profile->equippedSkin->rarity ?? 'Common' }}
                    </div>
                </div>
                
                <h2 class="text-2xl font-bold text-white font-sans">{{ $profile->equippedSkin->name ?? 'Navo Original' }}</h2>
                <p class="text-cyan-400 text-sm font-mono mt-1">{{ '@' . $user->username }}</p>
                
                <div class="mt-4 flex justify-center gap-2 font-sans">
                    <span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-xs font-bold px-3 py-1 rounded-full uppercase">
                        {{ $user->rank }}
                    </span>
                    <span class="bg-fuchsia-500/20 text-fuchsia-400 border border-fuchsia-500/30 text-xs font-bold px-3 py-1 rounded-full">
                        ⭐ {{ $user->total_points }}
                    </span>
                </div>

                <div class="mt-6 flex gap-2">
                    <a href="{{ route('play.profile.skins') }}" class="flex-1 bg-slate-800 hover:bg-slate-700 text-cyan-300 border border-cyan-500/30 text-xs font-bold py-2 rounded-lg transition uppercase tracking-wide">
                        Ganti Skin
                    </a>
                    <a href="{{ route('play.profile.show') }}" class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-xs font-bold py-2 rounded-lg transition uppercase tracking-wide">
                        Profil
                    </a>
                </div>
            </div>
        </div>

        {{-- Combat Stats --}}
        <div class="bg-[#111] border border-slate-800 rounded-2xl p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Combat Stats</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-400">Total Battle</p>
                    <p class="text-2xl font-black text-white">{{ $profile->total_battles }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Win Rate</p>
                    <p class="text-2xl font-black text-green-400">{{ $profile->winRate() }}%</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Wins</p>
                    <p class="text-xl font-bold text-cyan-400">{{ $profile->wins }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Losses</p>
                    <p class="text-xl font-bold text-red-400">{{ $profile->losses }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN: QUICK ACTIONS & DECK --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Battle PvE --}}
            <a href="{{ route('play.battle.pve') }}" class="group relative bg-[#111] border border-cyan-500/30 rounded-2xl p-6 hover:bg-cyan-500/10 hover:border-cyan-400 transition-all duration-300 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:opacity-30 group-hover:scale-110 transition-all duration-500">⚔️</div>
                <h3 class="text-xs text-cyan-500 font-bold uppercase tracking-wider mb-1">Latihan</h3>
                <h2 class="text-2xl font-black text-white font-sans">Battle PvE</h2>
                <p class="text-sm text-slate-400 mt-2">Lawan AI untuk menguji strategi deck-mu.</p>
                <div class="mt-4 text-cyan-400 text-sm font-bold group-hover:translate-x-2 transition-transform">Mulai →</div>
            </a>

            {{-- Battle PvP --}}
            <a href="{{ route('play.battle.pvp') }}" class="group relative bg-[#111] border border-fuchsia-500/30 rounded-2xl p-6 hover:bg-fuchsia-500/10 hover:border-fuchsia-400 transition-all duration-300 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:opacity-30 group-hover:scale-110 transition-all duration-500">🏆</div>
                <h3 class="text-xs text-fuchsia-500 font-bold uppercase tracking-wider mb-1">Kompetitif</h3>
                <h2 class="text-2xl font-black text-white font-sans">Battle PvP</h2>
                <p class="text-sm text-slate-400 mt-2">Cari lawan sungguhan dan rebut posisi rank.</p>
                <div class="mt-4 text-fuchsia-400 text-sm font-bold group-hover:translate-x-2 transition-transform">Cari Lawan →</div>
            </a>
        </div>

        {{-- Active Deck --}}
        <div class="bg-[#111] border border-slate-800 rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4 border-b border-slate-800 pb-3">
                <h3 class="font-bold text-white text-lg flex items-center gap-2">
                    <span class="text-cyan-400">🃏</span> Deck Aktif
                </h3>
                <a href="{{ route('play.decks.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-bold uppercase tracking-wider">Kelola Deck →</a>
            </div>
            
            @if ($activeDeck && $activeDeck->cards->isNotEmpty())
                <div class="grid grid-cols-5 gap-2">
                    @foreach ($activeDeck->cards->take(10) as $card)
                        <div class="aspect-[2/3] bg-slate-900 border border-slate-700 rounded-lg flex flex-col items-center justify-center p-1 relative group cursor-pointer hover:border-cyan-500 transition-colors">
                            <span class="text-2xl mb-1">
                                @if ($card->type === 'attack') ⚔️
                                @elseif ($card->type === 'defense') 🛡️
                                @else ✨
                                @endif
                            </span>
                            <p class="text-[8px] text-center text-slate-300 leading-tight line-clamp-2">{{ $card->name }}</p>
                            <div class="absolute top-1 right-1 bg-cyan-500 text-black text-[8px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                {{ $card->energy_cost }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 flex justify-between items-center text-xs text-slate-500">
                    <span>Status: <strong class="text-green-400">Siap Tempur</strong></span>
                    <span>{{ $activeDeck->cards->sum('pivot.quantity') }} / 10 Kartu</span>
                </div>
            @else
                <div class="text-center py-8 bg-slate-900/50 rounded-xl border border-dashed border-slate-700">
                    <p class="text-slate-400 text-sm mb-3">Kamu belum memiliki deck aktif.</p>
                    <a href="{{ route('play.decks.index') }}" class="inline-block bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold px-4 py-2 rounded transition">
                        Buat Deck Sekarang
                    </a>
                </div>
            @endif
        </div>

        {{-- Recent Battles --}}
        <div class="bg-[#111] border border-slate-800 rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-white text-sm">Riwayat Pertempuran</h3>
                <a href="{{ route('play.leaderboard') }}" class="text-xs text-yellow-400 hover:text-yellow-300 font-bold uppercase tracking-wider">Leaderboard →</a>
            </div>
            @if ($recentBattles->isEmpty())
                <p class="text-xs text-slate-500 text-center py-4">Belum ada riwayat pertempuran.</p>
            @else
                <div class="space-y-2">
                    @foreach ($recentBattles as $battle)
                        @php
                            $isWin = $battle->winner_id === $user->id;
                            $opp = $battle->player1_id === $user->id ? $battle->player2 : $battle->player1;
                        @endphp
                        <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-900/50 border border-slate-800/50">
                            <div class="w-8 h-8 rounded {{ $isWin ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }} flex items-center justify-center font-black text-xs">
                                {{ $isWin ? 'W' : 'L' }}
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-white">VS <strong class="text-cyan-400">{{ $opp->name ?? 'AI' }}</strong></p>
                                <p class="text-[10px] text-slate-500">{{ $battle->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-xs font-bold {{ $isWin ? 'text-green-400' : 'text-red-400' }}">
                                {{ $isWin ? '+50' : '-20' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

@endsection