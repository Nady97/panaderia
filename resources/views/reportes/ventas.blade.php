@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-graph-up me-2 text-gold"></i> Reporte de Ventas
                </h2>
                <p class="mb-0 text-muted">Análisis de ventas por período</p>
            </div>
            <form method="GET" class="d-flex gap-2">
                <select name="periodo" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="hoy" {{ $periodo === 'hoy' ? 'selected' : '' }}>Hoy</option>
                    <option value="mes" {{ $periodo === 'mes' ? 'selected' : '' }}>Este Mes</option>
                    <option value="trimestre" {{ $periodo === 'trimestre' ? 'selected' : '' }}>Este Trimestre</option>
                    <option value="año" {{ $periodo === 'año' ? 'selected' : '' }}>Este Año</option>
                </select>
            </form>
        </div>
    </x-card>

    <!-- Resumen -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #10b981; font-weight: bold;">{{ $resumen['cantidad_facturas'] ?? 0 }}</div>
                    <small class="text-muted">Total Facturas</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #3b82f6; font-weight: bold;">Bs. {{ number_format($resumen['monto_total_pagado'] ?? 0, 2) }}</div>
                    <small class="text-muted">Pagado</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #f59e0b; font-weight: bold;">Bs. {{ number_format($resumen['monto_total_emitido'] ?? 0, 2) }}</div>
                    <small class="text-muted">Pendiente de Pago</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #8b5cf6; font-weight: bold;">{{ $resumen['puntos_totales'] ?? 0 }}</div>
                    <small class="text-muted">Puntos Acumulados</small>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Detalle por día -->
    <x-card>
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-calendar-event me-2"></i> Ventas por Fecha
            </h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">Fecha</th>
                            <th class="fw-semibold text-center">Cantidad</th>
                            <th class="fw-semibold text-right">Monto Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventasDiarias as $venta)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                                <td class="text-center"><span class="badge bg-primary">{{ $venta->cantidad }}</span></td>
                                <td class="text-right"><strong>Bs. {{ number_format($venta->monto_total, 2) }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox me-2"></i>Sin ventas en este período
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
