@extends('playground.layouts.app')

@section('title', 'Riwayat Pull — DIGANOVA Playground')

@section('content')

<div class="mb-4">
    <a href="{{ route('play.gacha.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
        ← Kembali ke Gacha
    </a>
</div>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">📜 Riwayat Pull</h1>
    <p class="text-sm text-slate-400 mt-1">Semua hasil gacha kamu</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-3 mb-6">
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wide">Total Pull</p>
        <p class="text-2xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-slate-900 border border-green-500/30 rounded-xl p-4">
        <p class="text-xs text-green-400 uppercase tracking-wide">Item Unik</p>
        <p class="text-2xl font-bold text-green-400 mt-1">{{ $stats['unique_items'] }}</p>
    </div>
    <div class="bg-slate-900 border border-yellow-500/30 rounded-xl p-4">
        <p class="text-xs text-yellow-400 uppercase tracking-wide">Duplikat</p>
        <p class="text-2xl font-bold text-yellow-400 mt-1">{{ $stats['duplicates'] }}</p>
    </div>
</div>

{{-- History --}}
@if ($pulls->isEmpty())
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-12 text-center">
        <div class="text-5xl mb-3">🎲</div>
        <p class="text-white font-medium">Belum ada pull</p>
        <p class="text-sm text-slate-500 mt-1">Coba keberuntunganmu di gacha!</p>
        <a href="{{ route('play.gacha.index') }}"
           class="inline-block mt-4 bg-gradient-to-r from-cyan-500 to-violet-600 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition">
            Buka Gacha
        </a>
    </div>
@else
    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
        <div class="divide-y divide-slate-800">
            @foreach ($pulls as $pull)
                @php
                    $item = $pull->item;
                    $rarityColor = match($item->rarity) {
                        'common' => 'slate',
                        'rare' => 'blue',
                        'epic' => 'purple',
                        'legendary' => 'yellow',
                        default => 'slate',
                    };
                @endphp
                <div class="flex items-center gap-3 p-3 hover:bg-slate-800/30 transition">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br border-2 flex items-center justify-center flex-shrink-0
                        @if ($item->rarity === 'common') from-slate-500/30 to-slate-700/30 border-slate-500/40
                        @elseif ($item->rarity === 'rare') from-blue-500/30 to-cyan-500/30 border-blue-500/50
                        @elseif ($item->rarity === 'epic') from-purple-500/30 to-violet-600/30 border-purple-500/50
                        @else from-yellow-400/40 via-pink-500/30 to-violet-600/40 border-yellow-400/60
                        @endif">
                        <span class="text-xl">
                            @if ($item->type === 'skin') 👕
                            @elseif ($item->type === 'emote') 😊
                            @else 🏅
                            @endif
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-medium text-white text-sm">{{ $item->name }}</p>
                            <span class="text-[10px] bg-{{ $rarityColor }}-500/20 text-{{ $rarityColor }}-300 px-1.5 py-0.5 rounded capitalize">
                                {{ $item->rarity }}
                            </span>
                            <span class="text-[10px] text-slate-500 capitalize">{{ $item->type }}</span>
                            @if ($pull->is_duplicate)
                                <span class="text-[10px] bg-yellow-500/20 text-yellow-300 px-1.5 py-0.5 rounded">
                                    DUP
                                </span>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-500 mt-0.5">
                            {{ $pull->created_at->format('d M Y H:i') }} · {{ $pull->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        {{ $pulls->links() }}
    </div>
@endif

@endsection