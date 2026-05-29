@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4">
            <h2 class="fw-bold mb-1 text-main">
                <i class="bi bi-plus-circle me-2 text-gold"></i> Nueva Nota de Compra
            </h2>
            <p class="mb-0 text-muted">Registra una nueva compra a proveedor</p>
        </div>
    </x-card>

    <x-card>
        <form action="{{ route('notas_compra.store') }}" method="POST" class="p-4">
            @csrf

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="proveedor_codigo" class="form-label fw-semibold">Proveedor <span class="text-danger">*</span></label>
                    <select name="proveedor_codigo" id="proveedor_codigo" class="form-select @error('proveedor_codigo') is-invalid @enderror" required>
                        <option value="">-- Selecciona un proveedor --</option>
                        @foreach($proveedores ?? [] as $proveedor)
                            <option value="{{ $proveedor->codigo }}" @selected(old('proveedor_codigo') === $proveedor->codigo)>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedor_codigo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="4" placeholder="Ingresa notas o detalles sobre la compra">{{ old('observaciones') }}</textarea>
                </div>
            </div>

            <div class="alert alert-info mb-3" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                La fecha de pedido se asignará automáticamente. Puedes editar después si es necesario.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-panaderia">
                    <i class="bi bi-check-circle me-1"></i> Crear Nota
                </button>
                <a href="{{ route('notas_compra.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
