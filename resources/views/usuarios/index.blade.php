@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-people-fill me-2 text-gold"></i>Gestión de Usuarios
                </h2>
                <p class="mb-0 text-muted">Administra los accesos y personal del sistema</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                @can('roles.view')
                    <a href="{{ route('roles.index') }}" class="btn btn-light-panaderia text-nowrap">
                        <i class="bi bi-shield-check me-1"></i> Roles y Permisos
                    </a>
                @endcan
                @can('usuarios.create')
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary-panaderia text-nowrap">
                        <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
                    </a>
                @endcan
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de resumen de Roles -->
    <div class="row g-2 mb-2">
        @foreach($roles as $rol)
        @if($rol->slug !== 'cliente' && strtolower($rol->nombre) !== 'cliente')
        <div class="col-6 col-md-3">
            @if($rol->slug === 'proveedor' || $rol->nombre === 'Proveedor')
            <a href="{{ url('/proveedores') }}" class="text-decoration-none">
            @endif
            <x-card class="h-100 bg-transparent-hover kpi-card {{ ($rol->slug === 'proveedor' || $rol->nombre === 'Proveedor') ? 'border-primary' : '' }}">
                <div class="d-flex align-items-center gap-2 gap-md-3 w-100 p-1">
                    <div class="p-2 p-md-3 rounded-circle kpi-icon-wrapper flex-shrink-0" style="color: var(--gold-dark);">
                        {!! $rol->icono ?? '<i class="bi bi-person kpi-icon"></i>' !!}
                    </div>
                    <div class="overflow-hidden">
                        <h3 class="mb-0 fw-bold text-main" style="font-size: clamp(1rem, 4vw, 1.75rem); line-height: 1.2;">
                            {{ $rol->usuarios_count ?? $totalPorRol[$rol->slug] ?? 0 }}
                        </h3>
                        <span class="text-muted small text-uppercase fw-semibold d-block text-truncate" style="font-size: clamp(0.6rem, 2.5vw, 0.75rem);">
                            {{ $rol->nombre }}
                        </span>
                    </div>
                </div>
            </x-card>
            @if($rol->slug === 'proveedor' || $rol->nombre === 'Proveedor')
            </a>
            @endif
        </div>
        @endif
        @endforeach
    </div>

    <!-- Buscador Integrado y Filtros Server-Side -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('usuarios.index') }}" method="GET" class="row g-3 align-items-end" id="serverFilterForm">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-2 text-main">
                        <i class="bi bi-search me-1"></i> Buscar Usuario
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, email o código..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2 text-main">Filtrar por Rol</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select name="rol_id" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('rol_id') == 'all' ? 'selected' : '' }}>Todos los roles</option>
                            @foreach($roles as $rol)
                                @if($rol->slug !== 'cliente' && strtolower($rol->nombre) !== 'cliente' && $rol->slug !== 'proveedor' && strtolower($rol->nombre) !== 'proveedor')
                                    <option value="{{ $rol->id }}" {{ request('rol_id') == $rol->id ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="submit" class="btn btn-gold-panaderia w-100"><i class="bi bi-search"></i></button>
                    @if(request()->has('search') || request()->has('rol_id'))
                        <a href="{{ route('usuarios.index') }}" class="btn btn-light-panaderia w-100" title="Limpiar"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>
        
    <!-- Tabla Principal de Usuarios Paginada -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <th class="py-3 px-2 text-muted text-uppercase fw-semibold small">Usuario</th>
                            <th class="py-3 px-2 d-none d-md-table-cell text-muted text-uppercase fw-semibold small">Contacto</th>
                            <th class="py-3 px-2 text-muted text-uppercase fw-semibold small">Estado</th>
                            <th class="py-3 px-2 text-muted text-uppercase fw-semibold small">Rol</th>
                            <th class="py-3 px-2 text-end text-muted text-uppercase fw-semibold small">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        @php
                            $nombreRol = $usuario->rol ? $usuario->rol->nombre : 'Sin rol';
                            $rolIcono = $usuario->rol->icono ?? match($nombreRol) {
                                'Administrador' => '<i class="bi bi-shield-lock"></i>',
                                'Cajero' => '<i class="bi bi-cash-coin"></i>',
                                'Cocinero / Panadero' => '<i class="bi bi-egg-fried"></i>',
                                'Proveedor' => '<i class="bi bi-truck"></i>',
                                default => '<i class="bi bi-question-circle"></i>'
                            };
                            
                            $rolColorClass = match($nombreRol) {
                                'Administrador' => 'danger',
                                'Cajero' => 'success',
                                'Cocinero / Panadero' => 'warning',
                                'Proveedor' => 'info',
                                default => 'secondary'
                            };
                            
                            $esUsuarioActual = auth()->check() && auth()->user()->codigo === $usuario->codigo;
                            $estaActivo = $esUsuarioActual || ($usuario->last_login_at && (!$usuario->last_logout_at || $usuario->last_login_at->gt($usuario->last_logout_at)));
                        @endphp
                        <tr class="border-bottom-modern" style="transition: background 0.2s;">
                            {{-- Usuario --}}
                            <td class="py-3 px-2">
                                <div class="d-flex align-items-center gap-2 gap-md-3">
                                    <div class="p-2 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                         style="width: 36px; height: 36px; background: var(--bg-primary); color: var(--gold-dark); border: 1px solid var(--border-color);">
                                        <span style="font-size: 0.9rem; font-weight: bold;">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</span>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="fw-bold text-main text-truncate" style="font-size: clamp(0.85rem, 3vw, 1rem);">
                                            {{ $usuario->nombre }}
                                        </div>
                                        <small class="d-md-none text-muted text-truncate d-block">{{ $usuario->email }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Contacto (oculto en móvil) --}}
                            <td class="py-3 px-2 d-none d-md-table-cell">
                                <div class="d-flex flex-column gap-1">
                                    <span style="font-size: 0.9rem;"><i class="bi bi-envelope me-2 text-muted"></i>{{ $usuario->email }}</span>
                                    @if($usuario->telefono)
                                    <span style="font-size: 0.85rem; color: var(--text-secondary);"><i class="bi bi-telephone me-2 text-muted"></i>{{ $usuario->telefono }}</span>
                                    @endif
                                </div>
                            </td>
                            
                            {{-- Estado --}}
                            <td class="py-3 px-2">
                                @if($estaActivo)
                                    <x-badge type="success"><i class="bi bi-circle-fill me-1"></i>Activo</x-badge>
                                    <div class="small text-muted mt-1 d-none d-md-block">
                                        Desde: {{ $usuario->last_login_at ? $usuario->last_login_at->timezone('America/La_Paz')->format('d/m/Y h:i A') : 'Sin registro' }}
                                    </div>
                                @else
                                    <x-badge type="secondary"><i class="bi bi-circle me-1"></i>Inactivo</x-badge>
                                    <div class="small text-muted mt-1 d-none d-md-block">
                                        Salida: {{ $usuario->last_logout_at ? $usuario->last_logout_at->timezone('America/La_Paz')->format('d/m/Y h:i A') : ($usuario->last_login_at ? $usuario->last_login_at->timezone('America/La_Paz')->format('d/m/Y h:i A') : 'Sin registro') }}
                                    </div>
                                @endif
                            </td>
                            
                            {{-- Rol --}}
                            <td class="py-3 px-2">
                                <x-badge type="{{ $rolColorClass }}">
                                    {!! $rolIcono !!} {{ $nombreRol }}
                                </x-badge>
                            </td>
                            
                            {{-- Acciones --}}
                            <td class="py-3 px-2 text-end">
                                <div class="d-flex justify-content-end gap-1 flex-wrap">
                                    @can('usuarios.view')
                                        <a href="{{ route('usuarios.show', $usuario->codigo) }}" class="btn btn-sm btn-light border text-gold" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('usuarios.historial')
                                        <a href="{{ route('usuarios.historial', $usuario->codigo) }}" class="btn btn-sm btn-light border text-secondary" title="Historial de acceso">
                                            <i class="bi bi-clock-history"></i>
                                        </a>
                                    @endcan
                                    @can('usuarios.edit')
                                        <a href="{{ route('usuarios.edit', $usuario->codigo) }}" class="btn btn-sm btn-light border text-main" title="Editar usuario">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('usuarios.delete')
                                        @if(auth()->user()->codigo !== $usuario->codigo)
                                            <form action="{{ route('usuarios.destroy', $usuario->codigo) }}" method="POST" class="d-inline p-0 m-0 form-delete" data-confirm-text="¿Está seguro de que desea eliminar al usuario {{$usuario->nombre}}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Eliminar usuario">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    
                                    {{-- Botón Forzar Logout --}}
                                    @if($estaActivo && auth()->user()->codigo !== $usuario->codigo)
                                        @can('usuarios.delete')
                                            <form action="{{ route('usuarios.force-logout', $usuario->codigo) }}" method="POST" class="d-inline p-0 m-0" onsubmit="return confirm('¿Cerrar la sesión de {{ $usuario->nombre }}? Será desconectado inmediatamente.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border text-warning" title="Forzar cierre de sesión">
                                                    <i class="bi bi-door-closed"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                    
                                    @can('usuarios.reset-password')
                                        @if(auth()->user()->codigo !== $usuario->codigo)
                                            <form action="{{ route('usuarios.reset-password', $usuario->codigo) }}" method="POST" class="d-inline p-0 m-0" onsubmit="return confirm('¿Restablecer la contraseña de {{$usuario->nombre}}? Se generará una contraseña temporal.');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border text-warning" title="Restablecer contraseña">
                                                    <i class="bi bi-key"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-0 border-0">
                                <x-empty-state 
                                    icon="bi-people" 
                                    title="No se encontraron usuarios" 
                                    description="Crea un nuevo usuario para permitir el acceso al sistema o ajusta los filtros de búsqueda."
                                >
                                    @if(request()->has('search') || request()->has('rol_id'))
                                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary mt-3">Limpiar filtros</a>
                                    @else
                                        @can('usuarios.create')
                                            <a href="{{ route('usuarios.create') }}" class="btn btn-primary-panaderia mt-3 text-nowrap"><i class="bi bi-plus-lg me-1"></i>Crear primer Usuario</a>
                                        @endcan
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($usuarios->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $usuarios->firstItem() }}</span> a <span class="fw-bold">{{ $usuarios->lastItem() }}</span> de <span class="fw-bold">{{ $usuarios->total() }}</span> registros
                    </div>
                </div>
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/usuarios.js'])
@endpush