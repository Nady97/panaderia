@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-3">
        <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-0 text-main" style="font-size: 1.25rem;">
                    <i class="bi bi-hand-wave me-2" style="color: var(--gold-dark);"></i>¡Bienvenida, {{ auth()->user()->nombre ?? 'Usuario' }}!
                </h4>
                <p class="mb-0 text-secondary" style="font-size: 0.85rem;">Panel de control · Gestión de producción</p>
            </div>
            <div class="d-flex align-items-center py-1 px-3 rounded-3" style="background: rgba(210, 150, 75, 0.08); border: 1px solid var(--border-color);">
                <i class="bi bi-calendar3 me-2" style="color: var(--gold-dark);"></i>
                <span class="text-main" style="font-size: 0.85rem;">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de estadísticas -->
    <div class="row g-2 mb-2">
        <div class="col-6 col-md-3">
            <x-card class="h-100 kpi-card">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam" style="font-size: 1.5rem; color: var(--gold-dark);"></i>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalProductos ?? 0 }}</h3>
                        <small class="text-muted">Productos</small>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-6 col-md-3">
            <x-card class="h-100 kpi-card">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cart-check" style="font-size: 1.5rem; color: var(--success);"></i>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalVentas ?? 0 }}</h3>
                        <small class="text-muted">Ventas</small>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-6 col-md-3">
            <x-card class="h-100 kpi-card">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cup-hot" style="font-size: 1.5rem; color: #8B4513;"></i>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalProduccion ?? 0 }}</h3>
                        <small class="text-muted">Producción</small>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-6 col-md-3">
            <x-card class="h-100 kpi-card">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-currency-dollar" style="font-size: 1.5rem; color: var(--gold-dark);"></i>
                    <div>
                        <h3 class="fw-bold mb-0">Bs {{ number_format($valorInventario ?? 0, 2) }}</h3>
                        <small class="text-muted">Inventario</small>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Alertas de Stock -->
    <div class="row g-2 mb-2">
        <div class="col-12">
            <x-card>
                <div class="p-3">
                    <h5 class="fw-bold mb-3 text-main">
                        <i class="bi bi-bell me-2" style="color: var(--gold-dark);"></i>Alertas de Stock
                    </h5>
                    @if(($productosStockBajo ?? 0) > 0 || ($productosAgotados ?? 0) > 0)
                        <div class="d-flex gap-2 flex-wrap">
                            @if(($productosStockBajo ?? 0) > 0)
                                <span class="badge bg-warning text-dark p-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i> {{ $productosStockBajo }} con stock bajo
                                </span>
                            @endif
                            @if(($productosAgotados ?? 0) > 0)
                                <span class="badge bg-danger p-2">
                                    <i class="bi bi-x-circle me-1"></i> {{ $productosAgotados }} agotados
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0 text-muted">¡Todo en orden! No hay productos con stock bajo.</p>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

    <!-- Últimos Productos -->
    <x-card>
        <div class="p-3">
            <h5 class="fw-bold mb-3 text-main">
                <i class="bi bi-box-seam me-2" style="color: var(--gold-dark);"></i>Últimos Productos
            </h5>
            @if(isset($ultimosProductos) && count($ultimosProductos) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosProductos as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td>Bs {{ number_format($producto->precio_venta, 2) }}</td>
                                <td>{{ $producto->stock }}</td>
                                <td>
                                    @if($producto->estado == 'activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">No hay productos registrados.</p>
            @endif
        </div>
    </x-card>
</div>
@endsection