@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <i class="bi bi-box-seam text-[var(--gold-dark)]"></i> Gestion de Productos
                </h2>
                <p class="text-sm text-[var(--text-muted)]">Bienvenida, <strong>{{ auth()->user()->nombre ?? 'Usuario' }}</strong></p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $productos->count() }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Total Productos</p>
                </div>
                <a href="{{ url('/productos/create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2.5 text-sm font-semibold text-[var(--btn-text)] transition hover:bg-[var(--btn-hover)]">
                    <i class="bi bi-plus-circle"></i> Nuevo Producto
                </a>
            </div>
        </div>
    </x-card>

    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="bi bi-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $productos->where('estado', 'activo')->count() }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Productos Activos</p>
                </div>
            </div>
        </x-card>
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    @php
                        $stockBajo = $productos->filter(function($p) {
                            $minimo = (float)$p->stock_minimo > 0 ? (float)$p->stock_minimo : 5;
                            return (float)$p->stock <= $minimo && (float)$p->stock > 0;
                        })->count();
                    @endphp
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $stockBajo }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Stock Bajo</p>
                </div>
            </div>
        </x-card>
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="bi bi-x-circle text-xl"></i>
                </div>
                <div>
                    @php
                        $agotados = $productos->where('stock', 0)->count();
                    @endphp
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $agotados }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Agotados</p>
                </div>
            </div>
        </x-card>
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="bi bi-x-circle text-xl"></i>
                </div>
                <div>
                    @php
                        $descontinuados = $productos->where('estado', 'descontinuado')->count();
                    @endphp
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $descontinuados }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Descontinuados</p>
                </div>
            </div>
        </x-card>
    </div>

    <x-card>
        <div class="p-4">
            <form class="grid gap-4 md:grid-cols-12 items-end" id="filterForm">
                <div class="md:col-span-6">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        <i class="bi bi-search mr-1"></i> Filtrar Productos
                    </label>
                    <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                        <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-box"></i></span>
                        <input type="text" id="searchInput" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" placeholder="Escribe el nombre del producto...">
                    </div>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Filtrar por Stock</label>
                    <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                        <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-funnel"></i></span>
                        <select id="filterStock" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none">
                            <option value="all">Todos los productos</option>
                            <option value="active">Solo activos</option>
                            <option value="low">Stock bajo</option>
                            <option value="out">Agotados</option>
                        </select>
                    </div>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="button" class="inline-flex w-full items-center justify-center rounded-xl bg-[var(--btn-bg)] px-3 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]" id="searchBtn"><i class="bi bi-search"></i></button>
                    <button type="button" class="inline-flex w-full items-center justify-center rounded-xl border border-[var(--border-color)] px-3 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]" onclick="location.reload()" title="Refrescar"><i class="bi bi-arrow-repeat"></i></button>
                </div>
            </form>
        </div>
    </x-card>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-[var(--text-primary)]">
                <thead class="border-b border-[var(--border-color)] text-xs uppercase tracking-wide text-[var(--text-muted)]">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Producto</th>
                        <th class="px-4 py-3 text-left">Precio</th>
                        <th class="px-4 py-3 text-left">Stock</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)]">
                    @forelse($productos as $producto)
                        <tr class="producto-fila" data-stock="{{ $producto->stock }}" data-nombre="{{ strtolower($producto->nombre) }}" data-precio="{{ $producto->precio_venta }}" data-itera="{{ $loop->iteration }}" data-estado="{{ $producto->estado }}">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium">{{ $producto->nombre }}</td>
                            <td class="px-4 py-3">Bs {{ number_format($producto->precio_venta, 2) }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $minimo = (float)$producto->stock_minimo > 0 ? (float)$producto->stock_minimo : 5;
                                @endphp
                                @if((float)$producto->stock <= 0)
                                    <x-badge type="danger" title="Minimo: {{ $minimo }} uds"><i class="bi bi-x-circle mr-1"></i> {{ $producto->stock }}</x-badge>
                                @elseif((float)$producto->stock <= $minimo)
                                    <x-badge type="warning" title="Minimo: {{ $minimo }} uds"><i class="bi bi-exclamation-triangle mr-1"></i> {{ $producto->stock }}</x-badge>
                                @else
                                    <x-badge type="success" title="Minimo: {{ $minimo }} uds"><i class="bi bi-check-circle mr-1"></i> {{ $producto->stock }}</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($producto->estado == 'activo')
                                    <x-badge type="success">Activo</x-badge>
                                @elseif($producto->estado == 'agotado')
                                    <x-badge type="danger">Agotado</x-badge>
                                @else
                                    <x-badge type="secondary">Descontinuado</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('productos.show', $producto->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-700 hover:bg-green-100" title="Ver Detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="form-delete" data-confirm-text="¿Esta seguro de que desea eliminar el producto {{ $producto->nombre }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-700 hover:bg-red-100" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-empty-state
                                    icon="bi-box-seam"
                                    title="No hay productos registrados"
                                    description="Aun no tienes productos en tu catalogo de panaderia."
                                    buttonLabel="Agregar primer producto"
                                    :buttonRoute="url('/productos/create')"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/productos.js'])
@endpush
