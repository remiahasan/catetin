@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-2 ml-1 font-bold text-xs text-slate-700']) }}>
    {{ $value ?? $slot }}
</label>
