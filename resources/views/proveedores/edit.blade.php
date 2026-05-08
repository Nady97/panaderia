@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/proveedores/edit.blade.php
    PROPÓSITO: Formulario para la edición de Proveedores.
    ARQUITECTURA: Mantenimiento de legibilidad con Componentes Blade (<x-card>,
                  <x-input>, <x-select>). Código DRY, uniforme a todo el ecosistema.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main"><i class="bi bi-pencil-square me-2 text-gold"></i> Editar Proveedor</h2>
                <p class="mb-0 text-muted">Actualiza los datos del proveedor: <strong>{{ $proveedor->empresa }}</strong></p>
            </div>
            <a href="{{ route('proveedores.index') }}" class="btn btn-light-panaderia text-nowrap">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
    </x-card>

    @if($errors->any())
        <x-alert type="danger" class="mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <!-- Formulario de Edición -->
    <x-card>
        <div class="card-body p-4">
            <form action="{{ route('proveedores.update', $proveedor->codigo) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-6 form-group">
                        <x-input name="codigo_display" label="Código Identificador" disabled="true" icon='<i class="bi bi-hash text-muted"></i>' value="{{ $proveedor->codigo }}" />
                        <small class="form-text mt-1 text-muted" style="display: block; margin-top: -15px;">El código del proveedor no puede modificarse.</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="empresa" label="Nombre de la Empresa" required="true" maxlength="60" icon='<i class="bi bi-building text-muted"></i>' value="{{ old('empresa', $proveedor->empresa) }}" />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="nombre_contacto" label="Nombre del Contacto" required="true" maxlength="60" icon='<i class="bi bi-person text-muted"></i>' value="{{ old('nombre_contacto', $proveedor->nombre_contacto) }}" />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="nit" label="NIT / RUC" maxlength="20" icon='<i class="bi bi-card-text text-muted"></i>' value="{{ old('nit', $proveedor->nit) }}" />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="telefono" label="Teléfono" required="true" maxlength="15" icon='<i class="bi bi-telephone text-muted"></i>' value="{{ old('telefono', $proveedor->telefono) }}" />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input type="email" name="email" label="Correo Electrónico" maxlength="100" icon='<i class="bi bi-envelope text-muted"></i>' value="{{ old('email', $proveedor->email) }}" />
                    </div>

                    <div class="col-md-8 form-group">
                        <x-input name="direccion" label="Dirección" maxlength="255" icon='<i class="bi bi-geo-alt text-muted"></i>' value="{{ old('direccion', $proveedor->direccion) }}" />
                    </div>

                    <div class="col-md-4 form-group">
                        <x-select name="estado" label="Estado" required="true" icon='<i class="bi bi-circle-square text-muted"></i>'>
                            <option value="activo" {{ old('estado', $proveedor->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="suspendido" {{ old('estado', $proveedor->estado) == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                            <option value="inactivo" {{ old('estado', $proveedor->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </x-select>
                    </div>
                </div>

                <hr class="my-4">
                
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-light-panaderia text-nowrap">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-gold-panaderia" style="border-radius: 10px; padding: 0.6rem 1.5rem; background: var(--gold-light); color: #fff; border: 1px solid var(--gold-dark);">
                        <i class="bi bi-arrow-repeat me-1"></i> Actualizar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection
