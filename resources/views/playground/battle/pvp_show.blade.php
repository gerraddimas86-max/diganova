@extends('playground.layouts.app')

@section('title', 'Battle PvP — DIGANOVA Playground')

@section('content')

<div x-data="pvpBattle({
        battleId: {{ $battle->id }},
        mySlot: {{ $mySlot }},
        myUserId: {{ auth()->id() }},
        initialHand: {{ json_encode($orderedHand->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'type' => $c->type,
            'energy_cost' => $c->energy_cost,
            'damage' => $c->damage,
            'heal' => $c->heal,
            'block' => $c->block,
        ])->toJson()) }},
        initialHp1: {{ $state->player1_hp }},
        initialHp2: {{ $state->player2_hp }},
        initialEnergy: {{ $mySlot === 1 ? $state->player1_energy : $state->player2_energy }},
        player1Name: '{{ $battle->player1->name }}',
        player2Name: '{{ $battle->player2->name }}',
        deadline: '{{ $state->turn_deadline?->toIso8601String() }}',
     })"
     x-init="init()">

    {{-- Header --}}
    <div class="mb-4 flex justify-between items-start flex-wrap gap-2">
        <div>
            <h1 class="text-xl font-bold text-white">⚔️ Battle PvP</h1>
            <p class="text-sm text-slate-400">Rank: <strong class="capitalize text-cyan-400">{{ auth()->user()->rank }}</strong></p>
        </div>
        <form method="POST" action="{{ route('play.battle.pvp.forfeit', $battle) }}"
              onsubmit="return confirm('Yakin forfeit? Kamu kalah dan -30 poin, lawan +50 poin.');">
            @csrf
            <button type="submit" class="text-xs text-red-400 hover:text-red-300 border border-red-500/30 px-3 py-1.5 rounded-lg">
                🏳️ Forfeit
            </button>
        </form>
    </div>

    {{-- HP Bars --}}
    <div class="grid grid-cols-2 gap-3 mb-4">
        {{-- P1 --}}
        <div class="bg-slate-900 border {{ $mySlot === 1 ? 'border-cyan-500/50' : 'border-violet-500/50' }} rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-full {{ $mySlot === 1 ? 'bg-gradient-to-br from-cyan-500 to-blue-600' : 'bg-gradient-to-br from-violet-500 to-pink-600' }} flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($battle->player1->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ $battle->player1->name }}</p>
                    <p class="text-[10px] {{ $mySlot === 1 ? 'text-cyan-400' : 'text-violet-400' }} uppercase tracking-wide">
                        {{ $mySlot === 1 ? 'YOU' : 'Opponent' }}
                    </p>
                </div>
                <div x-show="state.opp_pending && mySlot === 2 || state.my_pending && mySlot === 1"
                     class="text-xs text-green-400 font-semibold">
                    ✓ Ready
                </div>
            </div>
            <div class="bg-slate-800 rounded-full h-4 overflow-hidden relative">
                <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-600 transition-all duration-500"
                     :style="`width: ${Math.max(0, hp1)}%`"></div>
                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                    <span x-text="Math.max(0, hp1)"></span> / 100
                </div>
            </div>
        </div>

        {{-- P2 --}}
        <div class="bg-slate-900 border {{ $mySlot === 2 ? 'border-cyan-500/50' : 'border-violet-500/50' }} rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-full {{ $mySlot === 2 ? 'bg-gradient-to-br from-cyan-500 to-blue-600' : 'bg-gradient-to-br from-violet-500 to-pink-600' }} flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($battle->player2->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ $battle->player2->name }}</p>
                    <p class="text-[10px] {{ $mySlot === 2 ? 'text-cyan-400' : 'text-violet-400' }} uppercase tracking-wide">
                        {{ $mySlot === 2 ? 'YOU' : 'Opponent' }}
                    </p>
                </div>
                <div x-show="state.opp_pending && mySlot === 1 || state.my_pending && mySlot === 2"
                     class="text-xs text-green-400 font-semibold">
                    ✓ Ready
                </div>
            </div>
            <div class="bg-slate-800 rounded-full h-4 overflow-hidden relative">
                <div class="h-full bg-gradient-to-r from-violet-500 to-pink-600 transition-all duration-500"
                     :style="`width: ${Math.max(0, hp2)}%`"></div>
                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                    <span x-text="Math.max(0, hp2)"></span> / 100
                </div>
            </div>
        </div>
    </div>

    {{-- Status Bar --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-3 mb-4 flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-3">
            <div :class="timer <= 10 ? 'text-red-400 animate-pulse' : 'text-cyan-400'" class="text-lg font-bold font-mono">
                ⏱ <span x-text="timer"></span>s
            </div>
            <p class="text-xs text-slate-500">Turn <strong x-text="state.turn_count"></strong></p>
            <p class="text-xs text-cyan-400">⚡ Energy: <strong x-text="energy"></strong></p>
        </div>
        <div class="flex gap-2">
            <template x-if="!state.my_pending && !state.my_skip">
                <button type="button" @click="skipTurn()" :disabled="processing"
                        class="text-xs bg-slate-800 hover:bg-slate-700 text-yellow-400 px-3 py-1.5 rounded-lg border border-yellow-500/30 transition disabled:opacity-40">
                    ⏭ Skip
                </button>
            </template>
            <template x-if="state.my_pending || state.my_skip">
                <span class="text-xs bg-green-500/20 text-green-400 px-3 py-1.5 rounded-lg border border-green-500/30 font-semibold">
                    ✓ Menunggu lawan...
                </span>
            </template>
        </div>
    </div>

    {{-- Log --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-3 mb-4">
        <h3 class="text-sm font-bold text-white mb-2">📜 Battle Log</h3>
        <div class="max-h-32 overflow-y-auto space-y-1 text-xs" x-ref="logBox">
            <template x-for="(log, i) in logs.slice(-10)" :key="'log-' + i">
                <div class="flex items-start gap-2 py-1 border-b border-slate-800/50 last:border-0">
                    <span :class="log.slot === mySlot ? 'text-cyan-400' : 'text-violet-400'" class="text-[10px] font-bold w-8 flex-shrink-0"
                          x-text="log.slot === mySlot ? 'YOU' : 'OPP'"></span>
                    <span class="text-slate-300 flex-1" x-text="log.description"></span>
                </div>
            </template>
            <p x-show="logs.length === 0" class="text-center text-slate-500 py-2">Battle dimulai...</p>
        </div>
    </div>

    {{-- Hand --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-bold text-white">🎴 Kartu di Tangan</h3>
            <p class="text-xs text-slate-500">Pilih kartu untuk turn ini</p>
        </div>

        <div x-show="!state.my_pending && !state.my_skip" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 gap-2">
            <template x-for="card in hand" :key="card.id">
                <button type="button" @click="playCard(card)"
                        :disabled="processing || energy < card.energy_cost"
                        :class="energy >= card.energy_cost ? 'border-slate-700 hover:border-cyan-500 hover:scale-105 cursor-pointer' : 'border-slate-800 opacity-40 cursor-not-allowed'"
                        class="border-2 rounded-lg p-2 text-left transition bg-slate-800/50">

                    <div class="text-center mb-1">
                        <span class="text-2xl" x-text="card.type === 'attack' ? '⚔️' : (card.type === 'defense' ? '🛡️' : '✨')"></span>
                    </div>
                    <p class="text-[11px] font-semibold text-white text-center leading-tight line-clamp-2 min-h-[1.75rem]" x-text="card.name"></p>
                    <div class="flex justify-between items-center mt-1 pt-1 border-t border-slate-700">
                        <span class="text-[10px] text-cyan-400">⚡<span x-text="card.energy_cost"></span></span>
                        <span class="text-[10px]"
                              :class="card.damage > 0 ? 'text-red-400' : (card.heal > 0 ? 'text-green-400' : 'text-blue-400')">
                            <template x-if="card.damage > 0"><span x-text="'💥' + card.damage"></span></template>
                            <template x-if="card.heal > 0"><span x-text="'💚' + card.heal"></span></template>
                            <template x-if="card.block > 0"><span x-text="'🛡️' + card.block"></span></template>
                        </span>
                    </div>
                </button>
            </template>
        </div>

        <div x-show="state.my_pending || state.my_skip" class="text-center py-8">
            <div class="text-4xl mb-2">⏳</div>
            <p class="text-white font-semibold">Menunggu lawan...</p>
            <p class="text-xs text-slate-500 mt-1">
                <span x-show="state.opp_pending || state.opp_skip">Lawan sudah siap! Menyelesaikan turn...</span>
                <span x-show="!state.opp_pending && !state.opp_skip">Lawan sedang memilih kartu</span>
            </p>
        </div>
    </div>

    {{-- Result Redirect --}}
    <div x-show="finished" x-cloak
         class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center"
         x-transition>
        <div class="text-center">
            <div class="text-6xl mb-3" x-text="winner === mySlot ? '🏆' : '💀'"></div>
            <h2 class="text-3xl font-bold" :class="winner === mySlot ? 'text-green-400' : 'text-red-400'"
                x-text="winner === mySlot ? 'VICTORY!' : 'DEFEAT'"></h2>
            <p class="text-slate-400 text-sm mt-2" x-text="winner === mySlot ? '+50 poin' : '-20 poin'"></p>
            <a :href="'/battle/pvp/' + battleId"
               class="inline-block mt-6 bg-gradient-to-r from-cyan-500 to-violet-600 text-white px-8 py-3 rounded-lg font-bold">
                Lihat Hasil
            </a>
        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important}</style>

<script>
function pvpBattle(config) {
    return {
        battleId: config.battleId,
        mySlot: config.mySlot,
        myUserId: config.myUserId,
        hand: config.initialHand || [],
        hp1: config.initialHp1,
        hp2: config.initialHp2,
        energy: config.initialEnergy,
        timer: 30,
        state: {
            turn_count: 1,
            my_pending: false,
            my_skip: false,
            opp_pending: false,
            opp_skip: false,
        },
        logs: [],
        processing: false,
        finished: false,
        winner: null,
        pollInterval: null,
        timerInterval: null,

        init() {
            this.startTimer();
            this.poll();
            this.pollInterval = setInterval(() => this.poll(), 3000);
        },

        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timer = 30;
            this.timerInterval = setInterval(() => {
                if (this.finished) return;
                this.timer--;
                if (this.timer <= 0) this.timer = 0;
            }, 1000);
        },

        async playCard(card) {
            if (this.processing || this.energy < card.energy_cost) return;
            this.processing = true;

            try {
                const res = await fetch(`/battle/pvp/${this.battleId}/play`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ card_id: card.id }),
                });

                const data = await res.json();
                if (!data.success) {
                    alert(data.error || 'Gagal memainkan kartu.');
                } else {
                    this.state.my_pending = true;
                    // Immediate poll to check if both ready
                    setTimeout(() => this.poll(), 500);
                }
            } catch (e) {}

            this.processing = false;
        },

        async skipTurn() {
            if (this.processing) return;
            this.processing = true;

            try {
                const res = await fetch(`/battle/pvp/${this.battleId}/skip`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (data.success) {
                    this.state.my_skip = true;
                    setTimeout(() => this.poll(), 500);
                }
            } catch (e) {}

            this.processing = false;
        },

        async poll() {
            if (this.finished) return;

            try {
                const res = await fetch(`/battle/pvp/${this.battleId}/state`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                if (!res.ok) return;
                const data = await res.json();

                // Update HP
                this.hp1 = data.state.player1_hp;
                this.hp2 = data.state.player2_hp;
                this.energy = data.state.my_energy;

                const wasPending = this.state.my_pending;
                const wasSkip = this.state.my_skip;
                const prevTurn = this.state.turn_count;

                // Update state
                this.state.turn_count = data.state.turn_count;
                this.state.my_pending = data.state.my_pending;
                this.state.my_skip = data.state.my_skip;
                this.state.opp_pending = data.state.opp_pending;
                this.state.opp_skip = data.state.opp_skip;

                // New turn began → reset timer
                if (prevTurn !== data.state.turn_count || (wasPending || wasSkip) && !data.state.my_pending && !data.state.my_skip) {
                    this.startTimer();
                }

                // Update hand
                if (data.hand) this.hand = data.hand;

                // Logs
                if (data.logs) this.logs = data.logs;

                // Finished?
                if (data.status === 'completed' || data.status === 'forfeited') {
                    this.finished = true;
                    this.winner = data.state.player1_hp <= 0 ? 2 : (data.state.player2_hp <= 0 ? 1 : 0);
                    if (data.winner_id === this.myUserId) this.winner = this.mySlot;
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    if (this.timerInterval) clearInterval(this.timerInterval);
                }
            } catch (e) {}
        },
    };
}
</script>

@endsection