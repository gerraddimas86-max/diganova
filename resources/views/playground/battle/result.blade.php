@extends('playground.layouts.app')

@section('title', 'Battle Result — DIGANOVA Playground')

@section('content')

@php
    $isWin = $battle->winner_id === auth()->id();
    $isDraw = !$battle->winner_id;
    $isForfeit = $battle->status === 'forfeited';
@endphp

<div class="max-w-2xl mx-auto">

    <div class="mb-4">
        <a href="{{ route('play.home') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
            ← Kembali ke Lobby
        </a>
    </div>

    {{-- Result Hero --}}
    <div class="bg-gradient-to-br {{ $isWin ? 'from-green-500/20 to-emerald-600/20 border-green-500/30' : ($isForfeit ? 'from-orange-500/20 to-red-600/20 border-orange-500/30' : 'from-red-500/20 to-rose-600/20 border-red-500/30') }} border-2 rounded-2xl p-8 text-center mb-4">
        <div class="text-7xl mb-3">
            @if ($isWin) 🏆
            @elseif ($isForfeit) 🏳️
            @else 💀
            @endif
        </div>
        <h1 class="text-3xl font-bold {{ $isWin ? 'text-green-400' : ($isForfeit ? 'text-orange-400' : 'text-red-400') }}">
            @if ($isWin) VICTORY
            @elseif ($isForfeit) FORFEIT
            @else DEFEAT
            @endif
        </h1>

        <p class="text-sm text-slate-400 mt-2">
            @if ($isWin) +50 poin · rank naik
            @elseif ($isForfeit) -30 poin · forfeit
            @else -20 poin
            @endif
        </p>

        <div class="grid grid-cols-3 gap-3 mt-6 max-w-md mx-auto">
            <div class="bg-slate-900/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 uppercase">HP Kamu</p>
                <p class="text-2xl font-bold text-white mt-1">{{ max(0, $battle->player1_hp_final ?? 0) }}</p>
            </div>
            <div class="bg-slate-900/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 uppercase">HP AI</p>
                <p class="text-2xl font-bold text-white mt-1">{{ max(0, $battle->player2_hp_final ?? 0) }}</p>
            </div>
            <div class="bg-slate-900/50 rounded-lg p-3">
                <p class="text-xs text-slate-500 uppercase">Turn</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $battle->turn_count }}</p>
            </div>
        </div>
    </div>

    {{-- Battle Log --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-4">
        <h2 class="font-bold text-white mb-3">📜 Battle Log Lengkap</h2>
        <div class="space-y-1.5 max-h-96 overflow-y-auto">
            @foreach ($battle->logs as $log)
                <div class="flex items-start gap-2 py-1.5 border-b border-slate-800/50 last:border-0 text-xs">
                    <span class="text-[10px] text-slate-500 font-mono w-8 flex-shrink-0">T{{ $log->turn_number }}</span>
                    <span class="text-[10px] font-bold w-8 flex-shrink-0
                        {{ $log->actor_user_id === auth()->id() ? 'text-cyan-400' : ($log->actor_user_id ? 'text-red-400' : 'text-slate-400') }}">
                        @if ($log->actor_user_id === auth()->id()) YOU
                        @elseif ($log->actor_user_id) AI
                        @else SYS
                        @endif
                    </span>
                    <span class="text-slate-300 flex-1">{{ $log->description }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-2">
        <a href="{{ route('play.battle.pve') }}"
           onclick="event.preventDefault(); document.getElementById('rematch-form').submit();"
           class="flex-1 text-center bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white px-6 py-3 rounded-lg font-bold transition">
            🔄 Rematch
        </a>
        <a href="{{ route('play.home') }}"
           class="flex-1 text-center bg-slate-800 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition border border-slate-700">
            🏠 Lobby
        </a>
    </div>

    <form id="rematch-form" method="POST" action="{{ route('play.battle.start') }}" class="hidden">
        @csrf
    </form>

</div>

@endsection