@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0 text-main">
                        <i class="bi bi-basket me-2 text-gold"></i>Detalle de Insumo
                    </h2>
                    <span class="badge bg-light text-secondary border border-secondary border-opacity-25 rounded-pill px-3">Vista</span>
                </div>
                <p class="mb-0 text-muted">Informacion del insumo "{{ $insumo->nombre }}"</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('insumos.edit', $insumo->id) }}" class="btn btn-warning text-nowrap" style="border-radius: 10px; font-weight: 600; color: #fff;">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <a href="{{ route('insumos.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </x-card>

    <div class="row g-4">
        <div class="col-lg-6">
            <x-card>
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-main"><i class="bi bi-info-circle me-2 text-muted"></i>Ficha del Insumo</h5>
                    <div class="detail-box p-3 mb-3">
                        <div class="text-muted small text-uppercase fw-semibold">Nombre</div>
                        <div class="fw-semibold text-main">{{ $insumo->nombre }}</div>
                    </div>
                    <div class="detail-box p-3 mb-3">
                        <div class="text-muted small text-uppercase fw-semibold">Unidad de medida</div>
                        <div class="fw-semibold text-main">{{ $insumo->unidad_medida }}</div>
                    </div>
                    <div class="detail-box p-3 mb-3">
                        <div class="text-muted small text-uppercase fw-semibold">Stock actual</div>
                        <div class="fw-semibold text-main">{{ $insumo->stock_actual }}</div>
                    </div>
                    <div class="detail-box p-3 mb-3">
                        <div class="text-muted small text-uppercase fw-semibold">Stock minimo</div>
                        <div class="fw-semibold text-main">{{ $insumo->stock_minimo ?? '-' }}</div>
                    </div>
                    <div class="detail-box p-3">
                        <div class="text-muted small text-uppercase fw-semibold">Costo promedio</div>
                        <div class="fw-semibold text-main">Bs {{ number_format($insumo->precio_compra_promedio ?? 0, 2) }}</div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card>
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-main"><i class="bi bi-journal-text me-2 text-muted"></i>Recetas asociadas</h5>
                    @if($insumo->recetas && $insumo->recetas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-main">
                                <thead class="border-bottom-modern border-2">
                                    <tr>
                                        <th class="py-2">Receta</th>
                                        <th class="py-2">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($insumo->recetas as $receta)
                                        <tr>
                                            <td class="py-2">
                                                <a href="{{ route('recetas.show', $receta->id) }}" class="text-decoration-none text-main">
                                                    {{ $receta->nombre }}
                                                </a>
                                            </td>
                                            <td class="py-2">{{ floatval($receta->pivot->cantidad_necesaria) }} {{ $insumo->unidad_medida }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">No hay recetas asociadas a este insumo.</div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
