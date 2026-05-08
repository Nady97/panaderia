@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Categorías -->
    <x-card class="mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-tags me-2 text-gold"></i> Gestión de Categorías
                </h2>
                <p class="mb-0 text-muted">Organiza y clasifica tu catálogo de inventario</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('categorias.create') }}" class="btn btn-primary-panaderia text-nowrap">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de resumen (Globales) -->
    <div class="row g-4 mb-4">
        <!-- Total -->
        <div class="col-md-4">
            <x-card class="h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-monospace text-uppercase text-xs">Total Categorías</p>
                            <h4 class="fw-bold mb-0 text-main">{{ $totalCategorias ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-soft-gold text-gold" style="width: 48px; height: 48px;">
                            <i class="bi bi-tags fs-4"></i>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        <!-- Activas -->
        <div class="col-md-4">
            <x-card class="h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-monospace text-uppercase text-xs">Activas</p>
                            <h4 class="fw-bold mb-0 text-main">{{ $categoriasActivas ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-soft-green text-success" style="width: 48px; height: 48px;">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        <!-- Inactivas -->
        <div class="col-md-4">
            <x-card class="h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-monospace text-uppercase text-xs">Inactivas</p>
                            <h4 class="fw-bold mb-0 text-main">{{ $categoriasInactivas ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-soft-secondary text-secondary" style="width: 48px; height: 48px;">
                            <i class="bi bi-x-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros Server-Side -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('categorias.index') }}" method="GET" class="row g-3 align-items-end" id="serverFilterForm">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-2 text-main text-sm">
                        <i class="bi bi-search me-1"></i> Buscar categoría
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Nombre o descripción..." value="{{ request('search') }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2 text-main text-sm">Filtrar por Estado</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select name="status_filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>Todos los estados</option>
                            <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>En uso (Activas)</option>
                            <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Desactivadas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="submit" class="btn btn-gold-panaderia w-100"><i class="bi bi-search"></i></button>
                    @if(request()->has('search') || request()->has('status_filter'))
                        <a href="{{ route('categorias.index') }}" class="btn btn-light-panaderia w-100" title="Limpiar"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>

    <!-- Tabla Principal Paginada -->
    <x-card>
        <div class="card-body p-4">
            <div class="table-responsive m-0">
                <table class="table table-hover align-middle mb-0 text-main">
                <thead class="bg-primary-custom border-bottom-custom border-2">
                    <tr>
                        <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted border-0">#</th>
                        <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted border-0">Nombre y Detalle</th>
                        <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted text-center border-0">Items Asociados</th>
                        <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted text-center border-0">Estado Operativo</th>
                        <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted text-end border-0">Acciones</th>
                    </tr>
                </thead>
                <tbody >
                    @forelse($categorias as $categoria)
                    <tr class="categoria-fila border-bottom-custom transition-bg">
                        <td class="py-3 px-4 text-muted">{{ ($categorias->currentPage() - 1) * $categorias->perPage() + $loop->iteration }}</td>
                        <td class="py-3 px-4">
                            <span class="fw-medium d-block text-main">
                                {{ $categoria->nombre }}
                            </span>
                            @if($categoria->descripcion)
                                <small class="text-muted text-truncate d-inline-block max-w-250"><i class="bi bi-text-left pe-1"></i>{{ $categoria->descripcion }}</small>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if(isset($categoria->productos_count) && $categoria->productos_count > 0)
                                <a href="{{ route('categorias.show', $categoria->id) }}" class="text-decoration-none" title="Ver productos de esta categoría">
                                    <span class="badge bg-main text-white rounded-3 cursor-pointer p-2 fw-normal"><i class="bi bi-box me-1"></i>{{ $categoria->productos_count }} Productos</span>
                                </a>
                            @else
                                <span class="badge bg-light text-secondary border border-secondary border-opacity-25 rounded-3 p-2 fw-normal">Categoría vacía</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($categoria->activo)
                                <span class="badge bg-soft-green text-success border border-success border-opacity-25 rounded-3 p-2 fw-normal"><i class="bi bi-circle-fill me-1 text-xs"></i>Activa</span>
                            @else
                                <span class="badge bg-soft-secondary rounded-3 p-2 fw-normal"><i class="bi bi-circle-fill me-1 text-xs opacity-50"></i>Inactiva</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-end">
                            <div class="d-flex justify-content-end gap-2 acciones">
                                <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-sm btn-light text-gold border" title="Ver Productos">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-sm btn-light text-secondary border" title="Editar Categoría">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <!-- Confirmación de eliminación embebida minimalista -->
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline form-delete" data-confirm-text="¿Está seguro de que desea eliminar la categoría {{$categoria->nombre}}? Productos huérfanos se quedarán sin categoría.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border" title="Eliminar Categoría">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 border-0">
                            <x-empty-state 
                                icon="bi-tags" 
                                title="No se encontraron categorías" 
                                description="Ajusta los filtros de búsqueda o agrega una nueva familia de productos para organizar el catálogo."
                            >
                                @if(request()->has('search') || request()->has('status_filter'))
                                    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary mt-3">Limpiar filtros</a>
                                @else
                                    <a href="{{ route('categorias.create') }}" class="btn btn-primary-panaderia mt-3 text-nowrap"><i class="bi bi-plus-lg me-1"></i>Crear primera Categoría</a>
                                @endif
                            </x-empty-state>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Enlaces de Paginación Nativos Bootstrap 5 -->
            @if($categorias->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3" class="border-top-modern">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $categorias->firstItem() }}</span> a <span class="fw-bold">{{ $categorias->lastItem() }}</span> de <span class="fw-bold">{{ $categorias->total() }}</span> registros
                    </div>
                </div>
                
                {{-- Contenedor especial de paginación --}}
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $categorias->links() }}
                </div>
            @endif

        </div>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/categorias.js'])
@endpush


