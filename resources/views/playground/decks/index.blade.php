@extends('playground.layouts.app')

@section('title', 'My Decks — DIGANOVA Playground')

@section('content')

<div class="flex justify-between items-start mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-white">🎴 Deck Saya</h1>
        <p class="text-sm text-slate-400 mt-1">Susun deck 10 kartu dari 20 kartu tersedia</p>
    </div>
    <a href="{{ route('play.decks.create') }}"
       class="bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-600 hover:to-violet-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm transition">
        + Buat Deck Baru
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach ($decks as $deck)
        @php
            $totalCards = $deck->cards->sum('pivot.quantity');
            $isReady = $totalCards === 10;
        @endphp

        <div class="bg-slate-900 border-2 {{ $deck->is_active ? 'border-cyan-500 ring-2 ring-cyan-500/30' : 'border-slate-800' }} rounded-xl p-5 relative">

            {{-- Active Badge --}}
            @if ($deck->is_active)
                <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-cyan-500 text-white text-xs font-bold px-3 py-0.5 rounded-full">
                    ⚡ AKTIF
                </div>
            @endif

            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide">Slot {{ $deck->slot }}</p>
                    <h3 class="font-bold text-white text-lg mt-0.5">{{ $deck->name }}</h3>
                </div>
                <span class="text-xs {{ $isReady ? 'bg-green-500/20 text-green-300 border-green-500/30' : 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30' }} px-2 py-0.5 rounded border font-medium whitespace-nowrap">
                    {{ $totalCards }}/10
                </span>
            </div>

            {{-- Card Preview --}}
            @if ($deck->cards->isNotEmpty())
                <div class="grid grid-cols-5 gap-1 mb-3">
                    @foreach ($deck->cards->take(10) as $card)
                        <div class="aspect-square rounded
                            @if ($card->type === 'attack') bg-red-500/20 border border-red-500/30
                            @elseif ($card->type === 'defense') bg-blue-500/20 border border-blue-500/30
                            @else bg-violet-500/20 border border-violet-500/30
                            @endif
                            flex items-center justify-center text-lg">
                            @if ($card->type === 'attack') ⚔️
                            @elseif ($card->type === 'defense') 🛡️
                            @else ✨
                            @endif
                        </div>
                    @endforeach
                    {{-- Empty slots --}}
                    @for ($i = $deck->cards->count(); $i < 10; $i++)
                        <div class="aspect-square rounded bg-slate-800 border border-dashed border-slate-700 flex items-center justify-center text-slate-600 text-lg">
                            +
                        </div>
                    @endfor
                </div>
            @else
                <div class="grid grid-cols-5 gap-1 mb-3">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="aspect-square rounded bg-slate-800 border border-dashed border-slate-700 flex items-center justify-center text-slate-600 text-lg">
                            +
                        </div>
                    @endfor
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex gap-2 mt-4">
                <a href="{{ route('play.decks.edit', $deck) }}"
                   class="flex-1 text-center bg-slate-800 hover:bg-slate-700 text-cyan-300 px-3 py-2 rounded-lg font-medium text-sm transition border border-slate-700">
                    {{ $totalCards > 0 ? '✏️ Edit' : '⚙️ Susun' }}
                </a>
                @if ($isReady && !$deck->is_active)
                    <form method="POST" action="{{ route('play.decks.activate', $deck) }}" class="flex-shrink-0">
                        @csrf
                        <button class="bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                            Aktifkan
                        </button>
                    </form>
                @endif
                @if ($totalCards > 0)
                    <form method="POST" action="{{ route('play.decks.clear', $deck) }}"
                          onsubmit="return confirm('Kosongkan deck ini?');"
                          class="flex-shrink-0">
                        @csrf
                        <button class="bg-red-500/20 hover:bg-red-500/30 text-red-400 px-3 py-2 rounded-lg font-medium text-sm transition border border-red-500/30">
                            🗑️
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{-- Info --}}
<div class="mt-6 bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
    <p class="text-sm text-blue-300 font-semibold mb-1">💡 Tips</p>
    <ul class="text-xs text-blue-200 space-y-1 ml-4 list-disc">
        <li>Setiap deck harus berisi tepat <strong>10 kartu</strong></li>
        <li>Semua 20 kartu terbuka untuk semua pemain — <strong>free-to-win</strong></li>
        <li>Hanya 1 deck yang bisa aktif saat battle</li>
        <li>Kartu tidak boleh duplikat dalam 1 deck</li>
    </ul>
</div>

@endsection