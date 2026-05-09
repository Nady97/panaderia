@props(['padding' => 'p-3', 'bodyClass' => ''])

<div {{ $attributes->merge(['class' => 'card-modern']) }}>
    <div class="card-body {{ $padding }} {{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>