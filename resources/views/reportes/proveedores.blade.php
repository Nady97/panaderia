@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4">
            <h2 class="fw-bold mb-1 text-main">
                <i class="bi bi-truck me-2 text-gold"></i> Reporte de Proveedores
            </h2>
            <p class="mb-0 text-muted">Top proveedores y relaciones comerciales</p>
        </div>
    </x-card>

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #3b82f6; font-weight: bold;">{{ $estadisticas['total_proveedores'] ?? 0 }}</div>
                    <small class="text-muted">Total Proveedores</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #10b981; font-weight: bold;">{{ $estadisticas['total_compras'] ?? 0 }}</div>
                    <small class="text-muted">Total Compras</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #f59e0b; font-weight: bold;">Bs. {{ number_format($estadisticas['monto_total_compras'] ?? 0, 2) }}</div>
                    <small class="text-muted">Monto Total</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card>
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #8b5cf6; font-weight: bold;">Bs. {{ number_format($estadisticas['promedio_compra'] ?? 0, 2) }}</div>
                    <small class="text-muted">Promedio Compra</small>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Top Proveedores -->
    <x-card class="mb-4">
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-star me-2"></i> Top 10 Proveedores
            </h5>
            @if($proveedoresUsados->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Proveedor</th>
                                <th class="fw-semibold text-center">Cantidad de Compras</th>
                                <th class="fw-semibold text-right">Monto Total (Bs.)</th>
                                <th class="fw-semibold text-right">Promedio Compra</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proveedoresUsados as $proveedor)
                                <tr>
                                    <td>
                                        <strong>{{ $proveedor->proveedor->nombre ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $proveedor->proveedor->ciudad ?? '' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $proveedor->cantidad_compras }}</span>
                                    </td>
                                    <td class="text-right"><strong>Bs. {{ number_format($proveedor->monto_total, 2) }}</strong></td>
                                    <td class="text-right">Bs. {{ number_format($proveedor->monto_total / $proveedor->cantidad_compras, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i> No hay datos de compras registradas
                </div>
            @endif
        </div>
    </x-card>

    <div class="mt-4">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>
@endsection
