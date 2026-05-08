@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/categorias/create.blade.php
    PROPÓSITO: Formulario de Creación de Categorías.
    ARQUITECTURA: Utiliza componentes Blade (<x-card>, <x-input>, <x-textarea>) 
                  para mantener el principio DRY y consistencia del UI.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Creación de Categoría -->
    <x-card class="mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0 text-main">
                        <i class="bi bi-tag-fill me-2 text-gold"></i>Registro de Categoría
                    </h2>
                    <span class="badge bg-main bg-opacity-10 text-main border border-main border-opacity-25 rounded-pill px-3">Nuevo Registro</span>
                </div>
                <p class="mb-0 text-muted">Agregue una nueva línea o familia de productos al catálogo de su panadería</p>
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

            <form action="{{ route('categorias.store') }}" method="POST" id="formCrearCategoria">
                @csrf

                <!-- Sección: Información Básica -->
                <h5 class="fw-bold mb-4 d-flex align-items-center text-main">
                    <i class="bi bi-info-circle me-2 text-muted"></i> Información Básica
                </h5>

                <div class="row g-4 mb-5">
                    <!-- Campo: Nombre de la Categoría -->
                    <div class="col-md-6">
                        <x-input name="nombre" label="Nombre de la Colección" icon='<i class="bi bi-fonts"></i>' required="true" placeholder="Ej: Panadería Salada, Repostería, etc." autocomplete="off" />
                    </div>

                    <!-- Campo: Slug (Identificador URL) -->
                    <div class="col-md-6">
                        <x-input name="slug" label="URL (Opcional)" icon='<i class="bi bi-link-45deg"></i>' placeholder="Ej: pasteleria-fina" />
                        <div class="form-text text-muted" style="margin-top: -15px;"><i class="bi bi-lightbulb me-1"></i> Déjelo en blanco y el sistema lo generará automáticamente.</div>
                    </div>
                </div>

                <!-- Sección: Detalles Extendidos -->
                <h5 class="fw-bold mb-4 d-flex align-items-center pt-3 border-top-modern text-main">
                    <i class="bi bi-justify-left me-2 text-muted"></i> Detalles Extendidos
                </h5>

                <div class="row g-4 mb-4">
                    <!-- Campo: Descripción -->
                    <div class="col-12">
                        <x-textarea name="descripcion" label="Descripción o Notas Adicionales" icon='<i class="bi bi-card-text"></i>' rows="3" placeholder="Redacte objetivos o notas sobre qué tipo de productos van aquí (Opcional)..." />
                    </div>

                    <!-- Campo: Estado Operativo Toggle -->
                    <div class="col-12 mt-4">
                        <div class="detail-box p-3">
                            <div class="form-check form-switch d-flex align-items-center m-0 p-0">
                                <label class="form-check-label fw-semibold me-auto text-main" for="activo">
                                    <i class="bi bi-power text-muted me-2"></i>Estado Inicial (Visible)
                                </label>
                                <!-- Switch modernizado -->
                                <input class="form-check-input ms-3 mt-0" type="checkbox" role="switch" id="activo" name="activo" value="1" checked class="form-switch-lg cursor-pointer">
                            </div>
                        </div>
                        <div class="form-text mt-2 ms-1 text-muted"><i class="bi bi-info-circle me-1"></i>Si desactiva esta categoría, no aparecerá en los catálogos.</div>
                    </div>
                </div>

                <!-- Botonera de Acción -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top-modern">
                    <button type="button" class="btn btn-light-panaderia" onclick="window.history.back()">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary-panaderia" id="btnGuardar">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Guardar y Registrar
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


