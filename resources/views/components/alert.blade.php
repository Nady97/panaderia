@props(['type' => 'success'])
@php
    $icon = match($type) {
        'success' => 'bi-check-circle-fill',
        'error', 'danger' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info' => 'bi-info-circle-fill',
        default => 'bi-check-circle-fill'
    };
    
    $alertClass = match($type) {
        'success' => 'alert-success-modern bg-success-subtle text-success border border-success',
        'error', 'danger' => 'alert-danger-modern bg-danger-subtle text-danger border border-danger',
        'warning' => 'alert-warning-modern bg-warning-subtle text-warning border border-warning',
        'info' => 'alert-info-modern bg-warning bg-opacity-10 text-gold border border-warning border-opacity-25',
        default => 'alert-success-modern bg-success-subtle text-success border border-success'
    };
@endphp
<div {{ $attributes->merge(['class' => 'alert alert-modern alert-rounded-strong ' . $alertClass . ' alert-dismissible fade show']) }} role="alert">
    <i class="bi {{ $icon }} me-2"></i>{{ $slot }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

