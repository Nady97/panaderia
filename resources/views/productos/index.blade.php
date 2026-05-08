@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Productos -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main"><i class="bi bi-box-seam me-2 text-gold"></i> Gestión de Productos</h2>
                <p class="mb-0 text-muted">Bienvenida, <strong>{{ auth()->user()->nombre ?? 'Usuario' }}</strong></p>
            </div>
            <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $productos->count() }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Total Productos</p>
                    </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ url('/productos/create') }}" class="btn btn-primary-panaderia text-nowrap">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Producto
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de resumen -->
    <div class="row g-3 mb-4">
        <!--
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 12px; color: var(--gold-dark);">
                        <i class="bi bi-box-seam fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $productos->count() }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Total Productos</p>
                    </div>
                </div>
            </x-card>
        </div>
    -->
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(16, 185, 129, 0.1); border-radius: 12px; color: var(--success);">
                        <i class="bi bi-check-circle fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $productos->where('estado', 'activo')->count() }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Productos Activos</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(245, 158, 11, 0.1); border-radius: 12px; color: #f59e0b;">
                        <i class="bi bi-exclamation-triangle fs-2"></i>
                    </div>
                    <div>
                        @php
                            $stockBajo = $productos->filter(function($p) {
                                $minimo = (float)$p->stock_minimo > 0 ? (float)$p->stock_minimo : 5;
                                return (float)$p->stock <= $minimo && (float)$p->stock > 0;
                            })->count();
                        @endphp
                        <h3 class="fw-bold mb-0 text-main">{{ $stockBajo }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Stock Bajo</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(220, 38, 38, 0.1); border-radius: 12px; color: var(--danger);">
                        <i class="bi bi-x-circle fs-2"></i>
                    </div>
                    <div>
                        @php
                            $agotados = $productos->where('stock', 0)->count();
                        @endphp
                        <h3 class="fw-bold mb-0 text-main">{{ $agotados }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Agotados</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" style="background: rgba(220, 38, 38, 0.1); border-radius: 12px; color: var(--danger);">
                        <i class="bi bi-x-circle fs-2"></i>
                    </div>
                    <div>
                        @php
                            $descontinuados = $productos->where('estado', 'descontinuado')->count();
                        @endphp
                        <h3 class="fw-bold mb-0 text-main">{{ $descontinuados }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Descontinuados</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-2" style="font-size: 0.9rem;" class="text-main">
                        <i class="bi bi-search me-1"></i> Filtrar Productos
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-box"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Escribe el nombre del producto...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2" style="font-size: 0.9rem;" class="text-main">Filtrar por Stock</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select id="filterStock" class="form-select">
                            <option value="all">Todos los productos</option>
                            <option value="active">Solo activos</option>
                            <option value="low">Stock bajo</option>
                            <option value="out">Agotados</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="button" class="btn btn-gold-panaderia w-100" id="searchBtn"><i class="bi bi-search"></i></button>
                    <button type="button" class="btn btn-light-panaderia w-100" onclick="location.reload()" title="Refrescar"><i class="bi bi-arrow-repeat"></i></button>
                </div>
            </form>
        </div>
    </x-card>

    <!-- Tabla Principal de Productos -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                <thead class="border-bottom-modern border-2">
                    <tr>
                        <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">#</th>
                        <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Producto</th>
                        <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Precio</th>
                        <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Stock</th>
                        <th class="py-3 px-4 text-center" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Estado</th>
                        <th class="py-3 px-4 text-end" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;" class="text-muted">Acciones</th>
                    </tr>
                </thead>
                <tbody >
                    @forelse($productos as $index => $producto)
                    <tr class="producto-fila" class="border-bottom-modern" style="transition: background 0.2s;"
                        data-stock="{{ $producto->stock }}" 
                        data-nombre="{{ strtolower($producto->nombre) }}" data-precio="{{ $producto->precio_venta }}" data-itera="{{ $loop->iteration }}"
                        data-estado="{{ $producto->estado }}">
                        
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">
                            <span class="fw-medium text-main">
                                {{ $producto->nombre }}
                            </span>
                        </td>
                        <td class="py-3 px-4">Bs {{ number_format($producto->precio_venta, 2) }}</td>
                        <td class="py-3 px-4">
                            @php
                                $minimo = (float)$producto->stock_minimo > 0 ? (float)$producto->stock_minimo : 5;
                            @endphp
                            <!-- Estado de Stock con Componente Badge -->
                            @if((float)$producto->stock <= 0)
                                <x-badge type="danger" title="Mínimo: {{ $minimo }} uds"><i class="bi bi-x-circle me-1"></i> {{ $producto->stock }}</x-badge>
                            @elseif((float)$producto->stock <= $minimo)
                                <x-badge type="warning" title="Mínimo: {{ $minimo }} uds"><i class="bi bi-exclamation-triangle me-1"></i> {{ $producto->stock }}</x-badge>
                            @else
                                <x-badge type="success" title="Mínimo: {{ $minimo }} uds"><i class="bi bi-check-circle me-1"></i> {{ $producto->stock }}</x-badge>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($producto->estado == 'activo')
                                <x-badge type="success">Activo</x-badge>
                            @elseif($producto->estado == 'agotado')
                                <x-badge type="danger">Agotado</x-badge>
                            @else
                                <x-badge type="secondary">Descontinuado</x-badge>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-sm action-btn action-btn-success" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm action-btn action-btn-info" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline form-delete" data-confirm-text="¿Está seguro de que desea eliminar el producto {{$producto->nombre}}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm action-btn action-btn-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-0 border-0">
                            <x-empty-state 
                                icon="bi-box-seam" 
                                title="No hay productos registrados" 
                                description="Aún no tienes productos en tu catálogo de la panadería. Empieza a registrar tus productos aquí."
                                buttonLabel="Agregar primer producto"
                                :buttonRoute="url('/productos/create')" 
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </x-card>
</div>


@endsection

@push('scripts')
    @vite(['resources/js/productos.js'])
@endpush




