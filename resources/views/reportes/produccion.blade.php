@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-hammer me-2 text-gold"></i> Reporte de Producción
                </h2>
                <p class="mb-0 text-muted">Realizada vs Planificada</p>
            </div>
            <form method="GET" class="d-flex gap-2">
                <select name="periodo" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="hoy" {{ $periodo === 'hoy' ? 'selected' : '' }}>Hoy</option>
                    <option value="mes" {{ $periodo === 'mes' ? 'selected' : '' }}>Este Mes</option>
                    <option value="año" {{ $periodo === 'año' ? 'selected' : '' }}>Este Año</option>
                </select>
            </form>
        </div>
    </x-card>

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #f59e0b; font-weight: bold;">{{ $estadisticas['total_producciones'] ?? 0 }}</div>
                    <small class="text-muted">Total Producciones</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #10b981; font-weight: bold;">{{ $estadisticas['completadas'] ?? 0 }}</div>
                    <small class="text-muted">Completadas</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #3b82f6; font-weight: bold;">{{ $estadisticas['en_proceso'] ?? 0 }}</div>
                    <small class="text-muted">En Proceso</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #8b5cf6; font-weight: bold;">{{ number_format($estadisticas['eficiencia'] ?? 0, 1) }}%</div>
                    <small class="text-muted">Eficiencia</small>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Producción vs Planificado -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 1.8rem; color: #10b981; font-weight: bold;">{{ $estadisticas['cantidad_producida_total'] ?? 0 }}</div>
                    <small class="text-muted">Cantidad Producida</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 1.8rem; color: #f59e0b; font-weight: bold;">{{ $estadisticas['cantidad_planificada_total'] ?? 0 }}</div>
                    <small class="text-muted">Cantidad Planificada</small>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Detalle de producciones -->
    <x-card>
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-list-check me-2"></i> Detalle de Producciones
            </h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">Lote</th>
                            <th class="fw-semibold">Fecha Programada</th>
                            <th class="fw-semibold text-center">Cantidad Producida</th>
                            <th class="fw-semibold text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producciones as $produccion)
                            <tr>
                                <td>{{ $produccion->lote_codigo ?? 'S/N' }}</td>
                                <td>{{ $produccion->fecha_programada->format('d/m/Y') }}</td>
                                <td class="text-center"><span class="badge bg-info">{{ $produccion->cantidad_producida ?? 0 }}</span></td>
                                <td class="text-center">
                                    @switch($produccion->estado)
                                        @case('planificado')
                                            <span class="badge bg-secondary">Planificado</span>
                                            @break
                                        @case('en_proceso')
                                            <span class="badge bg-primary">En Proceso</span>
                                            @break
                                        @case('finalizada')
                                            <span class="badge bg-success">Finalizada</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox me-2"></i>Sin producciones en este período
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>

    <div class="mt-4">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>
@endsection
