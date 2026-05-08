@props([
    'icon' => 'bi-inbox',
    'title' => 'No hay registros',
    'description' => 'Aún no se ha añadido información en esta sección.',
    'buttonLabel' => null,
    'buttonRoute' => null
])

<div class="empty-state-container text-center py-5">
    <div class="empty-state-icon mb-4">
        <div class="icon-wrapper d-inline-flex align-items-center justify-content-center">
            <i class="bi {{ $icon }}"></i>
        </div>
    </div>
    
    <h4 class="empty-state-title fw-bold mb-2">{{ $title }}</h4>
    <p class="empty-state-desc mb-4 mx-auto" style="max-width: 400px;">
        {{ $description }}
    </p>

    @if($buttonLabel && $buttonRoute)
        <a href="{{ $buttonRoute }}" class="btn btn-primary-panaderia px-4 py-2 mt-2">
            <i class="bi bi-plus-lg me-2"></i> {{ $buttonLabel }}
        </a>
    @endif
    
    {{ $slot }}
</div>
