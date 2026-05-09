@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <i class="bi bi-tags text-[var(--gold-dark)]"></i> Catalogo: {{ $categoria->nombre }}
                </h2>
                <p class="text-sm text-[var(--text-secondary)]">Productos pertenecientes a esta clasificacion</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('categorias.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]">
                    <i class="bi bi-arrow-left"></i> Volver a categorias
                </a>
                <a href="{{ route('productos.create') }}?categoria_id={{ $categoria->id }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]">
                    <i class="bi bi-plus-circle"></i> Nuevo Producto
                </a>
            </div>
        </div>
    </x-card>

    <x-card>
        <div class="p-4">
            <form action="{{ route('categorias.show', $categoria->id) }}" method="GET" class="grid gap-4 md:grid-cols-12 items-end">
                <div class="md:col-span-10">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        <i class="bi bi-search mr-1"></i> Buscar en "{{ $categoria->nombre }}"
                    </label>
                    <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                        <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-box-seam"></i></span>
                        <input type="text" name="search" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" placeholder="Escribe el nombre del pan o producto..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[var(--btn-bg)] px-3 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]"><i class="bi bi-search"></i> Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('categorias.show', $categoria->id) }}" class="inline-flex w-full items-center justify-center rounded-xl border border-[var(--border-color)] px-3 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]" title="Limpiar"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-[var(--text-primary)]">
                <thead class="border-b border-[var(--border-color)] text-xs uppercase tracking-wide text-[var(--text-muted)]">
                    <tr>
                        <th class="px-4 py-3 text-left">Producto</th>
                        <th class="px-4 py-3 text-center">Metricas</th>
                        <th class="px-4 py-3 text-center">Stock</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)]">
                    @forelse($productos as $producto)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-[var(--bg-input)] text-[var(--gold-dark)] flex items-center justify-center border border-[var(--border-color)]">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="font-semibold">{{ $producto->nombre }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="font-semibold text-green-600"><i class="bi bi-tag-fill mr-1"></i>${{ number_format($producto->precio_venta, 2) }}</span>
                                    <span class="text-xs text-[var(--text-muted)]">Costo: ${{ number_format($producto->precio_costo, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($producto->stock > $producto->stock_minimo)
                                    <x-badge type="success"><i class="bi bi-check-circle mr-1"></i>{{ $producto->stock }} u.</x-badge>
                                @elseif($producto->stock > 0)
                                    <x-badge type="warning" title="Stock minimo: {{ $producto->stock_minimo }}"><i class="bi bi-exclamation-triangle mr-1"></i>{{ $producto->stock }} u.</x-badge>
                                @else
                                    <x-badge type="danger"><i class="bi bi-x-octagon mr-1"></i>Agotado</x-badge>
                                @endif
                                <div class="text-xs text-[var(--text-muted)] mt-1">Min. sugerido: {{ $producto->stock_minimo }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($producto->estado === 'activo')
                                    <x-badge type="success"><i class="bi bi-circle-fill mr-1 text-[0.5rem]"></i>A la Venta</x-badge>
                                @else
                                    <x-badge type="secondary"><i class="bi bi-circle mr-1 text-[0.5rem]"></i>Retirado</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('productos.show', $producto->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100" title="Ver Detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" title="Editar Producto">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state
                                    icon="bi-box-seam"
                                    title="No se encontraron productos"
                                    description="Esta categoria aun no tiene productos registrados."
                                >
                                    @if(request('search'))
                                        <a href="{{ route('categorias.show', $categoria->id) }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)] mt-3">Limpiar filtro</a>
                                    @else
                                        <a href="{{ route('productos.create') }}?categoria_id={{ $categoria->id }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)] mt-3">
                                            <i class="bi bi-plus-lg"></i> Crear producto en esta categoria
                                        </a>
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($productos->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[var(--border-color)] px-4 py-3 text-xs text-[var(--text-muted)]">
                <div>
                    Mostrando <span class="font-semibold text-[var(--text-primary)]">{{ $productos->firstItem() }}</span> a <span class="font-semibold text-[var(--text-primary)]">{{ $productos->lastItem() }}</span> de <span class="font-semibold text-[var(--text-primary)]">{{ $productos->total() }}</span> productos
                </div>
            </div>
            <div class="px-4 pb-4">
                {{ $productos->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/categorias.js'])
@endpush
