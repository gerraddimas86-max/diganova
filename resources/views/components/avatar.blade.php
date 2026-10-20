@props([
    'avatar' => null,
    'user' => null,
    'size' => 'w-10 h-10',
    'textSize' => 'text-base',
])

@php
    if ($avatar) {
        $emoji = match($avatar->slug) {
            'novo-male' => '🦊',
            'nova-female' => '🦊',
            'event-goer' => '🎉',
            'gamer' => '🎮',
            'social-butterfly' => '🦋',
            'influencer' => '⭐',
            'champion' => '🥇',
            'veteran' => '🏆',
            'legend' => '👑',
            'cosplayer' => '🎭',
            default => '🦊',
        };
        $bg = $avatar->gender === 'female'
            ? 'from-pink-500 to-rose-400'
            : 'from-violet-500 to-crystal-400';
        $content = $emoji;
    } else {
        $bg = 'from-gray-400 to-gray-500';
        $content = strtoupper(substr($user->name ?? '?', 0, 1));
    }
@endphp

<div {{ $attributes->merge([
    'class' => "$size rounded-full bg-gradient-to-br $bg flex items-center justify-center text-white font-bold flex-shrink-0 $textSize",
]) }}>
    {{ $content }}
</div>