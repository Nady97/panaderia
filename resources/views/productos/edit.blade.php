@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Edición de Producto -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0 text-main">
                        <i class="bi bi-pencil-square me-2 text-gold"></i>Editar Producto
                    </h2>
                    <x-badge type="warning" class="rounded-pill border border-warning border-opacity-25 px-3 bg-opacity-10 text-warning">Modo Edición</x-badge>
                </div>
                <p class="mb-0 text-secondary">Actualiza la información, costos o stock del producto "{{ $producto->nombre }}"</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('productos.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Regresar al Directorio
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjeta Principal del Formulario -->
    <x-card>
        <!-- Manejo Global de Errores -->
        @if ($errors->any())
            <div class="alert alert-danger alert-danger-modern border-0 d-flex align-items-start p-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 mt-1"></i>
                <div class="w-100">
                    <h6 class="fw-bold mb-2">Se encontraron problemas en la validación:</h6>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="mb-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('productos.update', $producto->id) }}" id="formEditarProducto" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Sección: Identificación y Clasificación -->
                <h5 class="fw-bold mb-4 d-flex align-items-center text-main">
                    <i class="bi bi-info-circle me-2 text-muted"></i> Identificación y Clasificación
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Campo: Nombre del producto -->
                    <div class="col-md-6">
                        <!-- MEJORA: Implementando Componente x-input para consistencia UI/UX -->
                        <x-input name="nombre" label="Nombre del Pan o Producto" required="true" icon='<i class="bi bi-cup-hot"></i>' value="{{ old('nombre', $producto->nombre) }}" placeholder="Ej: Pan Francés Molde..." />
                    </div>

                    <!-- Campo: Categoría -->
                    <div class="col-md-6">
                        <!-- MEJORA: Implementando el nuevo Componente x-select para estructurar Dropdowns -->
                        <x-select name="categoria_id" label="Familia o Categoría" icon='<i class="bi bi-tags"></i>'>
                            <option value="">-- Sin Categoría --</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                </div>

                <!-- Sección: Finanzas -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top text-main">
                    <i class="bi bi-currency-dollar me-2 text-muted"></i> Costos y Valoración Firme
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Precio Costo -->
                    <div class="col-md-6">
                        <x-input type="number" step="0.01" name="precio_costo" label="Precio de Producción (Costo)" icon='<span class="text-secondary fw-bold">Bs.</span>' value="{{ old('precio_costo', $producto->precio_costo) }}" placeholder="0.00" />
                    </div>

                    <!-- Precio Venta -->
                    <div class="col-md-6">
                        <x-input type="number" step="0.01" name="precio_venta" label="Precio Público (Venta)" required="true" icon='<span class="text-success fw-bold">Bs.</span>' value="{{ old('precio_venta', $producto->precio_venta) }}" placeholder="0.00" />
                    </div>
                </div>

                <!-- Sección: Operativa y Logística -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top text-main">
                    <i class="bi bi-truck me-2 text-muted"></i> Operativa de Almacén
                </h5>

                <div class="row g-4 mb-4">
                    <!-- Stock Actual -->
                    <div class="col-md-4">
                        <x-input type="number" step="0.01" name="stock" label="Stock Físico Actual" required="true" icon='<i class="bi bi-boxes"></i>' value="{{ old('stock', $producto->stock) }}" />
                    </div>

                    <!-- Stock Mínimo -->
                    <div class="col-md-4">
                        <x-input type="number" step="0.01" name="stock_minimo" label="Alerta de Stock Mínimo" icon='<i class="bi bi-exclamation-triangle text-warning"></i>' value="{{ old('stock_minimo', $producto->stock_minimo) }}" />
                        <div class="form-text text-muted" style="margin-top: -15px;"><i class="bi bi-lightbulb me-1"></i> Notificará cuando queden pocas unidades.</div>
                    </div>
					
                    <!-- Estado del Producto -->
                    <div class="col-md-4">
                        <x-select name="estado" label="Estado del Producto" required="true" icon='<i class="bi bi-toggle-on"></i>'>
                            <option value="activo" {{ old('estado', $producto->estado) == 'activo' ? 'selected' : '' }}>Activo (En Venta)</option>
                            <option value="agotado" {{ old('estado', $producto->estado) == 'agotado' ? 'selected' : '' }}>Agotado (Sin Stock)</option>
                            <option value="descontinuado" {{ old('estado', $producto->estado) == 'descontinuado' ? 'selected' : '' }}>Descontinuado (Oculto)</option>
                        </x-select>
                    </div>
                </div>

                <!-- Sección: Imagen -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top text-main">
                    <i class="bi bi-image me-2 text-muted"></i> Imagen del Producto
                </h5>

                <div class="row g-4 mb-4 align-items-end">
                    <div class="col-md-6">
                        <x-input type="file" name="imagen" label="Actualizar Fotografía" icon='<i class="bi bi-upload"></i>' accept="image/*" />
                        <div class="form-text mt-1 text-muted"><i class="bi bi-image me-1"></i> Si no subes una nueva imagen, se conserva la actual.</div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded border" style="background: var(--bg-input); border-color: var(--border-color) !important;">
                            <span class="d-block text-muted small text-uppercase fw-bold mb-2">Imagen actual</span>
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid rounded" style="max-height: 120px; object-fit: contain; background: var(--bg-card); width: 100%; padding: 8px;">
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-image fs-1 d-block mb-2"></i>
                                    Sin imagen
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Botonera de Acción -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-light-panaderia" onclick="window.history.back()">
                        <i class="bi bi-x-circle me-2"></i>Cancelar Cambios
                    </button>
                    <button type="submit" class="btn btn-warning text-white font-weight-bold" style="border-radius: 10px; padding: 0.6rem 1.5rem;">
                        <i class="bi bi-save me-2"></i>Actualizar Producto
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/productos.js'])
@endpush
