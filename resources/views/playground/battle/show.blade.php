@extends('playground.layouts.app')

@section('title', 'Duel PvE — DIGANOVA Playground')

@section('content')

<div
    class="duel-arena"
    x-data="battleApp({
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
        allCardsData: {{ $orderedHand->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'type' => $c->type,
            'energy_cost' => $c->energy_cost,
            'damage' => $c->damage,
            'heal' => $c->heal,
            'block' => $c->block,
            'description' => $c->description,
        ])->toJson() }},
    })"
    x-init="init()"
    @keydown.escape.window="if(!showTutorial) selectedCard = null"
>
    {{-- Background Pattern --}}
    <div class="duel-bg" aria-hidden="true">
        <div class="duel-grid"></div>
        <div class="duel-orb duel-orb-blue"></div>
        <div class="duel-orb duel-orb-purple"></div>
    </div>

    <div class="duel-content">

        {{-- HEADER --}}
        <header class="duel-header">
            <div class="header-left">
                <a href="{{ route('play.home') }}" class="btn-back" title="Kembali ke Lobby">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
            
            <div class="header-right">
                <button type="button" class="btn-restart" @click="confirmRestart()" title="Mulai Ulang Duel">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1.02 6.72 2.78L21 8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 3v5h-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button type="button" class="btn-help" @click="openTutorial()" title="Cara Main">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <form method="POST" action="{{ route('play.battle.forfeit', $battle) }}" onsubmit="return confirm('Yakin ingin menyerah?');">
                    @csrf
                    <button type="submit" class="btn-forfeit" title="Menyerah">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M5 21V4m0 0h12l-2.5 4L17 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- MAIN DUEL BOARD (YGO STYLE) --}}
        <div class="duel-board" :class="{ 'shake': screenShake }">
            
            {{-- TOP ZONE: ENEMY --}}
            <div class="board-zone zone-enemy">
                {{-- Panel Kiri (Karakter Musuh) --}}
                <div class="duelist-panel enemy-panel">
                    <div class="avatar-row">
                        <div class="avatar">
                            <svg viewBox="0 0 24 24" fill="none"><rect x="4" y="7" width="16" height="12" rx="3" stroke="currentColor" stroke-width="1.7"/><path d="M12 3v4M9 12h.01M15 12h.01M9 16h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </div>
                        <div class="nameplate">
                            <span class="name" x-text="aiName">Randy</span>
                            <div class="lp-container">
                                <span class="lp-label">LP</span>
                                <span class="lp-value" x-text="Math.max(0, hp2)"></span>
                            </div>
                        </div>
                    </div>
                    <div class="hand-deck-info">
                        <div class="info-badge"><svg viewBox="0 0 24 24" fill="none"><rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.7"/></svg> <span>5</span></div>
                        <div class="info-badge"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2 4 6v12l8 4 8-4V6l-8-4Z" stroke="currentColor" stroke-width="1.7"/></svg> <span>20</span></div>
                    </div>
                </div>

                {{-- Monster & Spell Zones (Musuh) --}}
                <div class="play-mat-area">
                    <div class="play-mat">
                        <div class="zone-row">
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                        </div>
                        <div class="zone-row monster-row">
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                        </div>
                    </div>
                </div>

                <div class="floating-text" x-show="combatTextTarget === 'enemy' && combatText" x-cloak x-text="combatText" :class="combatTextType"></div>
            </div>

            {{-- CENTER ZONE: BATTLEFIELD & PHASES --}}
            <div class="board-center">
                {{-- Phase Indicators --}}
                <div class="phase-tracker">
                    <div class="phase">DP</div>
                    <div class="phase">SP</div>
                    <div class="phase active">MP1</div>
                    <div class="phase">BP</div>
                    <div class="phase">MP2</div>
                    <div class="phase">EP</div>
                </div>

                {{-- YUGIOH SUMMON ANIMATION LAYER --}}
                <div class="summon-layer" aria-hidden="true">
                    <div x-show="summonedCard.active && summonedCard.actor === 2" x-cloak :key="'ai-sum-'+summonedCard.key" class="ygo-summon" :class="[summonedCard.type, 'enemy-summon']">
                        <div class="ygo-inner">
                            <div class="ygo-top"><span class="ygo-name" x-text="summonedCard.name"></span></div>
                            <div class="ygo-art">
                                <template x-if="summonedCard.type === 'attack'"><svg viewBox="0 0 64 64" fill="none"><path d="m13 48 30-30 8 8-30 30-8-8Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="m37 15 7-7 12 12-7 7M8 55l8-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></template>
                                <template x-if="summonedCard.type === 'defense'"><svg viewBox="0 0 64 64" fill="none"><path d="M32 7 52 15v14c0 13-8 22-20 28C20 51 12 42 12 29V15L32 7Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="m22 32 7 7 14-15" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></template>
                                <template x-if="summonedCard.type === 'skill'"><svg viewBox="0 0 64 64" fill="none"><path d="m32 5 5 18 18 5-18 5-5 18-5-18-18-5 18-5 5-18Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg></template>
                            </div>
                        </div>
                    </div>

                    <div x-show="summonedCard.active && summonedCard.actor === 1" x-cloak :key="'pl-sum-'+summonedCard.key" class="ygo-summon" :class="[summonedCard.type, 'player-summon']">
                        <div class="ygo-inner">
                            <div class="ygo-top"><span class="ygo-name" x-text="summonedCard.name"></span></div>
                            <div class="ygo-art">
                                <template x-if="summonedCard.type === 'attack'"><svg viewBox="0 0 64 64" fill="none"><path d="m13 48 30-30 8 8-30 30-8-8Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="m37 15 7-7 12 12-7 7M8 55l8-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></template>
                                <template x-if="summonedCard.type === 'defense'"><svg viewBox="0 0 64 64" fill="none"><path d="M32 7 52 15v14c0 13-8 22-20 28C20 51 12 42 12 29V15L32 7Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="m22 32 7 7 14-15" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></template>
                                <template x-if="summonedCard.type === 'skill'"><svg viewBox="0 0 64 64" fill="none"><path d="m32 5 5 18 18 5-18 5-5 18-5-18-18-5 18-5 5-18Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg></template>
                            </div>
                        </div>
                    </div>

                    <div x-show="projectile.active" x-cloak :key="'proj-'+projectile.key" class="duel-projectile" :class="projectile.direction">
                        <span class="proj-core"></span>
                        <span class="proj-trail"></span>
                    </div>

                    <div x-show="impact.active" x-cloak :key="'imp-'+impact.key" class="duel-impact" :class="impact.direction">
                        <span></span>
                    </div>
                </div>
            </div>

            {{-- BOTTOM ZONE: PLAYER --}}
            <div class="board-zone zone-player">
                {{-- Panel Kiri (Karakter Pemain) --}}
                <div class="duelist-panel player-panel">
                    <div class="avatar-row">
                        <div class="avatar player-av">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="nameplate">
                            <span class="name">{{ auth()->user()->name }}</span>
                            <div class="lp-container">
                                <span class="lp-label">LP</span>
                                <span class="lp-value" x-text="Math.max(0, hp1)"></span>
                            </div>
                        </div>
                    </div>
                    <div class="hand-deck-info">
                        <div class="info-badge deck-clickable" @click="drawCard()" title="Klik Deck untuk Draw">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 2 4 6v12l8 4 8-4V6l-8-4Z" stroke="currentColor" stroke-width="1.7"/></svg> 
                            <span x-text="deckCount"></span>
                        </div>
                        <div class="info-badge mana-badge">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M13 2 4 14h7l-1 8 10-13h-7V2Z" stroke="currentColor" stroke-width="1.7"/></svg> 
                            <span x-text="energy"></span>
                        </div>
                    </div>
                </div>

                {{-- Monster & Spell Zones (Pemain) --}}
                <div class="play-mat-area">
                    <div class="play-mat">
                        <div class="zone-row monster-row">
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                            <div class="zone-slot monster-slot"></div>
                        </div>
                        <div class="zone-row">
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                            <div class="zone-slot spell-slot"></div>
                        </div>
                    </div>
                </div>

                <div class="floating-text" x-show="combatTextTarget === 'player' && combatText" x-cloak x-text="combatText" :class="combatTextType"></div>
            </div>
            
        </div>

        {{-- CONTROL & HAND UI --}}
        <div class="duel-bottom-ui">
            
            {{-- Turn Info --}}
            <div class="turn-panel">
                <div class="turn-status">
                    <span class="status-dot" :class="{ 'working': processing, 'my-turn': isMyTurn && !processing }"></span>
                    <div>
                        <strong x-text="processing ? 'RESOLVING...' : (isMyTurn ? 'YOUR TURN' : 'AI TURN')"></strong>
                        <p x-text="processing ? 'Memproses duel...' : (isMyTurn ? 'Pilih kartu.' : 'Menunggu AI...')"></p>
                    </div>
                </div>
                <div class="turn-timer-row">
                    <div class="turn-timer" :class="{ 'danger': timer <= 10 }">
                        <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="13" r="8" stroke="currentColor" stroke-width="1.8"/><path d="M12 9v4l3 2M9 2h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <span x-text="timer"></span>s
                    </div>
                    <button type="button" @click="skipTurn()" :disabled="processing || finished" class="btn-skip">SKIP</button>
                </div>
            </div>

            {{-- Log (Samping) --}}
            <div class="duel-log">
                <div class="log-scroll" x-ref="logBox">
                    <template x-for="(log, i) in visibleLogs" :key="'log-'+i">
                        <div class="log-item" :class="log.slot === 1 ? 'is-player' : 'is-enemy'">
                            <span class="log-who" x-text="log.slot === 1 ? 'YOU' : 'AI'"></span>
                            <span class="log-text" x-text="log.description"></span>
                            <span class="log-val" x-show="log.damage > 0" x-text="'-'+log.damage"></span>
                        </div>
                    </template>
                    <div x-show="visibleLogs.length === 0" class="log-empty">Menunggu aksi...</div>
                </div>
            </div>

            {{-- Hand (Kartu di Tangan) --}}
            <div class="duel-hand">
                <div class="hand-cards">
                    <template x-for="card in handCards" :key="card.uid">
                        <button
                            type="button"
                            @click="playCard(card)"
                            :disabled="processing || finished || energy < card.energy_cost || !isMyTurn"
                            class="tcg-card"
                            :class="[
                                'type-' + card.type,
                                energy >= card.energy_cost && isMyTurn && !processing && !finished ? 'is-playable' : 'is-locked'
                            ]"
                        >
                            <div class="tcg-top">
                                <span class="tcg-type" x-text="card.type"></span>
                                <span class="tcg-cost">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M13 2 4 14h7l-1 8 10-13h-7V2Z" stroke="currentColor" stroke-width="2"/></svg>
                                    <span x-text="card.energy_cost"></span>
                                </span>
                            </div>
                            
                            <div class="tcg-art">
                                <template x-if="card.type === 'attack'"><svg viewBox="0 0 64 64" fill="none"><path d="m13 48 30-30 8 8-30 30-8-8Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="m37 15 7-7 12 12-7 7M8 55l8-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg></template>
                                <template x-if="card.type === 'defense'"><svg viewBox="0 0 64 64" fill="none"><path d="M32 7 52 15v14c0 13-8 22-20 28C20 51 12 42 12 29V15L32 7Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="m22 32 7 7 14-15" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></template>
                                <template x-if="card.type === 'skill'"><svg viewBox="0 0 64 64" fill="none"><path d="m32 5 5 18 18 5-18 5-5 18-5-18-18-5 18-5 5-18Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg></template>
                            </div>

                            <div class="tcg-info">
                                <h4 x-text="card.name"></h4>
                                <p x-text="card.description"></p>
                            </div>

                            <div class="tcg-stats">
                                <template x-if="card.damage > 0"><span class="s-dmg">ATK <b x-text="card.damage"></b></span></template>
                                <template x-if="card.heal > 0"><span class="s-heal">HEAL <b x-text="card.heal"></b></span></template>
                                <template x-if="card.block > 0"><span class="s-block">DEF <b x-text="card.block"></b></span></template>
                            </div>
                        </button>
                    </template>
                    <div x-show="handCards.length === 0" class="empty-hand">
                        Kartu habis. Klik <b>DECK</b> di kiri untuk menarik kartu acak!
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ==================== PRE-GAME MODAL (STARTER DECK) ==================== --}}
    <div x-show="showPreGame" x-cloak class="duel-overlay" style="z-index: 2000;">
        <div class="tutorial-modal" style="width: min(100%, 500px); text-align: center;">
            <div class="modal-top-line"></div>
            
            <h2 class="t-title" style="font-size: 24px; color: var(--c-cyan);">PILIH STARTER DECK</h2>
            <p class="t-desc" style="margin-bottom: 20px;">Pilih strategi awalmu sebelum duel dimulai.</p>

            <div class="starter-grid">
                <button class="starter-card" @click="startDuel('aggressive')">
                    <div class="s-icon" style="color: #FF8999;"><svg viewBox="0 0 64 64" fill="none"><path d="m13 48 30-30 8 8-30 30-8-8Z" stroke="currentColor" stroke-width="2.5"/></svg></div>
                    <h4>Aggressive</h4>
                    <p>Fokus pada serangan besar dan damage cepat.</p>
                </button>
                
                <button class="starter-card" @click="startDuel('defensive')">
                    <div class="s-icon" style="color: #8A8CFF;"><svg viewBox="0 0 64 64" fill="none"><path d="M32 7 52 15v14c0 13-8 22-20 28C20 51 12 42 12 29V15L32 7Z" stroke="currentColor" stroke-width="2.5"/></svg></div>
                    <h4>Defensive</h4>
                    <p>Bertahan, curi kesempatan, dan balas dengan aman.</p>
                </button>

                <button class="starter-card" @click="startDuel('balanced')">
                    <div class="s-icon" style="color: var(--c-cyan);"><svg viewBox="0 0 64 64" fill="none"><path d="m32 5 5 18 18 5-18 5-5 18-5-18-18-5 18-5 5-18Z" stroke="currentColor" stroke-width="2.5"/></svg></div>
                    <h4>Balanced</h4>
                    <p>Campuran antara serangan dan pertahanan.</p>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== TUTORIAL MODAL ==================== --}}
    <div x-show="showTutorial" x-cloak x-transition.opacity.duration.250ms class="duel-overlay" @keydown.escape.window="closeTutorial()">
        <div class="tutorial-modal" x-show="showTutorial" x-transition.scale.duration.300ms @click.outside="closeTutorial()">
            <div class="modal-top-line"></div>
            <button type="button" class="modal-close" @click="closeTutorial()">&times;</button>

            <div class="tutorial-progress">
                <template x-for="(step, idx) in tutorialSteps" :key="'dot-' + idx">
                    <span class="t-dot" :class="{ on: tutorialStep === idx, done: tutorialStep > idx }"></span>
                </template>
            </div>

            <div class="tutorial-content">
                <div class="t-icon" :class="'icon-' + tutorialSteps[tutorialStep].key">
                    <template x-if="tutorialSteps[tutorialStep].key === 'welcome'"><svg viewBox="0 0 64 64" fill="none"><path d="M32 7 52 15v14c0 13-8 22-20 28C20 51 12 42 12 29V15L32 7Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="m22 32 7 7 14-15" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></template>
                    <template x-if="tutorialSteps[tutorialStep].key === 'hp'"><svg viewBox="0 0 64 64" fill="none"><path d="M12 28v-8a6 6 0 0 1 6-6h28a6 6 0 0 1 6 6v8M8 28h48v24a6 6 0 0 1-6 6H14a6 6 0 0 1-6-6V28Z" stroke="currentColor" stroke-width="2.5"/><path d="M32 38v10M26 43h12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></template>
                    <template x-if="tutorialSteps[tutorialStep].key === 'mana'"><svg viewBox="0 0 64 64" fill="none"><path d="M34 6 12 36h16l-2 22 26-30H36l-2-22Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg></template>
                    <template x-if="tutorialSteps[tutorialStep].key === 'play'"><svg viewBox="0 0 64 64" fill="none"><rect x="14" y="10" width="36" height="44" rx="4" stroke="currentColor" stroke-width="2.5"/><path d="M22 24h20M22 32h20M22 40h12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></template>
                </div>

                <p class="t-kicker" x-text="'STEP ' + (tutorialStep + 1) + ' / ' + tutorialSteps.length"></p>
                <h3 class="t-title" x-text="tutorialSteps[tutorialStep].title"></h3>
                <p class="t-desc" x-text="tutorialSteps[tutorialStep].desc"></p>

                <div class="t-tip" x-show="tutorialSteps[tutorialStep].tip">
                    <span class="tip-badge">TIPS</span>
                    <span x-text="tutorialSteps[tutorialStep].tip"></span>
                </div>
            </div>

            <div class="tutorial-actions">
                <button type="button" class="btn-ghost" @click="prevTutorialStep()" :disabled="tutorialStep === 0" x-show="tutorialStep > 0">← Kembali</button>
                <button type="button" class="btn-ghost" @click="closeTutorial()" x-show="tutorialStep === 0" style="margin-right:auto;">Lewati</button>
                <button type="button" class="btn-primary" @click="nextTutorialStep()">
                    <span x-show="tutorialStep < tutorialSteps.length - 1">Lanjut →</span>
                    <span x-show="tutorialStep === tutorialSteps.length - 1">Mengerti! ⚔️</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== RESTART CONFIRM MODAL ==================== --}}
    <div x-show="showRestartConfirm" x-cloak x-transition.opacity.duration.200ms class="duel-overlay" style="z-index: 900;">
        <div class="tutorial-modal" style="width: min(100%, 380px);" x-show="showRestartConfirm" x-transition.scale.duration.250ms @click.outside="showRestartConfirm = false">
            <div class="modal-top-line" style="background: linear-gradient(90deg, #FFD76A, #E7B02A, #FFD76A);"></div>
            
            <div class="tutorial-content">
                <div class="t-icon" style="color: #FFD76A; border-color: rgba(255, 215, 106, 0.3); background: rgba(255, 215, 106, 0.1);">
                    <svg viewBox="0 0 64 64" fill="none"><path d="M21 12a9 9 0 0 0-9 9v22a9 9 0 0 0 9 9h22a9 9 0 0 0 9-9V21a9 9 0 0 0-9-9H21ZM32 20v24M20 32h24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                </div>

                <h3 class="t-title" style="color: #FFD76A;">RESTART DUEL?</h3>
                <p class="t-desc">Pertarungan saat ini akan diulang dari awal. Semua HP, Mana, dan kartu di tanganmu akan di-reset.</p>
            </div>

            <div class="tutorial-actions" style="justify-content: center;">
                <button type="button" class="btn-ghost" @click="showRestartConfirm = false">BATAL</button>
                <button type="button" class="btn-primary" style="background: linear-gradient(100deg, #E7B02A, #FFD76A); border-color: #FFD76A; color: #1A1200;" @click="restartDuel()">YA, RESTART</button>
            </div>
        </div>
    </div>

    {{-- ==================== RESULT MODAL ==================== --}}
    <div x-show="finished" x-cloak x-transition.opacity.duration.500ms class="result-overlay" :class="winner === 1 ? 'res-victory' : 'res-defeat'">
        
        <div class="res-particles" aria-hidden="true">
            <div class="particle p1"></div>
            <div class="particle p2"></div>
            <div class="particle p3"></div>
            <div class="particle p4"></div>
            <div class="particle p5"></div>
        </div>

        <div class="res-content" x-transition.scale.duration.600ms>
            <div class="res-icon-wrap" :class="winner === 1 ? 'icon-vic' : 'icon-def'">
                <template x-if="winner === 1">
                    <svg viewBox="0 0 64 64" fill="none"><path d="M20 9h24v13c0 11-5 18-12 18s-12-7-12-18V9Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M20 15H9v7c0 7 5 11 12 11M44 15h11v7c0 7-5 11-12 11M32 40v9m-10 6h20m-17-6h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                </template>
                <template x-if="winner !== 1">
                    <svg viewBox="0 0 64 64" fill="none"><circle cx="32" cy="32" r="23" stroke="currentColor" stroke-width="2.5"/><path d="m23 23 18 18m0-18L23 41" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                </template>
            </div>

            <div class="res-text-wrap">
                <p class="res-sub" x-text="winner === 1 ? 'DUEL WON' : 'DUEL LOST'"></p>
                <h1 class="res-title" :class="winner === 1 ? 'text-vic' : 'text-def'" x-text="winner === 1 ? 'VICTORY' : 'DEFEAT'"></h1>
                <p class="res-desc" x-text="winner === 1 ? 'Kamu berhasil mengalahkan AI dan menguasai arena!' : 'Jangan menyerah, susun strategi baru dan coba lagi.'"></p>
            </div>

            <div class="res-stats">
                <div class="stat-box"><span>YOUR LP</span><strong x-text="Math.max(0, hp1)"></strong></div>
                <div class="stat-vs">VS</div>
                <div class="stat-box"><span>ENEMY LP</span><strong x-text="Math.max(0, hp2)"></strong></div>
            </div>

            <div style="display:flex; gap:15px; flex-wrap:wrap; justify-content:center;">
                <button @click="restartDuel()" class="res-btn" style="background:transparent; border:2px solid #8793B7; color:#8793B7;">
                    DUEL LAGI
                </button>
                <a href="{{ route('play.home') }}" class="res-btn" :class="winner === 1 ? 'btn-vic' : 'btn-def'">
                    KEMBALI
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }

    .duel-arena {
        --c-bg: #030612;
        --c-navy: #090E39;
        --c-purple: #4647AE;
        --c-blue: #4382DF;
        --c-cyan: #22E4FF;
        --c-white: #FFFFFF;
        --c-border: rgba(67, 130, 223, 0.3);
        
        position: relative;
        isolation: isolate;
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        color: var(--c-white);
        font-family: 'Rajdhani', 'Segoe UI', system-ui, sans-serif;
        background-color: var(--c-bg);
    }

    .duel-arena * { box-sizing: border-box; }

    /* Background */
    .duel-bg { position: absolute; inset: 0; z-index: -1; overflow: hidden; }
    .duel-grid {
        position: absolute; inset: 0;
        background-image: linear-gradient(var(--c-border) 1px, transparent 1px), linear-gradient(90deg, var(--c-border) 1px, transparent 1px);
        background-size: 50px 50px;
        opacity: 0.4;
        mask-image: radial-gradient(circle at center, black 20%, transparent 80%);
        -webkit-mask-image: radial-gradient(circle at center, black 20%, transparent 80%);
    }
    .duel-orb { position: absolute; width: 400px; height: 400px; border-radius: 50%; filter: blur(100px); opacity: 0.15; }
    .duel-orb-blue { top: -100px; left: -100px; background: var(--c-blue); }
    .duel-orb-purple { bottom: -100px; right: -100px; background: var(--c-purple); }

    .duel-content { display: flex; flex-direction: column; height: 100%; position: relative; }

    /* Header (Absolute di atas) */
    .duel-header { position: absolute; top: 10px; left: 10px; right: 10px; display: flex; justify-content: space-between; z-index: 100; pointer-events: none; }
    .header-right { display: flex; gap: 8px; pointer-events: auto; }
    
    .btn-back, .btn-restart, .btn-help, .btn-forfeit { 
        display: flex; align-items: center; justify-content: center; 
        width: 36px; height: 36px; border-radius: 8px; 
        cursor: pointer; transition: 0.2s; backdrop-filter: blur(5px);
    }
    .btn-back { border: 1px solid var(--c-blue); background: rgba(67, 130, 223, 0.2); color: var(--c-cyan); }
    .btn-restart { border: 1px solid rgba(255, 215, 106, 0.6); background: rgba(255, 215, 106, 0.2); color: #FFD76A; }
    .btn-help { border: 1px solid var(--c-border); background: rgba(9, 14, 57, 0.8); color: #8793B7; }
    .btn-forfeit { border: 1px solid rgba(255, 80, 100, 0.4); background: rgba(255, 80, 100, 0.2); color: #FF8999; }
    
    .btn-back:hover, .btn-restart:hover, .btn-help:hover, .btn-forfeit:hover { transform: translateY(-2px); filter: brightness(1.2); }
    .btn-back svg, .btn-restart svg, .btn-help svg, .btn-forfeit svg { width: 18px; height: 18px; }

    /* DUEL BOARD LAYOUT (YGO Style) */
    .duel-board {
        display: flex;
        flex-direction: column;
        flex: 1;
        position: relative;
        padding: 55px 0 20px;
    }

    .board-zone { display: flex; align-items: center; flex: 1; position: relative; padding: 0 20px; gap: 20px; }
    .zone-enemy { align-items: flex-start; }
    .zone-player { align-items: flex-end; }

    /* Duelist Panel (Kotak Karakter) */
    .duelist-panel { 
        width: 180px; background: rgba(9, 14, 57, 0.85); border: 1px solid var(--c-border); 
        border-radius: 10px; padding: 10px; display: flex; flex-direction: column; gap: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.5); backdrop-filter: blur(10px); flex-shrink: 0;
    }
    .zone-enemy .duelist-panel { border-top: 3px solid #8A8CFF; }
    .zone-player .duelist-panel { border-bottom: 3px solid var(--c-cyan); }

    .avatar-row { display: flex; gap: 10px; align-items: center; }
    .avatar { width: 45px; height: 45px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #112E81; border: 1px solid #8A8CFF; }
    .avatar.player-av { background: #090E39; border-color: var(--c-cyan); font-size: 20px; font-weight: 900; color: white; }
    .avatar svg { width: 26px; height: 26px; color: #C3C8FF; }
    
    .nameplate { flex: 1; overflow: hidden; }
    .name { display: block; font-size: 13px; font-weight: 800; color: white; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
    .lp-container { display: flex; align-items: baseline; gap: 4px; margin-top: 2px; }
    .lp-label { font-size: 9px; font-weight: 900; color: rgba(255,255,255,0.5); }
    .lp-value { font-size: 18px; font-weight: 900; font-family: 'Orbitron', sans-serif; color: var(--c-cyan); text-shadow: 0 0 10px rgba(34, 228, 255, 0.5); }
    .zone-enemy .lp-value { color: #8A8CFF; text-shadow: 0 0 10px rgba(138, 140, 255, 0.5); }

    .hand-deck-info { display: flex; gap: 8px; }
    .info-badge { flex: 1; display: flex; align-items: center; justify-content: center; gap: 5px; padding: 4px; border-radius: 4px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-size: 11px; font-weight: 800; color: #B9C2DA; }
    .info-badge svg { width: 14px; height: 14px; }
    .info-badge.mana-badge { color: var(--c-cyan); border-color: rgba(34, 228, 255, 0.3); background: rgba(34, 228, 255, 0.1); }
    .info-badge.deck-clickable { cursor: pointer; transition: 0.2s; }
    .info-badge.deck-clickable:hover { background: rgba(255,255,255,0.1); border-color: var(--c-blue); color: white; box-shadow: 0 0 10px var(--c-blue); }

    /* Play Mat (Zona Kartu) */
    .play-mat-area { flex: 1; display: flex; justify-content: center; align-items: center; }
    .play-mat { 
        display: flex; flex-direction: column; gap: 10px; 
        background: rgba(0,0,0,0.3); padding: 15px; border-radius: 16px; 
        border: 1px solid rgba(67, 130, 223, 0.15); 
    }
    .zone-row { display: flex; gap: 10px; justify-content: center; }
    .zone-slot { 
        width: 70px; height: 90px; border-radius: 8px; 
        border: 1px dashed rgba(67, 130, 223, 0.3); 
        background: rgba(17, 46, 129, 0.1); 
        position: relative; transition: 0.3s;
    }
    .monster-slot { border-style: solid; border-color: rgba(67, 130, 223, 0.4); }
    .spell-slot { border-color: rgba(70, 71, 174, 0.4); background: rgba(70, 71, 174, 0.05); }
    .zone-enemy .zone-slot { border-color: rgba(138, 140, 255, 0.3); }

    /* Center Zone (Phase) */
    .board-center { height: 40px; display: flex; align-items: center; justify-content: center; position: relative; z-index: 10; margin: -10px 0; }
    .phase-tracker { display: flex; background: #090E39; border: 1px solid var(--c-border); border-radius: 20px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.8); }
    .phase { padding: 4px 12px; font-size: 10px; font-weight: 900; color: rgba(255,255,255,0.3); border-right: 1px solid rgba(255,255,255,0.1); }
    .phase:last-child { border-right: none; }
    .phase.active { color: var(--c-cyan); background: rgba(34, 228, 255, 0.15); text-shadow: 0 0 8px var(--c-cyan); }

    /* Floating Text */
    .floating-text { position: absolute; font-size: 40px; font-weight: 900; text-shadow: 0 0 20px currentColor; animation: floatUp 1s ease-out forwards; pointer-events: none; z-index: 50; }
    .floating-text.damage-text { color: #FF8999; }
    .floating-text.heal-text { color: #22E4FF; }
    .zone-enemy .floating-text { top: 50%; right: 20%; }
    .zone-player .floating-text { bottom: 50%; left: 20%; }
    @keyframes floatUp { 0% { opacity: 0; transform: translateY(20px) scale(0.5); } 20% { opacity: 1; transform: translateY(0) scale(1.2); } 100% { opacity: 0; transform: translateY(-40px) scale(1); } }

    /* Shake Effect */
    .shake { animation: shakeAnim 0.4s cubic-bezier(.36,.07,.19,.97) both; }
    @keyframes shakeAnim {
        10%, 90% { transform: translate3d(-2px, 0, 0); }
        20%, 80% { transform: translate3d(4px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-6px, 0, 0); }
        40%, 60% { transform: translate3d(6px, 0, 0); }
    }

    /* YGO SUMMON ANIMATION */
    .summon-layer { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; z-index: 40; perspective: 1000px; }
    .ygo-summon { position: absolute; width: 180px; height: 260px; border-radius: 12px; background: #090E39; border: 2px solid #4382DF; box-shadow: 0 0 40px rgba(67, 130, 223, 0.6), inset 0 0 30px rgba(67, 130, 223, 0.2); display: flex; flex-direction: column; overflow: hidden; }
    .ygo-summon.defense { border-color: #4647AE; box-shadow: 0 0 40px rgba(70, 71, 174, 0.6); }
    .ygo-summon.skill { border-color: #22E4FF; box-shadow: 0 0 40px rgba(34, 228, 255, 0.6); }
    
    .player-summon { animation: summonFromBottom 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .enemy-summon { animation: summonFromTop 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    
    .ygo-inner { padding: 12px; display: flex; flex-direction: column; height: 100%; position: relative; z-index: 2; }
    .ygo-top { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(67, 130, 223, 0.3); padding-bottom: 8px; margin-bottom: 10px; }
    .ygo-name { font-size: 14px; font-weight: 900; text-transform: uppercase; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ygo-art { flex: 1; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(67, 130, 223, 0.2), transparent 70%); border: 1px solid rgba(67, 130, 223, 0.3); border-radius: 6px; color: var(--c-cyan); margin-bottom: 10px; }
    .ygo-art svg { width: 70px; height: 70px; }

    @keyframes summonFromBottom {
        0% { transform: translateY(150px) scale(0.5) rotateX(45deg); opacity: 0; }
        20% { transform: translateY(0) scale(1.1) rotateX(0); opacity: 1; box-shadow: 0 0 60px #22E4FF; }
        30% { transform: translateY(0) scale(1) rotateX(0); }
        80% { transform: translateY(0) scale(1) rotateX(0); opacity: 1; }
        100% { transform: translateY(-100px) scale(1.5) rotateX(20deg); opacity: 0; }
    }

    @keyframes summonFromTop {
        0% { transform: translateY(-150px) scale(0.5) rotateX(-45deg); opacity: 0; }
        20% { transform: translateY(0) scale(1.1) rotateX(0); opacity: 1; box-shadow: 0 0 60px #8A8CFF; }
        30% { transform: translateY(0) scale(1) rotateX(0); }
        80% { transform: translateY(0) scale(1) rotateX(0); opacity: 1; }
        100% { transform: translateY(100px) scale(1.5) rotateX(-20deg); opacity: 0; }
    }

    /* Attack Effect */
    .duel-projectile { position: absolute; width: 80px; height: 16px; opacity: 0; filter: drop-shadow(0 0 10px #22E4FF); z-index: 45; }
    .duel-projectile.direction-player { left: 20%; top: 50%; animation: projRight 0.4s ease-in forwards; }
    .duel-projectile.direction-enemy { right: 20%; top: 50%; animation: projLeft 0.4s ease-in forwards; }
    .proj-core { position: absolute; top: 4px; right: 0; width: 18px; height: 8px; border-radius: 50%; background: white; box-shadow: 0 0 15px #22E4FF, 0 0 30px #22E4FF; }
    .proj-trail { position: absolute; top: 6px; right: 10px; left: 0; height: 4px; border-radius: 4px; background: linear-gradient(90deg, transparent, #4382DF, white); }
    .direction-enemy .proj-core { right: auto; left: 0; box-shadow: 0 0 15px #8A8CFF, 0 0 30px #8A8CFF; }
    .direction-enemy .proj-trail { left: 10px; right: 0; background: linear-gradient(90deg, white, #4647AE, transparent); }
    
    .duel-impact { position: absolute; width: 90px; height: 90px; border-radius: 50%; border: 3px solid white; opacity: 0; z-index: 46; margin-top: -45px; }
    .duel-impact.direction-player { right: 25%; top: 50%; border-color: #22E4FF; box-shadow: 0 0 30px #22E4FF; animation: impactBurst 0.4s ease-out forwards; }
    .duel-impact.direction-enemy { left: 25%; top: 50%; border-color: #8A8CFF; box-shadow: 0 0 30px #8A8CFF; animation: impactBurst 0.4s ease-out forwards; }

    @keyframes projRight { 0% { left: 20%; opacity: 0; transform: scale(0.5); } 20% { opacity: 1; transform: scale(1); } 100% { left: 70%; opacity: 0; transform: scale(1.2); } }
    @keyframes projLeft { 0% { right: 20%; opacity: 0; transform: scale(0.5); } 20% { opacity: 1; transform: scale(1); } 100% { right: 70%; opacity: 0; transform: scale(1.2); } }
    @keyframes impactBurst { 0% { opacity: 0; transform: scale(0.2); } 50% { opacity: 1; transform: scale(1.2); } 100% { opacity: 0; transform: scale(1.8); } }

    /* Bottom UI */
    .duel-bottom-ui { display: flex; justify-content: space-between; align-items: flex-end; padding: 0 20px; gap: 15px; height: 220px; }
    
    .turn-panel { 
        width: 200px; display: flex; flex-direction: column; gap: 8px; 
        background: rgba(9, 14, 57, 0.9); padding: 12px; border-radius: 12px; 
        border: 1px solid var(--c-border); margin-bottom: 10px; flex-shrink: 0;
    }
    .turn-status { display: flex; align-items: center; gap: 10px; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #8793B7; }
    .status-dot.my-turn { background: var(--c-cyan); box-shadow: 0 0 10px var(--c-cyan); animation: pulse 1.5s infinite; }
    .status-dot.working { background: #FFD76A; box-shadow: 0 0 10px #FFD76A; animation: pulse 0.8s infinite; }
    .turn-status strong { display: block; font-size: 12px; font-weight: 900; color: white; }
    .turn-status p { font-size: 10px; color: rgba(255, 255, 255, 0.5); }
    
    .turn-timer-row { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 8px; }
    .turn-timer { display: flex; align-items: center; gap: 4px; font-size: 16px; font-weight: 900; color: var(--c-cyan); }
    .turn-timer svg { width: 16px; height: 16px; }
    .turn-timer.danger { color: #FF8999; animation: pulse 1s infinite; }
    .btn-skip { padding: 4px 10px; border-radius: 4px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.1); color: white; font-size: 9px; font-weight: 800; cursor: pointer; transition: 0.2s; }
    .btn-skip:hover:not(:disabled) { background: rgba(255, 255, 255, 0.2); }
    .btn-skip:disabled { opacity: 0.3; cursor: not-allowed; }

    .duel-log { width: 260px; height: 120px; border-radius: 10px; background: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.05); display: flex; flex-direction: column; overflow: hidden; margin-bottom: 10px; flex-shrink: 0; }
    .log-scroll { flex: 1; overflow-y: auto; padding: 6px 10px; display: flex; flex-direction: column; gap: 4px; scrollbar-width: thin; }
    .log-item { display: flex; align-items: center; gap: 6px; font-size: 10px; }
    .log-who { font-size: 8px; font-weight: 900; padding: 1px 4px; border-radius: 3px; }
    .is-player .log-who { background: rgba(67, 130, 223, 0.2); color: var(--c-cyan); }
    .is-enemy .log-who { background: rgba(70, 71, 174, 0.3); color: #8A8CFF; }
    .log-text { flex: 1; color: rgba(255, 255, 255, 0.8); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .log-val { font-weight: 900; color: #FF8999; }
    .log-empty { font-size: 10px; color: rgba(255, 255, 255, 0.4); font-style: italic; text-align: center; margin-top: 10px; }

    .duel-hand { flex: 1; display: flex; justify-content: center; align-items: flex-end; height: 100%; }
    .hand-cards { display: flex; gap: 12px; overflow-x: auto; padding: 10px 10px 0; align-items: flex-end; justify-content: center; perspective: 1000px; width: 100%; }
    .hand-cards::-webkit-scrollbar { display: none; }

    /* TCG Card Style */
    .tcg-card {
        flex: 0 0 130px; width: 130px; height: 190px; border-radius: 8px; background: #090E39; border: 2px solid rgba(67, 130, 223, 0.4); padding: 6px; display: flex; flex-direction: column; cursor: pointer; position: relative; transition: all 0.2s cubic-bezier(0.2, 0.8, 0.2, 1); transform-style: preserve-3d; box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }
    
    .tcg-card.is-playable:hover { transform: translateY(-15px) scale(1.05); border-color: var(--c-cyan); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5), 0 0 20px rgba(34, 228, 255, 0.3); z-index: 10; }
    .tcg-card.is-locked { opacity: 0.6; filter: grayscale(0.7); cursor: not-allowed; }

    .type-attack { border-color: rgba(67, 130, 223, 0.6); }
    .type-defense { border-color: rgba(70, 71, 174, 0.6); }
    .type-skill { border-color: rgba(34, 228, 255, 0.6); }

    .tcg-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .tcg-type { font-size: 8px; font-weight: 800; color: var(--c-cyan); text-transform: uppercase; letter-spacing: 0.5px; }
    .tcg-cost { display: flex; align-items: center; gap: 2px; font-size: 12px; font-weight: 900; color: white; }
    .tcg-cost svg { width: 12px; height: 12px; color: var(--c-cyan); }

    .tcg-art { height: 70px; border-radius: 4px; background: radial-gradient(circle, rgba(67, 130, 223, 0.2), transparent 70%); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 6px; color: var(--c-cyan); }
    .tcg-art svg { width: 40px; height: 40px; filter: drop-shadow(0 0 10px rgba(34, 228, 255, 0.5)); }

    .tcg-info { flex: 1; text-align: center; overflow: hidden; }
    .tcg-info h4 { font-size: 10px; font-weight: 900; color: white; line-height: 1.2; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tcg-info p { font-size: 8px; color: rgba(255, 255, 255, 0.5); line-height: 1.2; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    .tcg-stats { display: flex; justify-content: center; gap: 8px; margin-top: 4px; padding-top: 4px; border-top: 1px solid rgba(255, 255, 255, 0.1); font-size: 9px; font-weight: 800; }
    .s-dmg { color: #FF8999; }
    .s-heal { color: var(--c-cyan); }
    .s-block { color: #8A8CFF; }

    .empty-hand { color: rgba(255, 255, 255, 0.4); font-size: 12px; font-style: italic; padding: 40px; }
    .empty-hand b { color: var(--c-cyan); }

    /* Overlays (Modals) */
    .duel-overlay { position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(10px); }
    
    .tutorial-modal { position: relative; width: min(100%, 420px); padding: 30px; border-radius: 16px; background: #090E39; border: 1px solid var(--c-border); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8); }
    .modal-top-line { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 16px 16px 0 0; background: linear-gradient(90deg, var(--c-blue), var(--c-cyan), var(--c-purple)); }
    .modal-close { position: absolute; top: 12px; right: 12px; width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--c-border); background: transparent; color: #8793B7; font-size: 18px; cursor: pointer; transition: 0.2s; }
    .modal-close:hover { background: rgba(255, 255, 255, 0.1); color: white; }

    .tutorial-progress { display: flex; justify-content: center; gap: 6px; margin-bottom: 20px; }
    .t-dot { width: 20px; height: 4px; border-radius: 2px; background: rgba(255, 255, 255, 0.1); transition: 0.3s; }
    .t-dot.on { width: 32px; background: var(--c-cyan); box-shadow: 0 0 10px var(--c-cyan); }
    .t-dot.done { background: var(--c-blue); }

    .tutorial-content { text-align: center; }
    .t-icon { width: 80px; height: 80px; margin: 0 auto 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--c-border); background: rgba(67, 130, 223, 0.1); color: var(--c-cyan); animation: float 3s ease-in-out infinite; }
    .t-icon svg { width: 40px; height: 40px; }
    .icon-hp { color: #FF8999; border-color: rgba(255, 137, 153, 0.3); background: rgba(255, 137, 153, 0.1); }
    .icon-mana { color: #FFD76A; border-color: rgba(255, 215, 106, 0.3); background: rgba(255, 215, 106, 0.1); }
    
    .t-kicker { font-size: 10px; font-weight: 900; color: var(--c-cyan); letter-spacing: 2px; margin-bottom: 8px; }
    .t-title { font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 900; color: white; margin-bottom: 10px; }
    .t-desc { font-size: 13px; color: rgba(255, 255, 255, 0.7); line-height: 1.6; }
    
    .t-tip { display: inline-flex; align-items: flex-start; gap: 8px; margin-top: 16px; padding: 10px 14px; text-align: left; border-radius: 8px; background: rgba(67, 130, 223, 0.1); border: 1px solid rgba(67, 130, 223, 0.3); font-size: 12px; color: #DCE8FF; }
    .tip-badge { flex-shrink: 0; padding: 2px 6px; background: var(--c-cyan); color: #030612; font-size: 9px; font-weight: 900; border-radius: 3px; }

    .tutorial-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, 0.1); }
    .btn-ghost { padding: 8px 16px; border-radius: 6px; border: 1px solid var(--c-border); background: transparent; color: #8793B7; font-size: 11px; font-weight: 800; cursor: pointer; transition: 0.2s; }
    .btn-ghost:hover:not(:disabled) { background: rgba(255, 255, 255, 0.05); color: white; }
    .btn-ghost:disabled { opacity: 0.3; cursor: not-allowed; }
    .btn-primary { padding: 8px 20px; border-radius: 6px; border: 1px solid var(--c-cyan); background: linear-gradient(100deg, var(--c-blue), var(--c-cyan)); color: #030612; font-size: 11px; font-weight: 900; cursor: pointer; transition: 0.2s; }
    .btn-primary:hover { filter: brightness(1.2); box-shadow: 0 4px 15px rgba(34, 228, 255, 0.4); }

    /* PRE-GAME (STARTER DECK) */
    .starter-grid { display: grid; grid-template-columns: 1fr; gap: 12px; margin-top: 15px; }
    .starter-card { display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(0,0,0,0.4); border: 1px solid var(--c-border); border-radius: 10px; text-align: left; cursor: pointer; transition: 0.2s; }
    .starter-card:hover { background: rgba(67, 130, 223, 0.15); border-color: var(--c-cyan); transform: translateX(5px); }
    .s-icon { width: 50px; height: 50px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.1); }
    .s-icon svg { width: 30px; height: 30px; }
    .starter-card h4 { color: white; font-size: 16px; font-weight: 900; margin-bottom: 4px; }
    .starter-card p { color: rgba(255,255,255,0.6); font-size: 12px; line-height: 1.4; }

    /* RESULT SCREEN */
    .result-overlay { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .res-victory { background: radial-gradient(circle at center, rgba(67, 130, 223, 0.3), #000000 80%); }
    .res-defeat { background: radial-gradient(circle at center, rgba(70, 71, 174, 0.3), #000000 80%); }
    .res-particles { position: absolute; inset: 0; pointer-events: none; }
    .particle { position: absolute; width: 6px; height: 6px; background: white; border-radius: 50%; filter: blur(1px); opacity: 0; }
    .res-victory .particle { background: var(--c-cyan); box-shadow: 0 0 15px var(--c-cyan); animation: floatParticle 3s infinite linear; }
    .res-defeat .particle { background: #8A8CFF; box-shadow: 0 0 15px #8A8CFF; animation: floatParticle 4s infinite linear; }
    .p1 { left: 10%; top: 100%; animation-delay: 0s; } .p2 { left: 30%; top: 100%; animation-delay: 0.8s; }
    .p3 { left: 50%; top: 100%; animation-delay: 1.5s; } .p4 { left: 70%; top: 100%; animation-delay: 0.4s; }
    .p5 { left: 90%; top: 100%; animation-delay: 2s; }
    @keyframes floatParticle { 0% { transform: translateY(0) scale(1); opacity: 0; } 20% { opacity: 0.8; } 100% { transform: translateY(-100vh) scale(1.5); opacity: 0; } }

    .res-content { position: relative; z-index: 10; display: flex; flex-direction: column; align-items: center; text-align: center; padding: 20px; }
    .res-icon-wrap { width: 120px; height: 120px; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; position: relative; }
    .res-icon-wrap svg { width: 80px; height: 80px; }
    .icon-vic { background: rgba(34, 228, 255, 0.1); border: 2px solid var(--c-cyan); color: var(--c-cyan); box-shadow: 0 0 50px rgba(34, 228, 255, 0.4), inset 0 0 30px rgba(34, 228, 255, 0.2); }
    .icon-def { background: rgba(138, 140, 255, 0.1); border: 2px solid #8A8CFF; color: #8A8CFF; box-shadow: 0 0 50px rgba(138, 140, 255, 0.4), inset 0 0 30px rgba(138, 140, 255, 0.2); }
    .res-sub { font-size: 14px; font-weight: 900; letter-spacing: 6px; color: rgba(255, 255, 255, 0.6); margin-bottom: 10px; text-transform: uppercase; }
    .res-title { font-family: 'Orbitron', sans-serif; font-size: clamp(48px, 10vw, 80px); font-weight: 900; line-height: 1; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 2px; }
    .text-vic { color: var(--c-cyan); text-shadow: 0 0 30px var(--c-cyan), 0 0 60px rgba(34, 228, 255, 0.5); }
    .text-def { color: #8A8CFF; text-shadow: 0 0 30px #8A8CFF; }
    .res-desc { max-width: 400px; font-size: 15px; color: rgba(255, 255, 255, 0.7); line-height: 1.5; margin-bottom: 40px; }
    .res-stats { display: flex; align-items: center; gap: 30px; margin-bottom: 40px; }
    .stat-box { display: flex; flex-direction: column; gap: 5px; }
    .stat-box span { font-size: 11px; font-weight: 800; color: rgba(255, 255, 255, 0.5); letter-spacing: 1px; }
    .stat-box strong { font-size: 32px; font-weight: 900; color: white; }
    .stat-vs { font-size: 16px; font-weight: 900; color: rgba(255, 255, 255, 0.3); font-style: italic; }
    .res-btn { display: inline-block; padding: 16px 40px; border-radius: 12px; font-size: 14px; font-weight: 900; letter-spacing: 2px; text-decoration: none; text-transform: uppercase; transition: 0.3s; position: relative; overflow: hidden; }
    .btn-vic { background: var(--c-cyan); color: #030612; box-shadow: 0 0 30px rgba(34, 228, 255, 0.4); }
    .btn-vic:hover { transform: translateY(-3px); box-shadow: 0 10px 40px rgba(34, 228, 255, 0.6); }
    .btn-def { background: transparent; border: 2px solid #8A8CFF; color: #8A8CFF; }
    .btn-def:hover { background: rgba(138, 140, 255, 0.1); transform: translateY(-3px); box-shadow: 0 10px 40px rgba(138, 140, 255, 0.2); }

    /* Utilities */
    .damage-text { color: #FF8999; }
    .heal-text { color: var(--c-cyan); }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }

    /* Responsive */
    @media (max-width: 900px) {
        .duelist-panel { width: 140px; padding: 8px; }
        .zone-slot { width: 50px; height: 70px; }
        .duel-bottom-ui { flex-direction: column; height: auto; }
        .turn-panel, .duel-log { width: 100%; margin-bottom: 5px; }
        .duel-hand { width: 100%; height: auto; }
    }
    @media (max-width: 640px) {
        .board-zone { flex-direction: column; gap: 10px; padding: 10px; }
        .duelist-panel { flex-direction: row; width: 100%; justify-content: space-between; }
        .avatar-row { width: auto; }
        .hand-deck-info { width: auto; }
        .phase-tracker { display: none; }
        .zone-slot { width: 40px; height: 55px; }
        .play-mat { padding: 8px; gap: 6px; }
        .zone-row { gap: 6px; }
        .duel-bottom-ui { padding: 0 10px; }
        .tcg-card { flex: 0 0 100px; width: 100px; height: 150px; padding: 4px; }
        .tcg-art { height: 50px; }
        .tcg-art svg { width: 30px; height: 30px; }
        .tcg-info h4 { font-size: 9px; }
        .tcg-info p { display: none; }
        .tcg-stats { font-size: 8px; gap: 4px; }
        .starter-card { flex-direction: column; text-align: center; gap: 8px; }
    }
</style>

<script>
function battleApp(config) {
    return {
        // Config Base
        battleId: config.battleId,
        allCardsData: config.allCardsData || [],
        
        // State Game
        hp1: Number(config.initialHp1),
        hp2: Number(config.initialHp2),
        energy: Number(config.initialEnergy),
        energy2: 3,
        turnCount: 1,
        handIds: config.initialHand || [],
        logs: config.initialLogs || [],
        
        // Deck System
        deck: [], 
        deckCount: 20, 

        // UI Control
        processing: false,
        finished: false,
        winner: null,
        timer: 30,
        timerInterval: null,
        isMyTurn: true,
        showRestartConfirm: false,

        // Pre-Game State
        showPreGame: true, 

        // Tutorial State
        showTutorial: false,
        tutorialStep: 0,
        tutorialSeen: false,
        tutorialSteps: [
            { key: 'welcome', title: 'Selamat Datang di Duel!', desc: 'Ini adalah pertarungan kartu bergaya Yu-Gi-Oh!. Tujuanmu adalah mengurangi Life Points (LP) lawan menjadi 0.', tip: 'Kamu bermain melawan AI dengan nama acak. Fokus pada strategi!' },
            { key: 'hp', title: 'Life Points (LP)', desc: 'Setiap Duelist punya 100 LP. Jika LP-mu habis, kamu kalah. Kartu pertahanan (Defense) sangat penting untuk melindungi LP-mu.', tip: 'Pantau terus HP bar di atas kartu karaktermu.' },
            { key: 'mana', title: 'Mana (Energi)', desc: 'Untuk memainkan kartu dari tanganmu, kamu butuh Mana. Biaya Mana tertera di pojok kanan atas kartu.', tip: 'Kamu tidak bisa memainkan kartu jika Mana tidak cukup.' },
            { key: 'play', title: 'Cara Memainkan Kartu', desc: 'Klik kartu di bagian bawah layar. Kartu akan di-"Summon" ke tengah arena, lalu efeknya (Serangan, Bertahan, atau Skill) akan dipicu.', tip: 'Jika kartu di tangan habis, klik tumpukan DECK di panel kiri untuk mengambil kartu acak!' }
        ],

        // Animation State
        attacker: null,
        impactTarget: null,
        healingTarget: null,
        combatText: '',
        combatTextTarget: null,
        combatTextType: '',
        screenShake: false,
        summonedCard: { active: false, key: 0, name: '', type: '', desc: '', cost: 0, actor: 1 },
        projectile: { active: false, direction: '', key: 0 },
        impact: { active: false, direction: '', key: 0 },
        animationTimers: [],
        
        aiName: 'Randy',

        get handCards() {
            return this.handIds.map(id => this.allCardsData.find(card => Number(card.id) === Number(id))).filter(Boolean);
        },
        get visibleLogs() {
            return this.logs.slice(-20);
        },

        init() {
            const names = ['Randy', 'Meong', 'Evos', 'Ateng', 'Joko', 'Budi', 'Siti', 'Rina', 'Tono', 'Bahlil', 'Megawati', 'Prabowo', 'Gibran', 'Anies'];
            this.aiName = names[Math.floor(Math.random() * names.length)];

            try {
                this.tutorialSeen = localStorage.getItem('navo_battle_tutorial_v1') === '1';
            } catch (e) {
                this.tutorialSeen = false;
            }

            // Tutorial appears after starter deck selected
        },

        startDuel(strategy) {
            this.showPreGame = false;
            this.startTimer();
            this.$nextTick(() => this.scrollLog());
            this.fillDeck();

            // Optional: Modify starting deck based on strategy
            // For now, just a visual choice
            if (strategy === 'aggressive') {
                this.energy = 4; // bonus mana
            } else if (strategy === 'defensive') {
                this.hp1 = 120; // bonus hp
            }

            if (!this.tutorialSeen) {
                setTimeout(() => this.openTutorial(), 500);
            }
        },

        fillDeck() {
            this.deck = [];
            if (this.allCardsData.length > 0) {
                for (let i = 0; i < 20; i++) {
                    const randomCard = this.allCardsData[Math.floor(Math.random() * this.allCardsData.length)];
                    this.deck.push({ ...randomCard, uid: Math.random().toString(36).substring(7) });
                }
            }
            this.deckCount = this.deck.length;
        },

        drawCard() {
            if (this.processing || this.finished || !this.isMyTurn) return;
            if (this.deck.length === 0) {
                alert('Deck kamu sudah kosong!');
                return;
            }

            const card = this.deck.pop();
            this.deckCount = this.deck.length;
            this.handIds.push(card.id);

            this.logs.push({ slot: 1, description: `Kamu menarik kartu dari Deck.`, damage: 0, heal: 0 });
            this.$nextTick(() => this.scrollLog());
        },

        confirmRestart() {
            if (this.finished) {
                this.restartDuel(); 
            } else {
                this.showRestartConfirm = true; 
            }
        },

        restartDuel() {
            this.showRestartConfirm = false;
            this.finished = false;
            this.winner = null;
            this.processing = false;
            
            // Reset and show pre-game again
            this.showPreGame = true;

            this.hp1 = Number(config.initialHp1) || 100;
            this.hp2 = Number(config.initialHp2) || 100;
            this.energy = Number(config.initialEnergy) || 3;
            this.energy2 = 3;
            this.turnCount = 1;
            this.isMyTurn = true;

            this.handIds = config.initialHand || [];
            this.fillDeck();
            this.logs = [];

            const names = ['Randy', 'Meong', 'Evos', 'Ateng', 'Joko', 'Budi', 'Siti', 'Rina', 'Tono', 'Bahlil', 'Megawati', 'Prabowo', 'Gibran', 'Anies'];
            this.aiName = names[Math.floor(Math.random() * names.length)];

            this.startTimer();
            this.clearCombatAnimations();
        },

        openTutorial() { this.tutorialStep = 0; this.showTutorial = true; },
        closeTutorial() { this.showTutorial = false; this.tutorialSeen = true; try { localStorage.setItem('navo_battle_tutorial_v1', '1'); } catch (e) {} },
        nextTutorialStep() { if (this.tutorialStep < this.tutorialSteps.length - 1) this.tutorialStep++; else this.closeTutorial(); },
        prevTutorialStep() { if (this.tutorialStep > 0) this.tutorialStep--; },

        scheduleAnimation(callback, delay) {
            const timeout = setTimeout(callback, delay);
            this.animationTimers.push(timeout);
            return timeout;
        },

        clearCombatAnimations() {
            this.animationTimers.forEach(timeout => clearTimeout(timeout));
            this.animationTimers = [];
            this.attacker = null;
            this.impactTarget = null;
            this.healingTarget = null;
            this.combatText = '';
            this.combatTextTarget = null;
            this.projectile.active = false;
            this.impact.active = false;
            this.screenShake = false;
            this.summonedCard.active = false;
        },

        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timer = 30;
            this.timerInterval = setInterval(() => {
                if (this.finished || this.processing || this.showPreGame) return;
                this.timer--;
                if (this.timer <= 0) {
                    clearInterval(this.timerInterval);
                    this.skipTurn();
                }
            }, 1000);
        },

        resetTimer() { this.startTimer(); },

        async playCard(card) {
            if (this.processing || this.finished || !this.isMyTurn || this.energy < card.energy_cost) return;
            this.processing = true;

            const cardIndex = this.handIds.findIndex(id => Number(id) === Number(card.id));
            if (cardIndex !== -1) {
                this.handIds.splice(cardIndex, 1);
            }

            try {
                const response = await fetch(`/battle/${config.battleId}/play`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ card_id: card.id }),
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    alert(data.error || 'Gagal memainkan kartu.');
                    return;
                }

                this.processServerData(data, card);
            } catch (error) {
                console.error('Battle request failed:', error);
                alert('Koneksi bermasalah. Silakan coba lagi.');
            } finally {
                this.processing = false;
            }
        },

        async skipTurn() {
            if (this.processing || this.finished) return;
            this.processing = true;

            try {
                const response = await fetch(`/battle/${config.battleId}/skip`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    if (data.error) alert(data.error);
                    return;
                }

                this.processServerData(data, null);
            } catch (error) {
                console.error('Skip turn request failed:', error);
            } finally {
                this.processing = false;
            }
        },

        processServerData(data, playedCard = null) {
            const previousHp1 = this.hp1;
            const previousHp2 = this.hp2;
            const newLogs = Array.isArray(data.logs) ? data.logs : [];

            if (data.state) {
                this.energy = Number(data.state.player1_energy ?? this.energy);
                this.energy2 = Number(data.state.player2_energy ?? this.energy2);
                this.turnCount = Number(data.state.turn_count ?? this.turnCount);
                if (data.state.player1_hand) {
                    this.handIds = data.state.player1_hand; 
                }
            }

            newLogs.forEach(log => {
                this.logs.push({
                    slot: Number(log.slot ?? 2),
                    card: log.card ?? null,
                    description: log.description ?? 'Aksi dilakukan.',
                    damage: Number(log.damage ?? 0),
                    heal: Number(log.heal ?? 0),
                });
            });
            this.logs = this.logs.slice(-100);
            this.$nextTick(() => this.scrollLog());

            let delayOffset = 0;
            if (playedCard) {
                this.animateSummon(playedCard, 1);
                delayOffset += 800;

                if (playedCard.damage > 0 || playedCard.type === 'attack') {
                    this.scheduleAnimation(() => this.triggerAttack(1), delayOffset);
                    delayOffset += 600;
                }
            }

            const aiLog = newLogs.find(l => l.slot === 2);
            if (aiLog) {
                this.scheduleAnimation(() => {
                    this.animateSummon({
                        name: aiLog.card || 'AI Attack',
                        type: aiLog.damage > 0 ? 'attack' : (aiLog.heal > 0 ? 'skill' : 'defense'),
                        description: aiLog.description,
                        energy_cost: 3
                    }, 2);
                }, delayOffset);
                delayOffset += 800;

                if (aiLog.damage > 0) {
                    this.scheduleAnimation(() => this.triggerAttack(2), delayOffset);
                    delayOffset += 600;
                }
            }

            this.scheduleAnimation(() => {
                if (data.state) {
                    this.hp1 = Number(data.state.player1_hp ?? this.hp1);
                    this.hp2 = Number(data.state.player2_hp ?? this.hp2);
                }

                if (this.hp2 < previousHp2) {
                    this.showImpact('enemy', 'player');
                    this.showCombatText('enemy', '-' + (previousHp2 - this.hp2), 'damage-text');
                    this.screenShake = true;
                }
                if (this.hp1 < previousHp1) {
                    this.showImpact('player', 'enemy');
                    this.showCombatText('player', '-' + (previousHp1 - this.hp1), 'damage-text');
                    this.screenShake = true;
                }
                if (this.hp1 > previousHp1) {
                    this.showCombatText('player', '+' + (this.hp1 - previousHp1), 'heal-text');
                }
                if (this.hp2 > previousHp2) {
                    this.showCombatText('enemy', '+' + (this.hp2 - previousHp2), 'heal-text');
                }

                if (data.finished) {
                    this.finished = true;
                    this.winner = Number(data.winner);
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.isMyTurn = false;
                } else {
                    this.resetTimer();
                }
            }, delayOffset + 200);
        },

        animateSummon(card, actorSlot = 1) {
            this.summonedCard = {
                active: true,
                key: this.summonedCard.key + 1,
                name: card.name,
                type: card.type,
                desc: card.description || '',
                cost: card.energy_cost || 0,
                actor: actorSlot
            };

            this.scheduleAnimation(() => {
                this.summonedCard.active = false;
            }, 1200);
        },

        triggerAttack(actorSlot = 1) {
            this.attacker = actorSlot;
            const direction = actorSlot === 1 ? 'direction-player' : 'direction-enemy';
            
            this.projectile.direction = direction;
            this.projectile.key++;
            this.projectile.active = true;

            this.scheduleAnimation(() => {
                this.projectile.active = false;
                const target = actorSlot === 1 ? 'enemy' : 'player';
                this.showImpact(target, actorSlot === 1 ? 'player' : 'enemy');
            }, 280);

            this.scheduleAnimation(() => {
                this.attacker = null;
            }, 450);
        },

        showImpact(target, actor = 1) {
            this.impactTarget = target;
            this.impact.direction = actor === 1 ? 'direction-player' : 'direction-enemy';
            this.impact.key++;
            this.impact.active = true;

            this.screenShake = true;

            this.scheduleAnimation(() => {
                this.impact.active = false;
                this.impactTarget = null;
            }, 480);
            
            this.scheduleAnimation(() => {
                this.screenShake = false;
            }, 400);
        },

        showCombatText(target, text, type = 'damage-text') {
            this.combatTextTarget = target;
            this.combatText = text;
            this.combatTextType = type;

            this.scheduleAnimation(() => {
                this.combatText = '';
                this.combatTextTarget = null;
            }, 950);
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