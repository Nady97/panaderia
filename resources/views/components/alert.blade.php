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
        'success' => 'bg-green-50 text-green-700 border border-green-200',
        'error', 'danger' => 'bg-red-50 text-red-700 border border-red-200',
        'warning' => 'bg-yellow-50 text-yellow-800 border border-yellow-200',
        'info' => 'bg-blue-50 text-blue-700 border border-blue-200',
        default => 'bg-green-50 text-green-700 border border-green-200'
    };
@endphp
<div {{ $attributes->merge(['class' => 'flex items-start gap-2 rounded-xl px-4 py-3 text-sm font-medium ' . $alertClass]) }} role="alert" data-alert>
    <i class="bi {{ $icon }} mt-0.5"></i>
    <div class="flex-1">{{ $slot }}</div>
    <button type="button" class="ml-3 text-gray-500 hover:text-gray-700" aria-label="Close" onclick="this.closest('[data-alert]').remove()">
        <i class="bi bi-x-lg"></i>
    </button>
</div>

