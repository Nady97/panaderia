@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="flex-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="text-primary-heading mb-1">
                <i class="bi bi-pencil-square me-2 text-gold"></i>
                Editar Nota de Compra
            </h1>
            <p class="text-caption">
                <i class="bi bi-receipt me-1"></i>
                {{ $nota->nro_comprobante ?? 'NC-' . str_pad($nota->id, 4, '0', STR_PAD_LEFT) }}
            </p>
        </div>
        <div>
            @if($nota->estado === 'solicitado')
                <span class="badge bg-soft-warning text-warning px-3 py-2 rounded-pill">
                    <i class="bi bi-hourglass-split me-1"></i> Solicitado
                </span>
            @else
                <span class="badge bg-soft-secondary text-secondary px-3 py-2 rounded-pill">
                    <i class="bi bi-lock me-1"></i> {{ ucfirst($nota->estado) }}
                </span>
            @endif
        </div>
    </div>

    @if($nota->estado !== 'solicitado')
        <div class="alert alert-warning alert-dismissible fade show mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                <div>
                    <strong>¡No se puede editar!</strong> Esta nota está en estado 
                    <strong>"{{ ucfirst($nota->estado) }}"</strong>.
                    Solo las notas en estado <strong>"Solicitado"</strong> pueden ser modificadas.
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        
        <div class="text-center py-5">
            <a href="{{ route('notas_compra.show', $nota) }}" class="btn-gold-panaderia">
                <i class="bi bi-arrow-left me-2"></i> Volver a la Nota
            </a>
        </div>
    @else
        <div class="card-modern p-0 overflow-hidden">
            <form action="{{ route('notas_compra.update', $nota) }}" method="POST" id="editForm">
                @csrf 
                @method('PUT')
                
                <div class="p-4">
                    <!-- Información de la Nota -->
                    <div class="bg-soft-gold p-3 rounded-3 mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar3 text-gold"></i>
                                    <span class="text-muted">Fecha de pedido:</span>
                                    <strong class="text-main">{{ $nota->fecha_pedido->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>
                            @if($nota->fecha_recepcion)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span class="text-muted">Fecha de recepción:</span>
                                    <strong class="text-main">{{ $nota->fecha_recepcion->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Campos del formulario -->
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-main">
                                <i class="bi bi-building me-1 text-gold"></i> Proveedor 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="proveedor_codigo" id="proveedor_codigo" 
                                    class="form-select @error('proveedor_codigo') is-invalid @enderror" required>
                                <option value="">-- Selecciona un proveedor --</option>
                                @foreach($proveedores ?? [] as $proveedor)
                                    <option value="{{ $proveedor->codigo }}" @selected($nota->proveedor_codigo === $proveedor->codigo)>
                                        <strong>{{ $proveedor->empresa }}</strong> 
                                        @if($proveedor->nombre_contacto) - {{ $proveedor->nombre_contacto }} @endif
                                        @if($proveedor->nit) (NIT: {{ $proveedor->nit }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-main">
                                <i class="bi bi-chat-text me-1 text-gold"></i> Observaciones
                            </label>
                            <textarea name="observaciones" id="observaciones" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="Notas adicionales sobre el pedido...">{{ old('observaciones', $nota->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SECCIÓN DE PRODUCTOS EXISTENTES -->
                    <!-- ============================================ -->
                    <div class="mt-4">
                        <div class="flex-between flex-wrap gap-2 mb-3">
                            <h5 class="fw-bold mb-0 text-main">
                                <i class="bi bi-bag-check me-2 text-gold"></i> Productos en esta compra
                            </h5>
                            <button type="button" class="btn-gold-panaderia btn-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
                                <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                            </button>
                        </div>
                        
                        @if($nota->productos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="bg-soft-gold">
                                        <tr>
                                            <th class="text-caption">Producto</th>
                                            <th class="text-caption text-center">Cantidad</th>
                                            <th class="text-caption text-end">Precio Unit.</th>
                                            <th class="text-caption text-end">Subtotal</th>
                                            <th class="text-caption text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($nota->productos as $item)
                                        <tr>
                                            <td>
                                                <strong class="text-main">{{ $item->producto->nombre }}</strong>
                                                <br><small class="text-muted">ID: {{ $item->producto_id }}</small>
                                            </td>
                                            <td class="text-center align-middle">{{ number_format($item->cantidad, 2) }}</td>
                                            <td class="text-end align-middle">Bs. {{ number_format($item->precio_compra_unitario, 2) }}</td>
                                            <td class="text-end align-middle fw-semibold text-gold">Bs. {{ number_format($item->subtotal, 2) }}</td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="action-btn action-btn-danger p-2 rounded-circle" 
                                                        onclick="eliminarProducto({{ $item->id }})" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-soft-gold">
                                        <tr>
                                            <td colspan="3" class="text-end fw-semibold">TOTAL PRODUCTOS:</td>
                                            <td class="text-end fw-bold text-gold">Bs. {{ number_format($nota->productos->sum('subtotal'), 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert-modern border rounded-3 p-3 text-center bg-soft-gold">
                                <i class="bi bi-inbox fs-4 text-gold"></i>
                                <p class="mb-0 text-main">No hay productos agregados aún</p>
                                <small class="text-muted">Haz clic en "Agregar Producto" para comenzar</small>
                            </div>
                        @endif
                    </div>

                    <!-- ============================================ -->
                    <!-- SECCIÓN DE INSUMOS EXISTENTES -->
                    <!-- ============================================ -->
                    <div class="mt-4">
                        <div class="flex-between flex-wrap gap-2 mb-3">
                            <h5 class="fw-bold mb-0 text-main">
                                <i class="bi bi-box-seam me-2 text-gold"></i> Insumos en esta compra
                            </h5>
                            <button type="button" class="btn-gold-panaderia btn-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalAgregarInsumo">
                                <i class="bi bi-plus-circle me-1"></i> Agregar Insumo
                            </button>
                        </div>
                        
                        @if($nota->detalles->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="bg-soft-gold">
                                        <tr>
                                            <th class="text-caption">Insumo</th>
                                            <th class="text-caption text-center">Cantidad</th>
                                            <th class="text-caption text-end">Precio Unit.</th>
                                            <th class="text-caption text-end">Subtotal</th>
                                            <th class="text-caption text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($nota->detalles as $detalle)
                                        <tr>
                                            <td>
                                                <strong class="text-main">{{ $detalle->insumo->nombre }}</strong>
                                                <br><small class="text-muted">ID: {{ $detalle->insumo_id }}</small>
                                            </td>
                                            <td class="text-center align-middle">{{ number_format($detalle->cantidad, 2) }} {{ $detalle->insumo->unidad_medida ?? 'un' }}</td>
                                            <td class="text-end align-middle">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td class="text-end align-middle fw-semibold text-gold">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                                            <td class="text-center align-middle">
                                                <form action="{{ route('notas_compra.eliminar-detalle', $detalle) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="action-btn action-btn-danger p-2 rounded-circle border-0" 
                                                            onclick="return confirm('¿Eliminar este insumo?')" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-soft-gold">
                                        <tr>
                                            <td colspan="3" class="text-end fw-semibold">TOTAL INSUMOS:</td>
                                            <td class="text-end fw-bold text-gold">Bs. {{ number_format($nota->detalles->sum('subtotal'), 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert-modern border rounded-3 p-3 text-center bg-soft-gold">
                                <i class="bi bi-inbox fs-4 text-gold"></i>
                                <p class="mb-0 text-main">No hay insumos agregados aún</p>
                                <small class="text-muted">Haz clic en "Agregar Insumo" para comenzar</small>
                            </div>
                        @endif
                    </div>

                    <!-- Resumen Total -->
                    <div class="mt-4 pt-3 border-top border-color">
                        <div class="flex-between flex-wrap gap-2">
                            <h5 class="fw-bold mb-0 text-main">
                                <i class="bi bi-calculator me-2 text-gold"></i> Resumen de la Compra
                            </h5>
                            <div class="text-end">
                                <span class="text-muted small">Total Productos:</span>
                                <span class="fw-semibold me-3">Bs. {{ number_format($nota->productos->sum('subtotal'), 2) }}</span>
                                <span class="text-muted small">Total Insumos:</span>
                                <span class="fw-semibold me-3">Bs. {{ number_format($nota->detalles->sum('subtotal'), 2) }}</span>
                                <span class="text-muted small">MONTO TOTAL:</span>
                                <span class="fw-bold text-gold fs-5">Bs. {{ number_format($nota->monto_total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="mt-4 pt-3 border-top border-color d-flex gap-3 justify-content-end flex-wrap">
                        <a href="{{ route('notas_compra.show', $nota) }}" class="btn-light-panaderia px-4">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn-gold-panaderia px-4" id="btnGuardar">
                            <i class="bi bi-check-circle me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>

<!-- ============================================ -->
<!-- MODAL AGREGAR PRODUCTO -->
<!-- ============================================ -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-card border-0">
            <div class="modal-header border-color">
                <h5 class="modal-title text-main">
                    <i class="bi bi-bag-plus me-2 text-gold"></i> Agregar Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('notas_compra.agregar-producto', $nota) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Producto <span class="text-danger">*</span></label>
                        <select name="producto_id" id="producto_id_modal" class="form-select" required>
                            <option value="">-- Selecciona un producto --</option>
                            @foreach($productos ?? [] as $producto)
                                <option value="{{ $producto->id }}" 
                                        data-costo="{{ $producto->precio_costo }}"
                                        data-venta="{{ $producto->precio_venta }}"
                                        data-nombre="{{ $producto->nombre }}">
                                    {{ $producto->nombre }} 
                                    (Stock: {{ number_format($producto->stock_actual, 0) }} | 
                                     Venta: Bs. {{ number_format($producto->precio_venta, 2) }} |
                                     Costo: Bs. {{ number_format($producto->precio_costo, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="preciosReferencia" class="alert bg-soft-gold small d-none rounded-3">
                        <i class="bi bi-info-circle text-gold"></i> Precios de referencia:
                        <div class="row mt-1">
                            <div class="col-6">P. Venta: <strong id="refVenta">Bs. 0.00</strong></div>
                            <div class="col-6">P. Costo: <strong id="refCosto">Bs. 0.00</strong></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="cantidad_modal" class="form-control" step="0.01" min="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Precio de Compra Unitario (Bs.) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-soft-gold border-color">Bs.</span>
                            <input type="number" name="precio_compra_unitario" id="precio_compra_modal" class="form-control" step="0.01" min="0" required>
                            <button type="button" id="btnUsarCosto" class="btn-light-panaderia" title="Usar precio de costo">Costo</button>
                            <button type="button" id="btnUsarVenta" class="btn-light-panaderia" title="Usar precio de venta">Venta</button>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Subtotal</label>
                        <div class="bg-soft-gold p-2 rounded-3 text-end">
                            <span class="fs-4 fw-bold text-gold" id="subtotal_preview">Bs. 0.00</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-color">
                    <button type="button" class="btn-light-panaderia" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-gold-panaderia">Agregar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL AGREGAR INSUMO -->
<!-- ============================================ -->
<div class="modal fade" id="modalAgregarInsumo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-card border-0">
            <div class="modal-header border-color">
                <h5 class="modal-title text-main">
                    <i class="bi bi-box-seam me-2 text-gold"></i> Agregar Insumo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('notas_compra.agregar-detalle', $nota) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Insumo <span class="text-danger">*</span></label>
                        <select name="insumo_id" class="form-select" required>
                            <option value="">-- Selecciona un insumo --</option>
                            @foreach($insumos ?? [] as $insumo)
                                <option value="{{ $insumo->id }}" data-unidad="{{ $insumo->unidad_medida }}">
                                    {{ $insumo->nombre }} ({{ $insumo->unidad_medida ?? 'un' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" class="form-control" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Precio Unitario (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" name="precio_unitario" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-color">
                    <button type="button" class="btn-light-panaderia" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-gold-panaderia">Agregar Insumo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Prevenir doble envío
const editForm = document.getElementById('editForm');
const btnGuardar = document.getElementById('btnGuardar');

if (editForm) {
    editForm.addEventListener('submit', function() {
        if (btnGuardar) {
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Guardando...';
        }
    });
}

// ============================================
// Lógica del modal de productos
// ============================================
const productoSelect = document.getElementById('producto_id_modal');
const precioCompra = document.getElementById('precio_compra_modal');
const cantidad = document.getElementById('cantidad_modal');
const subtotalSpan = document.getElementById('subtotal_preview');
const preciosRef = document.getElementById('preciosReferencia');
const refVenta = document.getElementById('refVenta');
const refCosto = document.getElementById('refCosto');

function actualizarSubtotal() {
    let cant = parseFloat(cantidad?.value) || 0;
    let precio = parseFloat(precioCompra?.value) || 0;
    if (subtotalSpan) subtotalSpan.textContent = `Bs. ${(cant * precio).toFixed(2)}`;
}

productoSelect?.addEventListener('change', function() {
    let selected = this.options[this.selectedIndex];
    if (this.value) {
        let costo = parseFloat(selected.dataset.costo) || 0;
        let venta = parseFloat(selected.dataset.venta) || 0;
        if (refVenta) refVenta.textContent = `Bs. ${venta.toFixed(2)}`;
        if (refCosto) refCosto.textContent = `Bs. ${costo.toFixed(2)}`;
        if (preciosRef) preciosRef.classList.remove('d-none');
        if (precioCompra) precioCompra.value = costo;
        actualizarSubtotal();
    } else {
        if (preciosRef) preciosRef.classList.add('d-none');
        if (precioCompra) precioCompra.value = '';
        actualizarSubtotal();
    }
});

document.getElementById('btnUsarCosto')?.addEventListener('click', function() {
    let selected = productoSelect?.options[productoSelect.selectedIndex];
    if (selected?.value) {
        let costo = parseFloat(selected.dataset.costo) || 0;
        if (precioCompra) precioCompra.value = costo;
        actualizarSubtotal();
    }
});

document.getElementById('btnUsarVenta')?.addEventListener('click', function() {
    let selected = productoSelect?.options[productoSelect.selectedIndex];
    if (selected?.value) {
        let venta = parseFloat(selected.dataset.venta) || 0;
        if (precioCompra) precioCompra.value = venta;
        actualizarSubtotal();
    }
});

cantidad?.addEventListener('input', actualizarSubtotal);
precioCompra?.addEventListener('input', actualizarSubtotal);

// ============================================
// Eliminar producto con AJAX (opcional)
// ============================================
function eliminarProducto(productoId) {
    if (confirm('¿Eliminar este producto?')) {
        fetch(`/notas-compra/producto/${productoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error al eliminar');
            }
        });
    }
}
</script>
@endpush
@endsection