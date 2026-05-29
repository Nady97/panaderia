@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4">
            <h2 class="fw-bold mb-3 text-main">
                <i class="bi bi-plus-circle me-2 text-gold"></i> Crear Nueva Factura
            </h2>
            <p class="text-muted">Completa los datos del cliente y los productos vendidos</p>
        </div>
    </x-card>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Errores encontrados:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('facturas_internas.store') }}" method="POST" class="needs-validation">
        @csrf

        <!-- Información del Cliente -->
        <x-card class="mb-4">
            <div class="p-3">
                <h5 class="fw-bold text-main mb-3">
                    <i class="bi bi-person-circle me-2"></i> Información del Cliente
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cliente_ci" class="form-label fw-semibold">CI/NIT <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cliente_ci') is-invalid @enderror" 
                               id="cliente_ci" name="cliente_ci" value="{{ old('cliente_ci') }}" required>
                        @error('cliente_ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cliente_telefono" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" class="form-control @error('cliente_telefono') is-invalid @enderror" 
                               id="cliente_telefono" name="cliente_telefono" value="{{ old('cliente_telefono') }}">
                        @error('cliente_telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cliente_direccion" class="form-label fw-semibold">Dirección</label>
                    <textarea class="form-control @error('cliente_direccion') is-invalid @enderror" 
                              id="cliente_direccion" name="cliente_direccion" rows="2">{{ old('cliente_direccion') }}</textarea>
                    @error('cliente_direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </x-card>

        <!-- Información de la Factura -->
        <x-card class="mb-4">
            <div class="p-3">
                <h5 class="fw-bold text-main mb-3">
                    <i class="bi bi-calendar me-2"></i> Información de la Factura
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_emision" class="form-label fw-semibold">Fecha de Emisión <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror" 
                               id="fecha_emision" name="fecha_emision" value="{{ old('fecha_emision', date('Y-m-d')) }}" required>
                        @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="puntos_ganados" class="form-label fw-semibold">Puntos Ganados</label>
                        <input type="number" class="form-control @error('puntos_ganados') is-invalid @enderror" 
                               id="puntos_ganados" name="puntos_ganados" value="{{ old('puntos_ganados', 0) }}" min="0">
                        @error('puntos_ganados')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="total" class="form-label fw-semibold">Total (Bs.) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('total') is-invalid @enderror" 
                           id="total" name="total" value="{{ old('total') }}" step="0.01" min="0.01" required readonly>
                    @error('total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </x-card>

        <!-- Productos de la Factura -->
        <x-card class="mb-4">
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-main mb-0">
                        <i class="bi bi-box-seam me-2"></i> Productos <span class="text-danger">*</span>
                    </h5>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-agregar-producto">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                    </button>
                </div>

                <div id="productos-container">
                    <!-- Los productos se agregan aquí con JavaScript -->
                </div>

                @error('productos')<div class="alert alert-danger mt-2">{{ $message }}</div>@enderror
            </div>
        </x-card>

        <!-- Botones de Acción -->
        <x-card>
            <div class="p-3 d-flex gap-2 justify-content-end">
                <a href="{{ route('facturas_internas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Crear Factura
                </button>
            </div>
        </x-card>
    </form>
</div>

<!-- Modal para agregar productos -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="select-producto" class="form-label fw-semibold">Producto <span class="text-danger">*</span></label>
                    <select id="select-producto" class="form-select" required>
                        <option value="">-- Seleccionar Producto --</option>
                        @foreach(\App\Models\Producto::where('estado', 'activo')->orderBy('nombre')->get() as $producto)
                            <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_venta }}">
                                {{ $producto->nombre }} (Bs. {{ number_format($producto->precio_venta, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="input-cantidad" class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
                    <input type="number" id="input-cantidad" class="form-control" min="1" value="1" required>
                </div>
                <div class="mb-3">
                    <label for="input-precio" class="form-label fw-semibold">Precio Unitario (Bs.) <span class="text-danger">*</span></label>
                    <input type="number" id="input-precio" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="input-descuento" class="form-label fw-semibold">Descuento (Bs.)</label>
                    <input type="number" id="input-descuento" class="form-control" step="0.01" min="0" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-confirmar-producto">Agregar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAgregarProducto = document.getElementById('btn-agregar-producto');
    const btnConfirmarProducto = document.getElementById('btn-confirmar-producto');
    const selectProducto = document.getElementById('select-producto');
    const inputCantidad = document.getElementById('input-cantidad');
    const inputPrecio = document.getElementById('input-precio');
    const inputDescuento = document.getElementById('input-descuento');
    const productosContainer = document.getElementById('productos-container');
    const totalInput = document.getElementById('total');
    const modalAgregarProducto = new bootstrap.Modal(document.getElementById('modalAgregarProducto'));
    let productoIndex = 0;

    // Mostrar modal cuando se hace clic en "Agregar Producto"
    btnAgregarProducto.addEventListener('click', function() {
        selectProducto.value = '';
        inputCantidad.value = '1';
        inputPrecio.value = '';
        inputDescuento.value = '0';
        modalAgregarProducto.show();
    });

    // Actualizar precio cuando se selecciona un producto
    selectProducto.addEventListener('change', function() {
        if (this.value) {
            const option = this.options[this.selectedIndex];
            const precio = option.getAttribute('data-precio');
            inputPrecio.value = precio;
        }
    });

    // Confirmar y agregar producto
    btnConfirmarProducto.addEventListener('click', function() {
        if (!selectProducto.value || !inputCantidad.value || !inputPrecio.value) {
            alert('Por favor completa todos los campos requeridos.');
            return;
        }

        const productId = selectProducto.value;
        const productName = selectProducto.options[selectProducto.selectedIndex].text;
        const cantidad = parseInt(inputCantidad.value);
        const precio = parseFloat(inputPrecio.value);
        const descuento = parseFloat(inputDescuento.value) || 0;
        const subtotal = cantidad * precio;
        const totalLinea = subtotal - descuento;

        // Crear HTML del producto
        const productoHTML = `
            <div class="row mb-3 p-2 border rounded bg-light" data-producto-index="${productoIndex}">
                <div class="col-md-5">
                    <small class="text-muted">Producto</small>
                    <div class="fw-semibold">${productName}</div>
                    <input type="hidden" name="productos[${productoIndex}][producto_id]" value="${productId}">
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Cantidad</small>
                    <div>${cantidad}</div>
                    <input type="hidden" name="productos[${productoIndex}][cantidad]" value="${cantidad}">
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Precio Unit.</small>
                    <div>Bs. ${precio.toFixed(2)}</div>
                    <input type="hidden" name="productos[${productoIndex}][precio_unitario]" value="${precio}">
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Desc./Total</small>
                    <div>Bs. ${descuento.toFixed(2)} / ${totalLinea.toFixed(2)}</div>
                    <input type="hidden" name="productos[${productoIndex}][descuento]" value="${descuento}">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-producto" data-index="${productoIndex}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;

        productosContainer.innerHTML += productoHTML;
        productoIndex++;

        // Actualizar total
        actualizarTotal();

        // Mostrar botón eliminar
        document.querySelectorAll('.btn-eliminar-producto').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                document.querySelector(`[data-producto-index="${index}"]`).remove();
                actualizarTotal();
            });
        });

        modalAgregarProducto.hide();
    });

    function actualizarTotal() {
        let total = 0;
        document.querySelectorAll('[data-producto-index]').forEach(el => {
            const subtotal = el.querySelector('input[name*="cantidad"]').value * 
                           el.querySelector('input[name*="precio_unitario"]').value;
            const descuento = parseFloat(el.querySelector('input[name*="descuento"]').value) || 0;
            total += (subtotal - descuento);
        });
        totalInput.value = total.toFixed(2);
    }
});
</script>

<style>
    .text-gold {
        color: #8B7355 !important;
    }
    
    .text-main {
        color: #2c3e50;
    }
</style>
@endsection
