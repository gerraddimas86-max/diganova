@props([
    'skin' => null,
    'size' => 'w-24 h-24',
    'textSize' => 'text-5xl', // kept for backward compat — tidak dipakai lagi
])

@php
    if ($skin) {
        $bg = match($skin->rarity) {
            'common'    => 'from-orange-400 to-amber-500',
            'rare'      => 'from-cyan-400 to-blue-500',
            'epic'      => 'from-purple-500 to-violet-600',
            'legendary' => 'from-yellow-400 via-pink-500 to-violet-600',
            default     => 'from-gray-400 to-gray-500',
        };
        $rarity = $skin->rarity;
    } else {
        $bg = 'from-orange-400 to-amber-500';
        $rarity = 'common';
    }

    // Hard-shadow color sesuai rarity (neo-brutalist)
    $shadowColor = match($rarity) {
        'common'    => '#C2410C',  // amber-800
        'rare'      => '#0891B2',  // cyan-700
        'epic'      => '#6D28D9',  // violet-700
        'legendary' => '#B45309',  // amber-700
        default     => '#475569',  // slate-600
    };

    $rarityLabel = ucfirst($rarity ?? 'common');
@endphp

<div {{ $attributes->merge([
    'class' => "$size rounded-2xl bg-gradient-to-br $bg flex items-center justify-center flex-shrink-0 border-2 border-[#090E39] relative overflow-hidden",
    'style' => "box-shadow: 3px 3px 0 $shadowColor;",
]) }}>

    {{-- Rarity decorative overlays --}}
    @if ($rarity === 'legendary')
        {{-- Crown untuk legendary --}}
        <span class="absolute top-0.5 right-0.5 text-yellow-100 drop-shadow-md pointer-events-none" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                <path d="M11.56 3.27a.5.5 0 0 1 .88 0l2.95 5.6a1 1 0 0 0 1.52.29l4.28-3.67a.5.5 0 0 1 .8.52l-2.83 10.25a1 1 0 0 1-.96.73H5.81a1 1 0 0 1-.96-.73L2.02 6.02a.5.5 0 0 1 .8-.52l4.28 3.67a1 1 0 0 0 1.52-.29z"/>
            </svg>
        </span>
    @elseif ($rarity === 'epic')
        {{-- Sparkle untuk epic --}}
        <span class="absolute top-0.5 right-0.5 text-yellow-200 drop-shadow pointer-events-none" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>
            </svg>
        </span>
    @elseif ($rarity === 'rare')
        {{-- Dot kecil untuk rare --}}
        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-cyan-100 shadow pointer-events-none" aria-hidden="true"></span>
    @endif

    {{-- Mascot image --}}
    <img
        src="{{ asset('images/navo-mascot.png') }}"
        alt="Navo — {{ $rarityLabel }}"
        class="w-[78%] h-[78%] object-contain drop-shadow-md pointer-events-none select-none"
        draggable="false"
        loading="lazy"
    >
</div>