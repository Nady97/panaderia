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
                @can('produccion.create')
                    <a href="{{ route('produccion.create') }}" class="btn btn-primary-panaderia text-nowrap">
                        <i class="bi bi-plus-lg me-1"></i> Nueva Orden
                    </a>
                @endcan
            </div>
        </div>
    </x-card>

    <!-- Buscador y Filtros -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('produccion.index') }}" method="GET" class="row g-3 align-items-end" id="serverFilterForm">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-2 text-main">
                        <i class="bi bi-search me-1"></i> Buscar orden
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Buscar por producto o descripción..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2 text-main">Filtrar por estado</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select name="estado" class="form-select" onchange="this.form.submit()">
                            <option value="" {{ !request('estado') ? 'selected' : '' }}>Todos los estados</option>
                            <option value="planificado" {{ request('estado') == 'planificado' ? 'selected' : '' }}>Planificados</option>
                            <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                            <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completados</option>
                            <option value="fallido" {{ request('estado') == 'fallido' ? 'selected' : '' }}>Cancelados</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="submit" class="btn btn-gold-panaderia w-100">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request()->has('search') || request()->has('estado'))
                        <a href="{{ route('produccion.index') }}" class="btn btn-light-panaderia w-100" title="Limpiar">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>

    <!-- Tabla de Producción -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <th class="py-3 px-4 text-muted text-uppercase fw-semibold small">Producto</th>
                            <th class="py-3 px-4 text-muted text-uppercase fw-semibold small">Cantidad</th>
                            <th class="py-3 px-4 text-muted text-uppercase fw-semibold small">Fecha Programada</th>
                            <th class="py-3 px-4 text-muted text-uppercase fw-semibold small">Estado</th>
                            <th class="py-3 px-4 text-end text-muted text-uppercase fw-semibold small">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producciones as $produccion)
                            <tr class="border-bottom-modern" style="transition: background 0.2s;">
                                <td class="py-3 px-4">
                                    @if($produccion->producto)
                                        <span class="fw-bold text-main">{{ $produccion->producto->nombre }}</span>
                                    @else
                                        <span class="text-danger">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Producto no encontrado
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="py-3 px-4">
                                    <span class="fw-bold">
                                        {{ number_format($produccion->cantidad_producida, 2) }} uds
                                    </span>
                                </td>
                                
                                <td class="py-3 px-4">
                                    <i class="bi bi-calendar3 me-2 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($produccion->fecha_programada)->format('d/m/Y') }}
                                </td>
                                
                                <td class="py-3 px-4">
                                    @switch($produccion->estado)
                                        @case('planificado')
                                            <x-badge type="secondary"><i class="bi bi-calendar-check me-1"></i> Planificado</x-badge>
                                            @break
                                        @case('en_proceso')
                                            <x-badge type="warning"><i class="bi bi-hourglass-split me-1"></i> En Proceso</x-badge>
                                            @break
                                        @case('completado')
                                            <x-badge type="success"><i class="bi bi-check-circle me-1"></i> Completado</x-badge>
                                            @break
                                        @case('fallido')
                                            <x-badge type="danger"><i class="bi bi-x-circle me-1"></i> Cancelado</x-badge>
                                            @break
                                        @default
                                            <x-badge type="light">{{ $produccion->estado }}</x-badge>
                                    @endswitch
                                </td>
                                
                                <td class="py-3 px-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        @can('produccion.view')
                                            <a href="{{ route('produccion.show', $produccion->id) }}" 
                                               class="btn btn-sm btn-light border text-gold" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endcan

                                        @can('produccion.edit')
                                            <a href="{{ route('produccion.edit', $produccion->id) }}" 
                                               class="btn btn-sm btn-light border text-main" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        @if($produccion->estado === 'planificado')
                                            @can('solicitudes_produccion.create')
                                                <button type="button" class="btn btn-sm btn-warning border-0" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalUrgencia{{ $produccion->id }}"
                                                        title="Marcar como urgente">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                </button>
                                            @endcan
                                        @endif
                                        
                                        @can('produccion.delete')
                                            <form action="{{ route('produccion.destroy', $produccion->id) }}" 
                                                  method="POST" class="d-inline form-delete" 
                                                  data-confirm-text="¿Eliminar esta orden de producción?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Eliminar"
                                                        {{ $produccion->estado === 'en_proceso' ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de Urgencia para esta producción -->
                            @if($produccion->estado === 'planificado')
                                <div class="modal fade" id="modalUrgencia{{ $produccion->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('solicitudes_produccion.crear', $produccion) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom">
                                                    <h5 class="modal-title fw-bold text-main">
                                                        <i class="bi bi-exclamation-triangle me-2"></i> Solicitar Producción Urgente
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted mb-3">Producto: <strong>{{ $produccion->producto->nombre ?? 'N/A' }}</strong></p>
                                                    
                                                    <div class="mb-3">
                                                        <label for="tipo_urgencia{{ $produccion->id }}" class="form-label fw-semibold">Tipo de Urgencia</label>
                                                        <select name="tipo_urgencia" id="tipo_urgencia{{ $produccion->id }}" class="form-select" required onchange="toggleMotivo('{{ $produccion->id }}')">
                                                            <option value="">Selecciona una opción...</option>
                                                            <option value="urgente">Urgente</option>
                                                            <option value="muy_urgente">Muy Urgente</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3" id="motivo-{{ $produccion->id }}" style="display: none;">
                                                        <label for="motivo_urgencia{{ $produccion->id }}" class="form-label fw-semibold">Motivo</label>
                                                        <textarea name="motivo_urgencia" id="motivo_urgencia{{ $produccion->id }}" class="form-control" rows="3" placeholder="Explica por qué es urgente..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="bi bi-check-circle me-1"></i> Solicitar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="p-0 border-0">
                                    <x-empty-state 
                                        icon="bi-arrow-repeat" 
                                        title="No hay Órdenes de Producción" 
                                        description="Registra tus órdenes de producción para llevar el control."
                                    >
                                        @can('produccion.create')
                                            <a href="{{ route('produccion.create') }}" class="btn btn-primary-panaderia mt-3">
                                                <i class="bi bi-plus-lg me-1"></i>Crear Primera Orden
                                            </a>
                                        @endcan
                                    </x-empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($producciones->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3">
                    <div class="text-muted small">
                        Mostrando {{ $producciones->firstItem() }} a {{ $producciones->lastItem() }} de {{ $producciones->total() }} registros
                    </div>
                </div>
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
function toggleMotivo(produccionId) {
    const select = document.getElementById('tipo_urgencia' + produccionId);
    const motivoField = document.getElementById('motivo-' + produccionId);
    const motivoTextarea = document.getElementById('motivo_urgencia' + produccionId);
    
    if (select.value && select.value !== '') {
        motivoField.style.display = 'block';
        motivoTextarea.required = true;
    } else {
        motivoField.style.display = 'none';
        motivoTextarea.required = false;
        motivoTextarea.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: this.dataset.confirmText || '¿Estás seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
});
</script>
@endpush