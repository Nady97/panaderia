@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Creación de Producto -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0 text-main">
                        <i class="bi bi-box-seam me-2 text-gold"></i>Registro de Producto
                    </h2>
                    <x-badge type="primary" class="rounded-pill border border-primary border-opacity-25 px-3 bg-opacity-10 text-main">Nuevo Registro</x-badge>
                </div>
                <p class="mb-0 text-secondary">Agregue un nuevo pan o artículo al catálogo de operaciones</p>
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
            <form method="POST" action="{{ route('productos.store') }}" id="formCrearProducto">
                @csrf

                <!-- Sección: Identificación y Clasificación -->
                <h5 class="fw-bold mb-4 d-flex align-items-center text-main">
                    <i class="bi bi-info-circle me-2 text-muted"></i> Identificación y Clasificación
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Campo: Nombre del producto -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">
                            Nombre del Pan o Producto <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('nombre') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-cup-hot"></i></span>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" required value="{{ old('nombre') }}" placeholder="Ej: Pan Francés Molde..." autocomplete="off">
                        </div>
                        @error('nombre')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campo: Categoría -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">
                            Familia o Categoría
                        </label>
                        <div class="input-group input-group-modern @error('categoria_id') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-tags"></i></span>
                            <select name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror">
                                <option value="">-- Seleccionar Categoría (Opcional) --</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', request('categoria_id')) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('categoria_id')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Sección: Finanzas -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top text-main">
                    <i class="bi bi-currency-dollar me-2 text-muted"></i> Costos y Valoración Firme
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Precio Costo -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">
                            Precio de Producción (Costo)
                        </label>
                        <div class="input-group input-group-modern @error('precio_costo') is-invalid @enderror">
                            <span class="input-group-text text-secondary fw-bold">Bs.</span>
                            <input type="number" step="0.01" name="precio_costo" class="form-control @error('precio_costo') is-invalid @enderror" value="{{ old('precio_costo') }}" placeholder="0.00">
                        </div>
                        @error('precio_costo')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Precio Venta -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">
                            Precio Público (Venta) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('precio_venta') is-invalid @enderror">
                            <span class="input-group-text text-success fw-bold">Bs.</span>
                            <input type="number" step="0.01" name="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror" required value="{{ old('precio_venta') }}" placeholder="0.00">
                        </div>
                        @error('precio_venta')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Sección: Operativa y Logística -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top text-main">
                    <i class="bi bi-truck me-2 text-muted"></i> Operativa de Almacén
                </h5>

                <div class="row g-4 mb-4">
                    <!-- Stock Inicial -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">
                            Stock Físico Inicial <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('stock') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                            <input type="number" step="0.01" name="stock" class="form-control @error('stock') is-invalid @enderror" required value="{{ old('stock') }}" placeholder="Unidades actuales">
                        </div>
                        @error('stock')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock Mínimo -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">
                            Alerta de Stock Mínimo
                        </label>
                        <div class="input-group input-group-modern @error('stock_minimo') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-exclamation-triangle text-warning"></i></span>
                            <input type="number" step="0.01" name="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" value="{{ old('stock_minimo', 5) }}">
                        </div>
                        <div class="form-text mt-1 text-muted"><i class="bi bi-lightbulb me-1"></i> Notificará cuando queden pocas unidades.</div>
                        @error('stock_minimo')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Botonera de Acción -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-light-panaderia" onclick="window.history.back()">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary-panaderia">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Guardar e Ingresar Producto
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

