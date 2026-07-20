@props(['status' => 'default'])
@php
$classes = match($status) {
    'low-stock' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
    'in-stock'  => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
    'out'       => 'bg-red-500/20 text-red-300 border border-red-500/30',
    default     => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
};
@endphp
<span {{ $attributes->merge(['class' => "px-2 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ $slot }}
</span>
