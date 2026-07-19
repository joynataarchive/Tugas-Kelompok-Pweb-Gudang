{{--
    Kartu statistik/KPI ringkas untuk dashboard, mis:
    <x-stat-card label="Total Produk" value="128" icon="fa-solid fa-boxes-stacked" />
    <x-stat-card label="Stok Rendah" value="7" icon="fa-solid fa-triangle-exclamation"
                 trend="+2 minggu ini" trend-direction="down" />

    trend-direction: 'up' (emerald), 'down' (red), atau 'neutral' (slate, default).
    Dibangun berdiri sendiri di atas class .glass-card (bukan wrap <x-card>) supaya
    komponen ini tetap jalan walau card.blade.php belum ada di project.
--}}
@props([
    'label' => null,
    'value' => null,
    'icon' => null,
    'trend' => null,
    'trendDirection' => 'neutral', // up | down | neutral
])
@php
$trendColor = match($trendDirection) {
    'up'    => 'text-emerald-400',
    'down'  => 'text-red-400',
    default => 'text-slate-400',
};
$trendIcon = match($trendDirection) {
    'up'    => 'fa-solid fa-arrow-trend-up',
    'down'  => 'fa-solid fa-arrow-trend-down',
    default => 'fa-solid fa-minus',
};
@endphp

<div {{ $attributes->merge(['class' => 'glass-card']) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            @if($label)
                <p class="mb-1 truncate text-sm text-slate-400">{{ $label }}</p>
            @endif

            <p class="text-2xl font-bold text-brand-400">
                {{ $value ?? $slot }}
            </p>

            @if($trend)
                <p class="mt-2 flex items-center gap-1 text-xs font-medium {{ $trendColor }}">
                    <i class="{{ $trendIcon }}"></i>
                    <span>{{ $trend }}</span>
                </p>
            @endif
        </div>

        @if($icon)
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-500/10 text-brand-400">
                <i class="{{ $icon }} text-lg"></i>
            </div>
        @endif
    </div>
</div>
