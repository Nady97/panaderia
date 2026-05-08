@props([
    'title',
    'value',
    'icon',
    'trend' => null,
    'trendValue' => null,
    'variant' => 'primary',
    'subtitle' => null
])

@php
    $variants = [
        'primary' => ['bg' => 'bg-soft-brown', 'text' => 'text-brown'],
        'success' => ['bg' => 'bg-soft-green', 'text' => 'text-success'],
        'warning' => ['bg' => 'bg-soft-warning', 'text' => 'text-warning'],
        'danger' => ['bg' => 'bg-soft-danger', 'text' => 'text-danger'],
        'gold' => ['bg' => 'bg-soft-gold', 'text' => 'text-gold-dark'],
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<div class="card-kpi card-kpi-{{ $variant }}">
    <div class="card-kpi-body">
        <div class="card-kpi-icon {{ $variantClass['bg'] }} {{ $variantClass['text'] }}">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="card-kpi-content">
            <span class="card-kpi-title">{{ $value }}</span>
            <span class="card-kpi-label">{{ $title }}</span>
            @if($trend)
                <span class="card-kpi-trend {{ $trend >= 0 ? 'trend-up' : 'trend-down' }}">
                    <i class="fas fa-arrow-{{ $trend >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($trend) }}% {{ $trendValue }}
                </span>
            @endif
            @if($subtitle)
                <span class="card-kpi-subtitle">{{ $subtitle }}</span>
            @endif
        </div>
    </div>
</div>