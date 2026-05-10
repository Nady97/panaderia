@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-arrow-repeat text-gold me-2"></i>Gestión de Producción
                </h2>
                <p class="mb-0 text-muted">Listado de órdenes de producción activas e históricas.</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('produccion.create') }}" class="btn btn-primary-panaderia text-nowrap">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Orden
                </a>
            </div>
        </div>
    </x-card>

    <!-- Buscador Integrado y Filtros Server-Side -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('produccion.index') }}" method="GET" class="row align-items-end g-3" id="serverFilterForm">
                @if(request()->has('estado') && request('estado') != '')
                    <input type="hidden" name="estado" value="{{ request('estado') }}">
                @endif
                <div class="col-md-7">
                    <label class="form-label fw-bold mb-2 text-main" style="font-size: 0.9rem;">
                        <i class="bi bi-search me-1"></i> Buscar Orden
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Buscar por lote, producto o descripción..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-gold-panaderia px-4"><i class="bi bi-search text-white"></i></button>
                        @if(request()->has('search') || request()->has('estado'))
                            <a href="{{ route('produccion.index') }}" class="btn btn-light-panaderia" title="Limpiar"><i class="bi bi-x-circle text-danger"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-5 text-md-end">
                    <label class="form-label fw-bold mb-2 text-main text-start w-100 text-md-end" style="font-size: 0.9rem;">
                        <i class="bi bi-funnel me-1"></i> Filtrar por Estado
                    </label>
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('produccion.index', ['search' => request('search')]) }}" 
                           class="btn {{ !request('estado') ? 'btn-primary-panaderia text-white bg-primary' : 'btn-outline-secondary' }}">
                            Todos
                        </a>
                        <a href="{{ route('produccion.index', ['estado' => 'planificado', 'search' => request('search')]) }}" 
                           class="btn {{ request('estado') == 'planificado' ? 'btn-primary-panaderia text-white bg-primary' : 'btn-outline-secondary' }}">
                            Planificados
                        </a>
                        <a href="{{ route('produccion.index', ['estado' => 'en_proceso', 'search' => request('search')]) }}" 
                           class="btn {{ request('estado') == 'en_proceso' ? 'btn-primary-panaderia text-white bg-primary' : 'btn-outline-secondary' }}">
                            En proceso
                        </a>
                        <a href="{{ route('produccion.index', ['estado' => 'completado', 'search' => request('search')]) }}" 
                           class="btn {{ request('estado') == 'completado' ? 'btn-primary-panaderia text-white bg-primary' : 'btn-outline-secondary' }}">
                            Completados
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </x-card>

    <!-- Tabla Principal de Produccion Paginada -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Lote</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Producto</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Cantidad</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Fecha Programada</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Estado</th>
                            <th class="py-3 px-4 text-end" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producciones as $produccion)
                            <tr class="border-bottom-modern" style="transition: background 0.2s;">
                                {{-- Lote --}}
                                <td class="py-3 px-4">
                                    <span class="fw-bold" style="font-family: monospace; font-size: 0.95rem;">
                                        {{ $produccion->lote_codigo ?? '#' . $produccion->id }}
                                    </span>
                                    @if($produccion->descripcion)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($produccion->descripcion, 30) }}</small>
                                    @endif
                                </td>
                                
                                {{-- Producto --}}
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--bg-primary); color: var(--gold-dark); border: 1px solid var(--border-color);">
                                            <i class="bi bi-box-seam" style="font-size: 1.2rem;"></i>
                                        </div>
                                        <div>
                                            @if($produccion->producto)
                                                <div class="fw-bold" style="font-size: 1rem;">
                                                    {{ $produccion->producto->nombre }}
                                                </div>
                                            @else
                                                <div class="fw-bold text-danger text-truncate" title="Producto ID: {{ $produccion->receta_id }}">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Producto no encontrado
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Cantidad --}}
                                <td class="py-3 px-4">
                                    <span class="fw-bold text-main" style="font-size: 0.95rem;">
                                        {{ number_format($produccion->cantidad_producida, 2) }} 
                                        {{ $produccion->producto?->unidad_medida ?? 'uds' }}
                                    </span>
                                </td>
                                
                                {{-- Fecha Programada --}}
                                <td class="py-3 px-4">
                                    <div class="d-flex flex-column gap-1">
                                        <span style="font-size: 0.9rem;">
                                            <i class="bi bi-calendar3 me-2 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($produccion->fecha_programada)->format('d/m/Y') }}
                                        </span>
                                        @if($produccion->hora_inicio)
                                            <span style="font-size: 0.85rem; color: var(--text-secondary);">
                                                <i class="bi bi-clock me-2 text-muted"></i>
                                                {{ \Carbon\Carbon::parse($produccion->hora_inicio)->format('H:i') }}
                                                @if($produccion->hora_fin)
                                                    - {{ \Carbon\Carbon::parse($produccion->hora_fin)->format('H:i') }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                {{-- Estado --}}
                                <td class="py-3 px-4">
                                    @switch($produccion->estado)
                                        @case('planificado')
                                            <x-badge type="secondary">
                                                <i class="bi bi-calendar-check me-1"></i> Planificado
                                            </x-badge>
                                            @break
                                            
                                        @case('en_proceso')
                                            <x-badge type="warning">
                                                <i class="bi bi-hourglass-split me-1"></i> En Proceso
                                            </x-badge>
                                            @break
                                            
                                        @case('completado')
                                            <x-badge type="success">
                                                <i class="bi bi-check-circle me-1"></i> Completado
                                            </x-badge>
                                            @break
                                            
                                        @case('fallido')
                                            <x-badge type="danger">
                                                <i class="bi bi-x-circle me-1"></i> Cancelado
                                            </x-badge>
                                            @break
                                            
                                        @default
                                            <x-badge type="light">
                                                {{ $produccion->estado }}
                                            </x-badge>
                                    @endswitch
                                </td>
                                
                                {{-- Acciones --}}
                                <td class="py-3 px-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        {{-- Ver detalles --}}
                                        <a href="{{ route('produccion.show', $produccion->id) }}" 
                                           class="btn btn-sm btn-light border text-gold" 
                                           title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('produccion.edit', $produccion->id) }}" 
                                           class="btn btn-sm btn-light border text-main" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        {{-- Eliminar --}}
                                        <form action="{{ route('produccion.destroy', $produccion->id) }}" 
                                              method="POST" 
                                              class="d-inline p-0 m-0 form-delete" 
                                              data-confirm-text="¿Eliminar la orden de producción {{ $produccion->lote_codigo ?? '#' . $produccion->id }}? Si estaba completada, se revertirá el inventario.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light border text-danger" 
                                                    title="Eliminar"
                                                    {{ $produccion->estado === 'en_proceso' ? 'disabled' : '' }}>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-0 border-0">
                                    <x-empty-state 
                                        icon="bi-arrow-repeat" 
                                        title="No hay Órdenes de Producción" 
                                        description="Lleva el control de los panes horneados diariamente registrando tus órdenes y su impacto en inventario."
                                        buttonLabel="Crear Primera Orden"
                                        buttonRoute="{{ route('produccion.create') }}"
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enlaces de Paginación Nativos Bootstrap 5 -->
            @if($producciones->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3 border-top-modern">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $producciones->firstItem() }}</span> a <span class="fw-bold">{{ $producciones->lastItem() }}</span> de <span class="fw-bold">{{ $producciones->total() }}</span> registros
                    </div>
                </div>
                
                {{-- Contenedor especial de paginación --}}
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $producciones->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmación para eliminar
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const confirmText = this.dataset.confirmText || '¿Estás seguro de eliminar este registro?';
            
            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
    
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@section('content')
<div class="dashboard-container p-4 animate-fade-in">
    
    <!-- Encabezado y Acciones -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h2 class="h3 mb-0 text-main fw-bold">
                <i class="bi bi-arrow-repeat text-gold me-2"></i> Gestión de Producción
            </h2>
            <p class="text-secondary mt-1 mb-0">Listado de órdenes de producción activas e históricas.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('produccion.create') }}" class="btn btn-primary-panaderia shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Nueva Orden
            </a>
        </div>
    </div>

    {{-- Filtros rápidos --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('produccion.index') }}" 
                   class="btn {{ !request('estado') ? 'btn-primary-panaderia' : 'btn-outline-secondary' }}">
                    Todos
                </a>
                <a href="{{ route('produccion.index', ['estado' => 'planificado']) }}" 
                   class="btn {{ request('estado') == 'planificado' ? 'btn-primary-panaderia' : 'btn-outline-secondary' }}">
                    Planificados
                </a>
                <a href="{{ route('produccion.index', ['estado' => 'en_proceso']) }}" 
                   class="btn {{ request('estado') == 'en_proceso' ? 'btn-primary-panaderia' : 'btn-outline-secondary' }}">
                    En Proceso
                </a>
                <a href="{{ route('produccion.index', ['estado' => 'completado']) }}" 
                   class="btn {{ request('estado') == 'completado' ? 'btn-primary-panaderia' : 'btn-outline-secondary' }}">
                    Completados
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta Principal de la Tabla -->
    <x-card class="border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            @if($producciones->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-primary text-secondary">
                            <tr>
                                <th class="px-4 py-3">Lote</th>
                                <th class="px-4 py-3">Producto</th>
                                <th class="px-4 py-3">Cantidad</th>
                                <th class="px-4 py-3">Fecha Programada</th>
                                <th class="px-4 py-3">Estado</th>
                                <th class="px-4 py-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach($producciones as $produccion)
                                <tr>
                                    {{-- Lote --}}
                                    <td class="px-4 py-3">
                                        <span class="fw-medium">{{ $produccion->lote_codigo ?? '#' . $produccion->id }}</span>
                                        @if($produccion->descripcion)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($produccion->descripcion, 30) }}</small>
                                        @endif
                                    </td>
                                    
                                    {{-- Producto --}}
                                    <td class="px-4 py-3 fw-medium">
                                        @if($produccion->producto)
                                            {{ $produccion->producto->nombre }}
                                        @else
                                            <span class="text-danger" title="Producto ID: {{ $produccion->receta_id }}">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Producto no encontrado
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- Cantidad --}}
                                    <td class="px-4 py-3">
                                        <span class="badge bg-soft-blue text-info border border-info border-opacity-25 rounded-pill px-3 py-2">
                                            {{ number_format($produccion->cantidad_producida, 2) }} 
                                            {{ $produccion->producto?->unidad_medida ?? 'unid' }}
                                        </span>
                                    </td>
                                    
                                    {{-- Fecha Programada --}}
                                    <td class="px-4 py-3 text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ \Carbon\Carbon::parse($produccion->fecha_programada)->format('d/m/Y') }}
                                        
                                        @if($produccion->hora_inicio)
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($produccion->hora_inicio)->format('H:i') }}
                                                @if($produccion->hora_fin)
                                                    - {{ \Carbon\Carbon::parse($produccion->hora_fin)->format('H:i') }}
                                                @endif
                                            </small>
                                        @endif
                                    </td>
                                    
                                    {{-- Estado --}}
                                    <td class="px-4 py-3">
                                        @switch($produccion->estado)
                                            @case('planificado')
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-2">
                                                    <i class="bi bi-calendar-check me-1"></i> Planificado
                                                </span>
                                                @break
                                                
                                            @case('en_proceso')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-2">
                                                    <i class="bi bi-hourglass-split me-1"></i> En Proceso
                                                </span>
                                                @break
                                                
                                            @case('completado')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-2">
                                                    <i class="bi bi-check-circle me-1"></i> Completado
                                                </span>
                                                @break
                                                
                                            @case('fallido')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-2">
                                                    <i class="bi bi-x-circle me-1"></i> Cancelado
                                                </span>
                                                @break
                                                
                                            @default
                                                <span class="badge bg-light text-dark px-3 py-2 rounded-2">
                                                    {{ $produccion->estado }}
                                                </span>
                                        @endswitch
                                    </td>
                                    
                                    {{-- Acciones --}}
                                    <td class="px-4 py-3 text-end">
                                        <div class="btn-group" role="group">
                                            {{-- Ver detalles --}}
                                            <a href="{{ route('produccion.show', $produccion->id) }}" 
                                               class="btn btn-sm btn-light-panaderia me-1" 
                                               title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            {{-- Editar --}}
                                            <a href="{{ route('produccion.edit', $produccion->id) }}" 
                                               class="btn btn-sm btn-light-panaderia me-1" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            {{-- Eliminar --}}
                                            <form action="{{ route('produccion.destroy', $produccion->id) }}" 
                                                  method="POST" 
                                                  class="d-inline form-delete" 
                                                  data-confirm-text="¿Eliminar la orden de producción {{ $produccion->lote_codigo ?? '#' . $produccion->id }}? Si estaba completada, se revertirá el inventario.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-light-panaderia text-danger" 
                                                        title="Eliminar"
                                                        {{ $produccion->estado === 'en_proceso' ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-4 py-3 border-top bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                    <span class="text-secondary small">
                        Mostrando {{ $producciones->firstItem() }} a {{ $producciones->lastItem() }} de {{ $producciones->total() }} órdenes
                    </span>
                    <div class="paginacion-personalizada">
                        {{ $producciones->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="p-5 text-center empty-state-container">
                    <div class="empty-state-icon mb-3">
                        <div class="icon-wrapper">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                    </div>
                    <h4 class="empty-state-title mb-2">No hay Órdenes de Producción</h4>
                    <p class="empty-state-desc mb-4">
                        Lleva el control de los panes horneados diariamente registrando tus órdenes y su impacto en inventario.
                    </p>
                    <a href="{{ route('produccion.create') }}" class="btn btn-primary-panaderia">
                        <i class="bi bi-plus-lg me-1"></i> Crear Primera Orden
                    </a>
                </div>
            @endif
        </div>
    </x-card>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmación para eliminar
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const confirmText = this.dataset.confirmText || '¿Estás seguro de eliminar este registro?';
            
            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
    
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection