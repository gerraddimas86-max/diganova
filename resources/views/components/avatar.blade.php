@props([
    'avatar' => null,
    'user' => null,
    'size' => 'w-10 h-10',
    'textSize' => 'text-base',
])

@php
    /* ====== Icon library (Lucide-style) ====== */
    $icons = [
        'party'     => '<path d="M5.8 11.3 2 22l10.7-3.79"/><path d="M4 3h.01M22 8h.01M15 2h.01M22 20h.01"/><path d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10"/><path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11-.11.7-.72 1.22-1.43 1.22H17"/><path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7"/><path d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2z"/>',
        'gamepad'   => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
        'butterfly' => '<path d="M12 12c-1.5-2-4-4-6-4-3 0-4 3-4 5 0 3 2 5 4 5 2 0 3.5-1 6-4"/><path d="M12 12c1.5-2 4-4 6-4 3 0 4 3 4 5 0 3-2 5-4 5-2 0-3.5-1-6-4"/><path d="M12 12v6"/><path d="M9 6c0-1 1-2 3-2s3 1 3 2"/>',
        'star'      => '<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>',
        'medal'     => '<path d="M7.21 15 2.66 7.14a2 2 0 0 1 .13-2.2L4.4 2.8A2 2 0 0 1 6 2h12a2 2 0 0 1 1.6.8l1.6 2.14a2 2 0 0 1 .14 2.2L16.79 15"/><path d="M11 12 5.12 2.2"/><path d="m13 12 5.88-9.8"/><path d="M8 7h8"/><circle cx="12" cy="17" r="5"/>',
        'trophy'    => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>',
        'crown'     => '<path d="M11.56 3.27a.5.5 0 0 1 .88 0l2.95 5.6a1 1 0 0 0 1.52.29l4.28-3.67a.5.5 0 0 1 .8.52l-2.83 10.25a1 1 0 0 1-.96.73H5.81a1 1 0 0 1-.96-.73L2.02 6.02a.5.5 0 0 1 .8-.52l4.28 3.67a1 1 0 0 0 1.52-.29z"/><path d="M5 21h14"/>',
        'drama'     => '<path d="M10 11h.01M14 11h.01M10 16h4"/><path d="M21.55 6.55a1 1 0 0 1 .45 1.1l-1 8.35a2 2 0 0 1-1.4 1.68C18.44 18.1 16.15 18.5 13 18.5c-3.15 0-5.44-.4-6.6-.82a2 2 0 0 1-1.4-1.68l-1-8.35a1 1 0 0 1 .45-1.1C6.63 5.28 9.6 4.5 13 4.5s6.37.78 8.55 2.05z"/>',
    ];

    $svg = fn (string $name, string $class) =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* ====== Determine icon type per avatar ====== */
    if ($avatar) {
        $iconType = match($avatar->slug) {
            'novo-male', 'nova-female' => 'image',
            'event-goer'                => 'party',
            'gamer'                     => 'gamepad',
            'social-butterfly'          => 'butterfly',
            'influencer'                => 'star',
            'champion'                  => 'medal',
            'veteran'                   => 'trophy',
            'legend'                    => 'crown',
            'cosplayer'                 => 'drama',
            default                     => 'image',
        };

        $isFemale    = $avatar->gender === 'female';
        $bg          = $isFemale
            ? 'bg-gradient-to-br from-pink-500 to-rose-400'
            : 'bg-gradient-to-br from-violet-500 to-[#4382DF]';
        $shadowColor = $isFemale ? '#9F1239' : '#4C1D95';
    } else {
        $iconType    = null;
        $bg          = 'bg-gradient-to-br from-slate-400 to-slate-600';
        $shadowColor = '#334155';
        $content     = strtoupper(substr($user->name ?? '?', 0, 1));
    }

    /* ====== Icon + shadow sizing based on textSize ====== */
    $iconSize = match($textSize) {
        'text-xs'   => 'w-3 h-3',
        'text-sm'   => 'w-4 h-4',
        'text-base' => 'w-5 h-5',
        'text-lg'   => 'w-6 h-6',
        'text-xl'   => 'w-7 h-7',
        'text-2xl'  => 'w-8 h-8',
        'text-3xl'  => 'w-10 h-10',
        'text-4xl'  => 'w-12 h-12',
        'text-5xl'  => 'w-14 h-14',
        'text-6xl'  => 'w-16 h-16',
        'text-7xl'  => 'w-20 h-20',
        default     => 'w-5 h-5',
    };

    $shadowSize = match($textSize) {
        'text-xs', 'text-sm', 'text-base'             => '2px',
        'text-lg', 'text-xl', 'text-2xl', 'text-3xl'  => '3px',
        default                                        => '4px',
    };
@endphp

<div {{ $attributes->merge([
    'class' => "$size rounded-full $bg flex items-center justify-center text-white font-extrabold flex-shrink-0 border-2 border-[#090E39] overflow-hidden $textSize",
    'style' => "box-shadow: $shadowSize $shadowSize 0 $shadowColor;",
]) }}>
    @if ($avatar && $iconType === 'image')
        <img src="{{ asset('images/navo-mascot.png') }}"
             alt="{{ $avatar->name ?? 'Avatar' }}"
             class="w-[75%] h-[75%] object-contain pointer-events-none select-none"
             draggable="false" loading="lazy">
    @elseif ($avatar && $iconType)
        {!! $svg($iconType, $iconSize) !!}
    @else
        <span class="leading-none">{{ $content }}</span>
    @endif
</div>