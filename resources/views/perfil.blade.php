@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4 border-0 shadow-sm rounded-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 border-bottom-custom border-border-color">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-person-circle me-2 text-gold"></i>Mi Perfil
                </h2>
                <p class="mb-0 text-muted">Gestiona tu información personal y configuración de cuenta</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ url('/dashboard') }}" class="btn btn-light-panaderia text-nowrap shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </x-card>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <x-alert type="success" class="mb-4 shadow-sm rounded-3">{{ session('success') }}</x-alert>
    @endif

    @if($errors->any())
        <x-alert type="error" class="mb-4 shadow-sm rounded-3">{{ $errors->first() }}</x-alert>
    @endif

    <div class="row g-4">
        <!-- Columna Izquierda: Información de Contacto -->
        <div class="col-lg-5 mb-4 mb-md-0">
            <x-card class="h-100 border-0 shadow-sm rounded-4">
                <!-- Avatar y Nombre -->
                <div class="text-center mb-4 pb-4 border-bottom border-border-color">
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle mb-3 shadow-sm border border-3 border-light overflow-hidden"
                         style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--gold-dark) 0%, var(--primary-brown) 100%); color: white; font-size: 3rem; font-weight: 700;">
                        @if($usuario->imagen)
                            <img src="{{ asset('storage/' . $usuario->imagen) }}" alt="{{ $usuario->nombre }}" class="w-100 h-100" style="object-fit: contain; background: var(--bg-card); padding: 8px;">
                        @else
                            {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1 text-main">{{ $usuario->nombre }}</h4>
                    <x-badge type="warning" class="rounded-pill shadow-sm px-3 py-2 mt-2">
                        <i class="bi bi-shield-check me-1"></i> {{ $rolNombre ?? 'Administrador' }}
                    </x-badge>
                </div>

                <div class="card-body p-4 pt-0">
                    <h5 class="fw-bold mb-4 text-main">
                        <i class="bi bi-person-lines-fill me-2 text-gold"></i> Detalles de Contacto
                    </h5>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="detail-box p-3 rounded bg-light border border-border-color">
                            <div class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Correo electrónico</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-medium text-main"><i class="bi bi-envelope me-2 text-muted"></i>{{ $usuario->email }}</span>
                                <i class="bi bi-check-circle-fill text-success fs-5" title="Verificado"></i>
                            </div>
                        </div>

                        <div class="detail-box p-3 rounded bg-light border border-border-color">
                            <div class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Teléfono</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-medium text-main">
                                    <i class="bi bi-telephone me-2 text-muted"></i>{{ $usuario->telefono ?? 'No registrado' }}
                                </span>
                                @if($usuario->telefono)
                                    <i class="bi bi-check-circle text-success fs-5" title="Confirmado"></i>
                                @endif
                            </div>
                        </div>

                        <div class="detail-box p-3 rounded bg-light border border-border-color">
                            <div class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Dirección</div>
                            <div class="fw-medium text-main">
                                <i class="bi bi-geo-alt me-2 text-muted"></i>{{ $usuario->direccion ?? 'No registrada' }}
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="detail-box p-3 rounded h-100 bg-light border border-border-color">
                                    <div class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Miembro desde</div>
                                    <div class="fw-medium text-main">
                                        <i class="bi bi-calendar-check me-2 text-muted"></i>{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : date('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="detail-box p-3 rounded h-100 bg-light border border-border-color">
                                    <div class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Sexo</div>
                                    <div class="fw-medium text-main">
                                        @if($usuario->sexo == 'M')
                                            <i class="bi bi-gender-male me-2 text-muted"></i>Masculino
                                        @elseif($usuario->sexo == 'F')
                                            <i class="bi bi-gender-female me-2 text-muted"></i>Femenino
                                        @else
                                            <i class="bi bi-gender-ambiguous me-2 text-muted"></i>N/E
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Columna Derecha: Formularios -->
        <div class="col-lg-7">
            <!-- Editar Perfil -->
            <x-card class="mb-4 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-4 pb-3 text-main border-bottom border-border-color">
                        <i class="bi bi-pencil-square me-2 text-gold"></i> Editar Perfil
                    </h5>
                    <form method="POST" action="{{ url('/perfil/update') }}" id="perfilForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-12 form-group">
                                <label class="form-label fw-medium text-main">
                                    Nombre completo <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label class="form-label fw-medium text-main">
                                    Correo electrónico <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                                </div>
                                <div class="form-text mt-1 small text-muted"><i class="bi bi-info-circle me-1"></i>Usado para iniciar sesión.</div>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label class="form-label fw-medium text-main">
                                    Teléfono
                                </label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" name="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono) }}" placeholder="Ej: +591 68824368">
                                </div>
                            </div>
                            
                            <div class="col-md-8 form-group">
                                <label class="form-label fw-medium text-main">
                                    Dirección
                                </label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $usuario->direccion) }}" placeholder="Calle, número, ciudad">
                                </div>
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label class="form-label fw-medium text-main">
                                    Sexo
                                </label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light"><i class="bi bi-gender-ambiguous"></i></span>
                                    <select name="sexo" class="form-select">
                                        <option value="">Seleccionar</option>
                                        <option value="M" {{ old('sexo', $usuario->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo', $usuario->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                <label class="form-label fw-medium text-main">
                                    Imagen de perfil
                                </label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light"><i class="bi bi-image"></i></span>
                                    <input type="file" name="imagen" class="form-control" accept="image/*">
                                </div>
                                <div class="form-text mt-1 small text-muted"><i class="bi bi-image me-1"></i> JPG, PNG o WEBP. Máximo 2 MB.</div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top border-border-color">
                            <button type="button" class="btn btn-light-panaderia px-4" onclick="document.getElementById('perfilForm').reset()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer
                            </button>
                            <button type="submit" class="btn btn-gold-panaderia px-4" style="border-radius: 10px; background: var(--gold-light); color: #fff; border: 1px solid var(--gold-dark);">
                                <i class="bi bi-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>

            <!-- Cambiar Contraseña (Desplegable) -->
            <x-card class="mb-4 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div data-bs-toggle="collapse" data-bs-target="#collapsePassword" aria-expanded="{{ $errors->has('password') || $errors->has('current_password') ? 'true' : 'false' }}" aria-controls="collapsePassword" style="cursor: pointer;" class="user-select-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-main">
                                <i class="bi bi-shield-lock me-2 text-gold"></i> Cambiar Contraseña
                            </h5>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-3 py-2 px-3 rounded-pill shadow-sm">Recomendado</span>
                                <i class="bi bi-chevron-down text-muted fs-5" style="transition: transform 0.3s;" id="iconPassword"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div id="collapsePassword" class="collapse {{ $errors->has('password') || $errors->has('current_password') ? 'show' : '' }} mt-4 pt-4 border-top border-border-color">
                        <form method="POST" action="{{ url('/perfil/password') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-12 form-group">
                                    <label class="form-label fw-medium text-main">
                                        Contraseña actual <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-modern">
                                        <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                                    </div>
                                    <div class="form-text mt-1 small text-muted"><i class="bi bi-info-circle me-1"></i>Requerida para autorizar el cambio.</div>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label class="form-label fw-medium text-main">
                                        Nueva contraseña <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-modern">
                                        <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                                        <input type="password" name="password" id="newPassword" class="form-control" placeholder="••••••••" required>
                                    </div>
                                    <div class="form-text mt-1 small text-muted"><i class="bi bi-info-circle me-1"></i>Mínimo 6 caracteres.</div>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label class="form-label fw-medium text-main">
                                        Confirmar contraseña <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-modern">
                                        <span class="input-group-text bg-light"><i class="bi bi-check-all"></i></span>
                                        <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" placeholder="••••••••" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-5 pt-4 border-top border-border-color">
                                <button type="submit" class="btn btn-warning text-dark px-4" style="border-radius: 10px; font-weight: 500;">
                                    <i class="bi bi-shield-check me-1"></i> Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-card>

            <!-- Zona de Peligro (Desplegable) -->
            <x-card class="mb-4 border-0 shadow-sm rounded-4" style="border: 1px solid rgba(220, 53, 69, 0.3) !important;">
                <div class="card-body p-4 p-md-5">
                    <div data-bs-toggle="collapse" data-bs-target="#collapseDanger" aria-expanded="{{ $errors->has('deletion_password') ? 'true' : 'false' }}" aria-controls="collapseDanger" style="cursor: pointer;" class="user-select-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i> Zona de Peligro
                            </h5>
                            <i class="bi bi-chevron-down text-danger fs-5" style="transition: transform 0.3s;" id="iconDanger"></i>
                        </div>
                    </div>
                    
                    <div id="collapseDanger" class="collapse {{ $errors->has('deletion_password') ? 'show' : '' }} mt-4 pt-4" style="border-top: 1px solid rgba(220, 53, 69, 0.1);">
                        <div class="p-4 mb-4 rounded-3" style="background: rgba(220, 53, 69, 0.05); border-left: 4px solid #dc3545;">
                            <p class="mb-0 text-danger" style="font-size: 0.95rem;">
                                <strong>⚠️ Esta acción no se puede deshacer.</strong> Eliminará permanentemente tu cuenta y todos los datos asociados.
                            </p>
                        </div>
                        
                        @if($errors->has('deletion_password'))
                            <div class="alert alert-danger py-2 px-3 mb-4 rounded-3 shadow-sm" style="font-size: 0.85rem;">
                                {{ $errors->first('deletion_password') }}
                            </div>
                        @endif

                        <form action="{{ url('/perfil/delete') }}" method="POST" onsubmit="return confirm('¿Estás SEGURO de eliminar TU PROPIA cuenta? Perderás acceso y podrías romper registros históricos.')">
                            @csrf
                            @method('DELETE')
                            <div class="mb-4">
                                <label class="form-label text-danger fw-medium mb-2">
                                    Confirma tu contraseña para continuar:
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-danger bg-danger bg-opacity-10 text-danger"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control border-danger" placeholder="Tu contraseña actual" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-danger px-4" style="border-radius: 10px;">
                                    <i class="bi bi-trash me-1"></i> Eliminar Cuenta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>

<script>
    // Rotar iconos en colapsables
    document.addEventListener('DOMContentLoaded', function() {
        ['Password', 'Danger'].forEach(function(item) {
            const collapseElement = document.getElementById('collapse' + item);
            const iconElement = document.getElementById('icon' + item);
            
            if (collapseElement && iconElement) {
                // Rotar inicialmente si está abierto
                if (collapseElement.classList.contains('show')) {
                    iconElement.style.transform = 'rotate(180deg)';
                }
                
                collapseElement.addEventListener('show.bs.collapse', function () {
                    iconElement.style.transform = 'rotate(180deg)';
                });
                
                collapseElement.addEventListener('hide.bs.collapse', function () {
                    iconElement.style.transform = 'rotate(0deg)';
                });
            }
        });
    });
</script>
@endsection

@push('scripts')
    @vite(['resources/js/perfil.js'])
@endpush
