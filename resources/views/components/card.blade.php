@props(['padding' => 'p-3', 'bodyClass' => ''])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm']) }}>
    <div class="{{ $padding }} {{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>