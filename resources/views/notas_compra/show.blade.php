@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-file-earmark me-2 text-gold"></i> 
                    @if($nota->nro_comprobante)
                        {{ $nota->nro_comprobante }}
                    @else
                        NCP-{{ str_pad($nota->id, 4, '0', STR_PAD_LEFT) }}
                    @endif
                </h2>
                <p class="mb-0 text-muted">Nota de Compra #{{ $nota->id }}</p>
            </div>
            <div>
                @switch($nota->estado)
                    @case('solicitado')
                        <span class="badge bg-warning text-dark" style="font-size: 0.9rem;">Solicitado</span>
                        @break
                    @case('recibido')
                        <span class="badge bg-success" style="font-size: 0.9rem;">Recibido</span>
                        @break
                    @case('cancelado')
                        <span class="badge bg-secondary" style="font-size: 0.9rem;">Cancelado</span>
                        @break
                @endswitch
            </div>
        </div>
    </x-card>

    <!-- Información del Proveedor -->
    <x-card class="mb-4">
        <div class="p-4">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-building me-2"></i> Información del Proveedor
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row small">
                        <dt class="col-sm-5">Empresa:</dt>
                        <dd class="col-sm-7">
                            <strong>{{ $nota->proveedor->empresa ?? 'N/A' }}</strong>
                        </dd>
                        
                        <dt class="col-sm-5">Contacto:</dt>
                        <dd class="col-sm-7">{{ $nota->proveedor->nombre_contacto ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">NIT:</dt>
                        <dd class="col-sm-7">{{ $nota->proveedor->nit ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Código:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-light text-dark">{{ $nota->proveedor->codigo }}</span>
                        </dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row small">
                        <dt class="col-sm-5">Email:</dt>
                        <dd class="col-sm-7">
                            <a href="mailto:{{ $nota->proveedor->email }}">{{ $nota->proveedor->email ?? 'N/A' }}</a>
                        </dd>
                        
                        <dt class="col-sm-5">Teléfono:</dt>
                        <dd class="col-sm-7">{{ $nota->proveedor->telefono ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Dirección:</dt>
                        <dd class="col-sm-7">{{ $nota->proveedor->direccion ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Estado:</dt>
                        <dd class="col-sm-7">
                            @if($nota->proveedor->estado === 'activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Información de Fechas y Montos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <x-card class="h-100">
                <div class="p-4">
                    <h5 class="fw-bold text-main mb-3">
                        <i class="bi bi-calendar me-2"></i> Fechas y Seguimiento
                    </h5>
                    <dl class="row small">
                        <dt class="col-sm-6">Fecha Pedido:</dt>
                        <dd class="col-sm-6 fw-semibold">{{ $nota->fecha_pedido->format('d/m/Y H:i') }}</dd>
                        
                        @if($nota->fecha_recepcion)
                            <dt class="col-sm-6">Fecha Recepción:</dt>
                            <dd class="col-sm-6 fw-semibold text-success">{{ $nota->fecha_recepcion->format('d/m/Y H:i') }}</dd>
                            
                            <dt class="col-sm-6">Días Espera:</dt>
                            <dd class="col-sm-6 fw-semibold">{{ $nota->fecha_pedido->diffInDays($nota->fecha_recepcion) }} días</dd>
                        @else
                            <dt class="col-sm-6">Días Desde:</dt>
                            <dd class="col-sm-6 fw-semibold">{{ $nota->fecha_pedido->diffInDays(now()) }} días</dd>
                        @endif
                        
                        <dt class="col-sm-6">Usuario Solicitante:</dt>
                        <dd class="col-sm-6">{{ $nota->usuario->nombre ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card class="h-100">
                <div class="p-4">
                    <h5 class="fw-bold text-main mb-3">
                        <i class="bi bi-cash-coin me-2"></i> Resumen Financiero
                    </h5>
                    <dl class="row small">
                        <dt class="col-sm-6">Monto Total:</dt>
                        <dd class="col-sm-6 fw-semibold text-gold" style="font-size: 1.2rem;">Bs. {{ number_format($nota->monto_total, 2) }}</dd>
                        
                        <dt class="col-sm-6">Total Insumos:</dt>
                        <dd class="col-sm-6">Bs. {{ number_format($nota->detalles?->sum('subtotal') ?? 0, 2) }}</dd>
                        
                        <dt class="col-sm-6">Total Productos:</dt>
                        <dd class="col-sm-6">Bs. {{ number_format($nota->productos?->sum('subtotal') ?? 0, 2) }}</dd>
                        
                        <dt class="col-sm-6">Ítems Totales:</dt>
                        <dd class="col-sm-6 fw-semibold">{{ ($nota->detalles?->count() ?? 0) + ($nota->productos?->count() ?? 0) }}</dd>
                    </dl>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Observaciones -->
    @if($nota->observaciones)
    <x-card class="mb-4">
        <div class="p-3 border-bottom">
            <h5 class="fw-bold text-main mb-0">
                <i class="bi bi-chat-left-text me-2"></i> Observaciones
            </h5>
        </div>
        <div class="p-3">
            <p class="mb-0">{{ $nota->observaciones }}</p>
        </div>
    </x-card>
    @endif

    <!-- Insumos Pedidos (Materias Primas) -->
    @if($nota->detalles && count($nota->detalles) > 0)
    <x-card class="mb-4">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-main mb-0">
                <i class="bi bi-box2 me-2"></i> Insumos Pedidos (Materias Primas)
            </h5>
            @can('notas_compra.edit')
                @if($nota->estado === 'solicitado')
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#agregarInsumoModal">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Insumo
                    </button>
                @endif
            @endcan
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold">Insumo</th>
                        <th class="fw-semibold text-center">Cantidad</th>
                        <th class="fw-semibold text-end">Precio Unit.</th>
                        <th class="fw-semibold text-end">Subtotal</th>
                        <th class="fw-semibold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nota->detalles as $detalle)
                        <tr>
                            <td>
                                <strong>{{ $detalle->insumo->nombre ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">ID: {{ $detalle->insumo_id }}</small>
                            </td>
                            <td class="text-center">{{ number_format($detalle->cantidad, 2) }} {{ $detalle->insumo->unidad_medida ?? 'un' }}</td>
                            <td class="text-end">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td class="text-end fw-semibold">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                            <td class="text-center">
                                @can('notas_compra.edit')
                                    @if($nota->estado === 'solicitado')
                                        <form action="{{ route('notas_compra.eliminar-detalle', $detalle) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este insumo?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif

    <!-- Productos Pedidos (Para Venta) -->
    @if($nota->productos && count($nota->productos) > 0)
    <x-card class="mb-4">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-main mb-0">
                <i class="bi bi-bag me-2"></i> Productos Pedidos (Para Venta)
            </h5>
            @can('notas_compra.edit')
                @if($nota->estado === 'solicitado')
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#agregarProductoModal">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                    </button>
                @endif
            @endcan
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold">Producto</th>
                        <th class="fw-semibold text-center">Cantidad</th>
                        <th class="fw-semibold text-end">Precio Compra Unit.</th>
                        <th class="fw-semibold text-end">Subtotal</th>
                        <th class="fw-semibold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nota->productos as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->producto->nombre ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">ID: {{ $item->producto_id }} | P. Venta: Bs. {{ number_format($item->producto->precio_venta ?? 0, 2) }}</small>
                            </td>
                            <td class="text-center">{{ number_format($item->cantidad, 2) }}</td>
                            <td class="text-end">Bs. {{ number_format($item->precio_compra_unitario, 2) }}</td>
                            <td class="text-end fw-semibold">Bs. {{ number_format($item->subtotal, 2) }}</td>
                            <td class="text-center">
                                @can('notas_compra.edit')
                                    @if($nota->estado === 'solicitado')
                                        <form action="{{ route('notas_compra.eliminar-producto', $item) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este producto?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif

    <!-- Acciones -->
    <x-card class="mb-4">
        <div class="p-3 d-flex gap-2 flex-wrap">
            @can('notas_compra.edit')
                @if($nota->estado === 'solicitado')
                    <a href="{{ route('notas_compra.edit', $nota) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Editar Nota
                    </a>
                    <form action="{{ route('notas_compra.marcar-recibida', $nota) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Marcar Recibida
                        </button>
                    </form>
                @endif
            @endcan
            
            @can('notas_compra.create')
                <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#nuevosPedidosModal">
                    <i class="bi bi-plus-lg me-1"></i> Pedir Nuevos Insumos
                </button>
            @endcan
            
            <a href="{{ route('notas_compra.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </x-card>
</div>

<!-- Modal: Pedir Nuevos Insumos -->
<div class="modal fade" id="nuevosPedidosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('notas_compra.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-main">
                        <i class="bi bi-plus-circle me-2"></i> Crear Nuevo Pedido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="proveedor_codigo" class="form-label fw-semibold">Proveedor</label>
                        <select name="proveedor_codigo" id="proveedor_codigo" class="form-select" required>
                            <option value="">Selecciona un proveedor...</option>
                            @foreach(\App\Models\Proveedor::all() as $proveedor)
                                <option value="{{ $proveedor->codigo }}">
                                    {{ $proveedor->empresa }} - {{ $proveedor->nombre_contacto }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Notas adicionales sobre el pedido..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> Después de crear el pedido, podrás agregar insumos y productos desde la vista de detalles.
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Crear Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar detalle -->
@if($nota->estado === 'solicitado')
<div class="modal fade" id="agregarDetalleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Insumo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('notas_compra.agregar-detalle', $nota) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="insumo_id" class="form-label fw-semibold">Insumo <span class="text-danger">*</span></label>
                        <select name="insumo_id" id="insumo_id" class="form-select" required>
                            <option value="">-- Selecciona un insumo --</option>
                            @foreach($insumos ?? [] as $insumo)
                                <option value="{{ $insumo->id }}">{{ $insumo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio_unitario" class="form-label fw-semibold">Precio Unitario <span class="text-danger">*</span></label>
                        <input type="number" name="precio_unitario" id="precio_unitario" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal para agregar producto -->
@if($nota->estado === 'solicitado')
<div class="modal fade" id="agregarProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('notas_compra.agregar-producto', $nota) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="producto_id" class="form-label fw-semibold">Producto <span class="text-danger">*</span></label>
                        <select name="producto_id" id="producto_id" class="form-select" required>
                            <option value="">-- Selecciona un producto --</option>
                            @foreach(\App\Models\Producto::all() as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad_prod" class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="cantidad_prod" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio_compra" class="form-label fw-semibold">Precio de Compra Unitario <span class="text-danger">*</span></label>
                        <input type="number" name="precio_compra_unitario" id="precio_compra" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
