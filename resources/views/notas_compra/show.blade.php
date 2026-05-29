@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-color">
        <div>
            <h2 class="fw-bold mb-0 text-main">{{ $nota->nro_comprobante ?? 'NC-' . str_pad($nota->id, 4, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-muted small mb-0">
                Nota de Compra #{{ $nota->id }} | {{ $nota->fecha_pedido->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            @can('notas_compra.edit')
                @if($nota->estado === 'solicitado')
                    <a href="{{ route('notas_compra.edit', $nota) }}" class="btn-gold-panaderia px-4 py-2 rounded-pill">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                @endif
            @endcan
            <a href="{{ route('notas_compra.index') }}" class="btn-light-panaderia px-4 py-2 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- ==================== COLUMNA IZQUIERDA ==================== -->
        <div class="col-md-4">
            <!-- Estado -->
            <div class="card-modern p-4 mb-4">
                <div class="text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-main rounded-circle mb-3" style="width: 70px; height: 70px;">
                        @switch($nota->estado)
                            @case('solicitado')
                                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                                @break
                            @case('recibido')
                                <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                                @break
                            @case('cancelado')
                                <i class="bi bi-x-circle-fill fs-2 text-secondary"></i>
                                @break
                        @endswitch
                    </div>
                    <h6 class="text-muted small text-uppercase mb-2">Estado Actual</h6>
                    @switch($nota->estado)
                        @case('solicitado')
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                <i class="bi bi-hourglass-split me-1"></i> Solicitado
                            </span>
                            @break
                        @case('recibido')
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                <i class="bi bi-check-circle me-1"></i> Recibido
                            </span>
                            @break
                        @case('cancelado')
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                <i class="bi bi-x-circle me-1"></i> Cancelado
                            </span>
                            @break
                    @endswitch
                </div>
            </div>

            <!-- Resumen Financiero -->
            <div class="card-modern p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-color">
                    <div class="rounded-circle bg-main d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-cash-stack text-gold-dark fs-6"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-main">Resumen Financiero</h6>
                </div>
                <div class="bg-soft-gold rounded-3 p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Productos</span>
                        <div class="text-end">
                            <span class="fw-semibold text-success">{{ $nota->productos->count() }} items</span>
                            <span class="text-muted mx-1">•</span>
                            <span class="fw-semibold text-success">Bs. {{ number_format($nota->productos?->sum('subtotal') ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Insumos</span>
                        <div class="text-end">
                            <span class="fw-semibold text-info">{{ $nota->detalles->count() }} items</span>
                            <span class="text-muted mx-1">•</span>
                            <span class="fw-semibold text-info">Bs. {{ number_format($nota->detalles?->sum('subtotal') ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between pt-2 mt-2 border-top border-color">
                        <span class="fw-bold text-main">MONTO TOTAL</span>
                        <span class="fw-bold fs-5 text-gold-dark">Bs. {{ number_format($nota->monto_total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="card-modern p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-color">
                    <div class="rounded-circle bg-main d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-calendar-week text-gold-dark fs-6"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-main">Fechas</h6>
                </div>
                <div class="bg-soft-gold rounded-3 p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i> Pedido
                        </span>
                        <span class="fw-medium">{{ $nota->fecha_pedido->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($nota->fecha_recepcion)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">
                                <i class="bi bi-check-circle-fill me-1 text-success"></i> Recepción
                            </span>
                            <span class="fw-medium text-success">{{ $nota->fecha_recepcion->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 mt-1 border-top border-color">
                            <span class="text-muted small">
                                <i class="bi bi-clock-history me-1"></i> Días de Espera
                            </span>
                            <span class="fw-semibold text-gold-dark">{{ round($nota->fecha_pedido->diffInDays($nota->fecha_recepcion)) }} días</span>
                        </div>
                    @else
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">
                                <i class="bi bi-hourglass-split me-1 text-warning"></i> Transcurrido
                            </span>
                            <span class="fw-semibold text-warning">{{ $nota->fecha_pedido->diffInDays(now()) }} días</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Solicitante -->
            <div class="card-modern p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-color">
                    <div class="rounded-circle bg-main d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-person text-gold-dark fs-6"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-main">Solicitante</h6>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-medium text-main">{{ $nota->usuario->nombre ?? 'N/A' }}</span>
                        <br><small class="text-muted">{{ $nota->usuario->email ?? '' }}</small>
                    </div>
                    @if($nota->usuario->rol)
                        <span class="badge bg-soft-gold text-gold-dark">{{ $nota->usuario->rol->nombre ?? '' }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- ==================== COLUMNA DERECHA ==================== -->
        <div class="col-md-8">
            <!-- Proveedor - Información Detallada -->
            <div class="card-modern p-0 overflow-hidden mb-4">
                <div class="bg-soft-gold px-4 py-3 border-bottom border-color">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-main d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-building text-gold-dark fs-6"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-main">Información del Proveedor</h5>
                    </div>
                </div>
                <div class="p-4">
                    <!-- Empresa -->
                    <div class="mb-4 pb-3 border-bottom border-color">
                        <span class="text-muted small text-uppercase d-block mb-1">Empresa / Razón Social</span>
                        <span class="fs-4 fw-bold text-gold-dark">{{ $nota->proveedor->empresa ?? 'N/A' }}</span>
                    </div>

                    <div class="row g-4">
                        <!-- Contacto Principal -->
                        <div class="col-md-6">
                            <div class="bg-soft-gold rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-person-badge text-gold-dark"></i>
                                    <span class="fw-semibold text-main">Contacto Principal</span>
                                </div>
                                <div class="ps-3">
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Nombre</span>
                                        <span class="fw-medium">{{ $nota->proveedor->nombre_contacto ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Cargo</span>
                                        <span class="fw-medium">{{ $nota->proveedor->cargo_contacto ?? 'No especificado' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Identificación -->
                        <div class="col-md-6">
                            <div class="bg-soft-gold rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-upc-scan text-gold-dark"></i>
                                    <span class="fw-semibold text-main">Identificación</span>
                                </div>
                                <div class="ps-3">
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Código Proveedor</span>
                                        <span class="fw-medium">{{ $nota->proveedor->codigo ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">NIT / CI</span>
                                        <span class="fw-medium">{{ $nota->proveedor->nit ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contacto Comercial -->
                        <div class="col-md-6">
                            <div class="bg-soft-gold rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-telephone text-gold-dark"></i>
                                    <span class="fw-semibold text-main">Contacto Comercial</span>
                                </div>
                                <div class="ps-3">
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Teléfono</span>
                                        <span class="fw-medium">
                                            <a href="tel:{{ $nota->proveedor->telefono }}" class="text-decoration-none text-main">
                                                {{ $nota->proveedor->telefono ?? 'N/A' }}
                                            </a>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Teléfono Alternativo</span>
                                        <span class="fw-medium">{{ $nota->proveedor->telefono_alternativo ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comunicación -->
                        <div class="col-md-6">
                            <div class="bg-soft-gold rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-envelope text-gold-dark"></i>
                                    <span class="fw-semibold text-main">Comunicación</span>
                                </div>
                                <div class="ps-3">
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Email</span>
                                        <span class="fw-medium">
                                            <a href="mailto:{{ $nota->proveedor->email }}" class="text-decoration-none text-main">
                                                {{ $nota->proveedor->email ?? 'N/A' }}
                                            </a>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Sitio Web</span>
                                        <span class="fw-medium">{{ $nota->proveedor->sitio_web ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dirección Completa -->
                        <div class="col-12">
                            <div class="bg-soft-gold rounded-3 p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-geo-alt text-gold-dark"></i>
                                    <span class="fw-semibold text-main">Dirección</span>
                                </div>
                                <div class="ps-3">
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Dirección Principal</span>
                                        <span class="fw-medium">{{ $nota->proveedor->direccion ?? 'N/A' }}</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="text-muted small d-block">Ciudad / Departamento</span>
                                            <span class="fw-medium">{{ $nota->proveedor->ciudad ?? 'Santa Cruz' }} / {{ $nota->proveedor->departamento ?? 'Santa Cruz' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted small d-block">Código Postal</span>
                                            <span class="fw-medium">{{ $nota->proveedor->codigo_postal ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="col-12">
                            <div class="bg-soft-gold rounded-3 p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-info-circle text-gold-dark"></i>
                                    <span class="fw-semibold text-main">Información Adicional</span>
                                </div>
                                <div class="ps-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="text-muted small d-block">Forma de Pago</span>
                                            <span class="fw-medium">{{ $nota->proveedor->forma_pago ?? 'Contado' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted small d-block">Plazo de Entrega</span>
                                            <span class="fw-medium">{{ $nota->proveedor->plazo_entrega ?? '3-5 días hábiles' }}</span>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <span class="text-muted small d-block">Calificación Proveedor</span>
                                            <span class="fw-medium">
                                                @php $calificacion = $nota->proveedor->calificacion ?? 4.5; @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($calificacion))
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @elseif($i - 0.5 <= $calificacion)
                                                        <i class="bi bi-star-half text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1">({{ $calificacion }})</span>
                                            </span>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <span class="text-muted small d-block">Desde</span>
                                            <span class="fw-medium">{{ $nota->proveedor->created_at ? $nota->proveedor->created_at->format('d/m/Y') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            @if($nota->observaciones)
            <div class="card-modern p-0 overflow-hidden mb-4">
                <div class="bg-soft-gold px-4 py-3 border-bottom border-color">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-main d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-chat-text text-gold-dark fs-6"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-main">Observaciones de la Nota</h5>
                    </div>
                </div>
                <div class="p-4">
                    <p class="mb-0 text-main">{{ $nota->observaciones }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

    <!-- Sección de Productos Pedidos -->
    <div class="mt-4">
        <x-card>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold mb-0 text-main">
                        <i class="bi bi-bag-check me-2 text-success"></i>Productos Pedidos
                    </h5>
                    @can('notas_compra.edit')
                        @if($nota->estado === 'solicitado')
                            <button type="button" class="btn btn-gold-panaderia btn-sm" data-bs-toggle="modal" data-bs-target="#agregarProductoModal">
                                <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                            </button>
                        @endif
                    @endcan
                </div>

                @if($nota->productos && count($nota->productos) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-muted small text-uppercase fw-bold">Producto</th>
                                    <th class="text-muted small text-uppercase fw-bold text-center">Cantidad</th>
                                    <th class="text-muted small text-uppercase fw-bold text-end">Precio Unit.</th>
                                    <th class="text-muted small text-uppercase fw-bold text-end">Subtotal</th>
                                    <th class="text-muted small text-uppercase fw-bold text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nota->productos as $item)
                                <tr>
                                    <td>
                                        <i class="bi bi-tag text-success me-2"></i>
                                        <strong class="text-main">{{ $item->producto->nombre ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $item->producto_id }}</small>
                                    </td>
                                    <td class="text-center">{{ number_format($item->cantidad, 2) }}</td>
                                    <td class="text-end">Bs. {{ number_format($item->precio_compra_unitario, 2) }}</td>
                                    <td class="text-end fw-semibold text-gold-dark">Bs. {{ number_format($item->subtotal, 2) }}</td>
                                    <td class="text-center">
                                        @can('notas_compra.edit')
                                            @if($nota->estado === 'solicitado')
                                                <form action="{{ route('notas_compra.eliminar-producto', $item) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('¿Eliminar este producto?');" title="Eliminar">
                                                        <i class="bi bi-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-main">TOTAL PRODUCTOS:</td>
                                    <td class="text-end fw-bold text-gold-dark">Bs. {{ number_format($nota->productos->sum('subtotal'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-gold-dark opacity-50"></i>
                        <p class="mt-2 mb-0 text-muted">No hay productos agregados</p>
                        <small class="text-muted">Haz clic en "Agregar Producto" para comenzar</small>
                    </div>
                @endif
            </div>
        </x-card>
    </div>

    <!-- Sección de Insumos Pedidos -->
    <div class="mt-4">
        <x-card>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold mb-0 text-main">
                        <i class="bi bi-box-seam me-2 text-info"></i>Insumos Pedidos
                    </h5>
                    @can('notas_compra.edit')
                        @if($nota->estado === 'solicitado')
                            <button type="button" class="btn btn-gold-panaderia btn-sm" data-bs-toggle="modal" data-bs-target="#agregarInsumoModal">
                                <i class="bi bi-plus-circle me-1"></i> Agregar Insumo
                            </button>
                        @endif
                    @endcan
                </div>

                @if($nota->detalles && count($nota->detalles) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-muted small text-uppercase fw-bold">Insumo</th>
                                    <th class="text-muted small text-uppercase fw-bold text-center">Cantidad</th>
                                    <th class="text-muted small text-uppercase fw-bold text-end">Precio Unit.</th>
                                    <th class="text-muted small text-uppercase fw-bold text-end">Subtotal</th>
                                    <th class="text-muted small text-uppercase fw-bold text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nota->detalles as $detalle)
                                <tr>
                                    <td>
                                        <i class="bi bi-box text-info me-2"></i>
                                        <strong class="text-main">{{ $detalle->insumo->nombre ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $detalle->insumo_id }}</small>
                                    </td>
                                    <td class="text-center">{{ number_format($detalle->cantidad, 2) }} {{ $detalle->insumo->unidad_medida ?? 'un' }}</td>
                                    <td class="text-end">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="text-end fw-semibold text-gold-dark">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                                    <td class="text-center">
                                        @can('notas_compra.edit')
                                            @if($nota->estado === 'solicitado')
                                                <form action="{{ route('notas_compra.eliminar-detalle', $detalle) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('¿Eliminar este insumo?');" title="Eliminar">
                                                        <i class="bi bi-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-main">TOTAL INSUMOS:</td>
                                    <td class="text-end fw-bold text-gold-dark">Bs. {{ number_format($nota->detalles->sum('subtotal'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-gold-dark opacity-50"></i>
                        <p class="mt-2 mb-0 text-muted">No hay insumos agregados</p>
                        <small class="text-muted">Haz clic en "Agregar Insumo" para comenzar</small>
                    </div>
                @endif
            </div>
        </x-card>
    </div>

    <!-- Botones de Acción Adicionales -->
    <div class="mt-4 d-flex justify-content-end gap-3">
        @can('notas_compra.create')
            <button type="button" class="btn btn-light-panaderia" data-bs-toggle="modal" data-bs-target="#nuevosPedidosModal">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Pedido
            </button>
        @endcan
        
        @can('notas_compra.edit')
            @if($nota->estado === 'solicitado')
                <form action="{{ route('notas_compra.marcar-recibida', $nota) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success px-4" onclick="return confirm('¿Marcar esta nota como recibida? Esto actualizará el inventario.');">
                        <i class="bi bi-check-circle me-1"></i> Marcar Recibida
                    </button>
                </form>
            @endif
        @endcan
    </div>
</div>

<!-- Modals -->
<!-- Modal: Agregar Producto -->
<div class="modal fade" id="agregarProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-card border-0">
            <div class="modal-header border-color">
                <h5 class="modal-title text-main">
                    <i class="bi bi-bag-plus me-2 text-gold-dark"></i> Agregar Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('notas_compra.agregar-producto', $nota) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Producto <span class="text-danger">*</span></label>
                        <select name="producto_id" class="form-select" required>
                            <option value="">-- Selecciona un producto --</option>
                            @foreach($productos ?? [] as $producto)
                                <option value="{{ $producto->id }}" data-costo="{{ $producto->precio_costo }}" data-venta="{{ $producto->precio_venta }}">
                                    {{ $producto->nombre }} (Stock: {{ number_format($producto->stock_actual, 0) }} | Venta: Bs. {{ number_format($producto->precio_venta, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" class="form-control" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Precio de Compra (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" name="precio_compra_unitario" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-color">
                    <button type="button" class="btn btn-light-panaderia" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-gold-panaderia">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Agregar Insumo -->
<div class="modal fade" id="agregarInsumoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-card border-0">
            <div class="modal-header border-color">
                <h5 class="modal-title text-main">
                    <i class="bi bi-box-seam me-2 text-gold-dark"></i> Agregar Insumo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('notas_compra.agregar-detalle', $nota) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Insumo <span class="text-danger">*</span></label>
                        <select name="insumo_id" class="form-select" required>
                            <option value="">-- Selecciona un insumo --</option>
                            @foreach($insumos ?? [] as $insumo)
                                <option value="{{ $insumo->id }}">{{ $insumo->nombre }} ({{ $insumo->unidad_medida ?? 'un' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" class="form-control" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Precio Unitario (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" name="precio_unitario" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-color">
                    <button type="button" class="btn btn-light-panaderia" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-gold-panaderia">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Nuevo Pedido -->
<div class="modal fade" id="nuevosPedidosModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-card border-0">
            <div class="modal-header border-color">
                <h5 class="modal-title text-main">
                    <i class="bi bi-plus-circle me-2 text-gold-dark"></i> Crear Nuevo Pedido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('notas_compra.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Proveedor <span class="text-danger">*</span></label>
                        <select name="proveedor_codigo" class="form-select" required>
                            <option value="">-- Selecciona un proveedor --</option>
                            @foreach($proveedores ?? [] as $proveedor)
                                <option value="{{ $proveedor->codigo }}">{{ $proveedor->empresa }} - {{ $proveedor->nombre_contacto }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-main">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3" placeholder="Notas adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-color">
                    <button type="button" class="btn btn-light-panaderia" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-gold-panaderia">Crear Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection