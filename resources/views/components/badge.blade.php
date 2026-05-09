@props(['type' => null, 'active' => null])
@php
    $badgeClass = '';
    $text = '';
    
    // Si viene la prop 'active' (true/false)
    if ($active !== null) {
        $badgeClass = $active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle';
        $text = $active ? 'Activo' : 'Inactivo';
    } 
    // Si viene la prop nueva 'type' (success, warning, danger, info, etc)
    elseif ($type !== null) {
        $badgeClass = match($type) {
            'success' => 'bg-success-subtle text-success border border-success-subtle',
            'warning' => 'bg-warning-subtle text-warning border border-warning-subtle',
            'danger' => 'bg-danger-subtle text-danger border border-danger-subtle',
            'info' => 'bg-warning bg-opacity-10 text-gold border border-warning border-opacity-25',
            'primary' => 'bg-main bg-opacity-10 text-main border border-main border-opacity-25',
            'gold' => 'bg-warning text-dark border border-warning', // Gold fallback
            default => 'bg-secondary-subtle text-secondary border border-secondary-subtle'
        };
    } else {
        $badgeClass = 'bg-secondary-subtle text-secondary border border-secondary-subtle';
    }
@endphp
<span {{ $attributes->merge(['class' => 'badge rounded-pill ' . $badgeClass]) }}>
    {{ $slot->isEmpty() ? $text : $slot }}
</span>


