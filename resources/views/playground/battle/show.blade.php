@extends('playground.layouts.app')

@section('title', 'Battle PvE — DIGANOVA Playground')

@section('content')

<div x-data="battleApp({
        battleId: {{ $battle->id }},
        initialHp1: {{ $state->player1_hp }},
        initialHp2: {{ $state->player2_hp }},
        initialEnergy: {{ $state->player1_energy }},
        initialHand: {{ json_encode($state->player1_hand ?? []) }},
        initialLogs: {{ $recentLogs->map(fn($l) => [
            'slot' => $l->actor_user_id === auth()->id() ? 1 : 2,
            'card' => $l->card?->name,
            'description' => $l->description,
            'damage' => $l->damage_dealt,
            'heal' => $l->heal_amount,
            'turn' => $l->turn_number,
            'action' => $l->action_type,
        ])->toJson() }},
        deadline: '{{ $state->turn_deadline?->toIso8601String() }}',
        cardsData: {{ $orderedHand->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'type' => $c->type,
            'energy_cost' => $c->energy_cost,
            'damage' => $c->damage,
            'heal' => $c->heal,
            'block' => $c->block,
            'description' => $c->description,
            'effect_type' => $c->effect_type,
        ])->toJson() }},
     })"
     x-init="init()">

    {{-- Header --}}
    <div class="mb-4 flex justify-between items-start flex-wrap gap-2">
        <div>
            <a href="{{ route('play.home') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
                ← Lobby
            </a>
            <h1 class="text-xl font-bold text-white mt-1">Battle PvE</h1>
        </div>
        <form method="POST" action="{{ route('play.battle.forfeit', $battle) }}"
              onsubmit="return confirm('Yakin forfeit? Kamu akan kalah dan -30 poin.');">
            @csrf
            <button type="submit" class="text-xs text-red-400 hover:text-red-300 border border-red-500/30 px-3 py-1.5 rounded-lg">
                🏳️ Forfeit
            </button>
        </form>
    </div>

    {{-- HP Bars --}}
    <div class="grid grid-cols-2 gap-3 mb-4">
        {{-- Player --}}
        <div class="bg-slate-900 border border-cyan-500/30 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-cyan-400 uppercase tracking-wide">You</p>
                </div>
            </div>
            <div class="bg-slate-800 rounded-full h-4 overflow-hidden relative">
                <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-600 transition-all duration-500"
                     :style="`width: ${Math.max(0, hp1)}%`"></div>
                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                    <span x-text="Math.max(0, hp1)"></span> / 100
                </div>
            </div>
            <div class="flex items-center justify-between mt-2 text-xs">
                <span class="text-cyan-400">⚡ Energy: <strong x-text="energy"></strong></span>
                <span class="text-slate-500">Turn <strong x-text="turnCount"></strong></span>
            </div>
        </div>

        {{-- AI --}}
        <div class="bg-slate-900 border border-red-500/30 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center text-white text-lg">
                    🤖
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">AI Opponent</p>
                    <p class="text-[10px] text-red-400 uppercase tracking-wide">Enemy</p>
                </div>
            </div>
            <div class="bg-slate-800 rounded-full h-4 overflow-hidden relative">
                <div class="h-full bg-gradient-to-r from-red-500 to-orange-600 transition-all duration-500"
                     :style="`width: ${Math.max(0, hp2)}%`"></div>
                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                    <span x-text="Math.max(0, hp2)"></span> / 100
                </div>
            </div>
            <div class="flex items-center justify-between mt-2 text-xs">
                <span class="text-red-400">⚡ Energy: <strong x-text="energy2"></strong></span>
                <span class="text-slate-500">Mode: <strong>PvE</strong></span>
            </div>
        </div>
    </div>

    {{-- Timer + Status --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-3 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div :class="timer <= 10 ? 'text-red-400 animate-pulse' : 'text-cyan-400'"
                 class="text-lg font-bold font-mono">
                ⏱ <span x-text="timer"></span>s
            </div>
            <p class="text-xs text-slate-500" x-show="processing">(AI sedang berpikir...)</p>
        </div>
        <button type="button"
                @click="skipTurn()"
                :disabled="processing"
                class="text-xs bg-slate-800 hover:bg-slate-700 text-yellow-400 px-3 py-1.5 rounded-lg border border-yellow-500/30 transition disabled:opacity-40">
            ⏭ Skip Turn
        </button>
    </div>

    {{-- Battle Log --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-3 mb-4">
        <h3 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
            <span>📜 Battle Log</span>
            <span class="text-xs text-slate-500 font-normal">(20 terakhir)</span>
        </h3>
        <div class="max-h-40 overflow-y-auto space-y-1 text-xs" x-ref="logBox">
            <template x-for="(log, i) in visibleLogs" :key="'log-' + i">
                <div class="flex items-start gap-2 py-1 border-b border-slate-800/50 last:border-0">
                    <span :class="log.slot === 1 ? 'text-cyan-400' : 'text-red-400'" class="text-[10px] font-bold w-8 flex-shrink-0"
                          x-text="log.slot === 1 ? 'YOU' : 'AI'"></span>
                    <span class="text-slate-300 flex-1" x-text="log.description"></span>
                </div>
            </template>
            <p x-show="visibleLogs.length === 0" class="text-center text-slate-500 py-2">
                Battle baru dimulai
            </p>
        </div>
    </div>

    {{-- Hand --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-bold text-white">🎴 Kartu di Tangan (<span x-text="handCards.length"></span>)</h3>
            <p class="text-xs text-slate-500">Klik kartu untuk memainkan</p>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 gap-2">
            <template x-for="card in handCards" :key="card.id">
                <button type="button"
                        @click="playCard(card)"
                        :disabled="processing || energy < card.energy_cost || !isMyTurn"
                        :class="energy >= card.energy_cost && isMyTurn
                            ? 'border-slate-700 hover:border-cyan-500 hover:scale-105 cursor-pointer'
                            : 'border-slate-800 opacity-40 cursor-not-allowed'"
                        class="border-2 rounded-lg p-2 text-left transition bg-slate-800/50 relative">

                    {{-- Type icon --}}
                    <div class="text-center mb-1">
                        <span class="text-2xl">
                            <template x-if="card.type === 'attack'"><span>⚔️</span></template>
                            <template x-if="card.type === 'defense'"><span>🛡️</span></template>
                            <template x-if="card.type === 'skill'"><span>✨</span></template>
                        </span>
                    </div>

                    <p class="text-[11px] font-semibold text-white text-center leading-tight line-clamp-2 min-h-[1.75rem]" x-text="card.name"></p>

                    <div class="flex justify-between items-center mt-1 pt-1 border-t border-slate-700">
                        <span class="text-[10px] text-cyan-400">⚡<span x-text="card.energy_cost"></span></span>
                        <span class="text-[10px]"
                              :class="card.damage > 0 ? 'text-red-400' : (card.heal > 0 ? 'text-green-400' : (card.block > 0 ? 'text-blue-400' : 'text-slate-500'))">
                            <template x-if="card.damage > 0"><span x-text="'💥' + card.damage"></span></template>
                            <template x-if="card.heal > 0"><span x-text="'💚' + card.heal"></span></template>
                            <template x-if="card.block > 0"><span x-text="'🛡️' + card.block"></span></template>
                        </span>
                    </div>
                </button>
            </template>

            <template x-if="handCards.length === 0">
                <p class="col-span-full text-center text-slate-500 py-6 text-sm">
                    Tidak ada kartu di tangan. Deck habis?
                </p>
            </template>
        </div>
    </div>

    {{-- Result Modal --}}
    <div x-show="finished" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         :class="winner === 1 ? 'bg-green-500/30' : 'bg-red-500/30'">
        <div class="bg-slate-900 border-4 rounded-2xl shadow-2xl max-w-md w-full p-8 text-center"
             :class="winner === 1 ? 'border-green-500' : 'border-red-500'">

            <div class="text-7xl mb-3" x-text="winner === 1 ? '🏆' : '💀'"></div>
            <h2 class="text-3xl font-bold mb-2"
                :class="winner === 1 ? 'text-green-400' : 'text-red-400'"
                x-text="winner === 1 ? 'VICTORY!' : 'DEFEAT'"></h2>
            <p class="text-slate-400 text-sm mb-4"
               x-text="winner === 1 ? '+50 poin · rank naik' : '-20 poin"></p>

            <div class="bg-slate-800 rounded-lg p-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">HP Akhir</span>
                    <span class="font-bold text-white">
                        <span x-text="Math.max(0, hp1)"></span> vs <span x-text="Math.max(0, hp2)"></span>
                    </span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span class="text-slate-400">Turn</span>
                    <span class="font-bold text-white" x-text="turnCount"></span>
                </div>
            </div>

            <a href="{{ route('play.home') }}"
               class="inline-block bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white px-8 py-3 rounded-lg font-bold transition">
                Kembali ke Lobby
            </a>
        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important}</style>

<script>
function battleApp(config) {
    return {
        hp1: config.initialHp1,
        hp2: config.initialHp2,
        energy: config.initialEnergy,
        energy2: 3,
        turnCount: 1,
        handIds: config.initialHand || [],
        cardsData: config.cardsData || [],
        logs: config.initialLogs || [],
        processing: false,
        finished: false,
        winner: null,
        timer: 30,
        timerInterval: null,
        deadline: config.deadline,
        isMyTurn: true,

        get handCards() {
            return this.handIds
                .map(id => this.cardsData.find(c => c.id === id))
                .filter(Boolean);
        },

        get visibleLogs() {
            return this.logs.slice(-20);
        },

        init() {
            this.startTimer();
            this.$nextTick(() => this.scrollLog());
        },

        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timer = 30;
            this.timerInterval = setInterval(() => {
                if (this.finished || this.processing) return;
                this.timer--;
                if (this.timer <= 0) {
                    clearInterval(this.timerInterval);
                    this.skipTurn();
                }
            }, 1000);
        },

        resetTimer() {
            this.startTimer();
        },

        async playCard(card) {
            if (this.processing || this.finished || this.energy < card.energy_cost) return;
            this.processing = true;

            try {
                const res = await fetch(`/battle/${config.battleId}/play`, {
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
                    this.applyResult(data);
                }
            } catch (e) {
                alert('Error koneksi.');
            }

            this.processing = false;
        },

        async skipTurn() {
            if (this.processing || this.finished) return;
            this.processing = true;

            try {
                const res = await fetch(`/battle/${config.battleId}/skip`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                const data = await res.json();
                if (data.success) {
                    this.applyResult(data);
                }
            } catch (e) {}

            this.processing = false;
        },

        applyResult(data) {
            // Animate HP
            if (data.state) {
                this.hp1 = data.state.player1_hp;
                this.hp2 = data.state.player2_hp;
                this.energy = data.state.player1_energy;
                this.energy2 = data.state.player2_energy;
                this.handIds = data.state.player1_hand;
                this.turnCount = data.state.turn_count;
            }

            // Append logs
            if (data.logs) {
                data.logs.forEach(log => {
                    this.logs.push({
                        slot: log.slot,
                        card: log.card,
                        description: log.description,
                    });
                });
                this.$nextTick(() => this.scrollLog());
            }

            if (data.finished) {
                this.finished = true;
                this.winner = data.winner;
                if (this.timerInterval) clearInterval(this.timerInterval);
            } else {
                this.resetTimer();
            }
        },

        scrollLog() {
            if (this.$refs.logBox) {
                this.$refs.logBox.scrollTop = this.$refs.logBox.scrollHeight;
            }
        },
    };
}
</script>

@endsection