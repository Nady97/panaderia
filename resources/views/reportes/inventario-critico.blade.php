@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4">
            <h2 class="fw-bold mb-1 text-main">
                <i class="bi bi-exclamation-triangle me-2 text-gold"></i> Inventario Crítico
            </h2>
            <p class="mb-0 text-muted">Productos e insumos por debajo del nivel mínimo</p>
        </div>
    </x-card>

    <!-- Alertas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <x-card style="border-left: 4px solid #ef4444;">
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #ef4444; font-weight: bold;">{{ $resumenInventario['productos_criticos'] ?? 0 }}</div>
                    <small class="text-muted">Productos Críticos</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-4">
            <x-card style="border-left: 4px solid #ef4444;">
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #ef4444; font-weight: bold;">{{ $resumenInventario['insumos_criticos'] ?? 0 }}</div>
                    <small class="text-muted">Insumos Críticos</small>
                </div>
            </x-card>
        </div>
        <div class="col-md-4">
            <x-card style="border-left: 4px solid #ef4444;">
                <div class="p-4 text-center">
                    <div style="font-size: 2rem; color: #ef4444; font-weight: bold;">{{ $resumenInventario['productos_sin_stock'] ?? 0 }}</div>
                    <small class="text-muted">Sin Stock</small>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Productos Críticos -->
    <x-card class="mb-4">
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-box me-2"></i> Productos en Nivel Crítico
            </h5>
            @if($productosCriticos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Producto</th>
                                <th class="fw-semibold text-center">Stock Actual</th>
                                <th class="fw-semibold text-center">Stock Mínimo</th>
                                <th class="fw-semibold text-right">Valor (Bs.)</th>
                                <th class="fw-semibold text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productosCriticos as $producto)
                                <tr class="table-danger-light">
                                    <td><strong>{{ $producto->nombre }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $producto->stock }}</span>
                                    </td>
                                    <td class="text-center">{{ $producto->stock_minimo }}</td>
                                    <td class="text-right">Bs. {{ number_format($producto->stock * $producto->precio_costo, 2) }}</td>
                                    <td class="text-center">
                                        @if($producto->stock == 0)
                                            <span class="badge bg-danger">AGOTADO</span>
                                        @else
                                            <span class="badge bg-warning text-dark">CRÍTICO</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle me-2"></i> Todos los productos están en niveles normales
                </div>
            @endif
        </div>
    </x-card>

    <!-- Insumos Críticos -->
    <x-card class="mb-4">
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-capsule me-2"></i> Insumos en Nivel Crítico
            </h5>
            @if($insumosCriticos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Insumo</th>
                                <th class="fw-semibold text-center">Cantidad Actual</th>
                                <th class="fw-semibold text-center">Cantidad Mínima</th>
                                <th class="fw-semibold text-right">Valor (Bs.)</th>
                                <th class="fw-semibold text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($insumosCriticos as $insumo)
                                <tr class="table-danger-light">
                                    <td><strong>{{ $insumo->nombre }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $insumo->cantidad_disponible }}</span>
                                    </td>
                                    <td class="text-center">{{ $insumo->cantidad_minima }}</td>
                                    <td class="text-right">Bs. {{ number_format(($insumo->cantidad_disponible ?? 0) * ($insumo->precio_unitario ?? 0), 2) }}</td>
                                    <td class="text-center">
                                        @if($insumo->cantidad_disponible == 0)
                                            <span class="badge bg-danger">AGOTADO</span>
                                        @else
                                            <span class="badge bg-warning text-dark">CRÍTICO</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle me-2"></i> Todos los insumos están en niveles normales
                </div>
            @endif
        </div>
    </x-card>

    <!-- Resumen General -->
    <x-card>
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-graph-up me-2"></i> Resumen General de Inventario
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row small">
                        <dt class="col-sm-8">Total de Productos:</dt>
                        <dd class="col-sm-4 fw-semibold">{{ $resumenInventario['productos_totales'] ?? 0 }}</dd>

                        <dt class="col-sm-8">Valor Inventario Productos:</dt>
                        <dd class="col-sm-4 fw-semibold text-success">Bs. {{ number_format($resumenInventario['valor_inventario_productos'] ?? 0, 2) }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row small">
                        <dt class="col-sm-8">Total de Insumos:</dt>
                        <dd class="col-sm-4 fw-semibold">{{ $resumenInventario['insumos_totales'] ?? 0 }}</dd>

                        <dt class="col-sm-8">Valor Inventario Insumos:</dt>
                        <dd class="col-sm-4 fw-semibold text-success">Bs. {{ number_format($resumenInventario['valor_inventario_insumos'] ?? 0, 2) }}</dd>
                    </dl>
                </div>
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
