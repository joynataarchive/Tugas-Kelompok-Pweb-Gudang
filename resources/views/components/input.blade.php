@props(['label' => null, 'name', 'type' => 'text'])
<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-300 mb-1">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full rounded-lg bg-slate-800/50 border border-white/10 text-slate-100 placeholder-slate-500 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500']) }}>
    @error($name)
        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
    @enderror
</div>
