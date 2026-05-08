@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-main text-gold-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 text-main">Detalle del Producto</h2>
                    <p class="mb-0 text-secondary">Vista detallada de la información del inventario</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-gold-panaderia text-nowrap">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
                <a href="{{ route('productos.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
                </a>
            </div>
        </div>
    </x-card>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-8">
            <x-card class="h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-main border-bottom border-color pb-3">
                        <i class="bi bi-info-circle me-2 text-gold-dark"></i>Información Principal
                    </h5>
                    
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="detail-box p-3 rounded" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                                <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Nombre del Producto</span>
                                <span class="detail-value fs-5 fw-bold text-main">{{ $producto->nombre }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="detail-box p-3 rounded" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                                <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Descripción</span>
                                <span class="detail-value text-secondary">{{ $producto->descripcion ?? 'Sin descripción adicional para este producto.' }}</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-box p-3 rounded h-100" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                                <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Estado</span>
                                @if($producto->estado === 'activo')
                                    <x-badge type="success"><i class="bi bi-check-circle me-1"></i>Activo</x-badge>
                                @elseif($producto->estado === 'agotado')
                                    <x-badge type="danger"><i class="bi bi-x-circle me-1"></i>Agotado</x-badge>
                                @else
                                    <x-badge type="secondary"><i class="bi bi-dash-circle me-1"></i>Descontinuado</x-badge>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-box p-3 rounded h-100" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                                <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Categoría</span>
                                <span class="detail-value text-main fw-medium"><i class="bi bi-tag-fill text-gold-dark me-1"></i>{{ $producto->categoria ? $producto->categoria->nombre : 'Sin Categoría' }}</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-box p-3 rounded h-100" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                                <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Tipo de Origen</span>
                                @if($producto->es_producido)
                                    <span class="detail-value text-gold-dark fw-medium"><i class="bi bi-tools me-1"></i>Producción Propia</span>
                                @else
                                    <span class="detail-value text-gold fw-medium"><i class="bi bi-box-arrow-in-down me-1"></i>Comprado/Revendido</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-5 mb-4 text-main border-bottom border-color pb-3">
                        <i class="bi bi-currency-dollar me-2 text-gold-dark"></i>Información de Precios
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-box p-4 rounded h-100 text-center" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                                <span class="detail-label d-block text-muted small text-uppercase mb-2 fw-bold">Precio de Venta</span>
                                <span class="detail-value display-6 fw-bold text-success mb-0">${{ number_format($producto->precio_venta, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-box p-4 rounded h-100 text-center" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                                <span class="detail-label d-block text-muted small text-uppercase mb-2 fw-bold">Costo Base</span>
                                <span class="detail-value fs-3 fw-bold text-main mb-2 d-block">${{ number_format($producto->precio_costo, 2) }}</span>
                                <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2 px-3 rounded-pill">
                                    <i class="bi bi-graph-up-arrow me-1"></i> Margen bruto: ${{ number_format($producto->precio_venta - $producto->precio_costo, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
            <x-card class="h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-4 pb-4 border-bottom border-color">
                        <div class="d-inline-flex align-items-center justify-content-center bg-main rounded-circle mb-3" style="width: 120px; height: 120px; border: 4px solid var(--bg-card); box-shadow: 0 0 0 2px var(--gold-dark);">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="bi bi-image text-gold-dark" style="font-size: 3rem;"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold text-main mb-1">Imagen del Producto</h6>
                    </div>

                    <h5 class="fw-bold mb-4 text-main">
                        <i class="bi bi-box me-2 text-gold-dark"></i>Control de Inventario
                    </h5>
                    
                    <div class="mb-4 detail-box p-3 rounded" style="background: var(--bg-input); border: 1px solid var(--border-color);">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="detail-label text-muted small text-uppercase fw-bold">Stock Actual</span>
                            <span class="detail-value fs-5 fw-bold text-main">{{ $producto->stock }} und.</span>
                        </div>
                        @php
                            $minimo = (float)$producto->stock_minimo > 0 ? (float)$producto->stock_minimo : 5;
                            $stockPercent = min(100, ($producto->stock / ($minimo * 3)) * 100);
                            $stockColor = $producto->stock <= 0 ? 'var(--alert-danger)' : ($producto->stock <= $minimo ? 'var(--alert-warning)' : 'var(--alert-success)');
                        @endphp
                        <div class="progress mb-2" style="height: 10px; background-color: var(--bg-card); border-radius: 5px;">
                            <div class="progress-bar progress-bar-striped {{ $producto->stock > $minimo ? 'progress-bar-animated' : '' }}" role="progressbar" style="width: {{ $stockPercent }}%; background-color: {{ $stockColor }}; border-radius: 5px;" aria-valuenow="{{ $producto->stock }}" aria-valuemin="0" aria-valuemax="{{ $minimo * 3 }}"></div>
                        </div>
                        @if($producto->stock <= 0)
                            <div class="text-danger small fw-medium"><i class="bi bi-x-circle-fill me-1"></i> Producto agotado.</div>
                        @elseif($producto->stock <= $minimo)
                            <div class="text-warning small fw-medium"><i class="bi bi-exclamation-triangle-fill me-1"></i> Stock en nivel crítico (Debajo de {{ $minimo }}).</div>
                        @else
                            <div class="text-success small fw-medium"><i class="bi bi-check-circle-fill me-1"></i> Stock en niveles saludables.</div>
                        @endif
                    </div>

                    <div class="p-3 bg-light rounded" style="background: var(--bg-input) !important; border: 1px solid var(--border-color);">
                        <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                            <li class="d-flex justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--border-color) !important;">
                                <span class="text-muted"><i class="bi bi-flag me-2 text-gold-dark"></i>Stock Mínimo</span>
                                <span class="fw-bold text-main">{{ $producto->stock_minimo }} und.</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--border-color) !important;">
                                <span class="text-muted"><i class="bi bi-clock-history me-2 text-gold-dark"></i>Creado</span>
                                <span class="fw-medium text-end text-secondary">{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-arrow-repeat me-2 text-gold-dark"></i>Actualizado</span>
                                <span class="fw-medium text-end text-secondary">{{ $producto->updated_at ? $producto->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @vite(['resources/js/productos.js'])
@endpush

