@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-lightning me-2 text-gold"></i> Solicitud #{{ $solicitud->id }}
                </h2>
                <p class="mb-0 text-muted">Detalles de la solicitud de producción urgente</p>
            </div>
            <div>
                @switch($solicitud->estado)
                    @case('solicitada')
                        <span class="badge bg-warning text-dark" style="font-size: 0.9rem;">Solicitada</span>
                        @break
                    @case('aprobada')
                        <span class="badge bg-success" style="font-size: 0.9rem;">Aprobada</span>
                        @break
                    @case('rechazada')
                        <span class="badge bg-danger" style="font-size: 0.9rem;">Rechazada</span>
                        @break
                    @case('completada')
                        <span class="badge bg-info" style="font-size: 0.9rem;">Completada</span>
                        @break
                @endswitch
            </div>
        </div>
    </x-card>

    <div class="row mb-4">
        <div class="col-md-6">
            <x-card class="mb-3">
                <div class="p-3">
                    <h5 class="fw-bold text-main mb-3">Información de la Solicitud</h5>
                    <dl class="row small">
                        <dt class="col-sm-5">Tipo Urgencia:</dt>
                        <dd class="col-sm-7">
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
                        </dd>
                        
                        <dt class="col-sm-5">Solicitante:</dt>
                        <dd class="col-sm-7">{{ $solicitud->usuarioSolicitante->nombre ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Fecha Solicitud:</dt>
                        <dd class="col-sm-7">{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</dd>
                        
                        <dt class="col-sm-5">Estado:</dt>
                        <dd class="col-sm-7">{{ ucfirst($solicitud->estado) }}</dd>
                    </dl>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card class="mb-3">
                <div class="p-3">
                    <h5 class="fw-bold text-main mb-3">Información de Producción</h5>
                    <dl class="row small">
                        <dt class="col-sm-5">Lote:</dt>
                        <dd class="col-sm-7">
                            <a href="{{ route('produccion.show', $solicitud->produccion) }}">
                                {{ $solicitud->produccion->lote_codigo ?? 'N/A' }}
                            </a>
                        </dd>
                        
                        <dt class="col-sm-5">Producto:</dt>
                        <dd class="col-sm-7">
                            <strong>{{ $solicitud->produccion->receta->producto->nombre ?? 'N/A' }}</strong>
                        </dd>
                        
                        <dt class="col-sm-5">Cantidad:</dt>
                        <dd class="col-sm-7">{{ $solicitud->produccion->cantidad_producida ?? 0 }}</dd>
                        
                        <dt class="col-sm-5">Receta:</dt>
                        <dd class="col-sm-7">{{ $solicitud->produccion->receta->nombre ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Estado Prod.:</dt>
                        <dd class="col-sm-7">{{ ucfirst(str_replace('_', ' ', $solicitud->produccion->estado)) }}</dd>
                    </dl>
                </div>
            </x-card>
        </div>
    </div>

    @if($solicitud->motivo_urgencia)
        <x-card class="mb-4">
            <div class="p-3">
                <h5 class="fw-bold text-main mb-2">Motivo de Urgencia</h5>
                <p class="mb-0">{{ $solicitud->motivo_urgencia }}</p>
            </div>
        </x-card>
    @endif

    @if($solicitud->estado !== 'solicitada')
        <x-card class="mb-4">
            <div class="p-3 bg-light">
                <h5 class="fw-bold text-main mb-3">Respuesta de Aprobación</h5>
                <dl class="row small">
                    <dt class="col-sm-4">Aprobado por:</dt>
                    <dd class="col-sm-8">{{ $solicitud->usuarioAprobador->nombre ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Fecha Aprobación:</dt>
                    <dd class="col-sm-8">{{ $solicitud->fecha_aprobacion?->format('d/m/Y H:i') ?? 'N/A' }}</dd>
                    
                    @if($solicitud->comentario_aprobador)
                        <dt class="col-sm-4">Comentario:</dt>
                        <dd class="col-sm-8">{{ $solicitud->comentario_aprobador }}</dd>
                    @endif
                </dl>
            </div>
        </x-card>
    @endif

    <!-- Acciones -->
    <div class="d-flex gap-2">
        @can('solicitudes_produccion.approve')
            @if($solicitud->estado === 'solicitada')
                <form action="{{ route('solicitudes_produccion.aprobar', $solicitud) }}" method="POST" style="display:inline;">
                    @csrf
                    <div class="input-group">
                        <input type="hidden" name="comentario_aprobador" value="">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Aprobar
                        </button>
                    </div>
                </form>
                <form action="{{ route('solicitudes_produccion.rechazar', $solicitud) }}" method="POST" style="display:inline;">
                    @csrf
                    <div class="input-group">
                        <input type="hidden" name="comentario_aprobador" value="">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i> Rechazar
                        </button>
                    </div>
                </form>
            @endif
        @endcan
            <a href="{{ route('solicitudes_produccion.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>
@endsection
