@extends('layouts.app')

@section('content')
<div class="dashboard-container p-4 animate-fade-in">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="h3 mb-0 text-main fw-bold">
                <i class="bi bi-plus-circle text-gold me-2"></i> Crear Orden de Producción
            </h2>
            <p class="text-secondary mt-1 mb-0">Esta acción alimentará el stock al completarse.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('produccion.index') }}" class="btn btn-light-panaderia shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <x-card class="border-0 shadow-sm rounded-4 overflow-hidden max-w-3xl mx-auto col-md-8">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('produccion.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-medium text-main">Producto a Fabricar <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-box"></i></span>
                            <select name="producto_id" class="form-select @error('producto_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Seleccione el pan/postre...</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre }} (Stock acts: {{ $producto->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-medium text-main">Cantidad <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-123"></i></span>
                            <input type="number" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror" value="{{ old('cantidad') }}" min="1" required placeholder="N° de unidades producidas">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-medium text-main">Fecha de Producción <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" name="fecha_produccion" class="form-control @error('fecha_produccion') is-invalid @enderror" value="{{ old('fecha_produccion', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-medium text-main">Estado de Órden <span class="text-danger">*</span></label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-text"><i class="bi bi-activity"></i></span>
                            <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="planificado" {{ old('estado') == 'planificado' ? 'selected' : '' }}>Planificado</option>
                                <option value="en_proceso" {{ old('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="fallido" {{ old('estado') == 'fallido' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Solo "Completado" sumará inventario real.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-main">Observaciones (Trazabilidad o Insumos Perdidos)</label>
                        <textarea name="observaciones" class="form-control bg-input border-border-color text-primary @error('observaciones') is-invalid @enderror" rows="3" placeholder="Añadir notas sobre la temperatura del horno, mermas, etc...">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-top text-end">
                    <button type="reset" class="btn btn-light-panaderia me-2">Manejar de cero</button>
                    <button type="submit" class="btn btn-primary-panaderia">
                        <i class="bi bi-save me-1"></i> Registrar Órden
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection
