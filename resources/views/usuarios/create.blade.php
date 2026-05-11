@extends('layouts.app')

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
                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Código <span class="text-danger">*</span></label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}" required>
                    </div>
                    <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Código único identificador, ej: US0002</small>
                    @error('codigo')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Nombre completo <span class="text-danger">*</span></label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    </div>
                    @error('nombre')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Correo electrónico <span class="text-danger">*</span></label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Contraseña <span class="text-danger">*</span></label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" pattern="(?=.*[A-Z])(?=.*\d).{6,}" title="Minimo 6 caracteres, una mayuscula y un numero" required>
                    </div>
                    <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Minimo 6 caracteres, una mayuscula y un numero</small>
                    @error('password')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Teléfono</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                    </div>
                </div>

                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Dirección</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
                    </div>
                </div>
                
                <div class="col-md-6 flex-column">
                    <label class="form-label fw-bold text-main">Sexo</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                        <select name="sexo" class="form-select @error('sexo') is-invalid @enderror">
                            <option value="">Seleccionar (Opcional)</option>
                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
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
                            @foreach($roles->sortBy(fn($r) => ($r->slug === 'proveedor' || strtolower($r->nombre) === 'proveedor') ? 1 : 0) as $rol)
                                @if($rol->slug !== 'cliente' && strtolower($rol->nombre) !== 'cliente')
                                    <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
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

            </div>

            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top-modern">
                <a href="{{ url('/usuarios') }}" class="btn btn-light-panaderia">Cancelar</a>
                <button type="submit" class="btn btn-gold-panaderia">
                    <i class="bi bi-save me-2"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
