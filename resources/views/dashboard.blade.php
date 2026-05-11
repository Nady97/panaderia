@extends('layouts.app')
@section('content')
<div class="dashboard-container">
    <!-- Encabezado con saludo -->
    <x-card class="mb-3">
        <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-0 text-main" style="font-size: 1.25rem; letter-spacing: -0.01em;">
                    <i class="text-gold"></i>¡Bienvenida, {{ auth()->user()->nombre ?? 'Usuario' }}!
                </h4>
                <p class="mb-0 text-secondary" style="font-size: 0.85rem;">Panel de control · Gestión de producción</p>
            </div>
            <div class="d-flex align-items-center py-1 px-3 rounded-3" style="background: rgba(210, 150, 75, 0.08); border: 1px solid var(--border-color);">
                <i class="bi bi-calendar3 me-2 text-gold" style="font-size: 0.9rem;"></i>
                <span class="text-main" style="font-size: 0.85rem;">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de estadísticas -->
    <div class="row g-2 mb-2">
        <!-- Productos -->
        <div class="col-md-3">
            <x-card class="h-100 stat-card-elegant kpi-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper me-3 text-gold">
                        <i class="bi bi-box-seam kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="stat-value-elegant">{{ $totalProductos ?? 0 }}</h3>
                        <p class="stat-label-elegant">Productos</p>
                    </div>
                </div>
            </x-card>
        </div>
        
        <!-- Ventas -->
        <div class="col-md-3">
            <x-card class="h-100 stat-card-elegant kpi-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper me-3 text-success">
                        <i class="bi bi-cart-check kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="stat-value-elegant">{{ $totalVentas ?? 0 }}</h3>
                        <p class="stat-label-elegant">Ventas</p>
                    </div>
                </div>
            </x-card>
        </div>
        
        <!-- Producción -->
        <div class="col-md-3">
            <x-card class="h-100 stat-card-elegant kpi-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper me-3 text-brown">
                        <i class="bi bi-cup-hot kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="stat-value-elegant">{{ $totalProduccion ?? 0 }}</h3>
                        <p class="stat-label-elegant">Producción</p>
                    </div>
                </div>
            </x-card>
        </div>
        
        <!-- Inventario -->
        <div class="col-md-3">
            <x-card class="h-100 stat-card-elegant kpi-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-wrapper me-3 text-gold">
                        <i class="bi bi-currency-dollar kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="stat-value-elegant-monetary">Bs {{ number_format($valorInventario ?? 0, 2) }}</h3>
                        <p class="stat-label-elegant">Inventario</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Panel de Métricas Principales / Alertas -->
    <div class="row g-3 mb-3">
        <!-- Alertas de Stock -->
<div class="col-md-6">
    <x-card class="h-100">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 text-main" style="font-size: 1.25rem;">
                    <i class="bi bi-bell me-2" style="color: #D4A34B;"></i>Alertas de Stock
                </h4>
                @php
                    $totalAlertas = ($productosStockBajo ?? 0) + ($productosAgotados ?? 0);
                @endphp
                @if($totalAlertas > 0)
                    <span class="badge rounded-pill px-3 py-2" style="background: transparent; color: #8B3A3A; font-size: 0.85rem; font-weight: 600; border: 1px solid rgba(139, 58, 58, 0.2);">
                        {{ $totalAlertas }} {{ $totalAlertas == 1 ? 'Alerta' : 'Alertas' }}
                    </span>
                @endif
            </div>
            
            @if($totalAlertas > 0)
                <div class="alert-container" style="display: flex; flex-direction: column; gap: 12px;">
                    {{-- Alerta Stock Bajo --}}
                    @if(($productosStockBajo ?? 0) > 0)
                    <div class="alert-card-simple">
                        <div class="alert-icon-only">
                            <i class="bi bi-exclamation-triangle-fill" style="color: #F59E0B;"></i>
                        </div>
                        <div class="alert-text-only">
                            <span style="color: #8B3A3A; font-weight: 500;">Stock bajo</span>
                            <span style="color: #8B3A3A; opacity: 0.8;">{{ $productosStockBajo }} {{ $productosStockBajo == 1 ? 'producto necesita' : 'productos necesitan' }} revisión.</span>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Alerta Productos Agotados --}}
                    @if(($productosAgotados ?? 0) > 0)
                    <div class="alert-card-simple">
                        <div class="alert-icon-only">
                            <i class="bi bi-x-circle-fill" style="color: #DC2626;"></i>
                        </div>
                        <div class="alert-text-only">
                            <span style="color: #8B3A3A; font-weight: 500;">Productos agotados</span>
                            <span style="color: #8B3A3A; opacity: 0.8;">{{ $productosAgotados }} {{ $productosAgotados == 1 ? 'producto debe' : 'productos deben' }} reponerse.</span>
                        </div>
                    </div>
                    @endif
                </div>
                
                <a href="/productos" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px 16px; margin-top: 16px; background: transparent; border: 1px solid rgba(139, 58, 58, 0.2); border-radius: 12px; color: #8B3A3A; font-weight: 500; font-size: 0.9rem; text-decoration: none; transition: all 0.2s ease;">
                    <span>Revisar inventario</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            @else
                <div style="text-align: center; padding: 30px 20px;">
                    <div style="width: 70px; height: 70px; margin: 0 auto 16px; background: rgba(16, 185, 129, 0.08); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                        <i class="bi bi-check-circle-fill" style="color: #10B981;"></i>
                    </div>
                    <h5 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">¡Todo en orden!</h5>
                    <p style="font-size: 0.95rem; color: var(--text-secondary); margin-bottom: 0;">No hay productos con stock bajo o agotados.</p>
                </div>
            @endif
        </div>
    </x-card>
</div>
        
        <!-- Resumen del Inventario -->
        <div class="col-md-6">
            <x-card class="h-100">
                <div class="p-3">
                    <div class="mb-3">
                        <h5 class="fw-semibold mb-0 text-main" style="font-size: 1rem; letter-spacing: -0.01em;">
                            <i class="bi bi-pie-chart me-2 text-gold"></i>Resumen del Inventario
                        </h5>
                    </div>

                    @php
                        $stockTotalValue = $stockTotal ?? 0;
                        $totalProductosValue = $totalProductos ?? 0;
                        $precioPromedioValue = $precioPromedio ?? 0;
                        $stockBajoValue = $productosStockBajo ?? 0;
                        $agotadosValue = $productosAgotados ?? 0;
                        $saludableValue = max(0, $totalProductosValue - $stockBajoValue - $agotadosValue);

                        $donutTotal = max(1, $saludableValue + $stockBajoValue + $agotadosValue);
                        $saludablePct = round(($saludableValue / $donutTotal) * 100, 1);
                        $stockBajoPct = round(($stockBajoValue / $donutTotal) * 100, 1);
                        $agotadosPct = round(($agotadosValue / $donutTotal) * 100, 1);
                    @endphp

                    <div class="inventory-donut-wrap mb-3">
                        <div class="inventory-donut" style="--saludable: {{ $saludablePct }}; --stockbajo: {{ $stockBajoPct }}; --agotados: {{ $agotadosPct }};">
                            <div class="inventory-donut-center">
                                <span class="donut-label">Productos</span>
                                <span class="donut-value">{{ $totalProductosValue }}</span>
                            </div>
                        </div>
                        <div class="inventory-donut-legend">
                            <div class="legend-item">
                                <span class="legend-swatch saludable"></span>
                                <div>
                                    <span class="legend-title">Stock saludable</span>
                                    <span class="legend-value">{{ $saludableValue }}</span>
                                </div>
                            </div>
                            <div class="legend-item">
                                <span class="legend-swatch bajo"></span>
                                <div>
                                    <span class="legend-title">Stock bajo</span>
                                    <span class="legend-value">{{ $stockBajoValue }}</span>
                                </div>
                            </div>
                            <div class="legend-item">
                                <span class="legend-swatch agotados"></span>
                                <div>
                                    <span class="legend-title">Agotados</span>
                                    <span class="legend-value">{{ $agotadosValue }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="inventory-highlight">
                        <div class="highlight-item">
                            <i class="bi bi-star-fill text-gold"></i>
                            <div>
                                <span class="highlight-title">Producto estrella</span>
                                <span class="highlight-value">{{ $productoEstrella ?? 'Pendiente de datos' }}</span>
                            </div>
                        </div>
                        {{--
                        <div class="highlight-item">
                            <i class="bi bi-fire text-brown"></i>
                            <div>
                                <span class="highlight-title">Mas elaborado</span>
                                <span class="highlight-value">{{ $productoMasElaborado ?? 'Pendiente de datos' }}</span>
                            </div>
                        </div>
                        --}}
                        <div class="highlight-item">
                            <i class="bi bi-journal-text text-success"></i>
                            <div>
                                <span class="highlight-title">Receta mas usada</span>
                                <span class="highlight-value">{{ $recetaMasUsada ?? 'Pendiente de datos' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!--
                    <div class="inventory-value-card mb-3">
                        <div class="inventory-label">VALOR TOTAL DEL INVENTARIO</div>
                        <div class="inventory-value">Bs {{ number_format($valorInventario ?? 0, 2) }}</div>
                    </div> -->
                    
                    {{--
                    <div class="d-flex flex-column gap-2">
                        <div class="inventory-detail-item">
                            <div class="detail-icon bg-soft-gold">
                                <i class="bi bi-box-seam text-gold"></i>
                            </div>
                            <div class="detail-info">
                                <span class="detail-label">Unidades en stock</span>
                                <span class="detail-value">{{ $stockTotalValue }}</span>
                            </div>
                        </div>
                        
                        <div class="inventory-detail-item">
                            <div class="detail-icon bg-soft-brown">
                                <i class="bi bi-tag text-brown"></i>
                            </div>
                            <div class="detail-info">
                                <span class="detail-label">Precio promedio</span>
                                <span class="detail-value">Bs {{ number_format($precioPromedioValue, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="inventory-detail-item">
                            <div class="detail-icon bg-soft-gold">
                                <i class="bi bi-cup-hot text-gold"></i>
                            </div>
                            <div class="detail-info">
                                <span class="detail-label">Total productos distintos</span>
                                <span class="detail-value">{{ $totalProductosValue }}</span>
                            </div>
                        </div>
                    </div>
                    --}}
                </div>
            </x-card>
        </div>
    </div>

    <!-- Últimos productos agregados -->
    <x-card>
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-semibold mb-0 text-main" style="font-size: 1rem;">
                    <i class="bi bi-box-seam me-2 text-gold"></i>Últimos Productos Agregados
                </h5>
                <a href="/productos" class="btn btn-sm btn-outline-panaderia">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            
            @if(isset($ultimosProductos) && count($ultimosProductos) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 px-3 text-muted" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Producto</th>
                                <th class="py-2 px-3 text-muted" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Precio</th>
                                <th class="py-2 px-3 text-center text-muted" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Stock</th>
                                <th class="py-2 px-3 text-center text-muted" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosProductos as $producto)
                            <tr>
                                <td class="py-2 px-3">
                                    <div class="fw-medium text-main" style="font-size: 0.9rem;">
                                        <i class="bi bi-cup-hot me-2 text-muted"></i>{{ $producto->nombre }}
                                    </div>
                                </td>
                                <td class="py-2 px-3 fw-semibold text-gold" style="font-size: 0.9rem;">
                                    Bs {{ number_format($producto->precio_venta, 2) }}
                                </td>
                                <td class="py-2 px-3 text-center">
                                    @if($producto->stock <= 0)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1">Agotado</span>
                                    @elseif($producto->stock <= $producto->stock_minimo)
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1">{{ $producto->stock }} uds</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">{{ $producto->stock }} uds</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-center">
                                    @if($producto->estado == 'activo')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">Activo</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    <h6 class="fw-normal mt-3 text-main">No hay productos nuevos</h6>
                    <p class="text-muted mb-3" style="font-size: 0.85rem;">Aún no se han registrado productos en el inventario.</p>
                    <a href="/productos/create" class="btn btn-gold-panaderia btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Agregar primer producto
                    </a>
                </div>
            @endif
        </div>
    </x-card>
</div>

<style>
/* =========================================
   ESTILOS ELEGANTES PARA TARJETAS
   ========================================= */

/* Tarjeta estadística elegante */
.stat-card-elegant {
    /* padding: 1.25rem 1rem !important; */
    /* transition: all 0.25s ease !important; */
}


/* Icono de estadística */
.stat-icon-wrapper {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    transition: all 0.2s ease;
}

/* Etiqueta elegante */
.stat-label-elegant {
    font-size: 0.78rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.12em !important;
    color: var(--text-secondary) !important;
    margin: 0.2rem 0 0 !important;
    font-weight: 500 !important;
}

/* Valor elegante */
.stat-value-elegant {
    font-size: 1.9rem !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
    color: var(--text-primary) !important;
    margin-bottom: 0 !important;
    letter-spacing: -0.01em !important;
}

.stat-value-elegant-monetary {
    font-size: 1.55rem !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
    color: var(--text-primary) !important;
    margin-bottom: 0 !important;
    letter-spacing: -0.01em !important;
}

/* Alertas elegantes */
/* =========================================
   NUEVO DISEÑO DE ALERTAS DE STOCK
   ========================================= */

.alert-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Tarjeta de alerta */
/* =========================================
   ALERTAS SIMPLES - SOLO ÍCONOS CON COLOR
   ========================================= */

.alert-card-simple {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 12px;
    background: rgba(139, 58, 58, 0.03);
    border: 1px solid rgba(139, 58, 58, 0.1);
    transition: all 0.2s ease;
}

.alert-card-simple:hover {
    background: rgba(139, 58, 58, 0.06);
    border-color: rgba(139, 58, 58, 0.15);
}

.alert-icon-only {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.alert-text-only {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Botón hover */
.alert-card-simple + a:hover {
    background: rgba(139, 58, 58, 0.05) !important;
    border-color: rgba(139, 58, 58, 0.3) !important;
}

/* Dark mode */
.dark-mode .alert-card-simple {
    background: rgba(139, 58, 58, 0.06);
    border-color: rgba(139, 58, 58, 0.15);
}

.dark-mode .alert-card-simple:hover {
    background: rgba(139, 58, 58, 0.1);
    border-color: rgba(139, 58, 58, 0.25);
}

/* Dark mode */
.dark-mode .alert-warning-card {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(245, 158, 11, 0.03) 100%);
}

.dark-mode .alert-danger-card {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.12) 0%, rgba(239, 68, 68, 0.03) 100%);
}

.dark-mode .alert-card-badge {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Check success */
.check-success-wrapper {
    width: 50px;
    height: 50px;
    margin: 0 auto;
    font-size: 2.5rem;
}

/* Tarjeta de valor de inventario */
.inventory-value-card {
    background: linear-gradient(135deg, rgba(210, 150, 75, 0.08) 0%, rgba(210, 150, 75, 0.02) 100%);
    border: 1px dashed var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
}

.inventory-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.inventory-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gold-dark);
    line-height: 1.2;
    letter-spacing: -0.02em;
}

/* Items de detalle de inventario */
.inventory-detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 0.75rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.inventory-detail-item:hover {
    background: rgba(210, 150, 75, 0.04);
}

.detail-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.detail-info {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.detail-value {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
}

.detail-note {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 2px;
}

/* Donut de resumen */
.inventory-donut-wrap {
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 16px;
    align-items: center;
}

.inventory-donut {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background:
        conic-gradient(
            rgba(16, 185, 129, 0.9) 0 calc(var(--saludable) * 1%),
            rgba(245, 158, 11, 0.9) 0 calc((var(--saludable) + var(--stockbajo)) * 1%),
            rgba(239, 68, 68, 0.9) 0 100%
        );
    display: grid;
    place-items: center;
    box-shadow: inset 0 0 0 10px rgba(255, 255, 255, 0.9);
}

.inventory-donut-center {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 4px;
}

.donut-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--text-muted);
    font-weight: 600;
}

.donut-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-primary);
}

.inventory-donut-legend {
    display: grid;
    gap: 10px;
}

.legend-item {
    display: flex;
    gap: 10px;
    align-items: center;
}

.legend-swatch {
    width: 10px;
    height: 10px;
    border-radius: 999px;
    display: inline-block;
}

.legend-swatch.saludable {
    background: rgba(16, 185, 129, 0.9);
}

.legend-swatch.bajo {
    background: rgba(245, 158, 11, 0.9);
}

.legend-swatch.agotados {
    background: rgba(239, 68, 68, 0.9);
}

.legend-title {
    display: block;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    font-weight: 600;
}

.legend-value {
    display: block;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-primary);
}

.inventory-highlight {
    display: grid;
    gap: 8px;
}

.highlight-item {
    display: grid;
    grid-template-columns: 18px 1fr;
    gap: 10px;
    align-items: center;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    background: rgba(210, 150, 75, 0.03);
}

.highlight-title {
    display: block;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    font-weight: 600;
}

.highlight-value {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
}

/* Botón outline panadería */
.btn-outline-panaderia {
    background: transparent !important;
    color: var(--text-primary) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
}

.btn-outline-panaderia:hover {
    background: var(--bg-input) !important;
    border-color: var(--gold-dark) !important;
}

/* Dark mode ajustes */
.dark-mode .inventory-value-card {
    background: linear-gradient(135deg, rgba(230, 143, 66, 0.1) 0%, rgba(230, 143, 66, 0.02) 100%);
}

.dark-mode .alert-warning-elegant {
    background: rgba(232, 149, 30, 0.08);
}

.dark-mode .alert-danger-elegant {
    background: rgba(207, 59, 59, 0.08);
}

.dark-mode .stat-card-elegant:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(230, 143, 66, 0.2) !important;
}
</style>
@endsection

@push('scripts')
    @vite(['resources/js/dashboard.js'])
@endpush