@extends('layouts.app')

{{-- 
    -----------------------------------------------------------------------
    ARCHIVO: resources/views/produccion/create.blade.php
    PROPÓSITO: Formulario para la creación de una nueva Orden de Producción.
    ARQUITECTURA: Mantenimiento de legibilidad con Componentes Blade (<x-card>,
                  <x-input>, <x-select>, <x-textarea>). Código DRY, uniforme a todo el ecosistema.
    -----------------------------------------------------------------------
--}}

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
                <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
            </a>
        </div>
    </div>

    <x-card class="border-0 shadow-sm rounded-4 overflow-hidden max-w-3xl mx-auto col-md-8">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('produccion.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-12 col-md-6 form-group">
                        <x-select name="producto_id" label="Producto a Fabricar" required="true" icon='<i class="bi bi-box"></i>'>
                            <option value="" disabled selected>Seleccione el pan/postre...</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                    {{ $producto->nombre }} (Stock acts: {{ $producto->stock }})
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <x-input type="number" name="cantidad" label="Cantidad" required="true" icon='<i class="bi bi-123"></i>' value="{{ old('cantidad') }}" min="1" placeholder="N° de unidades producidas" />
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <x-input type="date" name="fecha_produccion" label="Fecha de Producción" required="true" icon='<i class="bi bi-calendar"></i>' value="{{ old('fecha_produccion', date('Y-m-d')) }}" />
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <x-select name="estado" label="Estado de Órden" required="true" icon='<i class="bi bi-activity"></i>'>
                            <option value="planificado" {{ old('estado') == 'planificado' ? 'selected' : '' }}>Planificado</option>
                            <option value="en_proceso" {{ old('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="fallido" {{ old('estado') == 'fallido' ? 'selected' : '' }}>Cancelado</option>
                        </x-select>
                        <small class="text-muted mt-1 d-block" style="margin-top: -15px !important;"><i class="bi bi-info-circle"></i> Solo "Completado" sumará inventario real.</small>
                    </div>

                    <div class="col-12 form-group">
                        <x-textarea name="observaciones" label="Observaciones (Trazabilidad o Insumos Perdidos)" rows="3" placeholder="Añadir notas sobre la temperatura del horno, mermas, etc..." value="{{ old('observaciones') }}" />
                    </div>
                </div>

                <div class="mt-5 pt-3 border-top text-end" style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <button type="reset" class="btn btn-light-panaderia me-2">Manejar de cero</button>
                    <button type="submit" class="btn btn-gold-panaderia" style="border-radius: 10px; padding: 0.6rem 1.5rem; background: var(--gold-light); color: #fff; border: 1px solid var(--gold-dark);">
                        <i class="bi bi-save me-1"></i> Registrar Órden
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection
