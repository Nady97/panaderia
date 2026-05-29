@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-lightning me-2 text-gold"></i> Solicitudes de Producción
                </h2>
                <p class="mb-0 text-muted">produccion urgente o de emergencia</p>
            </div>
        </div>
    </x-card>

    <!-- Información del módulo 
    <x-card class="mb-4" style="background: linear-gradient(135deg, #f0e6dd 0%, #faf7f2 100%);">
        <div class="p-3">
            <h5 class="fw-bold text-main mb-2">
                <i class="bi bi-info-circle me-2"></i> ¿Cómo funciona?
            </h5>
            <ul class="mb-0 ms-3">
                <li class="mb-1"><strong>Crear solicitud:</strong> Desde una producción planificada, marca si es normal, urgente o muy urgente. Si es urgente, explica el motivo.</li>
                <li class="mb-1"><strong>Flujo:</strong> 
                    <span class="badge bg-warning text-dark">Solicitada</span> 
                    <i class="bi bi-arrow-right ms-2 me-2"></i> 
                    <span class="badge bg-success">Aprobada</span> 
                    <i class="bi bi-arrow-right ms-2 me-2"></i> 
                    <span class="badge bg-info">Completada</span>
                </li>
                <li class="mb-1"><strong>Aprobación:</strong> Solo usuarios con permisos pueden aprobar o rechazar solicitudes en estado "Solicitada".</li>
                <li><strong>Urgentes:</strong> Si apruebas una solicitud urgente, la producción pasa automáticamente a "En Proceso".</li>
            </ul>
        </div>
    </x-card> -->

    <!-- KPI Cards -->
    <div class="row g-2 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: var(--gold-dark);">
                        <i class="bi bi-lightning kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['total'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Total</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #ef4444;">
                        <i class="bi bi-exclamation-circle kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['solicitadas'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Pendientes</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #10b981;">
                        <i class="bi bi-check-circle kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['aprobadas'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Aprobadas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #8b5cf6;">
                        <i class="bi bi-x-circle kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['urgentes'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Urgentes</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Tabla de Solicitudes -->
    <x-card>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold text-main">ID</th>
                        <th class="fw-semibold text-main">Lote</th>
                        <th class="fw-semibold text-main">Urgencia</th>
                        <th class="fw-semibold text-main">Solicitante</th>
                        <th class="fw-semibold text-main">Fecha Solicitud</th>
                        <th class="fw-semibold text-main">Estado</th>
                        <th class="fw-semibold text-main">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes ?? [] as $solicitud)
                        <tr>
                            <td class="fw-semibold">#{{ $solicitud->id }}</td>
                            <td>
                                <strong>{{ $solicitud->produccion->lote_codigo ?? 'S/N' }}</strong>
                                <br>
                                <small class="text-muted">{{ $solicitud->produccion->receta->producto->nombre ?? 'Producto N/A' }}</small>
                            </td>
                            <td>
                                @switch($solicitud->tipo_urgencia)
                                    @case('normal')
                                        <span class="badge bg-secondary">Normal</span>
                                        @break
                                    @case('urgente')
                                        <span class="badge bg-warning text-dark">Urgente</span>
                                        @break
                                    @case('muy_urgente')
                                        <span class="badge bg-danger">Muy Urgente</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $solicitud->usuarioSolicitante->nombre ?? 'N/A' }}</td>
                            <td>{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</td>
                            <td>
                                @switch($solicitud->estado)
                                    @case('solicitada')
                                        <span class="badge bg-warning text-dark">Solicitada</span>
                                        @break
                                    @case('aprobada')
                                        <span class="badge bg-success">Aprobada</span>
                                        @break
                                    @case('rechazada')
                                        <span class="badge bg-danger">Rechazada</span>
                                        @break
                                    @case('completada')
                                        <span class="badge bg-info">Completada</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @can('solicitudes_produccion.view')
                                        <a href="{{ route('solicitudes_produccion.show', $solicitud) }}" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('solicitudes_produccion.approve')
                                        @if($solicitud->estado === 'solicitada')
                                            <form action="{{ route('solicitudes_produccion.aprobar', $solicitud) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" title="Aprobar" onclick="return confirm('¿Aprobar solicitud?');">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('solicitudes_produccion.rechazar', $solicitud) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger" title="Rechazar" onclick="return confirm('¿Rechazar solicitud?');">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox me-2"></i>No hay solicitudes registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($solicitudes) && $solicitudes->count())
            <div class="p-3 border-top">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
