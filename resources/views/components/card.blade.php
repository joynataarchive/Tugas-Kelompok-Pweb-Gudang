@props(['title' => null])
<div {{ $attributes->merge(['class' => 'glass-card']) }}>
    @if($title)
        <h3 class="text-slate-100 font-semibold mb-2">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>
