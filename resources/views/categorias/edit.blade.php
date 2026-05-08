@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/categorias/edit.blade.php
    PROPÓSITO: Formulario de Edición de Categorías Existentes.
    ARQUITECTURA: Utiliza componentes Blade (<x-card>, <x-input>, <x-textarea>) 
                  para mantener diseño limpio y modular.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Edición de Categoría -->
    <x-card class="mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0 text-main">
                        <i class="bi bi-pencil-square me-2 text-gold"></i>Editar Categoría
                    </h2>
                    <span class="badge bg-light text-warning border border-warning border-opacity-25 rounded-pill px-3">Modo Edición</span>
                </div>
                <p class="mb-0 text-muted">Actualiza los datos o el estado de la categoría "{{ $categoria->nombre }}"</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('categorias.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Regresar al Directorio
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjeta Principal del Formulario -->
    <x-card>
        <div class="card-body p-4">
            <!-- Manejo Global de Errores de Validación -->
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

            <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" id="formEditarCategoria">
                @csrf
                @method('PUT')

                <!-- Sección: Información Básica -->
                <h5 class="fw-bold mb-4 d-flex align-items-center text-main">
                    <i class="bi bi-info-circle me-2 text-muted"></i> Información Básica
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Campo: Nombre de la Categoría -->
                    <div class="col-md-6">
                        <x-input name="nombre" label="Nombre de la Colección" icon='<i class="bi bi-fonts"></i>' required="true" value="{{ old('nombre', $categoria->nombre) }}" autocomplete="off" />
                    </div>

                    <!-- Campo: Slug (Identificador URL) -->
                    <div class="col-md-6">
                        <x-input name="slug" label="URL Amigable" icon='<i class="bi bi-link-45deg"></i>' value="{{ old('slug', $categoria->slug) }}" />
                    </div>
                </div>

                <!-- Sección: Detalles Extendidos -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top-modern text-main">
                    <i class="bi bi-justify-left me-2 text-muted"></i> Detalles Extendidos
                </h5>

                <div class="row g-4 mb-4">
                    <!-- Campo: Descripción -->
                    <div class="col-12">
                        <x-textarea name="descripcion" label="Descripción o Notas Adicionales" icon='<i class="bi bi-card-text"></i>' rows="3" value="{{ old('descripcion', $categoria->descripcion) }}" />
                    </div>

                    <!-- Campo: Estado Operativo Toggle -->
                    <div class="col-12 mt-4">
                        <div class="detail-box p-3">
                            <div class="form-check form-switch d-flex align-items-center m-0 p-0">
                                <label class="form-check-label fw-semibold me-auto text-main" for="activo">
                                    <i class="bi bi-power text-muted me-2"></i>Estado de la Categoría
                                </label>
                                <!-- Switch modernizado -->
                                <input class="form-check-input ms-3 mt-0" type="checkbox" role="switch" id="activo" name="activo" value="1" {{ old('activo', $categoria->activo) ? 'checked' : '' }} class="form-switch-lg cursor-pointer">
                            </div>
                        </div>
                        <div class="form-text mt-2 ms-1 text-muted"><i class="bi bi-info-circle me-1"></i>Si desactiva esta categoría, sus productos asociados podrían perder visibilidad en los catálogos públicos.</div>
                    </div>
                </div>

                <!-- Botonera de Acción -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top-modern">
                    <button type="button" class="btn btn-light-panaderia" onclick="window.history.back()">
                        <i class="bi bi-x-circle me-2"></i>Cancelar Cambios
                    </button>
                    <button type="submit" class="btn btn-warning rounded-3 fw-semibold py-2 px-4 text-white">
                        <i class="bi bi-save me-2"></i>Actualizar Datos
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/categorias.js'])
@endpush

