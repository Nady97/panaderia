@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/recetas/index.blade.php
    PROPÓSITO: Listado principal del módulo de Recetas.
    ARQUITECTURA: Mantenimiento de legibilidad con Componentes Blade (<x-card>).
                  Código DRY, uniforme a todo el ecosistema.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Recetas -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-journal-richtext me-2 text-gold"></i> Libro de Recetas
                </h2>
                <p class="mb-0 text-muted">Administra las recetas y fórmulas maestras de producción</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('recetas.create') }}" class="btn btn-primary-panaderia text-nowrap">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Receta
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de resumen -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-success bg-opacity-10 text-success rounded-3">
                        <i class="bi bi-journal-check fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $recetasActivas ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Recetas Activas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-warning bg-opacity-10 text-warning rounded-3">
                        <i class="bi bi-pencil-square fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $recetasBorrador ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Borradores</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-danger bg-opacity-10 text-danger rounded-3">
                        <i class="bi bi-journal-x fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $recetasObsoletas ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Obsoletas/Inactivas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover border-primary">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-warning bg-opacity-10 text-warning rounded-3">
                        <i class="bi bi-collection fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $totalRecetas ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Total Fórmulas</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros Server-Side -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('recetas.index') }}" method="GET" class="row g-3 align-items-end" id="serverFilterForm">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-2" class="text-main fs-6">
                        <i class="bi bi-search me-1"></i> Buscar receta
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Buscar receta o producto vinculado..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2" class="text-main fs-6">Filtrar por Estado</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select name="estado_filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('estado_filter') == 'all' ? 'selected' : '' }}>Todos los estados</option>
                            <option value="activa" {{ request('estado_filter') == 'activa' ? 'selected' : '' }}>En uso (Activas)</option>
                            <option value="borrador" {{ request('estado_filter') == 'borrador' ? 'selected' : '' }}>En desarrollo (Borrador)</option>
                            <option value="obsoleta" {{ request('estado_filter') == 'obsoleta' ? 'selected' : '' }}>Archivada (Obsoleta)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="submit" class="btn btn-gold-panaderia w-100"><i class="bi bi-search"></i></button>
                    @if(request()->has('search') || request()->has('estado_filter'))
                        <a href="{{ route('recetas.index') }}" class="btn btn-light-panaderia w-100" title="Limpiar"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>

    <!-- Tabla Principal de Recetas Paginada -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <th class="py-3 px-4" class="text-muted text-uppercase fw-semibold small" class="text-muted">Receta / Producto</th>
                            <th class="py-3 px-4" class="text-muted text-uppercase fw-semibold small" class="text-muted">Métricas</th>
                            <th class="py-3 px-4 text-center" class="text-muted text-uppercase fw-semibold small" class="text-muted">Estado Operativo</th>
                            <th class="py-3 px-4 text-end" class="text-muted text-uppercase fw-semibold small" class="text-muted">Acciones</th>
                        </tr>
                    </thead>
                    <tbody >
                        @forelse($recetas as $receta)
                        <tr class="border-bottom-modern" style="transition: background 0.2s;">
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded d-flex align-items-center justify-content-center" class="detail-box icon-box">
                                        <i class="bi bi-journal-check" class="fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-main fs-6">
                                            <a href="{{ route('recetas.show', $receta->id) }}" class="text-decoration-none text-main" class="receta-link">{{ $receta->nombre }}</a>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-box-seam me-1"></i>Para: {{ $receta->producto ? $receta->producto->nombre : 'Sin producto asignado' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex flex-column gap-1">
                                    <span class="fs-6"><i class="bi bi-pie-chart me-2 text-muted" title="Rendimiento"></i>{{ $receta->rendimiento_estimado }} unidades</span>
                                    <span class="small text-muted"><i class="bi bi-clock me-2 text-muted" title="Tiempo de preparación"></i>{{ $receta->tiempo_preparacion_min }} min</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($receta->estado == 'activa')
                                    <span class="badge bg-light text-success border border-success border-opacity-25" class="rounded"><i class="bi bi-circle-fill me-1" class="small"></i>Activa</span>
                                @elseif($receta->estado == 'borrador')
                                    <span class="badge bg-light text-warning border border-warning border-opacity-25" class="rounded"><i class="bi bi-pencil-square me-1"></i>Borrador</span>
                                @else
                                    <span class="badge bg-light text-danger border border-danger border-opacity-25" class="rounded"><i class="bi bi-x-circle me-1"></i>Obsoleta/Inactiva</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('recetas.show', $receta->id) }}" class="btn btn-sm btn-light text-gold border" title="Ver Detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('recetas.pdf', $receta->id) }}" class="btn btn-sm btn-light text-secondary border" title="Descargar PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                    <a href="{{ route('recetas.edit', $receta->id) }}" class="btn btn-sm btn-light text-main border" title="Editar Receta">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('recetas.destroy', $receta->id) }}" method="POST" class="d-inline p-0 m-0 form-delete" data-confirm-text="¿Está seguro de que desea eliminar la receta {{$receta->nombre}}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" title="Eliminar Receta">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="mb-4 mt-3">
                                    <i class="bi bi-journal-x text-muted" class="display-3 opacity-25"></i>
                                </div>
                                <h5 class="fw-normal mb-1">No se encontraron recetas</h5>
                                <p class="text-muted small">Ajusta los filtros de búsqueda o registra una nueva fórmula panadera.</p>
                                @if(request()->has('search') || request()->has('estado_filter'))
                                    <a href="{{ route('recetas.index') }}" class="btn btn-outline-secondary mt-3">Limpiar filtros</a>
                                @else
                                    <a href="{{ route('recetas.create') }}" class="btn btn-primary-panaderia mt-3 text-nowrap"><i class="bi bi-plus-lg me-1"></i>Crear primera Receta</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enlaces de Paginación Nativos Bootstrap 5 -->
            @if(method_exists($recetas, 'hasPages') && $recetas->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3" class="border-top-modern">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $recetas->firstItem() }}</span> a <span class="fw-bold">{{ $recetas->lastItem() }}</span> de <span class="fw-bold">{{ $recetas->total() }}</span> registros
                    </div>
                </div>
                
                {{-- Contenedor especial de paginación --}}
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $recetas->links() }}
                </div>
            @endif
        </div>
    </x-card>
</div>
@endsection



