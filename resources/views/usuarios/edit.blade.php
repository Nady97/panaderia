@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-pencil-square me-2 text-gold"></i>Editar Usuario
                </h2>
                <p class="mb-0 text-muted">Modifica la información del usuario</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ url('/usuarios') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
                </a>
            </div>
        </div>
    </x-card>

    <!-- Formulario de Edición -->
    <x-card>
        <form method="POST" action="{{ url('/usuarios/'.$usuario->codigo) }}">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Código</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text auth-icon"><i class="bi bi-hash"></i></span>
                        <input type="text" class="form-control text-muted" value="{{ $usuario->codigo }}" disabled>
                    </div>
                    <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>El código no se puede modificar</small>
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Nombre completo <span class="text-danger">*</span></label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $usuario->nombre) }}" required>
                    </div>
                    @error('nombre')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Correo electrónico <span class="text-danger">*</span></label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email) }}" required>
                    </div>
                    @error('email')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Teléfono</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono) }}">
                    </div>
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Dirección</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $usuario->direccion) }}">
                    </div>
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Sexo</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                        <select name="sexo" class="form-select @error('sexo') is-invalid @enderror">
                            <option value="">Seleccionar (Opcional)</option>
                            <option value="M" {{ old('sexo', $usuario->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo', $usuario->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>
                    @error('sexo')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Rol <span class="text-danger">*</span></label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                        <select name="rol_id" class="form-select @error('rol_id') is-invalid @enderror" required>
                            <option value="">Seleccionar rol</option>
                            @foreach($roles as $rol)
                                @if($rol->slug !== 'cliente' && strtolower($rol->nombre) !== 'cliente' && $rol->slug !== 'proveedor' && strtolower($rol->nombre) !== 'proveedor')
                                    <option value="{{ $rol->id }}" {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @error('rol_id')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <h5 class="fw-bold mt-4 pt-4 mb-2 col-12 text-main border-top-modern"><i class="bi bi-shield-lock me-2 text-gold"></i>Seguridad (Opcional)</h5>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Nueva contraseña</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para conservar la actual">
                    </div>
                    <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Mínimo 6 caracteres</small>
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Confirmar contraseña</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva contraseña">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top-modern">
                <a href="{{ url('/usuarios') }}" class="btn btn-light-panaderia">Cancelar</a>
                <button type="submit" class="btn btn-gold-panaderia">
                    <i class="bi bi-save me-2"></i> Actualizar Usuario
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
