@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-tags me-2 text-gold-dark"></i>Catálogo: {{ $categoria->nombre }}
                </h2>
                <p class="mb-0 text-secondary">Productos pertenecientes a esta clasificación</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('categorias.index') }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver a categorías
                </a>
                @can('productos.create')
                    <a href="{{ route('productos.create') }}?categoria_id={{ $categoria->id }}" class="btn btn-gold-panaderia text-nowrap">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo Producto
                    </a>
                @endcan
            </div>
        </div>
    </x-card>

    <!-- Buscador Integrado -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('categorias.show', $categoria->id) }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label class="form-label fw-bold mb-2 text-main" style="font-size: 0.9rem;">
                        <i class="bi bi-search me-1"></i> Buscar en "{{ $categoria->nombre }}"
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Escribe el nombre del pan o producto..." value="{{ request('request') }}">
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="submit" class="btn btn-gold-panaderia w-100"><i class="bi bi-search"></i> Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-light-panaderia" title="Limpiar"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>

    <!-- Lista Detallada de Productos -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="bg-main border-bottom border-color">
                        <tr>
                            <th class="py-3 px-4 text-muted fw-semibold text-uppercase" style="font-size: 0.85rem;">Producto</th>
                            <th class="py-3 px-4 text-center text-muted fw-semibold text-uppercase" style="font-size: 0.85rem;">Métricas (Precio/Costo)</th>
                            <th class="py-3 px-4 text-center text-muted fw-semibold text-uppercase" style="font-size: 0.85rem;">Stock en Almacén</th>
                            <th class="py-3 px-4 text-center text-muted fw-semibold text-uppercase" style="font-size: 0.85rem;">Estado Venta</th>
                            <th class="py-3 px-4 text-end text-muted fw-semibold text-uppercase" style="font-size: 0.85rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($productos as $producto)
                        <tr class="border-bottom border-color" style="transition: background 0.2s;">
                            <!-- Detalle del Producto -->
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded d-flex align-items-center justify-content-center bg-main text-gold-dark border border-color" style="width: 40px; height: 40px;">
                                        <i class="bi bi-box-seam" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-main" style="font-size: 1rem;">
                                            {{ $producto->nombre }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Métricas Financieras Básicas -->
                            <td class="py-3 px-4 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold text-success" style="font-size: 0.95rem;">
                                        <i class="bi bi-tag-fill me-1" style="font-size: 0.8rem;"></i>${{ number_format($producto->precio_venta, 2) }}
                                    </span>
                                    <span class="text-muted mt-1" style="font-size: 0.8rem;" title="Costo de Producción">
                                       Costo: ${{ number_format($producto->precio_costo, 2) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Stock Lógico -->
                            <td class="py-3 px-4 text-center">
                                @if($producto->stock > $producto->stock_minimo)
                                    <x-badge type="success" style="border-radius: 6px;"><i class="bi bi-check-circle me-1"></i> {{ $producto->stock }} u.</x-badge>
                                @elseif($producto->stock > 0)
                                    <x-badge type="warning" style="border-radius: 6px;" title="Stock mínimo: {{ $producto->stock_minimo }}"><i class="bi bi-exclamation-triangle me-1"></i> {{ $producto->stock }} u. (Bajo)</x-badge>
                                @else
                                    <x-badge type="danger" style="border-radius: 6px;"><i class="bi bi-x-octagon me-1"></i> Agotado (0)</x-badge>
                                @endif
                                <div class="mt-1 text-muted" style="font-size: 0.75rem;">Mín. sugerido: {{ $producto->stock_minimo }}</div>
                            </td>

                            <!-- Estado del producto -->
                            <td class="py-3 px-4 text-center">
                                @if($producto->estado === 'activo')
                                    <x-badge type="success" style="border-radius: 6px;"><i class="bi bi-circle-fill me-1" style="font-size:0.6rem"></i>A la Venta</x-badge>
                                @else
                                    <x-badge type="secondary" style="border-radius: 6px;"><i class="bi bi-circle me-1" style="font-size:0.6rem"></i>Retirado</x-badge>
                                @endif
                            </td>

                            <!-- Botones de Acción -->
                            <td class="py-3 px-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    @can('productos.view')
                                        <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-sm btn-light-panaderia text-gold border border-color" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('productos.edit')
                                        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-light-panaderia text-main border border-color" title="Editar Producto">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5" style="color: var(--text-secondary);">
                                <div class="mb-4 mt-3">
                                    <i class="bi bi-box-seam text-muted" style="font-size: 3.5rem; opacity: 0.3;"></i>
                                </div>
                                <h5 class="fw-normal mb-1">No se encontraron productos</h5>
                                @if(request('search'))
                                    <p class="text-muted small">No hay resultados para "<strong>{{ request('search') }}</strong>".</p>
                                    <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-light-panaderia mt-3">Limpiar filtro</a>
                                @else
                                    <p class="text-muted small">Esta categoría aún no tiene panes o productos registrados.</p>
                                    @can('productos.create')
                                        <a href="{{ route('productos.create') }}?categoria_id={{ $categoria->id }}" class="btn btn-gold-panaderia mt-3 text-nowrap"><i class="bi bi-plus-lg me-1"></i>Crear producto en esta categoría</a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($productos->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top border-color pt-3">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $productos->firstItem() }}</span> a <span class="fw-bold">{{ $productos->lastItem() }}</span> de <span class="fw-bold">{{ $productos->total() }}</span> productos
                    </div>
                </div>
                
                {{-- Contenedor especial de paginación --}}
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $productos->links() }}
                </div>
            @endif
        </div>
    </x-card>
</div>


@endsection

@push('scripts')
    @vite(['resources/js/categorias.js'])
@endpush


