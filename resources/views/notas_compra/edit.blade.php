@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4">
            <h2 class="fw-bold mb-1 text-main">
                <i class="bi bi-pencil me-2 text-gold"></i> Editar Nota {{ $nota->nro_comprobante ?? 'S/N' }}
            </h2>
            <p class="mb-0 text-muted">Modifica los datos de la nota solicitada</p>
        </div>
    </x-card>

    @if($nota->estado !== 'solicitado')
        <div class="alert alert-warning mb-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Solo se pueden editar notas en estado "Solicitado"
        </div>
    @endif

    <x-card>
        <form action="{{ route('notas_compra.update', $nota) }}" method="POST" class="p-4">
            @csrf @method('PUT')

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="proveedor_codigo" class="form-label fw-semibold">Proveedor <span class="text-danger">*</span></label>
                    <select name="proveedor_codigo" id="proveedor_codigo" class="form-select @error('proveedor_codigo') is-invalid @enderror" required>
                        <option value="">-- Selecciona un proveedor --</option>
                        @foreach($proveedores ?? [] as $proveedor)
                            <option value="{{ $proveedor->codigo }}" @selected($nota->proveedor_codigo === $proveedor->codigo)>
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
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="4">{{ $nota->observaciones }}</textarea>
                </div>
            </div>

            <div class="alert alert-info mb-3" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                Fecha de pedido: <strong>{{ $nota->fecha_pedido->format('d/m/Y H:i') }}</strong>
                @if($nota->fecha_recepcion)
                    | Fecha de recepción: <strong>{{ $nota->fecha_recepcion->format('d/m/Y H:i') }}</strong>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-panaderia">
                    <i class="bi bi-check-circle me-1"></i> Guardar Cambios
                </button>
                <a href="{{ route('notas_compra.show', $nota) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
