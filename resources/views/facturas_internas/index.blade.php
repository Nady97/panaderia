@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-receipt me-2 text-gold"></i> Facturas Internas
                </h2>
                <p class="mb-0 text-muted">Gestión de facturas emitidas</p>
            </div>
            @can('facturas_internas.create')
                <a href="{{ route('facturas_internas.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Factura
                </a>
            @endcan
        </div>
    </x-card>

    <!-- KPI Cards -->
    <div class="row g-2 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: var(--gold-dark);">
                        <i class="bi bi-receipt kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['total'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Total</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #3b82f6;">
                        <i class="bi bi-clock kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['emitidas'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Emitidas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #10b981;">
                        <i class="bi bi-check-circle kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['pagadas'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Pagadas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #ef4444;">
                        <i class="bi bi-currency-dollar kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">Bs. {{ number_format($estadisticas['monto_pendiente'] ?? 0, 2) }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Pendiente</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Tabla de Facturas -->
    <x-card>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold text-main">Número Factura</th>
                        <th class="fw-semibold text-main">Fecha Emisión</th>
                        <th class="fw-semibold text-main">Cliente</th>
                        <th class="fw-semibold text-main">Productos</th>
                        <th class="fw-semibold text-main">Monto Total</th>
                        <th class="fw-semibold text-main">Estado</th>
                        <th class="fw-semibold text-main">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas ?? [] as $factura)
                        <tr>
                            <td class="fw-semibold">{{ $factura->nro_factura }}</td>
                            <td>{{ $factura->fecha_emision->format('d/m/Y') ?? 'N/A' }}</td>
                            <td>{{ $factura->cliente_ci ?? 'N/A' }}</td>
                            <td>
                                @if($factura->detalles && count($factura->detalles) > 0)
                                    <div class="small">
                                        @foreach($factura->detalles->take(2) as $detalle)
                                            <div class="mb-1">
                                                <i class="bi bi-box-seam"></i> 
                                                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $detalle->producto->nombre }}">
                                                    {{ $detalle->producto->nombre ?? 'N/A' }}
                                                </span>
                                                <span class="badge bg-light text-dark ms-1">{{ number_format($detalle->cantidad, 1) }}</span>
                                            </div>
                                        @endforeach
                                        @if(count($factura->detalles) > 2)
                                            <div class="text-muted small">+{{ count($factura->detalles) - 2 }} más</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">Sin productos</span>
                                @endif
                            </td>
                            <td class="fw-semibold text-gold">Bs. {{ number_format($factura->total, 2) }}</td>
                            <td>
                                @switch($factura->estado)
                                    @case('emitida')
                                        <span class="badge bg-info">Emitida</span>
                                        @break
                                    @case('pagada')
                                        <span class="badge bg-success">Pagada</span>
                                        @break
                                    @case('anulada')
                                        <span class="badge bg-secondary">Anulada</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @can('facturas_internas.view')
                                        <a href="{{ route('facturas_internas.show', $factura) }}" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('facturas_internas.edit')
                                        @if($factura->estado === 'valida')
                                            <a href="{{ route('facturas_internas.edit', $factura) }}" class="btn btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('facturas_internas.view')
                                        <a href="{{ route('facturas_internas.pdf', $factura) }}" class="btn btn-outline-danger" target="_blank" title="PDF">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                    @endcan
                                    @can('facturas_internas.edit')
                                        @if($factura->estado === 'valida')
                                            <form action="{{ route('facturas_internas.marcar-pagada', $factura) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm" title="Marcar Pagada">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('facturas_internas.edit')
                                        @if($factura->estado !== 'anulada')
                                            <form action="{{ route('facturas_internas.anular', $factura) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas anular esta factura?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Anular">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('facturas_internas.delete')
                                        @if($factura->estado === 'valida')
                                            <form action="{{ route('facturas_internas.destroy', $factura) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta factura?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox me-2"></i>No hay facturas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($facturas) && $facturas->count())
            <div class="p-3 border-top">
                {{ $facturas->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
