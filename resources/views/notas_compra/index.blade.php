@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="flex-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="text-primary-heading mb-1">
                <i class="bi bi-receipt me-2 text-gold"></i>
                Notas de Compra
            </h1>
            <p class="text-caption">Gestión de compras a proveedores</p>
        </div>
        @can('notas_compra.create')
            <a href="{{ route('notas_compra.create') }}" class="btn-gold-panaderia">
                <i class="bi bi-plus-circle me-1"></i> Nueva Nota
            </a>
        @endcan
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card-modern kpi-card p-3">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-receipt kpi-icon text-gold"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['total'] ?? 0 }}</h3>
                    <p class="text-caption mb-0 text-uppercase">Total Notas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-modern kpi-card p-3">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-hourglass-split kpi-icon text-warning"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['solicitadas'] ?? 0 }}</h3>
                    <p class="text-caption mb-0 text-uppercase">Solicitadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-modern kpi-card p-3">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-check-circle-fill kpi-icon text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['recibidas'] ?? 0 }}</h3>
                    <p class="text-caption mb-0 text-uppercase">Recibidas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-modern kpi-card p-3">
                <div class="kpi-icon-wrapper me-3">
                    <i class="bi bi-x-circle-fill kpi-icon text-danger"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['canceladas'] ?? 0 }}</h3>
                    <p class="text-caption mb-0 text-uppercase">Canceladas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card-modern p-2 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('notas_compra.index') }}" class="btn btn-sm {{ !request('estado') ? 'btn-primary-panaderia' : 'btn-light-panaderia' }}">
                Todos
            </a>
            <a href="{{ route('notas_compra.index', ['estado' => 'solicitado']) }}" class="btn btn-sm {{ request('estado') === 'solicitado' ? 'btn-primary-panaderia' : 'btn-light-panaderia' }}">
                Solicitados
            </a>
            <a href="{{ route('notas_compra.index', ['estado' => 'recibido']) }}" class="btn btn-sm {{ request('estado') === 'recibido' ? 'btn-primary-panaderia' : 'btn-light-panaderia' }}">
                Recibidos
            </a>
            <a href="{{ route('notas_compra.index', ['estado' => 'cancelado']) }}" class="btn btn-sm {{ request('estado') === 'cancelado' ? 'btn-primary-panaderia' : 'btn-light-panaderia' }}">
                Cancelados
            </a>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card-modern p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-caption">NÚMERO</th>
                        <th class="text-caption">PROVEEDOR</th>
                        <th class="text-caption">CONTACTO</th>
                        <th class="text-caption">ÍTEMS</th>
                        <th class="text-caption">MONTO</th>
                        <th class="text-caption">FECHA</th>
                        <th class="text-caption">ESTADO</th>
                        <th class="text-caption">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notas ?? [] as $nota)
                    <tr>
                        <td class="align-middle">
                            <span class="badge bg-soft-gold text-gold px-3 py-1 rounded-pill">
                                {{ $nota->nro_comprobante ?? 'NC-' . str_pad($nota->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="fw-semibold text-main">{{ $nota->proveedor->empresa ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $nota->proveedor->codigo ?? '' }}</small>
                        </td>
                        <td class="align-middle">
                            <div class="small">{{ $nota->proveedor->nombre_contacto ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $nota->proveedor->telefono ?? '' }}</small>
                        </td>
                        <td class="align-middle">
                            @php $totalProd = $nota->productos->count(); @endphp
                            @if($totalProd > 0)
                                <span class="badge bg-soft-success text-success px-3 py-1 rounded-pill">
                                    <i class="bi bi-box me-1"></i> {{ $totalProd }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="align-middle fw-semibold text-gold">
                            Bs. {{ number_format($nota->monto_total, 2) }}
                        </td>
                        <td class="align-middle small">
                            <i class="bi bi-calendar me-1"></i> {{ $nota->fecha_pedido->format('d/m/Y') }}
                            @if($nota->fecha_recepcion)
                                <br><i class="bi bi-check-circle-fill text-success me-1"></i> {{ $nota->fecha_recepcion->format('d/m/Y') }}
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($nota->estado === 'solicitado')
                                <span class="badge bg-soft-warning text-warning px-3 py-1 rounded-pill">
                                    <i class="bi bi-hourglass-split me-1"></i> Solicitado
                                </span>
                            @elseif($nota->estado === 'recibido')
                                <span class="badge bg-soft-success text-success px-3 py-1 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Recibido
                                </span>
                            @else
                                <span class="badge bg-soft-danger text-danger px-3 py-1 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Cancelado
                                </span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1">
                                <a href="{{ route('notas_compra.show', $nota) }}" class="action-btn action-btn-info p-2 rounded-circle" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('notas_compra.edit')
                                    @if($nota->estado === 'solicitado')
                                        <a href="{{ route('notas_compra.edit', $nota) }}" class="action-btn action-btn-success p-2 rounded-circle" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                @endcan
                                @can('notas_compra.delete')
                                    @if($nota->estado === 'solicitado')
                                        <form action="{{ route('notas_compra.destroy', $nota) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-danger p-2 rounded-circle border-0" onclick="return confirm('¿Eliminar esta nota?')" title="Eliminar">
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
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state-container text-center">
                                <div class="empty-state-icon mb-3">
                                    <div class="icon-wrapper">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                </div>
                                <h5 class="empty-state-title">No hay notas de compra</h5>
                                <p class="empty-state-desc">Comienza creando tu primera nota de compra</p>
                                @can('notas_compra.create')
                                    <a href="{{ route('notas_compra.create') }}" class="btn-gold-panaderia mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Nueva Nota
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($notas) && $notas->count())
            <div class="paginacion-personalizada p-3 border-top">
                {{ $notas->links() }}
            </div>
        @endif
    </div>
</div>

@endsection