@extends('layouts.app')

@section('content')
<div class="space-y-5 bg-[var(--bg-primary)]">

    {{-- ENCABEZADO --}}
    <x-card>
        <div class="flex justify-between items-center flex-wrap gap-3 p-4">
            <div>
                <h2 class="text-xl font-extrabold mb-1 flex items-center gap-2 text-[var(--text-primary)]">
                    <i class="bi bi-person-circle text-[var(--gold-dark)]"></i>Mi Perfil
                </h2>
                <p class="text-sm text-[var(--text-muted)]">Gestiona tu información personal y configuración de cuenta</p>
            </div>
            <a href="{{ url('/dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-[var(--bg-card)] text-[var(--text-secondary)] border border-[var(--border-color)] hover:bg-[var(--bg-input)] hover:border-[var(--gold-dark)]">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </x-card>

    {{-- MENSAJES --}}
    @if(session('success'))
        <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
    @endif
    @if($errors->any())
        <x-alert type="error" class="mb-4">{{ $errors->first() }}</x-alert>
    @endif

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- COLUMNA IZQUIERDA: INFO DE CONTACTO --}}
        <div class="lg:col-span-5">
            <x-card>
                <div class="p-4">
                    {{-- Avatar y Nombre --}}
                    <div class="text-center mb-5 pb-5 border-b border-[var(--border-color)]">
                        <div class="w-36 h-36 rounded-full overflow-hidden mx-auto mb-4" 
                             class="border-4 border-[var(--border-color)] shadow-[var(--shadow-md)]">
                            @if($usuario->imagen)
                                <img src="{{ asset('storage/' . $usuario->imagen) }}" alt="{{ $usuario->nombre }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl font-extrabold bg-[var(--bg-input)] text-[var(--text-muted)]">
                                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-extrabold mb-2 text-[var(--text-primary)]">{{ $usuario->nombre }}</h3>
                        <x-badge type="warning" class="rounded-full px-4 py-1.5 text-sm font-semibold inline-flex items-center gap-1">
                            <i class="bi bi-shield-check"></i> {{ $rolNombre ?? 'Administrador' }}
                        </x-badge>
                    </div>

                    <h4 class="text-lg font-bold mb-4 flex items-center gap-2 text-[var(--text-primary)]">
                        <i class="bi bi-person-lines-fill text-[var(--gold-dark)]"></i> Detalles de Contacto
                    </h4>
                    
                    <div class="space-y-2">
                        {{-- Email --}}
                        <div class="p-3.5 rounded-xl transition-all duration-200 hover:bg-[rgba(210,150,75,0.04)]">
                            <p class="text-xs uppercase tracking-wider font-semibold mb-1 text-[var(--text-muted)]">Correo electrónico</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium flex items-center gap-2 text-[var(--text-primary)]">
                                    <i class="bi bi-envelope text-[var(--text-muted)]"></i>{{ $usuario->email }}
                                </span>
                                <i class="bi bi-check-circle-fill text-green-600" title="Verificado"></i>
                            </div>
                        </div>

                        {{-- Teléfono --}}
                        <div class="p-3.5 rounded-xl transition-all duration-200 hover:bg-[rgba(210,150,75,0.04)]">
                            <p class="text-xs uppercase tracking-wider font-semibold mb-1 text-[var(--text-muted)]">Teléfono</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium flex items-center gap-2 text-[var(--text-primary)]">
                                    <i class="bi bi-telephone text-[var(--text-muted)]"></i>{{ $usuario->telefono ?? 'No registrado' }}
                                </span>
                                @if($usuario->telefono)
                                    <i class="bi bi-check-circle text-green-600"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="p-3.5 rounded-xl transition-all duration-200 hover:bg-[rgba(210,150,75,0.04)]">
                            <p class="text-xs uppercase tracking-wider font-semibold mb-1 text-[var(--text-muted)]">Dirección</p>
                            <p class="text-sm font-medium flex items-center gap-2 text-[var(--text-primary)]">
                                <i class="bi bi-geo-alt text-[var(--text-muted)]"></i>{{ $usuario->direccion ?? 'No registrada' }}
                            </p>
                        </div>

                        {{-- Miembro desde + Sexo --}}
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-3.5 rounded-xl transition-all duration-200 hover:bg-[rgba(210,150,75,0.04)]">
                                <p class="text-xs uppercase tracking-wider font-semibold mb-1 text-[var(--text-muted)]">Miembro desde</p>
                                <p class="text-sm font-medium flex items-center gap-2 text-[var(--text-primary)]">
                                    <i class="bi bi-calendar-check text-[var(--text-muted)]"></i>
                                    {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : date('d/m/Y') }}
                                </p>
                            </div>
                            <div class="p-3.5 rounded-xl transition-all duration-200 hover:bg-[rgba(210,150,75,0.04)]">
                                <p class="text-xs uppercase tracking-wider font-semibold mb-1 text-[var(--text-muted)]">Sexo</p>
                                <p class="text-sm font-medium flex items-center gap-2 text-[var(--text-primary)]">
                                    @if($usuario->sexo == 'M')
                                        <i class="bi bi-gender-male text-[var(--text-muted)]"></i>Masculino
                                    @elseif($usuario->sexo == 'F')
                                        <i class="bi bi-gender-female text-[var(--text-muted)]"></i>Femenino
                                    @else
                                        <i class="bi bi-gender-ambiguous text-[var(--text-muted)]"></i>N/E
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- COLUMNA DERECHA: FORMULARIOS --}}
        <div class="lg:col-span-7 space-y-5">
            
            {{-- EDITAR PERFIL --}}
            <x-card>
                <div class="p-4">
                    <h4 class="text-lg font-bold mb-5 pb-3 flex items-center gap-2 text-[var(--text-primary)] border-b border-[var(--border-color)]">
                        <i class="bi bi-pencil-square text-[var(--gold-dark)]"></i> Editar Perfil
                    </h4>
                    <form method="POST" action="{{ url('/perfil/update') }}" id="perfilForm" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            {{-- Nombre --}}
                            <div class="md:col-span-12">
                                <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">
                                    Nombre completo <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required
                                       class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                            </div>

                            {{-- Email --}}
                            <div class="md:col-span-6">
                                <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">
                                    Correo electrónico <span class="text-red-600">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required
                                       class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                                <p class="text-xs mt-1 text-[var(--text-muted)]">Usado para iniciar sesión.</p>
                            </div>

                            {{-- Teléfono --}}
                            <div class="md:col-span-6">
                                    <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">Teléfono</label>
                                <input type="tel" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" placeholder="Ej: +591 68824368"
                                        class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                            </div>

                            {{-- Dirección --}}
                            <div class="md:col-span-8">
                                    <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">Dirección</label>
                                <input type="text" name="direccion" value="{{ old('direccion', $usuario->direccion) }}" placeholder="Calle, número, ciudad"
                                        class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                            </div>

                            {{-- Sexo --}}
                            <div class="md:col-span-4">
                                <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">Sexo</label>
                                <select name="sexo"
                                    class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                                    <option value="">Seleccionar</option>
                                    <option value="M" {{ old('sexo', $usuario->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo', $usuario->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>

                            {{-- Imagen --}}
                            <div class="md:col-span-12">
                                    <label class="block text-sm font-semibold mb-1.5 flex items-center gap-1 text-[var(--text-secondary)]">
                                    <i class="bi bi-camera"></i> Imagen de perfil
                                </label>
                                <input type="file" name="imagen" accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 file:mr-3 file:rounded-lg file:border-0 file:bg-[var(--bg-card)] file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-[var(--text-secondary)]">
                                    <p class="text-xs mt-1 text-[var(--text-muted)]">JPG, PNG o WEBP. Máximo 2 MB.</p>
                            </div>
                        </div>
                        
                        {{-- Botones --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-[var(--border-color)]">
                            <button type="button" onclick="document.getElementById('perfilForm').reset()"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border border-[var(--border-color)] text-[var(--text-secondary)] hover:bg-[var(--bg-input)] hover:border-[var(--gold-dark)]">
                                <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 hover:-translate-y-0.5 bg-[var(--btn-bg)] text-[var(--btn-text)] hover:bg-[var(--btn-hover)] hover:shadow-[0_8px_25px_rgba(129,87,45,0.35)]">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>

            {{-- CAMBIAR CONTRASEÑA --}}
            <x-card>
                <div class="p-4">
                    <div class="flex justify-between items-center cursor-pointer" onclick="toggleCollapse('collapsePassword', 'iconPassword')">
                        <h4 class="text-lg font-bold flex items-center gap-2 text-[var(--text-primary)]">
                            <i class="bi bi-shield-lock text-[var(--gold-dark)]"></i> Cambiar Contraseña
                        </h4>
                        <div class="flex items-center gap-3">
                            <x-badge type="warning" class="text-xs px-3 py-1 rounded-full font-semibold">Recomendado</x-badge>
                            <i class="bi bi-chevron-down text-lg transition-transform duration-300 text-[var(--text-muted)]" id="iconPassword"></i>
                        </div>
                    </div>

                    <div id="collapsePassword" class="mt-4 pt-4 border-t border-[var(--border-color)] {{ $errors->has('password') || $errors->has('current_password') ? '' : 'hidden' }}">
                        <form method="POST" action="{{ url('/perfil/password') }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-12">
                                    <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">
                                        Contraseña actual <span class="text-red-600">*</span>
                                    </label>
                                    <input type="password" name="current_password" required placeholder="••••••••"
                                           class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                                </div>
                                <div class="md:col-span-6">
                                    <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">
                                        Nueva contraseña <span class="text-red-600">*</span>
                                    </label>
                                    <input type="password" name="password" required placeholder="••••••••"
                                           class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                                    <p class="text-xs mt-1 text-[var(--text-muted)]">Mínimo 6 caracteres.</p>
                                </div>
                                <div class="md:col-span-6">
                                    <label class="block text-sm font-semibold mb-1.5 text-[var(--text-secondary)]">
                                        Confirmar contraseña <span class="text-red-600">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                                           class="w-full rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--border-color-focus)] focus:ring-2 focus:ring-[rgba(242,166,69,0.1)]">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 hover:-translate-y-0.5 w-full md:w-auto bg-[var(--btn-bg)] text-[var(--btn-text)] hover:bg-[var(--btn-hover)]">
                                    <i class="bi bi-key"></i> Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-card>

            {{-- ZONA DE PELIGRO --}}
            <x-card>
                <div class="p-4 border border-[rgba(212,107,94,0.3)] rounded-xl">
                    <div class="flex justify-between items-center cursor-pointer" onclick="toggleCollapse('collapseDanger', 'iconDanger')">
                        <h4 class="text-lg font-bold flex items-center gap-2 text-[var(--danger)]">
                            <i class="bi bi-exclamation-triangle"></i> Zona de Peligro
                        </h4>
                        <i class="bi bi-chevron-down text-lg transition-transform duration-300 text-[var(--danger)]" id="iconDanger"></i>
                    </div>

                    <div id="collapseDanger" class="mt-4 pt-4 border-t border-[rgba(212,107,94,0.15)] {{ $errors->has('deletion_password') ? '' : 'hidden' }}">
                        <div class="p-4 rounded-xl mb-4 flex items-start gap-3 bg-[rgba(212,107,94,0.06)] border-l-4 border-[var(--danger)]">
                            <i class="bi bi-exclamation-triangle-fill text-xl flex-shrink-0 text-[var(--danger)]"></i>
                            <p class="text-sm font-medium text-[var(--danger)]">
                                <strong>Esta acción no se puede deshacer.</strong> Eliminará permanentemente tu cuenta y todos los datos asociados.
                            </p>
                        </div>

                        <form action="{{ url('/perfil/delete') }}" method="POST" 
                              onsubmit="return confirm('¿Estás SEGURO de eliminar TU PROPIA cuenta? Perderás acceso y podrías romper registros históricos.')" 
                              class="space-y-4">
                            @csrf
                            @method('DELETE')
                            <div>
                                <label class="block text-sm font-semibold mb-1.5 text-[var(--danger)]">
                                    Confirma tu contraseña para continuar:
                                </label>
                                <input type="password" name="password" required placeholder="Tu contraseña actual"
                                       class="w-full rounded-xl border border-[rgba(212,107,94,0.4)] bg-[var(--bg-input)] py-3 px-4 text-sm text-[var(--text-primary)] outline-none transition-all duration-300 focus:border-[var(--danger)] focus:ring-2 focus:ring-[rgba(212,107,94,0.2)]">
                            </div>
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:-translate-y-0.5 bg-[var(--danger)] text-white hover:shadow-[0_8px_25px_rgba(212,107,94,0.4)]">
                                <i class="bi bi-trash"></i> Eliminar mi Cuenta Permanentemente
                            </button>
                        </form>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>

<script>
    function toggleCollapse(collapseId, iconId) {
        const el = document.getElementById(collapseId);
        const icon = document.getElementById(iconId);
        const isHidden = el.classList.contains('hidden');
        
        if (isHidden) {
            el.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            el.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
    
    // Inicializar íconos
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('collapsePassword').classList.contains('hidden')) {
            document.getElementById('iconPassword').style.transform = 'rotate(180deg)';
        }
        if (!document.getElementById('collapseDanger').classList.contains('hidden')) {
            document.getElementById('iconDanger').style.transform = 'rotate(180deg)';
        }
    });
</script>
@endsection

@push('scripts')
    @vite(['resources/js/perfil.js'])
@endpush