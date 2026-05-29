@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4">
            <h2 class="fw-bold mb-1 text-main">
                <i class="bi bi-bar-chart me-2 text-gold"></i> Reportes del Sistema
            </h2>
            <p class="mb-0 text-muted">Análisis integral de ventas, producción e inventario</p>
        </div>
    </x-card>

    <!-- Grid de opciones de reportes -->
    <div class="row g-3 mb-4">
        <!-- Dashboard Principal -->
        <div class="col-md-6 col-lg-4">
            <x-card class="h-100 cursor-pointer hover-shadow" style="transition: all 0.3s;" onclick="window.location.href='{{ route('reportes.dashboard') }}'">
                <div class="p-4 text-center">
                    <div style="font-size: 2.5rem; color: #8B7355; margin-bottom: 12px;">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Dashboard</h5>
                    <p class="text-muted small mb-0">Métricas clave en tiempo real</p>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark">KPIs</span>
                        <span class="badge bg-light text-dark">Resumen</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Ventas -->
        <div class="col-md-6 col-lg-4">
            <x-card class="h-100 cursor-pointer hover-shadow" style="transition: all 0.3s;" onclick="window.location.href='{{ route('reportes.ventas') }}'">
                <div class="p-4 text-center">
                    <div style="font-size: 2.5rem; color: #10b981; margin-bottom: 12px;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Ventas</h5>
                    <p class="text-muted small mb-0">Análisis diario, mensual y anual</p>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark">Diarias</span>
                        <span class="badge bg-light text-dark">Mensuales</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Producción -->
        <div class="col-md-6 col-lg-4">
            <x-card class="h-100 cursor-pointer hover-shadow" style="transition: all 0.3s;" onclick="window.location.href='{{ route('reportes.produccion') }}'">
                <div class="p-4 text-center">
                    <div style="font-size: 2.5rem; color: #f59e0b; margin-bottom: 12px;">
                        <i class="bi bi-hammer"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Producción</h5>
                    <p class="text-muted small mb-0">Realizada vs Planificada</p>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark">Realizado</span>
                        <span class="badge bg-light text-dark">Eficiencia</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Inventario Crítico -->
        <div class="col-md-6 col-lg-4">
            <x-card class="h-100 cursor-pointer hover-shadow" style="transition: all 0.3s;" onclick="window.location.href='{{ route('reportes.inventario-critico') }}'">
                <div class="p-4 text-center">
                    <div style="font-size: 2.5rem; color: #ef4444; margin-bottom: 12px;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Inventario Crítico</h5>
                    <p class="text-muted small mb-0">Productos y insumos bajo mínimo</p>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark">Alertas</span>
                        <span class="badge bg-light text-dark">Stock</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Proveedores -->
        <div class="col-md-6 col-lg-4">
            <x-card class="h-100 cursor-pointer hover-shadow" style="transition: all 0.3s;" onclick="window.location.href='{{ route('reportes.proveedores') }}'">
                <div class="p-4 text-center">
                    <div style="font-size: 2.5rem; color: #3b82f6; margin-bottom: 12px;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Proveedores</h5>
                    <p class="text-muted small mb-0">Top proveedores y movimientos</p>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark">Compras</span>
                        <span class="badge bg-light text-dark">Montos</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Acceso rápido -->
    <x-card>
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-lightning me-2"></i> Acceso Rápido
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('reportes.dashboard') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-speedometer2 me-2"></i> Ver Dashboard Completo
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('reportes.inventario-critico') }}" class="btn btn-outline-danger w-100 mb-2">
                        <i class="bi bi-exclamation-circle me-2"></i> Ver Alertas de Inventario
                    </a>
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection
