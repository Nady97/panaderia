@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/dashboard.blade.php
    PROPÓSITO: Panel principal (Dashboard) que muestra el resumen métrico del sistema.
    ARQUITECTURA: Mantenimiento de legibilidad con Componentes Blade (<x-card>).
                  Contiene tarjetas con KPIs, alertas de stock e integración de gráfico.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado con saludo -->
    <x-card class="mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1 text-main">
                    <i class=" text-gold"></i>¡Bienvenida, {{ auth()->user()->nombre ?? 'Usuario' }}!
                </h3>
                <p class="mb-0 text-secondary">Panel de control · Gestión de producción</p>
            </div>
            <div class="d-flex align-items-center justify-content-center p-2 px-3 rounded-3 bg-soft-gold border-gold-light">
                <i class="bi bi-calendar3 me-2 text-gold"></i>
                <span class="fw-medium text-main">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de estadísticas -->
    <div class="row g-4 mb-4">
        <!-- Productos -->
        <div class="col-md-3">
            <x-card class="h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-monospace text-uppercase text-xs">Productos</p>
                            <h4 class="fw-bold mb-0 text-main">{{ $totalProductos ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-soft-gold text-gold shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        <!-- Ventas -->
        <div class="col-md-3">
            <x-card class="h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-monospace text-uppercase text-xs">Ventas</p>
                            <h4 class="fw-bold mb-0 text-main">{{ $totalVentas ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-soft-green text-success shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-cart-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        <!-- Producción -->
        <div class="col-md-3">
            <x-card class="h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-monospace text-uppercase text-xs">Producción</p>
                            <h4 class="fw-bold mb-0 text-main">{{ $totalProduccion ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-soft-brown text-brown shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-cup-hot fs-4"></i>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        <!-- Inventario -->
        <div class="col-md-3">
            <x-card class="h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-monospace text-uppercase text-xs">Inventario</p>
                            <h4 class="fw-bold mb-0 text-main">Bs {{ number_format($valorInventario ?? 0, 2) }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-soft-gold text-gold shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-currency-dollar fs-4"></i>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

            <!-- Panel de Métricas Principales / Inventario -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <x-card class="h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom-custom border-border-color">
                        <h5 class="fw-bold mb-0 text-main">
                            <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Alertas de Stock
                        </h5>
                        <div class="badge bg-warning text-dark rounded-pill px-3 py-2">
                            {{ ($productosStockBajo ?? 0) + ($productosAgotados ?? 0) }} Alertas
                        </div>
                    </div>
                    
                    <div>
                        @if(($productosStockBajo ?? 0) > 0 || ($productosAgotados ?? 0) > 0)
                            <div class="d-flex flex-column gap-2">
                                @if($productosStockBajo > 0)
                                <div class="d-flex align-items-center p-2 px-3 rounded bg-soft-warning border-start border-4 border-warning">
                                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-main text-sm">Stock bajo</h6>
                                        <p class="mb-0 text-muted text-xs">{{ $productosStockBajo }} productos necesitan revisión.</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($productosAgotados > 0)
                                <div class="d-flex align-items-center p-2 px-3 rounded bg-soft-danger border-start border-4 border-danger">
                                    <i class="bi bi-x-circle-fill fs-4 me-3 text-danger"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-main text-sm">Agotados</h6>
                                        <p class="mb-0 text-muted text-xs">{{ $productosAgotados }} productos reponer ya.</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <a href="/productos" class="btn btn-light-panaderia text-decoration-none w-100 text-center d-block">
                                    Revisar inventario <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                <h5 class="fw-bold mt-3 text-main">¡Todo en orden!</h5>
                                <p class="text-muted mb-0">Todos los productos tienen stock suficiente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </x-card>
        </div>
        
        <div class="col-md-6">
            <x-card class="h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="mb-3 pb-2 border-bottom-custom border-border-color">
                        <h5 class="fw-bold mb-0 text-main">
                            <i class="bi bi-pie-chart me-2 text-gold"></i>Resumen del Inventario
                        </h5>
                    </div>
                    
                    <div class="row align-items-center">
                        <div class="col-md-5 mb-4 mb-md-0">
                            <!-- Contenedor del gráfico Donut -->
                            <div style="position: relative; height: 180px; width: 100%; display: flex; justify-content: center;">
                                <canvas id="inventoryChart"></canvas>
                                <!-- Texto central del Donut -->
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                    <span class="text-muted text-xs font-monospace d-block mb-1">STOCK</span>
                                    <span class="fw-bold text-main fs-4 lh-1">{{ $stockTotal ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 d-flex flex-column justify-content-center">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center p-2 px-3 rounded detail-box transition-bg">
                                    <div class="rounded p-2 me-3 bg-soft-green text-success">
                                        <i class="bi bi-grid fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Total Categorías </p>
                                        <h6 class="fw-bold mb-0 text-main text-sm">{{ $totalCategorias ?? 0 }}</h6>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center p-2 px-3 rounded detail-box transition-bg">
                                    <div class="rounded p-2 me-3 bg-soft-brown text-brown">
                                        <i class="bi bi-tag fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Precio prom. venta</p>
                                        <h6 class="fw-bold mb-0 text-main text-sm">Bs {{ number_format($precioPromedio ?? 0, 2) }}</h6>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center p-2 px-3 rounded detail-box transition-bg">
                                    <div class="rounded p-2 me-3 bg-soft-gold text-gold">
                                        <i class="bi bi-box-seam fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Variedad en catálogo</p>
                                        <h6 class="fw-bold mb-0 text-main text-sm">{{ $totalProductos ?? 0 }} ítems únicos</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Últimos productos agregados -->
    <x-card>
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom-custom">
                <h5 class="fw-bold mb-0 text-main">
                    <i class="bi bi-box-seam me-2 text-gold"></i>Últimos Productos Agregados
                </h5>
                <a href="/productos" class="btn btn-sm btn-light-panaderia">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            
            <div>
            @if(isset($ultimosProductos) && count($ultimosProductos) > 0)
                <div class="table-responsive m-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-primary-custom border-bottom-custom border-2">
                            <tr>
                                <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted border-0">Producto</th>
                                <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted border-0">Precio Venta</th>
                                <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted text-center border-0">Stock</th>
                                <th class="py-3 px-4 fw-semibold text-sm text-uppercase text-muted text-center border-0">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosProductos as $producto)
                            <tr class="border-bottom-custom transition-bg">
                                <td class="py-3 px-4">
                                    <div class="fw-medium text-main">
                                        <i class="bi bi-cup-hot me-2 text-muted"></i>{{ $producto->nombre }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 fw-bold text-gold">
                                    Bs {{ number_format($producto->precio_venta, 2) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($producto->stock <= 0)
                                        <x-badge type="danger" class="rounded-pill p-2 px-3 fw-normal text-xs">Agotado</x-badge>
                                    @elseif($producto->stock <= $producto->stock_minimo)
                                        <x-badge type="warning" class="rounded-pill p-2 px-3 fw-normal text-xs">{{ $producto->stock }} uds</x-badge>
                                    @else
                                        <x-badge type="success" class="rounded-pill p-2 px-3 fw-normal text-xs">{{ $producto->stock }} uds</x-badge>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($producto->estado == 'activo')
                                        <span class="badge bg-soft-green text-success rounded-pill p-2 px-3 fw-normal text-xs border border-success border-opacity-25">Activo</span>
                                    @else
                                        <span class="badge bg-soft-danger text-danger rounded-pill p-2 px-3 fw-normal text-xs border border-danger border-opacity-25">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted opacity-50" style="font-size: 3rem;"></i>
                    <h5 class="fw-normal mt-3 text-main">No hay productos nuevos</h5>
                    <p class="text-muted mb-4">Aún no se han registrado productos en el inventario.</p>
                    <a href="/productos/create" class="btn btn-gold-panaderia">
                        <i class="bi bi-plus-circle me-1"></i> Agregar primer producto
                    </a>
                </div>
            @endif
            </div>
        </div>
    </x-card>
</div>

<style>
/* Ajustes suplementarios para el dashboard */
.dark-mode .table { color: var(--text-primary); }
.dark-mode .table-hover tbody tr:hover { background-color: var(--bg-input) !important; }
</style>
@endsection

@push('scripts')
    @vite(['resources/js/dashboard.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('inventoryChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Panes', 'Pastelería', 'Bebidas', 'Insumos / Otros'],
                    datasets: [{
                        data: [45, 25, 20, 10], // Data ilustrativa de distribución
                        backgroundColor: [
                            '#643617', // primary-brown
                            '#cc7826', // gold-dark
                            '#A0643C', // secondary-brown
                            '#d1b08c'  // soft-gold (contrast)
                        ],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '78%', // Hueco grande y elegante
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#24140B',
                            padding: 12,
                            titleFont: { size: 13, family: 'system-ui' },
                            bodyFont: { size: 13, family: 'system-ui' },
                            cornerRadius: 8,
                            displayColors: true,
                            boxPadding: 4
                        }
                    }
                }
            });
        }
    });
    </script>
@endpush


