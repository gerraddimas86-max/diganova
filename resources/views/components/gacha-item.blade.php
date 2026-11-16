@props([
    'item' => null,
    'owned' => false,
    'equipped' => false,
    'size' => 'md',
    'showName' => true,
])

@php
    /* ====== Icon library (Lucide-style) ====== */
    $icons = [
        'shirt' => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
        'smile' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>',
        'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
        'gift'  => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
        'check' => '<path d="M20 6 9 17l-5-5"/>',
        'lock'  => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
    ];

    $svg = fn (string $name, string $class) =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* ====== Rarity config ====== */
    $rarity = $item->rarity ?? 'common';

    $config = match($rarity) {
        'common' => [
            'bg'     => 'bg-gradient-to-br from-slate-500 to-slate-700',
            'border' => 'border-[#090E39]',
            'text'   => 'text-slate-300',
            'shadow' => '#1E293B',
            'label'  => 'Common',
        ],
        'rare' => [
            'bg'     => 'bg-gradient-to-br from-blue-500 to-cyan-500',
            'border' => 'border-[#0C4A6E]',
            'text'   => 'text-cyan-300',
            'shadow' => '#0C4A6E',
            'label'  => 'Rare',
        ],
        'epic' => [
            'bg'     => 'bg-gradient-to-br from-purple-500 to-violet-600',
            'border' => 'border-[#4C1D95]',
            'text'   => 'text-violet-300',
            'shadow' => '#4C1D95',
            'label'  => 'Epic',
        ],
        'legendary' => [
            'bg'     => 'bg-gradient-to-br from-yellow-400 via-pink-500 to-violet-600',
            'border' => 'border-[#78350F]',
            'text'   => 'text-yellow-300',
            'shadow' => '#78350F',
            'label'  => 'Legendary',
        ],
        default => [
            'bg'     => 'bg-gradient-to-br from-slate-500 to-slate-700',
            'border' => 'border-[#090E39]',
            'text'   => 'text-slate-300',
            'shadow' => '#1E293B',
            'label'  => 'Unknown',
        ],
    };

    /* ====== Item type icon ====== */
    $iconName = match($item->type ?? '') {
        'skin'  => 'shirt',
        'emote' => 'smile',
        'badge' => 'award',
        default => 'gift',
    };

    /* ====== Size config ====== */
    $sizeClasses = match($size) {
        'sm'    => 'w-16 h-16',
        'lg'    => 'w-32 h-32',
        default => 'w-24 h-24',
    };

    $iconSize = match($size) {
        'sm'    => 'w-6 h-6',
        'lg'    => 'w-14 h-14',
        default => 'w-9 h-9',
    };

    $shadowSize = match($size) {
        'sm'    => '2px',
        'lg'    => '4px',
        default => '3px',
    };

    /* ====== Equipped state gets cyan shadow override ====== */
    $boxShadow = $equipped
        ? "{$shadowSize} {$shadowSize} 0 #0891B2"
        : "{$shadowSize} {$shadowSize} 0 {$config['shadow']}";
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    <div class="relative {{ $sizeClasses }} rounded-2xl {{ $config['bg'] }} border-2 {{ $config['border'] }} flex items-center justify-center"
         style="box-shadow: {{ $boxShadow }};">

        {{-- Item icon --}}
        <span class="text-white drop-shadow-sm">
            {!! $svg($iconName, $iconSize) !!}
        </span>

        {{-- Equipped badge --}}
        @if ($equipped)
            <div class="absolute -top-1.5 -right-1.5 w-6 h-6 grid place-items-center rounded-full bg-cyan-500 text-white border-2 border-[#090E39] shadow-[2px_2px_0_#0C4A6E]">
                {!! $svg('check', 'w-3.5 h-3.5') !!}
            </div>
        @endif

        {{-- Locked overlay --}}
        @if (!$owned && $item)
            <div class="absolute inset-0 bg-[#090E39]/75 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                <span class="text-white/90">
                    {!! $svg('lock', $iconSize) !!}
                </span>
            </div>
        @endif
    </div>

    @if ($showName && $item)
        <p class="text-xs text-white mt-1.5 text-center line-clamp-1 max-w-[7rem] font-extrabold">{{ $item->name }}</p>
        <p class="text-[10px] {{ $config['text'] }} capitalize font-extrabold uppercase tracking-wider">{{ $config['label'] }}</p>
    @endif
</div>