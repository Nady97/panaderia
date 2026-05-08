@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/proveedores/create.blade.php
    PROPÓSITO: Formulario para añadir nuevos Proveedores.
    ARQUITECTURA: Migrado a componentes Blade (<x-card>, <x-input>, <x-select>) 
                  para legibilidad máxima y escalabilidad.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main"><i class="bi bi-plus-circle me-2 text-gold"></i> Nuevo Proveedor</h2>
                <p class="mb-0 text-muted">Agrega un nuevo contacto o empresa al sistema</p>
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

    <!-- Formulario de Creación -->
    <x-card>
        <div class="card-body p-4">
            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-6 form-group">
                        <x-input name="codigo" label="Código Identificador" required="true" maxlength="10" icon='<i class="bi bi-hash text-muted"></i>' placeholder="Ej: PROV-001" />
                        <small class="form-text mt-1 text-muted" style="display: block; margin-top: -15px;">Máximo 10 caracteres, debe ser único</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="empresa" label="Nombre de la Empresa" required="true" maxlength="60" icon='<i class="bi bi-building text-muted"></i>' placeholder="Ej: Harinas Los Andes S.A." />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="nombre_contacto" label="Nombre del Contacto" required="true" maxlength="60" icon='<i class="bi bi-person text-muted"></i>' placeholder="Ej: Juan Pérez" />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="nit" label="NIT / RUC" maxlength="20" icon='<i class="bi bi-card-text text-muted"></i>' placeholder="Ej: 1234567890" />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input name="telefono" label="Teléfono" required="true" maxlength="15" icon='<i class="bi bi-telephone text-muted"></i>' placeholder="Ej: +591 60000000" />
                    </div>

                    <div class="col-md-6 form-group">
                        <x-input type="email" name="email" label="Correo Electrónico" maxlength="100" icon='<i class="bi bi-envelope text-muted"></i>' placeholder="Ej: ventas@empresa.com" />
                    </div>

                    <div class="col-md-8 form-group">
                        <x-input name="direccion" label="Dirección" maxlength="255" icon='<i class="bi bi-geo-alt text-muted"></i>' placeholder="Av. Principal #123, Ciudad" />
                    </div>

                    <div class="col-md-4 form-group">
                        <x-select name="estado" label="Estado" required="true" icon='<i class="bi bi-circle-square text-muted"></i>'>
                            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="suspendido" {{ old('estado') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </x-select>
                    </div>
                </div>

                <hr class="my-4">
                
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="reset" class="btn btn-light-panaderia text-nowrap">
                        <i class="bi bi-x-circle me-1"></i> Limpiar Campos
                    </button>
                    <button type="submit" class="btn btn-gold-panaderia" style="border-radius: 10px; padding: 0.6rem 1.5rem; background: var(--gold-light); color: #fff; border: 1px solid var(--gold-dark);">
                        <i class="bi bi-save me-1"></i> Guardar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection


