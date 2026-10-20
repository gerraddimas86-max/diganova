@props([
    'item' => null,
    'owned' => false,
    'equipped' => false,
    'size' => 'md',
    'showName' => true,
])

@php
    $rarity = $item->rarity ?? 'common';

    $config = match($rarity) {
        'common' => [
            'bg' => 'from-slate-500/30 to-slate-700/30',
            'border' => 'border-slate-500/40',
            'text' => 'text-slate-300',
            'glow' => '',
            'label' => 'Common',
        ],
        'rare' => [
            'bg' => 'from-blue-500/30 to-cyan-500/30',
            'border' => 'border-blue-500/50',
            'text' => 'text-blue-300',
            'glow' => 'shadow-lg shadow-blue-500/20',
            'label' => 'Rare',
        ],
        'epic' => [
            'bg' => 'from-purple-500/30 to-violet-600/30',
            'border' => 'border-purple-500/50',
            'text' => 'text-purple-300',
            'glow' => 'shadow-lg shadow-purple-500/30',
            'label' => 'Epic',
        ],
        'legendary' => [
            'bg' => 'from-yellow-400/40 via-pink-500/30 to-violet-600/40',
            'border' => 'border-yellow-400/60',
            'text' => 'text-yellow-300',
            'glow' => 'shadow-xl shadow-yellow-500/40',
            'label' => 'Legendary',
        ],
        default => [
            'bg' => 'from-slate-500/30 to-slate-700/30',
            'border' => 'border-slate-500/40',
            'text' => 'text-slate-300',
            'glow' => '',
            'label' => 'Unknown',
        ],
    };

    $icon = match($item->type ?? '') {
        'skin' => '👕',
        'emote' => '😊',
        'badge' => '🏅',
        default => '🎁',
    };

    $sizeClasses = match($size) {
        'sm' => 'w-16 h-16 text-2xl',
        'lg' => 'w-32 h-32 text-6xl',
        default => 'w-24 h-24 text-4xl',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    <div class="relative {{ $sizeClasses }} rounded-xl bg-gradient-to-br {{ $config['bg'] }} border-2 {{ $config['border'] }} {{ $config['glow'] }} flex items-center justify-center {{ $equipped ? 'ring-2 ring-cyan-400 ring-offset-2 ring-offset-slate-900' : '' }}">
        <span>{{ $icon }}</span>

        {{-- Equipped badge --}}
        @if ($equipped)
            <div class="absolute -top-1 -right-1 bg-cyan-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                ✓
            </div>
        @endif

        {{-- Locked overlay --}}
        @if (!$owned && $item)
            <div class="absolute inset-0 bg-black/60 rounded-xl flex items-center justify-center">
                <span class="text-2xl">🔒</span>
            </div>
        @endif
    </div>

    @if ($showName && $item)
        <p class="text-xs text-white mt-1 text-center line-clamp-1 max-w-[7rem]">{{ $item->name }}</p>
        <p class="text-[10px] {{ $config['text'] }} capitalize">{{ $config['label'] }}</p>
    @endif
</div>