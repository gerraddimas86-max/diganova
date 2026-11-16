@props([
    'rank' => 'bronze',
    'size' => 'md', // sm | md | lg
])

@php
    /* ====== Icon library (Lucide-style) ====== */
    $icons = [
        'award'   => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
        'medal'   => '<path d="M7.21 15 2.66 7.14a2 2 0 0 1 .13-2.2L4.4 2.8A2 2 0 0 1 6 2h12a2 2 0 0 1 1.6.8l1.6 2.14a2 2 0 0 1 .14 2.2L16.79 15"/><path d="M11 12 5.12 2.2"/><path d="m13 12 5.88-9.8"/><path d="M8 7h8"/><circle cx="12" cy="17" r="5"/><path d="M12 18v-2h-.5"/>',
        'trophy'  => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>',
        'gem'     => '<path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/>',
        'diamond' => '<path d="M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41L13.7 2.71a2.41 2.41 0 0 0-3.41 0z"/>',
        'crown'   => '<path d="M11.56 3.27a.5.5 0 0 1 .88 0l2.95 5.6a1 1 0 0 0 1.52.29l4.28-3.67a.5.5 0 0 1 .8.52l-2.83 10.25a1 1 0 0 1-.96.73H5.81a1 1 0 0 1-.96-.73L2.02 6.02a.5.5 0 0 1 .8-.52l4.28 3.67a1 1 0 0 0 1.52-.29z"/><path d="M5 21h14"/>',
        'star'    => '<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>',
    ];

    $icon = fn (string $name, string $class) =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* ====== Konfigurasi per rank ====== */
    $config = match($rank) {
        'bronze' => [
            'label'       => 'Bronze',
            'icon'        => 'award',
            'bg'          => 'bg-gradient-to-br from-amber-600 to-amber-800',
            'text'        => 'text-white',
            'border'      => 'border-[#78350F]',
            'shadowColor' => '#78350F',
        ],
        'silver' => [
            'label'       => 'Silver',
            'icon'        => 'medal',
            'bg'          => 'bg-gradient-to-br from-slate-300 to-slate-500',
            'text'        => 'text-slate-900',
            'border'      => 'border-slate-700',
            'shadowColor' => '#334155',
        ],
        'gold' => [
            'label'       => 'Gold',
            'icon'        => 'trophy',
            'bg'          => 'bg-gradient-to-br from-yellow-400 to-amber-500',
            'text'        => 'text-amber-950',
            'border'      => 'border-amber-800',
            'shadowColor' => '#78350F',
        ],
        'platinum' => [
            'label'       => 'Platinum',
            'icon'        => 'gem',
            'bg'          => 'bg-gradient-to-br from-cyan-400 to-teal-500',
            'text'        => 'text-teal-950',
            'border'      => 'border-teal-800',
            'shadowColor' => '#115E59',
        ],
        'diamond' => [
            'label'       => 'Diamond',
            'icon'        => 'diamond',
            'bg'          => 'bg-gradient-to-br from-blue-400 to-violet-500',
            'text'        => 'text-white',
            'border'      => 'border-violet-900',
            'shadowColor' => '#4C1D95',
        ],
        'legend' => [
            'label'       => 'Legend',
            'icon'        => 'crown',
            'bg'          => 'bg-gradient-to-br from-yellow-300 via-pink-400 to-violet-500',
            'text'        => 'text-white',
            'border'      => 'border-violet-950',
            'shadowColor' => '#4C1D95',
        ],
        default => [
            'label'       => ucfirst($rank),
            'icon'        => 'star',
            'bg'          => 'bg-gradient-to-br from-slate-600 to-slate-800',
            'text'        => 'text-white',
            'border'      => 'border-[#090E39]',
            'shadowColor' => '#090E39',
        ],
    };

    /* ====== Size config ====== */
    $sizeClasses = match($size) {
        'sm' => 'text-[10px] px-2 py-0.5 gap-1',
        'lg' => 'text-sm px-3.5 py-1.5 gap-1.5',
        default => 'text-xs px-2.5 py-1 gap-1.5',
    };

    $iconSize = match($size) {
        'sm' => 'w-3 h-3',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4',
    };

    $shadowSize = match($size) {
        'sm' => '2px',
        'lg' => '4px',
        default => '3px',
    };

    $classString = "inline-flex items-center rounded-full font-extrabold border-2 {$config['bg']} {$config['text']} {$config['border']} {$sizeClasses}";
    $styleString = "box-shadow: {$shadowSize} {$shadowSize} 0 {$config['shadowColor']};";
@endphp

<span {{ $attributes->merge(['class' => $classString, 'style' => $styleString]) }}>
    {!! $icon($config['icon'], $iconSize) !!}
    <span>{{ $config['label'] }}</span>
</span>