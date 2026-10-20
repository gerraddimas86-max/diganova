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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- LEFT: Card Pool --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Deck Name --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <label class="block text-sm font-medium text-slate-300 mb-1">Nama Deck</label>
                <input type="text" name="name" value="{{ old('name', $deck->name ?? '') }}"
                       required maxlength="50"
                       placeholder="Contoh: Deck Serangan Cepat"
                       class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 outline-none">
                @error('name')
                    <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Filter Tabs --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-2 flex gap-1">
                <button type="button" @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/50' : 'text-slate-400 hover:bg-slate-800 border-transparent'"
                        class="flex-1 px-3 py-2 text-sm font-medium rounded-lg border transition">
                    Semua
                </button>
                <button type="button" @click="filter = 'attack'"
                        :class="filter === 'attack' ? 'bg-red-500/20 text-red-300 border-red-500/50' : 'text-slate-400 hover:bg-slate-800 border-transparent'"
                        class="flex-1 px-3 py-2 text-sm font-medium rounded-lg border transition">
                    ⚔️ Attack
                </button>
                <button type="button" @click="filter = 'defense'"
                        :class="filter === 'defense' ? 'bg-blue-500/20 text-blue-300 border-blue-500/50' : 'text-slate-400 hover:bg-slate-800 border-transparent'"
                        class="flex-1 px-3 py-2 text-sm font-medium rounded-lg border transition">
                    🛡️ Defense
                </button>
                <button type="button" @click="filter = 'skill'"
                        :class="filter === 'skill' ? 'bg-violet-500/20 text-violet-300 border-violet-500/50' : 'text-slate-400 hover:bg-slate-800 border-transparent'"
                        class="flex-1 px-3 py-2 text-sm font-medium rounded-lg border transition">
                    ✨ Skill
                </button>
            </div>

            {{-- Card Grid --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-white text-sm">📚 Kartu Tersedia ({{ $allCards->count() }})</h3>
                    <p class="text-xs text-slate-500">Klik kartu untuk menambah / menghapus</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    <template x-for="card in filteredCards" :key="card.id">
                        <button type="button" @click="toggleCard(card.id)"
                                :class="isSelected(card.id)
                                    ? 'border-cyan-500 bg-cyan-500/10 ring-2 ring-cyan-500/30 scale-[0.97]'
                                    : 'border-slate-700 bg-slate-800/50 hover:border-slate-500 hover:bg-slate-800'"
                                class="border-2 rounded-lg p-2 text-left transition relative">

                            {{-- Selected check --}}
                            <div x-show="isSelected(card.id)"
                                 class="absolute top-1 right-1 w-5 h-5 bg-cyan-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                ✓
                            </div>

                            {{-- Type icon --}}
                            <div class="flex items-start gap-1.5 mb-1">
                                <span class="text-lg" x-text="card.type === 'attack' ? '⚔️' : (card.type === 'defense' ? '🛡️' : '✨')"></span>
                                <span class="text-xs font-semibold text-white leading-tight line-clamp-2" x-text="card.name"></span>
                            </div>

                            <div class="text-[10px] text-slate-400 space-y-0.5">
                                <p>⚡ <span x-text="card.energy_cost"></span> energy</p>
                                <template x-if="card.damage > 0">
                                    <p class="text-red-300">💥 <span x-text="card.damage"></span> dmg</p>
                                </template>
                                <template x-if="card.heal > 0">
                                    <p class="text-green-300">💚 <span x-text="card.heal"></span> heal</p>
                                </template>
                                <template x-if="card.block > 0">
                                    <p class="text-blue-300">🛡️ <span x-text="card.block"></span> block</p>
                                </template>
                            </div>
                        </button>
                    </template>
                </div>

                <template x-if="filteredCards.length === 0">
                    <p class="text-center text-slate-500 text-sm py-6">Tidak ada kartu di kategori ini</p>
                </template>
            </div>

            @error('cards')
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 text-sm text-red-300">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- RIGHT: Selected Cards --}}
        <div class="lg:col-span-1">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 sticky top-20">

                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-white text-sm">🎯 Deck Kamu</h3>
                    <span :class="selected.length === 10 ? 'bg-green-500/20 text-green-300 border-green-500/30' : 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30'"
                          class="text-xs px-2 py-0.5 rounded border font-bold">
                        <span x-text="selected.length"></span>/10
                    </span>
                </div>

                {{-- Progress bar --}}
                <div class="bg-slate-800 rounded-full h-2 overflow-hidden mb-4">
                    <div class="h-full bg-gradient-to-r from-cyan-500 to-violet-600 transition-all"
                         :style="'width: ' + (selected.length * 10) + '%'"></div>
                </div>

                {{-- Selected list --}}
                <div class="space-y-1 mb-4 max-h-[400px] overflow-y-auto">
                    <template x-for="id in selected" :key="'selected-' + id">
                        <div class="flex items-center gap-2 bg-slate-800 rounded-lg p-2">
                            <span class="text-lg" x-text="getCardType(id) === 'attack' ? '⚔️' : (getCardType(id) === 'defense' ? '🛡️' : '✨')"></span>
                            <p class="flex-1 text-xs text-white truncate" x-text="getCardName(id)"></p>
                            <button type="button" @click="toggleCard(id)"
                                    class="text-red-400 hover:text-red-300 px-1">
                                ✕
                            </button>
                        </div>
                    </template>

                    <template x-if="selected.length === 0">
                        <p class="text-xs text-slate-500 text-center py-6">
                            Belum ada kartu.<br>Klik kartu di sebelah kiri untuk menambah.
                        </p>
                    </template>
                </div>

                {{-- Status --}}
                <div x-show="selected.length === 10" class="bg-green-500/10 border border-green-500/30 rounded-lg p-2 mb-3">
                    <p class="text-xs text-green-300 text-center font-semibold">✓ Deck siap!</p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2">
                    <button type="submit"
                            :disabled="selected.length !== 10"
                            class="w-full bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white px-4 py-2.5 rounded-lg font-bold text-sm transition disabled:opacity-40 disabled:cursor-not-allowed">
                        💾 Simpan Deck
                    </button>
                    <a href="{{ route('play.decks.index') }}"
                       class="w-full text-center bg-slate-800 hover:bg-slate-700 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition border border-slate-700">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

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