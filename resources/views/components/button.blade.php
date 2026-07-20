@props(['variant' => 'primary'])
@php
$classes = match($variant) {
    'primary'   => 'bg-brand-600 hover:bg-brand-700 text-white',
    'secondary' => 'bg-slate-700/60 hover:bg-slate-700 text-slate-100 border border-white/10',
    'danger'    => 'bg-red-600 hover:bg-red-700 text-white',
    default     => 'bg-brand-600 hover:bg-brand-700 text-white',
};
@endphp
<button {{ $attributes->merge(['class' => "px-4 py-2 rounded-lg font-medium transition $classes"]) }}>
    {{ $slot }}
</button>
