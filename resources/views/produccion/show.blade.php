@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/produccion/show.blade.php
    PROPÓSITO: Vista de solo lectura para detallar una Orden de Producción.
    ARQUITECTURA: Mantenimiento de legibilidad con Componentes Blade (<x-card>).
                  Muestra trazabilidad, desglose de insumos y estados.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container p-4 animate-fade-in">
    
    <!-- Encabezado y Acciones -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h2 class="h3 mb-0 text-main fw-bold">
                <i class="bi bi-eye text-gold me-2"></i> Detalles de Orden
            </h2>
            <p class="text-secondary mt-1 mb-0">Revisa la trazabilidad de esta producción.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('produccion.index') }}" class="btn btn-light-panaderia shadow-sm me-2">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            @if($produccion->estado !== 'completado')
            <a href="{{ route('produccion.edit', $produccion->id) }}" class="btn btn-gold-panaderia shadow-sm" style="border-radius: 10px; padding: 0.6rem 1.5rem; background: var(--gold-light); color: #fff; border: 1px solid var(--gold-dark);">
                <i class="bi bi-pencil me-1"></i> Editar Orden
            </a>
            @endif
        </div>
    </div>

    <!-- Contenido Detalle -->
    <div class="row g-4">
        <div class="col-lg-8">
            <x-card class="border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
                        <div>
                            <h4 class="fw-bold text-main mb-1">{{ $produccion->lote_codigo ?? '#' . $produccion->id }}</h4>
                            <span class="text-muted"><i class="bi bi-person me-1"></i> Operador: {{ optional($produccion->usuario)->nombre ?? 'Desconocido' }}</span>
                        </div>
                        <div>
                            @switch($produccion->estado)
                                @case('planificado')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-2 fs-6">
                                        <i class="bi bi-calendar-check me-1"></i> Planificado
                                    </span>
                                    @break
                                @case('en_proceso')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-2 fs-6">
                                        <i class="bi bi-hourglass-split me-1"></i> En Proceso
                                    </span>
                                    @break
                                @case('completado')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-2 fs-6">
                                        <i class="bi bi-check-circle me-1"></i> Completado
                                    </span>
                                    @break
                                @case('fallido')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-2 fs-6">
                                        <i class="bi bi-x-circle me-1"></i> Cancelado
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <div class="row g-4 mb-4 mt-1">
                        <div class="col-sm-6">
                            <label class="text-muted d-block small mb-2"><i class="bi bi-box-seam me-1"></i> Producto Final</label>
                            <h6 class="mb-0 fw-bold">{{ $produccion->producto->nombre ?? 'N/A' }}</h6>
                            <small class="text-muted">Unidad: {{ $produccion->producto->unidad_medida ?? 'N/A' }}</small>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted d-block small mb-2"><i class="bi bi-123 me-1"></i> Cantidad Esperada / Producida</label>
                            <h6 class="mb-0 fw-bold text-info">{{ number_format($produccion->cantidad_producida, 2) }} {{ $produccion->producto->unidad_medida ?? 'unid' }}</h6>
                        </div>
                    </div>

                    <h5 class="fw-bold text-main mt-5 mb-3 border-bottom pb-2">Desglose de Insumos (Receta)</h5>
                    <p class="text-muted small mb-3">Cantidades matemáticas calculadas en base a la receta utilizada (Rendimiento Base: {{ $produccion->receta->rendimiento_estimado ?? 1 }} unid).</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th>Insumo Agregado</th>
                                    <th class="text-center">Cantidad Restada / Utilizada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($produccion->receta && $produccion->receta->insumos->count() > 0)
                                    @php
                                        $rendimiento = max($produccion->receta->rendimiento_estimado, 1);
                                    @endphp
                                    @foreach($produccion->receta->insumos as $insumo)
                                        @php
                                            $cantidadUsada = ($insumo->pivot->cantidad_necesaria / $rendimiento) * $produccion->cantidad_producida;
                                        @endphp
                                        <tr>
                                            <td>
                                                <i class="bi bi-droplet me-2 text-muted"></i> 
                                            {{ $insumo->nombre }}
                                        </td>
                                        <td class="text-center fw-medium text-primary">
                                            {{ number_format($cantidadUsada, 2) }} {{ $insumo->unidad_medida }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">
                                        No hay insumos registrados para esta receta.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Sidebar Trazabilidad -->
        <div class="col-lg-4">
            <x-card class="border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-main mb-3 border-bottom pb-2">Tiempos y Trazabilidad</h5>
                    
                    <ul class="list-unstyled mb-0 time-line">
                        <li class="mb-3">
                            <span class="text-muted d-block small"><i class="bi bi-calendar-event me-1"></i> Fecha Programada</span>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($produccion->fecha_programada)->format('d/m/Y') }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="text-muted d-block small"><i class="bi bi-clock me-1"></i> Hora de Inicio</span>
                            <span class="fw-medium">{{ $produccion->hora_inicio ? \Carbon\Carbon::parse($produccion->hora_inicio)->format('d/m/Y H:i') : '--:--' }}</span>
                        </li>
                        <li>
                            <span class="text-muted d-block small"><i class="bi bi-clock-history me-1"></i> Hora de Finalización</span>
                            <span class="fw-medium">{{ $produccion->hora_fin ? \Carbon\Carbon::parse($produccion->hora_fin)->format('d/m/Y H:i') : '--:--' }}</span>
                        </li>
                    </ul>
                </div>
            </x-card>

            <x-card class="border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-main mb-3">Observaciones / Mermas</h5>
                    @if($produccion->observaciones_calidad)
                        <p class="text-secondary small mb-0">{{ $produccion->observaciones_calidad }}</p>
                    @else
                        <p class="text-muted small mb-0 font-monospace">Ninguna nota de calidad registrada para esta orden.</p>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>

<style>
    /* Opcional: Pequeños ajustes para la vista de detalle */
    .time-line li {
        position: relative;
        padding-left: 20px;
    }
    .time-line li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--gold-panaderia);
    }
</style>
@endsection