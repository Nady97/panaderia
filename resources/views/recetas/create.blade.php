@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Creación -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0" class="text-main">
                        <i class="bi bi-journal-plus me-2" class="text-gold"></i>Nueva Receta
                    </h2>
                    <span class="badge bg-main bg-opacity-10 text-main border border-main border-opacity-25 rounded-pill px-3">Nuevo Registro</span>
                </div>
                <p class="mb-0" class="text-muted">Agrega una nueva fórmula de panadería a tu recetario logístico</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('recetas.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjeta Principal del Formulario -->
    <x-card>
        <!-- Manejo Global de Errores -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 d-flex align-items-start p-4 mb-4" class="bg-danger bg-opacity-10 text-danger rounded-3">
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
            <form method="POST" action="{{ route('recetas.store') }}" id="formCrearReceta">
                @csrf

                <!-- Sección: Información Base -->
                <h5 class="fw-bold mb-4 d-flex align-items-center" class="text-main">
                    <i class="bi bi-info-circle me-2 text-muted"></i> Ficha y Enfoque de Receta
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Campo: Nombre -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2" class="text-main">
                            Nombre de la Fórmula/Receta <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('nombre') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" required value="{{ old('nombre') }}" placeholder="Ej: Masa Madre Blanca 20h..." autocomplete="off">
                        </div>
                        @error('nombre')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campo: Producto de Destino -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2" class="text-main">
                            Producto Comercial Objetivo <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('producto_id') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-pie-chart"></i></span>
                            <select name="producto_id" class="form-select @error('producto_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Seleccione qué producto produce --</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('producto_id')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Sección: Operativa y Rendimiento -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top" class="text-main border-top-modern">
                    <i class="bi bi-gear-wide-connected me-2 text-muted"></i> Variables de Producción
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Rendimiento -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" class="text-main">
                            Rendimiento Esperado <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('rendimiento_estimado') is-invalid @enderror">
                            <span class="input-group-text fw-bold">Unid/Kg</span>
                            <input type="number" step="0.01" name="rendimiento_estimado" class="form-control @error('rendimiento_estimado') is-invalid @enderror" value="{{ old('rendimiento_estimado') }}" required placeholder="Ej: 50.00">
                        </div>
                        <div class="form-text mt-1 text-muted"><i class="bi bi-lightbulb me-1"></i> Producción final estimada.</div>
                        @error('rendimiento_estimado')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tiempo de Preparación -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" class="text-main">
                            Tiempo de Ciclo <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('tiempo_preparacion_min') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                            <input type="number" name="tiempo_preparacion_min" class="form-control @error('tiempo_preparacion_min') is-invalid @enderror" required value="{{ old('tiempo_preparacion_min') }}" placeholder="Minutos (Ej: 120)">
                        </div>
                        @error('tiempo_preparacion_min')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" class="text-main">
                            Estado Operativo <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern @error('estado') is-invalid @enderror">
                            <span class="input-group-text"><i class="bi bi-flag"></i></span>
                            <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="activa" {{ old('estado') == 'activa' ? 'selected' : '' }}>En Uso (Activa)</option>
                                <option value="borrador" {{ old('estado') == 'borrador' ? 'selected' : '' }}>En Pruebas (Borrador)</option>
                                <option value="obsoleta" {{ old('estado') == 'obsoleta' ? 'selected' : '' }}>Descartada (Obsoleta)</option>
                            </select>
                        </div>
                        @error('estado')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Sección: Memo / Notas -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2" class="text-main">
                            Instrucciones de Elaboración / Amasado
                        </label>
                        <div class="input-group input-group-modern @error('instrucciones') is-invalid @enderror">
                            <span class="input-group-text align-items-start pt-3"><i class="bi bi-card-text"></i></span>
                            <textarea name="instrucciones" class="form-control @error('instrucciones') is-invalid @enderror" rows="5" placeholder="1. Autólisis 30 min.&#10;2. Amasado de primera marcha...&#10;3. Fermentación en bloque..." style="resize: vertical;">{{ old('instrucciones') }}</textarea>
                        </div>
                        @error('instrucciones')
                            <div class="invalid-feedback d-block mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Botonera de Acción -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4" class="border-top-modern">
                    <button type="button" class="btn btn-light-panaderia" onclick="window.history.back()">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary-panaderia">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Registrar Fórmula
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection



