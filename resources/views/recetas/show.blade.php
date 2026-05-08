@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="detail-box icon-box-lg bg-main text-gold rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-journal-richtext fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 text-main">Detalle de Receta</h2>
                    <p class="mb-0 text-muted">Visualizando: {{ $receta->nombre }}</p>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a href="{{ route('recetas.pdf', $receta->id) }}" class="btn btn-outline-danger text-nowrap" style="border-radius: 8px;">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Imprimir / PDF
                </a>
                <a href="{{ route('recetas.edit', $receta->id) }}" class="btn btn-gold-panaderia text-nowrap">
                    <i class="bi bi-pencil-square me-1"></i> Editar Receta
                </a>
                <a href="{{ route('recetas.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Recetas
                </a>
            </div>
        </div>
    </x-card>

    <div class="row g-4">
        <!-- Detalles Principales -->
        <div class="col-lg-4">
            <x-card class="h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-4 pb-4 border-bottom border-color">
                        <div class="d-inline-flex align-items-center justify-content-center bg-main rounded-circle mb-3 border border-4 shadow-m receta-avatar">
                            <i class="bi bi-journal-text text-gold" class="display-4"></i>
                        </div>
                        <h4 class="fw-bold text-main mb-1">{{ $receta->nombre }}</h4>
                        
                        @if($receta->estado == 'activa')
                            <x-badge type="success" class="mt-2"><i class="bi bi-check-circle me-1"></i>Activa</x-badge>
                        @elseif($receta->estado == 'borrador')
                            <x-badge type="warning" class="mt-2"><i class="bi bi-pencil me-1"></i>Borrador</x-badge>
                        @else
                            <x-badge type="danger" class="mt-2"><i class="bi bi-x-circle me-1"></i>Obsoleta</x-badge>
                        @endif
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="detail-box p-3 rounded bg-input border-modern">
                            <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Producto Relacionado</span>
                            <span class="detail-value text-main fw-medium">
                                <i class="bi bi-box-seam me-1 text-gold"></i> {{ $receta->producto ? $receta->producto->nombre : 'Ninguno' }}
                            </span>
                        </div>
                        
                        <div class="detail-box p-3 rounded bg-input border-modern">
                            <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Rendimiento</span>
                            <span class="detail-value text-main">
                                <i class="bi bi-pie-chart me-1 text-gold"></i> {{ $receta->rendimiento_estimado }} unidades
                            </span>
                        </div>

                        <div class="detail-box p-3 rounded bg-input border-modern">
                            <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Tiempo de Preparación</span>
                            <span class="detail-value text-main">
                                <i class="bi bi-clock me-1 text-gold"></i> {{ $receta->tiempo_preparacion_min }} minutos
                            </span>
                        </div>
                        
                        <div class="detail-box p-3 rounded bg-input border-modern">
                            <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Creado Por</span>
                            <span class="detail-value text-muted">
                                <i class="bi bi-person me-1"></i> {{ $receta->usuario ? $receta->usuario->nombre . ' ' . $receta->usuario->apellido : 'Desconocido' }}
                            </span>
                        </div>

                        <div class="detail-box p-3 rounded bg-input border-modern">
                            <span class="detail-label d-block text-muted small text-uppercase mb-1 fw-bold">Última Modificación</span>
                            <span class="detail-value text-muted">
                                <i class="bi bi-calendar-event me-1"></i> {{ $receta->updated_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Instrucciones / Preparación y Materiales -->
        <div class="col-lg-8 d-flex flex-column gap-4">
            
            <!-- Insumos Requeridos -->
            <x-card class="h-auto">
                <div class="card-header bg-transparent border-bottom-modern pb-4 px-4 pt-4">
                    <h5 class="fw-bold mb-0 text-main"><i class="bi bi-basket me-2 text-gold"></i>Ingredientes / Insumos</h5>
                </div>
                <div class="card-body p-4">
                    @if($receta->insumos && $receta->insumos->count() > 0)
                        <div class="row g-3">
                            @foreach($receta->insumos as $insumo)
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-box d-flex align-items-center p-3 rounded bg-input border-modern transition-fast">
                                    <div class="icon-box-sm me-3 text-gold d-flex align-items-center justify-content-center bg-card rounded-circle border-modern">
                                        <i class="bi bi-tag-fill small"></i>
                                    </div>
                                    <div>
                                        <div class="detail-label text-muted small text-uppercase mb-1 fw-bold">{{ $insumo->nombre }}</div>
                                        <div class="detail-value text-main fw-bold fs-6">
                                            {{ floatval($insumo->pivot->cantidad_necesaria) }} <span class="fw-normal text-muted">{{ $insumo->unidad_medida }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <x-empty-state 
                            icon="bi bi-basket" 
                            title="Sin ingredientes" 
                            description="No se han especificado ingredientes para esta receta." />
                    @endif
                </div>
            </x-card>

            <!-- Paso a Paso -->
            <x-card class="flex-grow-1">
                <div class="card-header bg-transparent border-bottom-modern pb-4 px-4 pt-4">
                    <h5 class="fw-bold mb-0 text-main"><i class="bi bi-card-text me-2 text-gold"></i>Instrucciones de Preparación</h5>
                </div>
                <div class="card-body p-4">
                    <div class="detail-box p-4 rounded text-muted bg-input border-modern lh-lg text-wrap fs-5">{{ $receta->instrucciones ?? 'No se han registrado instrucciones de preparación para esta receta.' }}</div>
                </div>
            </x-card>
        </div>

    </div>
</div>
@endsection




