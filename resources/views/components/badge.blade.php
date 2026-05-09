@props(['type' => null, 'active' => null])
@php
    $badgeClass = '';
    $text = '';

    // Si viene la prop 'active' (true/false)
    if ($active !== null) {
        $badgeClass = $active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200';
        $text = $active ? 'Activo' : 'Inactivo';
    }
    // Si viene la prop nueva 'type' (success, warning, danger, info, etc)
    elseif ($type !== null) {
        $badgeClass = match($type) {
            'success' => 'bg-green-50 text-green-700 border border-green-200',
            'warning' => 'bg-yellow-50 text-yellow-800 border border-yellow-200',
            'danger' => 'bg-red-50 text-red-700 border border-red-200',
            'info' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'primary' => 'bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-color)]',
            'gold' => 'bg-yellow-400 text-gray-900 border border-yellow-500',
            default => 'bg-gray-100 text-gray-600 border border-gray-200'
        };
    } else {
        $badgeClass = 'bg-gray-100 text-gray-600 border border-gray-200';
    }
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-semibold ' . $badgeClass]) }}>
    {{ $slot->isEmpty() ? $text : $slot }}
</span>


