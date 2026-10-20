@extends('playground.layouts.app')

@section('title', 'Edit Deck — DIGANOVA Playground')

@section('content')

<div class="mb-4">
    <a href="{{ route('play.decks.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
        ← Kembali ke Deck Saya
    </a>
</div>

<div class="mb-6 flex justify-between items-start flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-white">✏️ Edit Deck: {{ $deck->name }}</h1>
        <p class="text-sm text-slate-400 mt-1">
            Slot {{ $deck->slot }}
            @if ($deck->is_active)
                · <span class="text-cyan-400 font-semibold">⚡ AKTIF</span>
            @endif
        </p>
    </div>
</div>

@include('playground.decks._form', [
    'action' => route('play.decks.update', $deck),
    'method' => 'PUT',
    'deck' => $deck,
    'selectedIds' => $selectedIds,
])

@endsection