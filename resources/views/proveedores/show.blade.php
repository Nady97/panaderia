@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-person-vcard me-2 text-gold"></i> Detalles del Proveedor
                </h2>
                <p class="mb-0 text-muted">Información completa de <strong>{{ $proveedor->empresa }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('proveedores.edit', $proveedor->codigo) }}" class="btn btn-gold-panaderia">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
                <a href="{{ route('proveedores.index') }}" class="btn btn-light-panaderia">
                    <i class="bi bi-arrow-left me-1"></i> Volver al listado
                </a>
            </div>
        </div>
    </x-card>

    <div class="row g-4">
        <!-- Columna Principal -->
        <div class="col-lg-8">
            <x-card class="h-100">
                <h5 class="fw-bold mb-4 pb-3 border-bottom">
                    <i class="bi bi-building me-2 text-gold"></i>Datos de la Empresa
                </h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Empresa</span>
                            <span class="detail-value large">{{ $proveedor->empresa }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Nombre de Contacto</span>
                            <span class="detail-value large">{{ $proveedor->nombre_contacto }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">NIT / RUC</span>
                            <span class="detail-value">{{ $proveedor->nit ?? 'No registrado' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Teléfono</span>
                            <span class="detail-value">
                                <i class="bi bi-telephone text-muted me-1"></i> {{ $proveedor->telefono }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <span class="detail-label">Correo Electrónico</span>
                            <span class="detail-value">
                                @if($proveedor->email)
                                    <i class="bi bi-envelope text-muted me-1"></i> 
                                    <a href="mailto:{{ $proveedor->email }}" class="text-gold text-decoration-none">{{ $proveedor->email }}</a>
                                @else
                                    <span class="text-muted fst-italic">No registrado</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-box">
                            <span class="detail-label">Dirección</span>
                            <span class="detail-value">
                                @if($proveedor->direccion)
                                    <i class="bi bi-geo-alt text-muted me-1"></i> {{ $proveedor->direccion }}
                                @else
                                    <span class="text-muted fst-italic">No registrada</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Columna Secundaria -->
        <div class="col-lg-4">
            <x-card class="h-100">
                <h5 class="fw-bold mb-4 pb-3 border-bottom">
                    <i class="bi bi-info-circle me-2 text-gold"></i>Estado y Registro
                </h5>
                <div class="d-flex flex-column gap-4">
                    <div class="detail-box">
                        <span class="detail-label">Código Identificador</span>
                        <span class="detail-value font-monospace d-flex align-items-center">
                            <i class="bi bi-hash text-muted me-1"></i>{{ $proveedor->codigo }}
                        </span>
                    </div>

                    <div class="detail-box">
                        <span class="detail-label mb-2">Estado Actual</span>
                        @if($proveedor->estado == 'activo')
                            <x-badge type="success">
                                <i class="bi bi-check-circle me-1"></i> Activo
                            </x-badge>
                        @elseif($proveedor->estado == 'suspendido')
                            <x-badge type="warning">
                                <i class="bi bi-exclamation-triangle me-1"></i> Suspendido
                            </x-badge>
                        @else
                            <x-badge type="danger">
                                <i class="bi bi-x-circle me-1"></i> Inactivo
                            </x-badge>
                        @endif
                    </div>

                    <div class="detail-box">
                        <span class="detail-label">Fecha de Registro</span>
                        <span class="detail-value small">
                            <i class="bi bi-calendar-date text-muted me-2"></i>{{ $proveedor->created_at ? $proveedor->created_at->format('d/m/Y H:i') : 'Desconocida' }}
                        </span>
                    </div>
                    
                    <div class="detail-box">
                        <span class="detail-label">Última Actualización</span>
                        <span class="detail-value small">
                            <i class="bi bi-clock-history text-muted me-2"></i>{{ $proveedor->updated_at ? $proveedor->updated_at->format('d/m/Y H:i') : 'Desconocida' }}
                        </span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

