@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-speedometer2 me-2 text-gold"></i> Dashboard de Control
                </h2>
                <p class="mb-0 text-muted">Métricas clave del sistema - Actualizado: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Reportes
            </a>
        </div>
    </x-card>

    <!-- SECCIÓN 1: VENTAS -->
    <div class="mb-4">
        <h5 class="fw-bold text-main mb-3">
            <i class="bi bi-graph-up me-2" style="color: #10b981;"></i> Ventas
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #10b981; font-weight: bold;">Bs. {{ number_format($kpis['ventas_hoy'] ?? 0, 2) }}</div>
                        <small class="text-muted">Ventas Hoy</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #3b82f6; font-weight: bold;">Bs. {{ number_format($kpis['ventas_mes'] ?? 0, 2) }}</div>
                        <small class="text-muted">Este Mes</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #f59e0b; font-weight: bold;">{{ $kpis['facturas_pendientes'] ?? 0 }}</div>
                        <small class="text-muted">Facturas Pendientes</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #10b981; font-weight: bold;">{{ $kpis['facturas_pagadas'] ?? 0 }}</div>
                        <small class="text-muted">Facturas Pagadas</small>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 2: PRODUCCIÓN -->
    <div class="mb-4">
        <h5 class="fw-bold text-main mb-3">
            <i class="bi bi-hammer me-2" style="color: #f59e0b;"></i> Producción
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #f59e0b; font-weight: bold;">{{ $kpis['producciones_completadas_mes'] ?? 0 }}</div>
                        <small class="text-muted">Completadas Este Mes</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #3b82f6; font-weight: bold;">{{ $kpis['producciones_en_proceso'] ?? 0 }}</div>
                        <small class="text-muted">En Proceso</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #10b981; font-weight: bold;">{{ $kpis['cantidad_producida_mes'] ?? 0 }}</div>
                        <small class="text-muted">Unidades Producidas</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #f59e0b; font-weight: bold;">{{ $kpis['producciones_hoy'] ?? 0 }}</div>
                        <small class="text-muted">Programadas Hoy</small>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 3: INVENTARIO -->
    <div class="mb-4">
        <h5 class="fw-bold text-main mb-3">
            <i class="bi bi-boxes me-2" style="color: #8b5cf6;"></i> Inventario
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #ef4444; font-weight: bold;">{{ $kpis['productos_criticos'] ?? 0 }}</div>
                        <small class="text-muted">Productos Críticos</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #ef4444; font-weight: bold;">{{ $kpis['insumos_criticos'] ?? 0 }}</div>
                        <small class="text-muted">Insumos Críticos</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #ef4444; font-weight: bold;">{{ $kpis['productos_sin_stock'] ?? 0 }}</div>
                        <small class="text-muted">Productos Sin Stock</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #8b5cf6; font-weight: bold;">Bs. {{ number_format($kpis['valor_inventario_productos'] ?? 0, 2) }}</div>
                        <small class="text-muted">Valor Inventario</small>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 4: COMPRAS -->
    <div class="mb-4">
        <h5 class="fw-bold text-main mb-3">
            <i class="bi bi-truck me-2" style="color: #3b82f6;"></i> Compras
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #3b82f6; font-weight: bold;">{{ $kpis['compras_mes'] ?? 0 }}</div>
                        <small class="text-muted">Compras Este Mes</small>
                    </div>
                </x-card>
            </div>
            <div class="col-md-3 col-sm-6">
                <x-card>
                    <div class="p-4 text-center">
                        <div style="font-size: 2rem; color: #3b82f6; font-weight: bold;">Bs. {{ number_format($kpis['monto_compras_mes'] ?? 0, 2) }}</div>
                        <small class="text-muted">Monto Total</small>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 5: TOP PRODUCTOS Y PROVEEDORES -->
    <div class="row g-4">
        <div class="col-lg-6">
            <x-card>
                <div class="p-4">
                    <h5 class="fw-bold text-main mb-3">
                        <i class="bi bi-star me-2"></i> Top Productos
                    </h5>
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Stock</th>
                                <th class="text-right">Valor Unit.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProductos as $producto)
                                <tr>
                                    <td>{{ $producto->nombre }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $producto->stock }}</span>
                                    </td>
                                    <td class="text-right">Bs. {{ number_format($producto->precio_venta, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Sin datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card>
                <div class="p-4">
                    <h5 class="fw-bold text-main mb-3">
                        <i class="bi bi-truck me-2"></i> Top Proveedores
                    </h5>
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Proveedor</th>
                                <th class="text-center">Compras</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProveedores as $proveedor)
                                <tr>
                                    <td>{{ $proveedor->proveedor->nombre ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $proveedor->compras }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Sin datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
