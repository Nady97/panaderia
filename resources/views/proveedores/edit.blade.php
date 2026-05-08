@extends('layouts.app')

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
                        <label class="form-label fw-bold mb-2" class="text-main">Código Identificador</label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-hash text-muted"></i></span>
                            <input type="text" class="form-control fw-bold" value="{{ $proveedor->codigo }}" disabled>
                        </div>
                        <small class="form-text mt-1">El código del proveedor no puede modificarse.</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label fw-bold mb-2" class="text-main">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-building text-muted"></i></span>
                            <input type="text" name="empresa" class="form-control" value="{{ old('empresa', $proveedor->empresa) }}" required maxlength="60">
                        </div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label fw-bold mb-2" class="text-main">Nombre del Contacto <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="nombre_contacto" class="form-control" value="{{ old('nombre_contacto', $proveedor->nombre_contacto) }}" required maxlength="60">
                        </div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label fw-bold mb-2" class="text-main">NIT / RUC</label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-card-text text-muted"></i></span>
                            <input type="text" name="nit" class="form-control" value="{{ old('nit', $proveedor->nit) }}" maxlength="20">
                        </div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label fw-bold mb-2" class="text-main">Teléfono <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-telephone text-muted"></i></span>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono) }}" required maxlength="15">
                        </div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label fw-bold mb-2" class="text-main">Correo Electrónico</label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $proveedor->email) }}" maxlength="100">
                        </div>
                    </div>

                    <div class="col-md-8 form-group">
                        <label class="form-label fw-bold mb-2" class="text-main">Dirección</label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-geo-alt text-muted"></i></span>
                            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion) }}">
                        </div>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label fw-bold mb-2" class="text-main">Estado <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-circle-square text-muted"></i></span>
                            <select name="estado" class="form-select" required>
                                <option value="activo" {{ old('estado', $proveedor->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="suspendido" {{ old('estado', $proveedor->estado) == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                                <option value="inactivo" {{ old('estado', $proveedor->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr my-4>
                
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-light-panaderia text-nowrap">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-gold-panaderia">
                        <i class="bi bi-arrow-repeat me-1"></i> Actualizar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>


@endsection
