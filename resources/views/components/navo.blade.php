@props([
    'skin' => null,
    'size' => 'w-24 h-24',
    'textSize' => 'text-5xl',
])

@php
    if ($skin) {
        $emoji = '🦊';
        $bg = match($skin->rarity) {
            'common' => 'from-orange-400 to-amber-500',
            'rare' => 'from-cyan-400 to-blue-500',
            'epic' => 'from-purple-500 to-violet-600',
            'legendary' => 'from-yellow-400 via-pink-500 to-violet-600',
            default => 'from-gray-400 to-gray-500',
        };
    } else {
        $emoji = '🦊';
        $bg = 'from-orange-400 to-amber-500';
    }
@endphp

<div {{ $attributes->merge([
    'class' => "$size rounded-2xl bg-gradient-to-br $bg flex items-center justify-center flex-shrink-0 shadow-md",
]) }}>
    <span class="{{ $textSize }}">{{ $emoji }}</span>
</div>