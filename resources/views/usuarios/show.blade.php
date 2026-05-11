@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-person-badge me-2 text-gold-dark"></i>Detalles del Usuario
                </h2>
                <p class="mb-0 text-secondary">Información completa de <strong>{{ $usuario->nombre }}</strong></p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                @if(auth()->user()->rol && auth()->user()->rol->nombre === 'Administrador')
                <a href="{{ route('usuarios.edit', $usuario->codigo) }}" class="btn btn-gold-panaderia text-nowrap">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
                <a href="{{ route('usuarios.historial', $usuario->codigo) }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-clock-history me-1"></i> Historial
                </a>
                @endif
                <a href="{{ route('usuarios.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
                </a>
            </div>
        </div>
    </x-card>

    <!-- Contenido Principal -->
    <div class="row g-4">
        <!-- Columna de Datos Personales -->
        <div class="col-lg-8">
            <x-card class="h-100">
                <h5 class="fw-bold mb-4 pb-3 border-bottom">
                    <i class="bi bi-person-lines-fill me-2 text-gold-dark"></i>Datos Personales
                </h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Nombre Completo</span>
                            <span class="detail-value large">{{ $usuario->nombre }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Correo Electrónico</span>
                            <a href="mailto:{{ $usuario->email }}" class="detail-value text-gold-dark text-decoration-none">
                                <i class="bi bi-envelope text-muted me-2"></i>{{ $usuario->email }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Teléfono</span>
                            <span class="detail-value">
                                @if($usuario->telefono)
                                    <i class="bi bi-telephone text-muted me-2"></i>{{ $usuario->telefono }}
                                @else
                                    <span class="text-muted fst-italic">No registrado</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Sexo</span>
                            <span class="detail-value">
                                @if($usuario->sexo == 'M')
                                    <i class="bi bi-gender-male text-muted me-2"></i>Masculino
                                @elseif($usuario->sexo == 'F')
                                    <i class="bi bi-gender-female text-muted me-2"></i>Femenino
                                @else
                                    <span class="text-muted fst-italic">No especificado</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-box">
                            <span class="detail-label">Dirección</span>
                            <span class="detail-value">
                                @if($usuario->direccion)
                                    <i class="bi bi-geo-alt text-muted me-2"></i>{{ $usuario->direccion }}
                                @else
                                    <span class="text-muted fst-italic">No registrada</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Columna de Registro y Sistema -->
        <div class="col-lg-4">
            <x-card class="h-100">
                <h5 class="fw-bold mb-4 pb-3 border-bottom">
                    <i class="bi bi-shield-lock me-2 text-gold-dark"></i>Cuenta en el Sistema
                </h5>
                <div class="d-flex flex-column gap-4">
                    <!-- Código -->
                    <div class="detail-box">
                        <span class="detail-label">Código Identificador</span>
                        <span class="detail-value font-monospace d-flex align-items-center">
                            <i class="bi bi-hash text-muted me-1"></i>{{ $usuario->codigo }}
                        </span>
                    </div>

                    <!-- Rol -->
                    <div class="detail-box">
                        <span class="detail-label mb-2">Rol Asignado</span>
                        @php
                            $nombreRol = $usuario->rol ? $usuario->rol->nombre : 'Sin rol';
                            $rolIcono = $usuario->rol->icono ?? match($nombreRol) {
                                'Administrador' => '👑',
                                'Cajero' => '💰',
                                'Cocinero / Panadero' => '🥖',
                                'Proveedor' => '🚚',
                               // 'Cliente' => '👤',
                                default => '❓'
                            };
                            $rolColorClass = match($nombreRol) {
                                'Administrador' => 'danger',
                                'Cajero' => 'success',
                                'Cocinero / Panadero' => 'warning',
                                'Proveedor' => 'info',
                                default => 'secondary'
                            };
                        @endphp
                        <x-badge type="{{ $rolColorClass }}" style="font-size: 0.95rem; padding: 8px 16px;">
                            {!! $rolIcono !!} {{ $nombreRol }}
                        </x-badge>
                    </div>

                    <!-- Registro -->
                    <div class="detail-box">
                        <span class="detail-label mb-3">Resumen Temporal</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Creación</span>
                            <span class="detail-value small">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Actualización</span>
                            <span class="detail-value small">{{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
