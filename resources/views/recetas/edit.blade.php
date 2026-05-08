@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/recetas/edit.blade.php
    PROPÓSITO: Formulario para la edición de una Receta existente.
    ARQUITECTURA: Mantenimiento de legibilidad con Componentes Blade (<x-card>,
                  <x-input>, <x-select>, <x-textarea>). Código DRY, uniforme.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Edición -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0" class="text-main">
                        <i class="bi bi-pencil-square me-2" class="text-gold"></i>Editar Receta
                    </h2>
                    <span class="badge bg-light text-warning border border-warning border-opacity-25 rounded-pill px-3">Modo Edición</span>
                </div>
                <p class="mb-0" class="text-muted">Modifica la fórmula operativa "{{ $receta->nombre }}"</p>
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
            <form method="POST" action="{{ route('recetas.update', $receta->id) }}" id="formEditarReceta">
                @csrf
                @method('PUT')

                <!-- Sección: Información Base -->
                <h5 class="fw-bold mb-4 d-flex align-items-center" class="text-main">
                    <i class="bi bi-info-circle me-2 text-muted"></i> Ficha y Enfoque de Receta
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Campo: Nombre -->
                    <div class="col-md-6 form-group">
                        <x-input name="nombre" label="Nombre de la Fórmula/Receta" required="true" icon='<i class="bi bi-journal-text"></i>' value="{{ old('nombre', $receta->nombre) }}" placeholder="Ej: Masa Madre..." />
                    </div>

                    <!-- Campo: Producto de Destino -->
                    <div class="col-md-6 form-group">
                        <x-select name="producto_id" label="Producto Comercial Objetivo" required="true" icon='<i class="bi bi-pie-chart"></i>'>
                            <option value="" disabled>-- Seleccione qué producto produce --</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" {{ old('producto_id', $receta->producto_id) == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </div>

                <!-- Sección: Operativa y Rendimiento -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top" class="text-main border-top-modern" style="border-top: 1px solid var(--border-color);">
                    <i class="bi bi-gear-wide-connected me-2 text-muted"></i> Variables de Producción
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Rendimiento -->
                    <div class="col-md-4 form-group">
                        <x-input type="number" step="0.01" name="rendimiento_estimado" label="Rendimiento Esperado" required="true" icon='<span class="fw-bold" style="font-size: 0.85rem">Unid/Kg</span>' value="{{ old('rendimiento_estimado', $receta->rendimiento_estimado) }}" />
                    </div>

                    <!-- Tiempo de Preparación -->
                    <div class="col-md-4 form-group">
                        <x-input type="number" name="tiempo_preparacion_min" label="Tiempo de Ciclo" required="true" icon='<i class="bi bi-clock-history"></i>' value="{{ old('tiempo_preparacion_min', $receta->tiempo_preparacion_min) }}" />
                    </div>

                    <!-- Estado -->
                    <div class="col-md-4 form-group">
                        <x-select name="estado" label="Estado Operativo" required="true" icon='<i class="bi bi-flag"></i>'>
                            <option value="activa" {{ old('estado', $receta->estado) == 'activa' ? 'selected' : '' }}>En Uso (Activa)</option>
                            <option value="borrador" {{ old('estado', $receta->estado) == 'borrador' ? 'selected' : '' }}>En Pruebas (Borrador)</option>
                            <option value="obsoleta" {{ old('estado', $receta->estado) == 'obsoleta' ? 'selected' : '' }}>Descartada (Obsoleta)</option>
                        </x-select>
                    </div>
                </div>

                <!-- Sección: Memo / Notas -->
                <div class="row g-4 mb-4">
                    <div class="col-12 form-group">
                        <x-textarea name="instrucciones" label="Instrucciones de Elaboración / Amasado" rows="5" icon='<i class="bi bi-card-text"></i>' value="{{ old('instrucciones', $receta->instrucciones) }}" />
                    </div>
                </div>

                <!-- Botonera de Acción -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top-modern" style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <button type="button" class="btn btn-light-panaderia" onclick="window.history.back()">
                        <i class="bi bi-x-circle me-2"></i>Cancelar Cambios
                    </button>
                    <button type="submit" class="btn btn-gold-panaderia" style="border-radius: 10px; padding: 0.6rem 1.5rem; background: var(--gold-light); color: #fff; border: 1px solid var(--gold-dark);">
                        <i class="bi bi-save me-2"></i>Actualizar Fórmula
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection


