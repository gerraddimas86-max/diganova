@extends('playground.layouts.app')

@section('title', 'Leaderboard — DIGANOVA Playground')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">🏆 Leaderboard</h1>
    <p class="text-sm text-slate-400 mt-1">
        @if ($selectedSeason)
            {{ $selectedSeason->name }}
            @if ($selectedSeason->is_active)
                · <span class="text-green-400 font-semibold">● Live</span>
            @else
                · <span class="text-slate-500">Ended {{ $selectedSeason->end_date->format('d M Y') }}</span>
            @endif
        @else
            All-Time (Gabungan semua season)
        @endif
    </p>
</div>

{{-- Season Filter --}}
<form method="GET" action="{{ route('play.leaderboard') }}" class="mb-4">
    <div class="flex flex-wrap gap-2">
        {{-- Active season (default) --}}
        @if ($activeSeason)
            <a href="{{ route('play.leaderboard') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition border
                      {{ !request('season') || $selectedSeason?->id === $activeSeason->id
                          ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/50'
                          : 'bg-slate-900 text-slate-400 border-slate-700 hover:bg-slate-800' }}">
                ⚡ {{ $activeSeason->name }}
            </a>
        @endif

        {{-- Other seasons --}}
        @foreach ($seasons as $s)
            @if (!$activeSeason || $s->id !== $activeSeason->id)
                <a href="{{ route('play.leaderboard') }}?season={{ $s->id }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition border
                          {{ $selectedSeason?->id === $s->id
                              ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/50'
                              : 'bg-slate-900 text-slate-400 border-slate-700 hover:bg-slate-800' }}">
                    {{ $s->name }}
                </a>
            @endif
        @endforeach

        {{-- All time --}}
        <a href="{{ route('play.leaderboard') }}?season=all"
           class="px-4 py-2 rounded-lg text-sm font-medium transition border
                  {{ request('season') === 'all'
                      ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/50'
                      : 'bg-slate-900 text-slate-400 border-slate-700 hover:bg-slate-800' }}">
            📊 All-Time
        </a>
    </div>
</form>

{{-- My Position Card --}}
@auth
    @if ($myEntry && $myPosition)
        <div class="bg-gradient-to-br from-cyan-500/20 to-violet-600/20 border border-cyan-500/30 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap">
                <div class="text-3xl font-bold {{ $myPosition <= 3 ? 'text-yellow-400' : 'text-cyan-400' }}">
                    #{{ $myPosition }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-bold">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400">{{ '@' . auth()->user()->username }}</p>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Points</p>
                        <p class="font-bold text-cyan-400">{{ number_format($myEntry->points, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">W/L</p>
                        <p class="font-bold text-white">{{ $myEntry->wins }}/{{ $myEntry->losses }}</p>
                    </div>
                    <x-rank-badge :rank="$myEntry->rank" />
                </div>
            </div>
        </div>
    @elseif ($selectedSeason)
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4 mb-6 text-sm text-yellow-300">
            ⚠️ Kamu belum punya peringkat di season ini. Mainkan 1 battle untuk masuk leaderboard!
        </div>
    @endif
@endauth

{{-- Top 3 Podium --}}
@if ($topPlayers->take(3)->count() >= 3)
    <div class="grid grid-cols-3 gap-2 mb-6">
        {{-- 2nd place --}}
        <div class="order-1 mt-8">
            <div class="bg-slate-900 border-2 border-slate-400/30 rounded-xl p-3 text-center">
                <div class="text-3xl">🥈</div>
                <div class="w-12 h-12 mx-auto mt-2 rounded-full bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr($topPlayers[1]->user->name ?? '?', 0, 1)) }}
                </div>
                <p class="text-xs text-white font-bold mt-2 truncate">{{ $topPlayers[1]->user->name ?? 'Unknown' }}</p>
                <p class="text-[10px] text-slate-500 truncate">{{ '@' . ($topPlayers[1]->user->username ?? '-') }}</p>
                <p class="text-sm font-bold text-cyan-400 mt-1">{{ number_format($topPlayers[1]->points, 0, ',', '.') }}</p>
                <div class="mt-1">
                    <x-rank-badge :rank="$topPlayers[1]->rank" size="sm" />
                </div>
            </div>
        </div>

        {{-- 1st place --}}
        <div class="order-2">
            <div class="bg-gradient-to-br from-yellow-500/20 to-amber-600/20 border-2 border-yellow-500/50 rounded-xl p-3 text-center shadow-lg shadow-yellow-500/10">
                <div class="text-4xl">🥇</div>
                <div class="w-14 h-14 mx-auto mt-2 rounded-full bg-gradient-to-br from-yellow-400 to-amber-600 flex items-center justify-center text-white font-bold text-xl ring-4 ring-yellow-500/30">
                    {{ strtoupper(substr($topPlayers[0]->user->name ?? '?', 0, 1)) }}
                </div>
                <p class="text-sm text-white font-bold mt-2 truncate">{{ $topPlayers[0]->user->name ?? 'Unknown' }}</p>
                <p class="text-[10px] text-slate-400 truncate">{{ '@' . ($topPlayers[0]->user->username ?? '-') }}</p>
                <p class="text-base font-bold text-yellow-400 mt-1">{{ number_format($topPlayers[0]->points, 0, ',', '.') }}</p>
                <div class="mt-1">
                    <x-rank-badge :rank="$topPlayers[0]->rank" size="sm" />
                </div>
            </div>
        </div>

        {{-- 3rd place --}}
        <div class="order-3 mt-8">
            <div class="bg-slate-900 border-2 border-amber-700/30 rounded-xl p-3 text-center">
                <div class="text-3xl">🥉</div>
                <div class="w-12 h-12 mx-auto mt-2 rounded-full bg-gradient-to-br from-amber-700 to-amber-900 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr($topPlayers[2]->user->name ?? '?', 0, 1)) }}
                </div>
                <p class="text-xs text-white font-bold mt-2 truncate">{{ $topPlayers[2]->user->name ?? 'Unknown' }}</p>
                <p class="text-[10px] text-slate-500 truncate">{{ '@' . ($topPlayers[2]->user->username ?? '-') }}</p>
                <p class="text-sm font-bold text-cyan-400 mt-1">{{ number_format($topPlayers[2]->points, 0, ',', '.') }}</p>
                <div class="mt-1">
                    <x-rank-badge :rank="$topPlayers[2]->rank" size="sm" />
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Full Table --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-800 flex justify-between items-center">
        <h2 class="font-bold text-white text-sm">Top Players ({{ $topPlayers->count() }})</h2>
        @if ($selectedSeason)
            <p class="text-xs text-slate-500">Season points · Reset setiap season baru</p>
        @else
            <p class="text-xs text-slate-500">Total semua season</p>
        @endif
    </div>

    @if ($topPlayers->isEmpty())
        <div class="p-12 text-center">
            <div class="text-5xl mb-3">🏆</div>
            <p class="text-white font-medium">Belum ada pemain di leaderboard</p>
            <p class="text-sm text-slate-500 mt-1">Jadilah yang pertama dengan main battle!</p>
            <a href="{{ route('play.battle.pve') }}"
               class="inline-block mt-4 bg-gradient-to-r from-cyan-500 to-violet-600 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition">
                Mulai Battle
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800/50 border-b border-slate-800">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-slate-400 text-xs uppercase tracking-wide">Rank</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-400 text-xs uppercase tracking-wide">Player</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-400 text-xs uppercase tracking-wide">Badge</th>
                        <th class="text-right px-4 py-3 font-semibold text-slate-400 text-xs uppercase tracking-wide">Points</th>
                        <th class="text-right px-4 py-3 font-semibold text-slate-400 text-xs uppercase tracking-wide">Wins</th>
                        <th class="text-right px-4 py-3 font-semibold text-slate-400 text-xs uppercase tracking-wide">Losses</th>
                        <th class="text-right px-4 py-3 font-semibold text-slate-400 text-xs uppercase tracking-wide">Win Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @foreach ($topPlayers as $i => $entry)
                        @php
                            $position = $i + 1;
                            $isMe = auth()->check() && $entry->user_id === auth()->id();
                            $totalGames = $entry->wins + $entry->losses;
                            $winRate = $totalGames > 0 ? round(($entry->wins / $totalGames) * 100) : 0;
                        @endphp
                        <tr class="{{ $isMe ? 'bg-cyan-500/10' : 'hover:bg-slate-800/30' }} transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if ($position === 1)
                                        <span class="text-xl">🥇</span>
                                    @elseif ($position === 2)
                                        <span class="text-xl">🥈</span>
                                    @elseif ($position === 3)
                                        <span class="text-xl">🥉</span>
                                    @endif
                                    <span class="font-bold {{ $position <= 3 ? 'text-yellow-400' : 'text-slate-400' }}">
                                        #{{ $position }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($entry->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-white truncate">
                                            {{ $entry->user->name ?? 'Unknown' }}
                                            @if ($isMe)
                                                <span class="text-[10px] bg-cyan-500/20 text-cyan-300 px-1.5 py-0.5 rounded ml-1">YOU</span>
                                            @endif
                                        </p>
                                        <p class="text-[10px] text-slate-500 truncate">{{ '@' . ($entry->user->username ?? '-') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <x-rank-badge :rank="$entry->rank" size="sm" />
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-bold text-cyan-400">{{ number_format($entry->points, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right text-green-400 font-medium">{{ $entry->wins }}</td>
                            <td class="px-4 py-3 text-right text-red-400 font-medium">{{ $entry->losses }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-xs {{ $winRate >= 60 ? 'text-green-400' : ($winRate >= 40 ? 'text-yellow-400' : 'text-red-400') }} font-semibold">
                                    {{ $winRate }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Info --}}
<div class="mt-4 bg-blue-500/10 border border-blue-500/30 rounded-xl p-3 text-xs text-blue-300">
    <p class="font-semibold mb-1">💡 Info Rank:</p>
    <div class="grid grid-cols-2 md:grid-cols-6 gap-2 mt-2">
        <div class="flex items-center gap-1">🥉 <span>Bronze</span> <span class="text-slate-500">0-499</span></div>
        <div class="flex items-center gap-1">🥈 <span>Silver</span> <span class="text-slate-500">500-999</span></div>
        <div class="flex items-center gap-1">🥇 <span>Gold</span> <span class="text-slate-500">1000-1999</span></div>
        <div class="flex items-center gap-1">💎 <span>Platinum</span> <span class="text-slate-500">2000-3499</span></div>
        <div class="flex items-center gap-1">💠 <span>Diamond</span> <span class="text-slate-500">3500-4999</span></div>
        <div class="flex items-center gap-1">👑 <span>Legend</span> <span class="text-slate-500">5000+</span></div>
    </div>
</div>

@endsection