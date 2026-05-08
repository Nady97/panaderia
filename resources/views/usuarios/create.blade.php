@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/usuarios/create.blade.php
    PROPÓSITO: Formulario para la creación de un nuevo Usuario.
    ARQUITECTURA: Mantenimiento de legibilidad con Componentes Blade (<x-card>,
                  <x-input>, <x-select>). Código DRY, uniforme a todo el ecosistema.
    -----------------------------------------------------------------------
--}}

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-person-plus-fill me-2 text-gold"></i>Nuevo Usuario
                </h2>
                <p class="mb-0 text-muted">Añade un nuevo usuario al sistema</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ url('/usuarios') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
                </a>
            </div>
        </div>
    </x-card>

    <!-- Formulario de Creación -->
    <x-card>
        <form method="POST" action="{{ url('/usuarios') }}">
            @csrf

            <div class="row g-4">
                <div class="col-md-6 form-group">
                    <x-input name="codigo" label="Código" required="true" icon='<i class="bi bi-hash"></i>' value="{{ old('codigo') }}" />
                    <small class="text-muted mt-1 d-block" style="margin-top: -15px !important;"><i class="bi bi-info-circle me-1"></i>Código único identificador, ej: US0002</small>
                </div>

                <div class="col-md-6 form-group">
                    <x-input name="nombre" label="Nombre completo" required="true" icon='<i class="bi bi-person"></i>' value="{{ old('nombre') }}" />
                </div>

                <div class="col-md-6 form-group">
                    <x-input type="email" name="email" label="Correo electrónico" required="true" icon='<i class="bi bi-envelope"></i>' value="{{ old('email') }}" />
                </div>

                <div class="col-md-6 form-group">
                    <x-input type="password" name="password" label="Contraseña" required="true" icon='<i class="bi bi-lock"></i>' />
                    <small class="text-muted mt-1 d-block" style="margin-top: -15px !important;"><i class="bi bi-info-circle me-1"></i>Mínimo 6 caracteres</small>
                </div>

                <div class="col-md-6 form-group">
                    <x-input name="telefono" label="Teléfono" icon='<i class="bi bi-telephone"></i>' value="{{ old('telefono') }}" />
                </div>

                <div class="col-md-6 form-group">
                    <x-input name="direccion" label="Dirección" icon='<i class="bi bi-geo-alt"></i>' value="{{ old('direccion') }}" />
                </div>
                
                <div class="col-md-6 form-group">
                    <x-select name="sexo" label="Sexo" icon='<i class="bi bi-gender-ambiguous"></i>'>
                        <option value="">Seleccionar (Opcional)</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </x-select>
                </div>

                <div class="col-md-6 form-group">
                    <x-select name="rol_id" label="Rol" required="true" icon='<i class="bi bi-shield-lock"></i>'>
                        <option value="">Seleccionar rol</option>
                        @foreach($roles->sortBy(fn($r) => ($r->slug === 'proveedor' || strtolower($r->nombre) === 'proveedor') ? 1 : 0) as $rol)
                            @if($rol->slug !== 'cliente' && strtolower($rol->nombre) !== 'cliente')
                                <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endif
                        @endforeach
                    </x-select>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top-modern" style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                <a href="{{ url('/usuarios') }}" class="btn btn-light-panaderia">Cancelar</a>
                <button type="submit" class="btn btn-gold-panaderia" style="border-radius: 10px; padding: 0.6rem 1.5rem; background: var(--gold-light); color: #fff; border: 1px solid var(--gold-dark);">
                    <i class="bi bi-save me-2"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
