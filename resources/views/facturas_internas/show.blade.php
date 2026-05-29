@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-receipt me-2 text-gold"></i> {{ $facturaInterna->nro_factura }}
                </h2>
                <p class="mb-0 text-muted">Factura Interna #{{ $facturaInterna->id }}</p>
            </div>
            <div>
                @switch($facturaInterna->estado)
                    @case('emitida')
                        <span class="badge bg-info" style="font-size: 0.9rem;">Emitida</span>
                        @break
                    @case('pagada')
                        <span class="badge bg-success" style="font-size: 0.9rem;">Pagada</span>
                        @break
                    @case('anulada')
                        <span class="badge bg-secondary" style="font-size: 0.9rem;">Anulada</span>
                        @break
                @endswitch
            </div>
        </div>
    </x-card>

    <div class="row mb-4">
        <div class="col-md-6">
            <x-card class="mb-3">
                <div class="p-3">
                    <h5 class="fw-bold text-main mb-3">Información de la Factura</h5>
                    <dl class="row small">
                        <dt class="col-sm-4">Número:</dt>
                        <dd class="col-sm-8">{{ $facturaInterna->nro_factura }}</dd>
                        
                        <dt class="col-sm-4">Fecha Emisión:</dt>
                        <dd class="col-sm-8">{{ $facturaInterna->fecha_emision->format('d/m/Y') }}</dd>
                        
                        <dt class="col-sm-4">Cliente:</dt>
                        <dd class="col-sm-8">{{ $facturaInterna->cliente_ci ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-4">Usuario:</dt>
                        <dd class="col-sm-8">{{ $facturaInterna->usuario->nombre ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">{{ ucfirst($facturaInterna->estado) }}</dd>
                    </dl>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card class="mb-3">
                <div class="p-3">
                    <h5 class="fw-bold text-main mb-3">Resumen Financiero</h5>
                    <dl class="row small">
                        <dt class="col-sm-5">Monto Total:</dt>
                        <dd class="col-sm-7 fw-semibold text-gold" style="font-size: 1.1rem;">Bs. {{ number_format($facturaInterna->total, 2) }}</dd>
                        
                        <dt class="col-sm-5">Puntos Ganados:</dt>
                        <dd class="col-sm-7 fw-semibold">{{ $facturaInterna->puntos_ganados ?? 0 }}</dd>
                        
                        @if($facturaInterna->motivo_anulacion)
                        <dt class="col-sm-5">Motivo Anulación:</dt>
                        <dd class="col-sm-7 text-danger">{{ $facturaInterna->motivo_anulacion }}</dd>
                        @endif
                    </dl>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Productos/Detalles de la Factura -->
    <x-card class="mb-4">
        <div class="p-3">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-box-seam me-2"></i> Productos en esta Factura
            </h5>
            
            @if($facturaInterna->detalles && count($facturaInterna->detalles) > 0)
                <div class="table-responsive">
                    <table class="table table-hover small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">P. Unitario</th>
                                <th class="text-end">Descuento</th>
                                <th class="text-end">Total Línea</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facturaInterna->detalles as $detalle)
                                <tr>
                                    <td>
                                        <strong>{{ $detalle->producto->nombre ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $detalle->producto_id }}</small>
                                    </td>
                                    <td class="text-center">{{ number_format($detalle->cantidad, 2) }}</td>
                                    <td class="text-end">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="text-end">
                                        @if($detalle->descuento > 0)
                                            <span class="badge bg-warning">Bs. {{ number_format($detalle->descuento, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold">Bs. {{ number_format($detalle->total_linea, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i> No hay detalles de productos registrados en esta factura.
                </div>
            @endif
        </div>
    </x-card>

    <!-- Proveedores Asociados -->
    <x-card class="mb-4">
        <div class="p-3">
            <h5 class="fw-bold text-main mb-3">
                <i class="bi bi-shop me-2"></i> Proveedores Asociados
            </h5>
            
            @php
                $proveedoresAsociados = $facturaInterna->proveedoresAsociados();
            @endphp
            
            @if(count($proveedoresAsociados) > 0)
                <div class="row">
                    @foreach($proveedoresAsociados as $item)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 bg-light">
                                @if($item['proveedor'])
                                    <h6 class="fw-bold text-main mb-2">
                                        <i class="bi bi-building me-2"></i> {{ $item['proveedor']->nombre_empresa ?? $item['proveedor']->empresa }}
                                    </h6>
                                    <dl class="row small mb-0">
                                        <dt class="col-sm-5">Contacto:</dt>
                                        <dd class="col-sm-7">{{ $item['proveedor']->nombre_contacto ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-5">Email:</dt>
                                        <dd class="col-sm-7">
                                            <a href="mailto:{{ $item['proveedor']->email }}">{{ $item['proveedor']->email ?? 'N/A' }}</a>
                                        </dd>
                                        
                                        <dt class="col-sm-5">Teléfono:</dt>
                                        <dd class="col-sm-7">{{ $item['proveedor']->telefono ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-5">Producto:</dt>
                                        <dd class="col-sm-7">
                                            <strong>{{ $item['producto']->nombre ?? 'N/A' }}</strong>
                                        </dd>
                                    </dl>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        <small><i class="bi bi-exclamation-triangle me-1"></i> Producto {{ $item['producto']->nombre }} sin proveedor registrado</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i> No hay proveedores asociados a los productos de esta factura.
                </div>
            @endif
        </div>
    </x-card>

    <!-- Acciones -->
    <x-card class="mb-4">
        <div class="p-3 d-flex gap-2 flex-wrap">
            @can('facturas_internas.download')
                <a href="{{ route('facturas_internas.pdf', $facturaInterna) }}" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-pdf me-2"></i> Descargar PDF
                </a>
            @endcan
            
            @can('facturas_internas.edit')
                @if($facturaInterna->estado === 'emitida')
                    <form action="{{ route('facturas_internas.marcar-pagada', $facturaInterna) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i> Marcar como Pagada
                        </button>
                    </form>
                @endif
                
                @if($facturaInterna->estado !== 'anulada')
                    <form action="{{ route('facturas_internas.anular', $facturaInterna) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Anular esta factura?');">
                            <i class="bi bi-x-circle me-2"></i> Anular
                        </button>
                    </form>
                @endif
            @endcan
            
            <a href="{{ route('facturas_internas.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </x-card>
</div>
@endsection
