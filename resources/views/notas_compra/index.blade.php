@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-file-earmark-pdf me-2 text-gold"></i> Notas de Compra
                </h2>
                <p class="mb-0 text-muted">Gestión de compras a proveedores</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                @can('notas_compra.create')
                    <a href="{{ route('notas_compra.create') }}" class="btn btn-primary-panaderia text-nowrap">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Nota
                    </a>
                @endcan
            </div>
        </div>
    </x-card>

    <!-- KPI Cards -->
    <div class="row g-2 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: var(--gold-dark);">
                        <i class="bi bi-file-earmark kpi-icon"></i>
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
                    <div class="p-3 me-3" style="color: #fbbf24;">
                        <i class="bi bi-hourglass-split kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['solicitadas'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Solicitadas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #10b981;">
                        <i class="bi bi-box-seam kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['recibidas'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Recibidas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: #ef4444;">
                        <i class="bi bi-x-circle kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $estadisticas['canceladas'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Canceladas</p>
                    </div>
                </div>
            </x-card>
        </div>
        <!--
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover kpi-card">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="color: var(--gold-dark);">
                        <i class="bi bi-cash-coin kpi-icon"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main" style="font-size: 1rem;">Bs. {{ number_format($estadisticas['monto_total_solicitado'] ?? 0, 2) }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Por Recibir</p>
                    </div>
                </div>
            </x-card> 
        </div>-->
    </div>

    <!-- Tabla de Notas -->
    <x-card>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold text-main">Número</th>
                        <th class="fw-semibold text-main">Proveedor</th>
                        <th class="fw-semibold text-main">Contacto</th>
                        <th class="fw-semibold text-main">Ítems Pedidos</th>
                        <th class="fw-semibold text-main">Monto Total</th>
                        <th class="fw-semibold text-main">Fecha</th>
                        <th class="fw-semibold text-main">Estado</th>
                        <th class="fw-semibold text-main">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notas ?? [] as $nota)
                        <tr>
                            <td class="fw-semibold">
                                @if($nota->nro_comprobante)
                                    {{ $nota->nro_comprobante }}
                                @else
                                    NCP-{{ str_pad($nota->id, 4, '0', STR_PAD_LEFT) }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $nota->proveedor->empresa ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">{{ $nota->proveedor->codigo }}</small>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-person"></i> {{ $nota->proveedor->nombre_contacto ?? 'N/A' }}
                                    <br>
                                    <i class="bi bi-telephone"></i> 
                                    <a href="tel:{{ $nota->proveedor->telefono }}">{{ $nota->proveedor->telefono ?? 'N/A' }}</a>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    @php
                                        $totalInsumos = $nota->detalles?->count() ?? 0;
                                        $totalProductos = $nota->productos?->count() ?? 0;
                                    @endphp
                                    @if($totalInsumos > 0)
                                        <span class="badge bg-info">{{ $totalInsumos }} Insumo{{ $totalInsumos > 1 ? 's' : '' }}</span>
                                    @endif
                                    @if($totalProductos > 0)
                                        <span class="badge bg-success">{{ $totalProductos }} Prod{{ $totalProductos > 1 ? 's' : '' }}</span>
                                    @endif
                                    @if($totalInsumos === 0 && $totalProductos === 0)
                                        <span class="text-muted">Sin ítems</span>
                                    @endif
                                </div>
                            </td>
                            <td class="fw-semibold text-gold">Bs. {{ number_format($nota->monto_total, 2) }}</td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-calendar"></i> {{ $nota->fecha_pedido->format('d/m/Y') }}
                                    @if($nota->fecha_recepcion)
                                        <br><i class="bi bi-check-circle"></i> {{ $nota->fecha_recepcion->format('d/m/Y') }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                @switch($nota->estado)
                                    @case('solicitado')
                                        <span class="badge bg-warning text-dark">Solicitado</span>
                                        @break
                                    @case('recibido')
                                        <span class="badge bg-success">Recibido</span>
                                        @break
                                    @case('cancelado')
                                        <span class="badge bg-secondary">Cancelado</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @can('notas_compra.view')
                                        <a href="{{ route('notas_compra.show', $nota) }}" class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('notas_compra.edit')
                                        @if($nota->estado === 'solicitado')
                                            <a href="{{ route('notas_compra.edit', $nota) }}" class="btn btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('notas_compra.delete')
                                        @if($nota->estado === 'solicitado')
                                            <form action="{{ route('notas_compra.destroy', $nota) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Eliminar?');" title="Eliminar">
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
                                <i class="bi bi-inbox me-2"></i>No hay notas de compra registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($notas) && $notas->count())
            <div class="p-3 border-top">
                {{ $notas->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
