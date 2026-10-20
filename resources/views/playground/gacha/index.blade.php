@extends('playground.layouts.app')

@section('title', 'Gacha — DIGANOVA Playground')

@section('content')

<div x-data="gachaApp()">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex justify-between items-start flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-white">🎲 Gacha Kristal</h1>
                <p class="text-sm text-slate-400 mt-1">
                    Dapatkan kosmetik eksklusif — skin, emote, badge
                </p>
            </div>
            <div class="bg-slate-900 border border-cyan-500/30 rounded-lg px-4 py-2">
                <p class="text-xs text-slate-500">Poin Kamu</p>
                <p class="text-xl font-bold text-cyan-400 flex items-center gap-1">
                    ⭐ <span x-text="points">{{ auth()->user()->total_points }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Pull Buttons --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        {{-- Big Crystal Art --}}
        <div class="md:row-span-2 bg-gradient-to-br from-cyan-500/20 via-violet-500/20 to-pink-500/20 border-2 border-cyan-500/30 rounded-2xl p-6 flex flex-col items-center justify-center min-h-[300px] relative overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl animate-pulse"></div>
            </div>
            <div class="relative text-center">
                <div class="text-9xl animate-bounce-slow">💎</div>
                <p class="text-white font-bold mt-4">Crystal of Fortune</p>
                <p class="text-xs text-slate-400 mt-1">Kumpulkan semua item!</p>
            </div>
        </div>

        {{-- Pull 1x --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col">
            <div class="flex-1">
                <h3 class="font-bold text-white text-lg">Pull 1x</h3>
                <p class="text-xs text-slate-400 mt-1">Coba keberuntunganmu</p>
                <div class="mt-4 text-3xl font-bold text-cyan-400 flex items-center gap-1">
                    ⭐ 50
                </div>
                <p class="text-xs text-slate-500 mt-1">poin / pull</p>
            </div>
            <button type="button" @click="pull(1)"
                    :disabled="pulling || points < 50"
                    class="mt-4 w-full bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white px-4 py-3 rounded-lg font-bold transition disabled:opacity-40 disabled:cursor-not-allowed">
                <span x-show="!pulling">Pull Now</span>
                <span x-show="pulling">...</span>
            </button>
        </div>

        {{-- Pull 10x --}}
        <div class="bg-gradient-to-br from-yellow-500/10 to-orange-500/10 border-2 border-yellow-500/40 rounded-2xl p-6 flex flex-col relative">
            <div class="absolute -top-2 right-4 bg-yellow-500 text-black text-xs font-bold px-3 py-1 rounded-full">
                🔥 DISKON 10%
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-white text-lg">Pull 10x</h3>
                <p class="text-xs text-slate-400 mt-1">Dijamin dapat minimal 1 item Rare+</p>
                <div class="mt-4 text-3xl font-bold text-yellow-400 flex items-center gap-1">
                    ⭐ 450
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    poin <span class="line-through">500</span> · hemat 50!
                </p>
            </div>
            <button type="button" @click="pull(10)"
                    :disabled="pulling || points < 450"
                    class="mt-4 w-full bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white px-4 py-3 rounded-lg font-bold transition disabled:opacity-40 disabled:cursor-not-allowed">
                <span x-show="!pulling">Pull 10x</span>
                <span x-show="pulling">...</span>
            </button>
        </div>
    </div>

    {{-- Collection --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="font-bold text-white">📚 Koleksi Item</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $stats['total_owned'] }} / {{ $stats['total_items'] }} terkumpul
                    · {{ $stats['total_pulls'] }} total pull
                </p>
            </div>
            <a href="{{ route('play.gacha.history') }}"
               class="text-xs text-cyan-400 hover:text-cyan-300">
                Riwayat Pull →
            </a>
        </div>

        {{-- Progress --}}
        <div class="mb-4">
            <div class="bg-slate-800 rounded-full h-2 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-cyan-500 to-violet-600 transition-all"
                     style="width: {{ $stats['total_items'] > 0 ? round(($stats['total_owned'] / $stats['total_items']) * 100) : 0 }}%"></div>
            </div>
        </div>

        {{-- Item grid --}}
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
            @foreach ($allItems as $item)
                <x-gacha-item :item="$item" :owned="in_array($item->id, $ownedIds)" />
            @endforeach
        </div>
    </div>

    {{-- ============ RESULT MODAL ============ --}}
    <div x-show="showResult" x-cloak
         x-transition.opacity
         class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
         @click.self="closeResult()">

        <div class="bg-slate-900 border-2 border-cyan-500/50 rounded-2xl max-w-3xl w-full p-6 shadow-2xl relative">

            {{-- Close --}}
            <button type="button" @click="closeResult()"
                    class="absolute top-3 right-3 text-slate-400 hover:text-white text-2xl">
                ✕
            </button>

            {{-- Title --}}
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white">
                    <template x-if="results.length === 1">✨ Hasil Pull</template>
                    <template x-if="results.length > 1">🎉 10x Pull Results</template>
                </h2>
                <template x-if="duplicatePoints > 0">
                    <p class="text-xs text-green-400 mt-1">
                        +<span x-text="duplicatePoints"></span> poin dari duplikat
                    </p>
                </template>
            </div>

            {{-- Items --}}
            <div class="grid gap-3 justify-center mb-6"
                 :class="results.length === 1 ? 'grid-cols-1' : 'grid-cols-5'">

                <template x-for="(r, i) in results" :key="'pull-' + i">
                    <div x-show="revealed > i"
                         x-transition.scale.300ms
                         class="flex flex-col items-center">

                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-gradient-to-br border-2 flex items-center justify-center"
                             :class="{
                                'from-slate-500/30 to-slate-700/30 border-slate-500/40': r.rarity === 'common',
                                'from-blue-500/30 to-cyan-500/30 border-blue-500/50 shadow-lg shadow-blue-500/20': r.rarity === 'rare',
                                'from-purple-500/30 to-violet-600/30 border-purple-500/50 shadow-lg shadow-purple-500/30': r.rarity === 'epic',
                                'from-yellow-400/40 via-pink-500/30 to-violet-600/40 border-yellow-400/60 shadow-xl shadow-yellow-500/40': r.rarity === 'legendary',
                             }">
                            <span class="text-3xl sm:text-4xl"
                                  x-text="r.type === 'skin' ? '👕' : (r.type === 'emote' ? '😊' : '🏅')"></span>

                            {{-- Duplicate marker --}}
                            <template x-if="r.is_duplicate">
                                <div class="absolute -top-1 -right-1 bg-slate-700 text-slate-300 text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                                    DUP
                                </div>
                            </template>
                        </div>

                        <p class="text-xs text-white mt-1.5 text-center line-clamp-1" x-text="r.name"></p>
                        <p class="text-[10px] capitalize"
                           :class="{
                              'text-slate-300': r.rarity === 'common',
                              'text-blue-300': r.rarity === 'rare',
                              'text-purple-300': r.rarity === 'epic',
                              'text-yellow-300': r.rarity === 'legendary',
                           }"
                           x-text="r.rarity"></p>

                        <template x-if="r.is_duplicate">
                            <p class="text-[10px] text-green-400 mt-0.5">+<span x-text="r.refund"></span> poin</p>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 justify-center">
                <button type="button" @click="closeResult()"
                        class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-medium text-sm transition border border-slate-700">
                    Tutup
                </button>
                <a href="{{ route('play.inventory.index') }}"
                   class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white rounded-lg font-medium text-sm transition">
                    🎒 Lihat Inventory
                </a>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak]{display:none!important}
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}
.animate-bounce-slow {
    animation: bounce-slow 3s ease-in-out infinite;
}
</style>

<script>
function gachaApp() {
    return {
        points: {{ auth()->user()->total_points }},
        pulling: false,
        showResult: false,
        results: [],
        revealed: 0,
        duplicatePoints: 0,

        async pull(count) {
            if (this.pulling) return;
            const cost = count === 10 ? 450 : 50;
            if (this.points < cost) {
                alert('Poin tidak cukup!');
                return;
            }

            if (!confirm(`Pull ${count}x dengan ${cost} poin?`)) return;

            this.pulling = true;
            this.results = [];
            this.revealed = 0;
            this.duplicatePoints = 0;

            try {
                const res = await fetch('{{ route('play.gacha.pull') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ count }),
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.error || 'Gagal pull.');
                    this.pulling = false;
                    return;
                }

                this.results = data.results;
                this.duplicatePoints = data.duplicate_points || 0;
                this.points = data.new_points;
                this.showResult = true;

                // Reveal one by one
                for (let i = 0; i < this.results.length; i++) {
                    await new Promise(r => setTimeout(r, count === 1 ? 300 : 150));
                    this.revealed = i + 1;
                }

            } catch (e) {
                alert('Error: ' + e.message);
            }

            this.pulling = false;
        },

        closeResult() {
            this.showResult = false;
            // Reload page so collection updates
            setTimeout(() => window.location.reload(), 200);
        },
    };
}
</script>

@endsection