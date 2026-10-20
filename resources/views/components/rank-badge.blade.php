@props([
    'rank' => 'bronze',
    'size' => 'md', // sm | md | lg
])

@php
    $config = match($rank) {
        'bronze' => [
            'label' => 'Bronze',
            'icon' => '🥉',
            'bg' => 'bg-gradient-to-br from-amber-700/30 to-amber-900/30',
            'text' => 'text-amber-400',
            'border' => 'border-amber-600/50',
        ],
        'silver' => [
            'label' => 'Silver',
            'icon' => '🥈',
            'bg' => 'bg-gradient-to-br from-slate-400/30 to-slate-600/30',
            'text' => 'text-slate-200',
            'border' => 'border-slate-400/50',
        ],
        'gold' => [
            'label' => 'Gold',
            'icon' => '🥇',
            'bg' => 'bg-gradient-to-br from-yellow-500/30 to-amber-600/30',
            'text' => 'text-yellow-400',
            'border' => 'border-yellow-500/50',
        ],
        'platinum' => [
            'label' => 'Platinum',
            'icon' => '💎',
            'bg' => 'bg-gradient-to-br from-cyan-400/30 to-teal-500/30',
            'text' => 'text-cyan-300',
            'border' => 'border-cyan-400/50',
        ],
        'diamond' => [
            'label' => 'Diamond',
            'icon' => '💠',
            'bg' => 'bg-gradient-to-br from-blue-400/30 to-violet-500/30',
            'text' => 'text-blue-300',
            'border' => 'border-blue-400/50',
        ],
        'legend' => [
            'label' => 'Legend',
            'icon' => '👑',
            'bg' => 'bg-gradient-to-br from-yellow-400/40 via-pink-500/30 to-violet-600/40',
            'text' => 'text-yellow-300',
            'border' => 'border-yellow-400/60',
        ],
        default => [
            'label' => ucfirst($rank),
            'icon' => '🏅',
            'bg' => 'bg-slate-700/50',
            'text' => 'text-slate-300',
            'border' => 'border-slate-600',
        ],
    };

    $sizeClasses = match($size) {
        'sm' => 'text-[10px] px-1.5 py-0.5',
        'lg' => 'text-sm px-3 py-1',
        default => 'text-xs px-2 py-0.5',
    };
    
    // Menggabungkan class dengan aman menggunakan kurung kurawal
    $classString = "inline-flex items-center gap-1 rounded-full font-semibold border {$config['bg']} {$config['text']} {$config['border']} {$sizeClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classString]) }}>
    <span>{{ $config['icon'] }}</span>
    <span>{{ $config['label'] }}</span>
</span>