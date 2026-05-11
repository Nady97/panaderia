@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-shield-check me-2 text-gold"></i> Permisos del Rol
                </h2>
                <p class="mb-0 text-muted">Rol: <strong>{{ $rol->nombre }}</strong></p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('roles.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Roles
                </a>
            </div>
        </div>
    </x-card>

    <x-card class="mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 text-main">
                <i class="bi bi-eye me-2 text-muted"></i> Permisos asignados
            </h5>
            @if($rol->permisos->isEmpty())
                <div class="text-muted">Este rol no tiene permisos asignados.</div>
            @else
                <div class="d-flex flex-wrap gap-2">
                    @foreach($rol->permisos as $permiso)
                        <x-badge type="success">{{ $permiso->nombre }}</x-badge>
                    @endforeach
                </div>
            @endif
        </div>
    </x-card>

    <x-card>
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="fw-bold mb-0 text-main">
                    <i class="bi bi-sliders me-2 text-muted"></i> Modificar permisos
                </h5>
                @can('roles.edit')
                    <button class="btn btn-gold-panaderia" type="button" data-bs-toggle="collapse" data-bs-target="#editPermisos" aria-expanded="false" aria-controls="editPermisos">
                        <i class="bi bi-pencil-square me-1"></i> Modificar permisos
                    </button>
                @endcan
            </div>

            @can('roles.edit')
                <div class="collapse" id="editPermisos">
                    <form method="POST" action="{{ route('roles.permisos.update', $rol->id) }}">
                        @csrf
                        @method('PUT')

                        @if($permisos->isEmpty())
                            <x-empty-state
                                icon="bi-shield-x"
                                title="No hay permisos disponibles"
                                description="Cree permisos para poder asignarlos a los roles."
                            />
                        @else
                            @foreach($permisos->groupBy('modulo') as $modulo => $grupo)
                                <div class="mb-4">
                                    <div class="fw-bold text-main mb-2">
                                        <i class="bi bi-folder me-2 text-muted"></i>{{ $modulo ?: 'General' }}
                                    </div>
                                    <div class="row g-2">
                                        @foreach($grupo as $permiso)
                                            <div class="col-12 col-md-6 col-lg-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="{{ $permiso->id }}" id="permiso_{{ $permiso->id }}" {{ $rol->permisos->contains($permiso->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="permiso_{{ $permiso->id }}">
                                                        <span class="fw-semibold text-main">{{ $permiso->nombre }}</span>
                                                        @if($permiso->descripcion)
                                                            <span class="d-block small text-muted">{{ $permiso->descripcion }}</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end gap-3 pt-3 border-top-modern">
                                <button type="submit" class="btn btn-primary-panaderia">
                                    <i class="bi bi-save me-1"></i> Guardar
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            @endcan
        </div>
    </x-card>
</div>
@endsection
