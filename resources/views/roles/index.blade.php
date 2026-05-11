@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-shield-lock me-2 text-gold"></i> Gestion de Roles
                </h2>
                <p class="mb-0 text-muted">Selecciona un rol para ver y asignar permisos</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('usuarios.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Usuarios
                </a>
            </div>
        </div>
    </x-card>

    <x-card>
        <div class="card-body p-4">
            @if($roles->isEmpty())
                <x-empty-state
                    icon="bi-shield"
                    title="No hay roles registrados"
                    description="Primero cree roles para poder asignar permisos."
                />
            @else
                <div class="row g-3">
                    @foreach($roles as $rol)
                        <div class="col-12 col-md-6 col-lg-4">
                            <x-card class="h-100 bg-transparent-hover">
                                <div class="p-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-main">{{ $rol->nombre }}</div>
                                        <div class="small text-muted">{{ $rol->descripcion ?? 'Sin descripcion' }}</div>
                                    </div>
                                    @can('roles.edit')
                                        <a href="{{ route('roles.permisos.edit', $rol->id) }}" class="btn btn-sm btn-primary-panaderia">
                                            <i class="bi bi-shield-check me-1"></i> Permisos
                                        </a>
                                    @endcan
                                </div>
                            </x-card>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-card>
</div>
@endsection
