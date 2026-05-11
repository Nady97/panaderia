@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Insumos -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-basket me-2 text-gold"></i> Gestion de Insumos
                </h2>
                <p class="mb-0 text-muted">Control de insumos disponibles y puntos de reposicion</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('insumos.create') }}" class="btn btn-primary-panaderia text-nowrap">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Insumo
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de resumen -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 12px; color: var(--gold-dark);">
                        <i class="bi bi-basket fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $totalInsumos ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Total Insumos</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-4 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(245, 158, 11, 0.1); border-radius: 12px; color: #f59e0b;">
                        <i class="bi bi-exclamation-triangle fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $stockBajo ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Stock Bajo</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-4 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(220, 38, 38, 0.1); border-radius: 12px; color: var(--danger);">
                        <i class="bi bi-x-circle fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $sinStock ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Agotados</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Barra de Busqueda y Filtros Server-Side -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('insumos.index') }}" method="GET" class="row g-3 align-items-end" id="serverFilterForm">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-2 text-main">
                        <i class="bi bi-search me-1"></i> Buscar insumo
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-basket"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Nombre del insumo..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2 text-main">Filtrar por Stock</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select name="stock_filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('stock_filter') == 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Stock bajo</option>
                            <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Agotados</option>
                            <option value="ok" {{ request('stock_filter') == 'ok' ? 'selected' : '' }}>Stock saludable</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="submit" class="btn btn-gold-panaderia w-100"><i class="bi bi-search"></i></button>
                    @if(request()->has('search') || request()->has('stock_filter'))
                        <a href="{{ route('insumos.index') }}" class="btn btn-light-panaderia w-100" title="Limpiar"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>

    <!-- Tabla Principal Paginada -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Nro</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Insumo</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Unidad</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Stock</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Minimo</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Costo Prom.</th>
                            <th class="py-3 px-4 text-end" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($insumos as $insumo)
                        <tr class="border-bottom-modern" style="transition: background 0.2s;">
                            <td class="py-3 px-4">{{ ($insumos->currentPage() - 1) * $insumos->perPage() + $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                <span class="fw-medium text-main">{{ $insumo->nombre }}</span>
                            </td>
                            <td class="py-3 px-4">{{ $insumo->unidad_medida }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $minimo = $insumo->stock_minimo ?? 0;
                                @endphp
                                @if($insumo->stock_actual <= 0)
                                    <x-badge type="danger"><i class="bi bi-x-circle me-1"></i>{{ $insumo->stock_actual }}</x-badge>
                                @elseif($minimo > 0 && $insumo->stock_actual <= $minimo)
                                    <x-badge type="warning"><i class="bi bi-exclamation-triangle me-1"></i>{{ $insumo->stock_actual }}</x-badge>
                                @else
                                    <x-badge type="success"><i class="bi bi-check-circle me-1"></i>{{ $insumo->stock_actual }}</x-badge>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $insumo->stock_minimo ?? '-' }}</td>
                            <td class="py-3 px-4">Bs {{ number_format($insumo->precio_compra_promedio ?? 0, 2) }}</td>
                            <td class="py-3 px-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('insumos.show', $insumo->id) }}" class="btn btn-sm btn-light text-gold border" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('insumos.edit', $insumo->id) }}" class="btn btn-sm btn-light text-secondary border" title="Editar Insumo">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('insumos.destroy', $insumo->id) }}" method="POST" class="d-inline form-delete" data-confirm-text="¿Desea eliminar el insumo {{ $insumo->nombre }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" title="Eliminar Insumo">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-0 border-0">
                                <x-empty-state
                                    icon="bi-basket"
                                    title="No se encontraron insumos"
                                    description="Agrega insumos para controlar costos y consumo de recetas."
                                >
                                    @if(request()->has('search') || request()->has('stock_filter'))
                                        <a href="{{ route('insumos.index') }}" class="btn btn-outline-secondary mt-3">Limpiar filtros</a>
                                    @else
                                        <a href="{{ route('insumos.create') }}" class="btn btn-primary-panaderia mt-3 text-nowrap"><i class="bi bi-plus-lg me-1"></i>Crear primer Insumo</a>
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($insumos->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3" class="border-top-modern">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $insumos->firstItem() }}</span> a <span class="fw-bold">{{ $insumos->lastItem() }}</span> de <span class="fw-bold">{{ $insumos->total() }}</span> registros
                    </div>
                </div>
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $insumos->links() }}
                </div>
            @endif
        </div>
    </x-card>
</div>
@endsection
