@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Creacion -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0 text-main">
                        <i class="bi bi-basket-fill me-2 text-gold"></i>Registro de Insumo
                    </h2>
                    <span class="badge bg-main bg-opacity-10 text-main border border-main border-opacity-25 rounded-pill px-3">Nuevo Registro</span>
                </div>
                <p class="mb-0 text-muted">Agrega un insumo al inventario de recetas y produccion</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('insumos.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Regresar al Directorio
                </a>
            </div>
        </div>
    </x-card>

    <x-card>
        @if ($errors->any())
            <div class="alert alert-danger-modern mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 mt-1"></i>
                <div class="w-100">
                    <h6 class="fw-bold mb-2">Se encontraron los siguientes problemas:</h6>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="mb-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('insumos.store') }}" method="POST" id="formCrearInsumo">
                @csrf

                <h5 class="fw-bold mb-4 d-flex align-items-center text-main">
                    <i class="bi bi-info-circle me-2 text-muted"></i> Informacion Basica
                </h5>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">Nombre del Insumo <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern @error('nombre') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-fonts"></i></span>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej: Harina de trigo" required autocomplete="off">
                        </div>
                        @error('nombre')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2 text-main">Unidad de Medida <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern @error('unidad_medida') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                            <input type="text" name="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" value="{{ old('unidad_medida') }}" placeholder="Ej: kg, g, lt, unid" required>
                        </div>
                        @error('unidad_medida')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top-modern text-main">
                    <i class="bi bi-graph-up me-2 text-muted"></i> Control de Stock
                </h5>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2 text-main">Stock Actual <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern @error('stock_actual') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                            <input type="number" name="stock_actual" class="form-control @error('stock_actual') is-invalid @enderror" value="{{ old('stock_actual', 0) }}" min="0" step="0.01" required>
                        </div>
                        @error('stock_actual')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2 text-main">Stock Minimo</label>
                        <div class="input-group input-group-modern @error('stock_minimo') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-exclamation-triangle"></i></span>
                            <input type="number" name="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" value="{{ old('stock_minimo') }}" min="0" step="0.01" placeholder="Ej: 5">
                        </div>
                        @error('stock_minimo')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2 text-main">Costo Promedio (Bs)</label>
                        <div class="input-group input-group-modern @error('precio_compra_promedio') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-cash"></i></span>
                            <input type="number" name="precio_compra_promedio" class="form-control @error('precio_compra_promedio') is-invalid @enderror" value="{{ old('precio_compra_promedio') }}" min="0" step="0.01" placeholder="Ej: 12.50">
                        </div>
                        @error('precio_compra_promedio')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top-modern">
                    <button type="button" class="btn btn-light-panaderia" onclick="window.history.back()">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary-panaderia" id="btnGuardar">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Guardar Insumo
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection
