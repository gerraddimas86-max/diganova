{{-- Partial form untuk create & edit deck --}}
<form method="POST" action="{{ $action }}"
      x-data="deckBuilder({
        initialSelected: {{ json_encode($selectedIds) }},
        allCards: {{ $allCards->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'type' => $c->type,
            'energy_cost' => $c->energy_cost,
            'damage' => $c->damage,
            'heal' => $c->heal,
            'block' => $c->block,
            'description' => $c->description,
            'effect_type' => $c->effect_type,
        ])->toJson() }}
      })">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Hidden input untuk cards --}}
    <template x-for="id in selected" :key="'input-' + id">
        <input type="hidden" name="cards[]" :value="id">
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Card Pool --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Deck Name --}}
            <div class="bg-[var(--bg-soft)] border border-[var(--line-strong)] rounded-xl p-5 shadow-[inset_0_0_0_1px_var(--line)]">
                <label class="block text-[11px] font-bold tracking-widest text-[var(--cyan)] uppercase mb-2">
                    Nama Deck
                </label>
                <input type="text" name="name" value="{{ old('name', $deck->name ?? '') }}"
                       required maxlength="50"
                       placeholder="Contoh: Deck Serangan Cepat"
                       class="w-full px-4 py-3 bg-[var(--panel)] border border-[var(--line-strong)] rounded-lg text-[var(--text)] placeholder-[var(--faint)] focus:ring-2 focus:ring-[var(--cyan)] focus:border-[var(--cyan)] outline-none transition-all">
                @error('name')
                    <p class="text-sm text-red-400 mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Filter Tabs --}}
            <div class="bg-[var(--panel)] border border-[var(--line)] rounded-xl p-1.5 flex gap-1">
                <button type="button" @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-[var(--cyan)]/20 text-[var(--cyan)] border-[var(--cyan)]/50' : 'text-[var(--muted)] hover:bg-[var(--bg-soft)] border-transparent'"
                        class="flex-1 px-3 py-2.5 text-xs font-bold tracking-wide uppercase rounded-lg border transition-all duration-200">
                    Semua
                </button>
                <button type="button" @click="filter = 'attack'"
                        :class="filter === 'attack' ? 'bg-red-500/20 text-red-400 border-red-500/50' : 'text-[var(--muted)] hover:bg-[var(--bg-soft)] border-transparent'"
                        class="flex-1 px-3 py-2.5 text-xs font-bold tracking-wide uppercase rounded-lg border transition-all duration-200">
                    ⚔️ Attack
                </button>
                <button type="button" @click="filter = 'defense'"
                        :class="filter === 'defense' ? 'bg-[var(--cyan)]/20 text-[var(--cyan)] border-[var(--cyan)]/50' : 'text-[var(--muted)] hover:bg-[var(--bg-soft)] border-transparent'"
                        class="flex-1 px-3 py-2.5 text-xs font-bold tracking-wide uppercase rounded-lg border transition-all duration-200">
                    🛡️ Defense
                </button>
                <button type="button" @click="filter = 'skill'"
                        :class="filter === 'skill' ? 'bg-purple-500/20 text-purple-400 border-purple-500/50' : 'text-[var(--muted)] hover:bg-[var(--bg-soft)] border-transparent'"
                        class="flex-1 px-3 py-2.5 text-xs font-bold tracking-wide uppercase rounded-lg border transition-all duration-200">
                    ✨ Skill
                </button>
            </div>

            {{-- Card Grid --}}
            <div class="bg-[var(--panel)] border border-[var(--line)] rounded-xl p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-[var(--text)] text-sm flex items-center gap-2">
                        <span class="text-[var(--cyan)]">📚</span> Kartu Tersedia ({{ $allCards->count() }})
                    </h3>
                    <p class="text-[10px] tracking-wide text-[var(--faint)] uppercase">Klik kartu untuk memilih</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <template x-for="card in filteredCards" :key="card.id">
                        <button type="button" @click="toggleCard(card.id)"
                                :class="isSelected(card.id)
                                    ? 'border-[var(--cyan)] bg-[var(--cyan)]/10 ring-2 ring-[var(--cyan)]/30 scale-[0.97] shadow-[0_0_15px_var(--glow)]'
                                    : 'border-[var(--line-strong)] bg-[var(--bg-soft)] hover:border-[var(--cyan)]/50 hover:bg-[var(--panel)]'"
                                class="border rounded-lg p-3 text-left transition-all duration-200 relative group overflow-hidden">

                            {{-- Selected check --}}
                            <div x-show="isSelected(card.id)"
                                 class="absolute top-2 right-2 w-5 h-5 bg-[var(--cyan)] rounded-full flex items-center justify-center text-[var(--cyan-ink)] text-xs font-black shadow-[0_0_10px_var(--cyan)] z-10">
                                ✓
                            </div>

                            {{-- Type icon --}}
                            <div class="flex items-start gap-2 mb-2 relative z-10">
                                <span class="text-xl drop-shadow-md" x-text="card.type === 'attack' ? '⚔️' : (card.type === 'defense' ? '🛡️' : '✨')"></span>
                                <span class="text-xs font-bold text-[var(--text)] leading-tight line-clamp-2" x-text="card.name"></span>
                            </div>

                            <div class="text-[10px] text-[var(--muted)] space-y-1 relative z-10">
                                <p class="flex items-center gap-1 text-[var(--cyan)]">
                                    <span>⚡</span> <span x-text="card.energy_cost"></span> Energy
                                </p>
                                <template x-if="card.damage > 0">
                                    <p class="text-red-400 flex items-center gap-1">
                                        <span>💥</span> <span x-text="card.damage"></span> Dmg
                                    </p>
                                </template>
                                <template x-if="card.heal > 0">
                                    <p class="text-emerald-400 flex items-center gap-1">
                                        <span>💚</span> <span x-text="card.heal"></span> Heal
                                    </p>
                                </template>
                                <template x-if="card.block > 0">
                                    <p class="text-sky-400 flex items-center gap-1">
                                        <span>🛡️</span> <span x-text="card.block"></span> Block
                                    </p>
                                </template>
                            </div>
                            
                            {{-- Hover effect glow --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-[var(--cyan)]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </button>
                    </template>
                </div>

                <template x-if="filteredCards.length === 0">
                    <p class="text-center text-[var(--faint)] text-sm py-8">Tidak ada kartu di kategori ini.</p>
                </template>
            </div>

            @error('cards')
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-sm text-red-400">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- RIGHT: Selected Cards --}}
        <div class="lg:col-span-1">
            <div class="bg-[var(--panel)] border border-[var(--line-strong)] rounded-xl p-5 sticky top-20 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)]">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-[var(--text)] text-sm flex items-center gap-2">
                        <span class="text-[var(--cyan)]">🎯</span> Deck Kamu
                    </h3>
                    <span :class="selected.length === 10 ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border-amber-500/30'"
                          class="text-xs px-2.5 py-1 rounded border font-bold font-mono">
                        <span x-text="selected.length"></span>/10
                    </span>
                </div>

                {{-- Progress bar --}}
                <div class="bg-[var(--bg-soft)] rounded-full h-2 overflow-hidden mb-5 border border-[var(--line)]">
                    <div class="h-full bg-[var(--cyan)] shadow-[0_0_10px_var(--cyan)] transition-all duration-300"
                         :style="'width: ' + (selected.length * 10) + '%'"></div>
                </div>

                {{-- Selected list --}}
                <div class="space-y-2 mb-5 max-h-[380px] overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="id in selected" :key="'selected-' + id">
                        <div class="flex items-center gap-3 bg-[var(--bg-soft)] border border-[var(--line-strong)] rounded-lg p-2.5">
                            <span class="text-lg drop-shadow-md" x-text="getCardType(id) === 'attack' ? '⚔️' : (getCardType(id) === 'defense' ? '🛡️' : '✨')"></span>
                            <p class="flex-1 text-xs font-semibold text-[var(--text)] truncate" x-text="getCardName(id)"></p>
                            <button type="button" @click="toggleCard(id)"
                                    class="text-[var(--faint)] hover:text-red-400 hover:bg-red-400/10 w-6 h-6 rounded flex items-center justify-center transition-colors">
                                ✕
                            </button>
                        </div>
                    </template>

                    <template x-if="selected.length === 0">
                        <div class="text-center py-8 border border-dashed border-[var(--line-strong)] rounded-lg bg-[var(--bg-soft)]">
                            <svg class="w-8 h-8 text-[var(--faint)] mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <p class="text-xs text-[var(--muted)]">
                                Belum ada kartu.<br>Klik kartu di sebelah kiri.
                            </p>
                        </div>
                    </template>
                </div>

                {{-- Status --}}
                <div x-show="selected.length === 10" class="bg-emerald-500/10 border border-emerald-500/30 rounded-lg p-3 mb-4 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-xs text-emerald-400 font-bold tracking-wide uppercase">Deck Siap!</p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3 mt-6">
                    <button type="submit"
                            :disabled="selected.length !== 10"
                            class="w-full relative overflow-hidden bg-[var(--cyan)] text-[var(--cyan-ink)] px-4 py-3 rounded-lg font-bold text-sm tracking-wide uppercase transition-all disabled:opacity-40 disabled:cursor-not-allowed hover:shadow-[0_0_20px_var(--glow)] group">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Deck
                        </span>
                        <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:animate-[shimmer_1s_forwards] skew-x-12"></div>
                    </button>
                    <a href="{{ route('play.decks.index') }}"
                       class="w-full text-center bg-transparent hover:bg-[var(--bg-soft)] text-[var(--muted)] hover:text-[var(--text)] px-4 py-3 rounded-lg font-medium text-sm transition-colors border border-[var(--line-strong)]">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
/* Scrollbar kustom untuk list di kanan */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: var(--bg-soft);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: var(--line-strong);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: var(--cyan);
}
@keyframes shimmer {
    100% { transform: translateX(100%); }
}
</style>

<script>
function deckBuilder(config) {
    return {
        selected: config.initialSelected || [],
        allCards: config.allCards || [],
        filter: 'all',

        get filteredCards() {
            if (this.filter === 'all') return this.allCards;
            return this.allCards.filter(c => c.type === this.filter);
        },

        isSelected(id) {
            return this.selected.includes(id);
        },

        toggleCard(id) {
            const idx = this.selected.indexOf(id);
            if (idx > -1) {
                this.selected.splice(idx, 1);
            } else {
                if (this.selected.length >= 10) {
                    alert('Deck sudah berisi 10 kartu. Hapus salah satu untuk menambah.');
                    return;
                }
                this.selected.push(id);
            }
        },

        getCardName(id) {
            const c = this.allCards.find(c => c.id === id);
            return c ? c.name : '';
        },

        getCardType(id) {
            const c = this.allCards.find(c => c.id === id);
            return c ? c.type : '';
        },
    };
}
</script>