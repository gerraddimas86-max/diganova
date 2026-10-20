@extends('playground.layouts.app')

@section('title', 'Buat Deck — DIGANOVA Playground')

@section('content')

<div class="mb-4">
    <a href="{{ route('play.decks.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
        ← Kembali ke Deck Saya
    </a>
</div>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">🎴 Buat Deck Baru</h1>
    <p class="text-sm text-slate-400 mt-1">Pilih 10 kartu untuk deck pertamamu</p>
</div>

@include('playground.decks._form', [
    'action' => route('play.decks.store'),
    'method' => 'POST',
    'deck' => null,
    'selectedIds' => [],
])

@endsection