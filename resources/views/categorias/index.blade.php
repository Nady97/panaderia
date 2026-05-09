@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <i class="bi bi-tags text-[var(--gold-dark)]"></i> Gestion de Categorias
                </h2>
                <p class="text-sm text-[var(--text-muted)]">Organiza y clasifica tu catalogo de inventario</p>
            </div>
            <a href="{{ route('categorias.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]">
                <i class="bi bi-plus-circle"></i> Nueva Categoria
            </a>
        </div>
    </x-card>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="bi bi-tags text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $totalCategorias ?? 0 }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Total Categorias</p>
                </div>
            </div>
        </x-card>
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="bi bi-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $categoriasActivas ?? 0 }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Activas</p>
                </div>
            </div>
        </x-card>
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center">
                    <i class="bi bi-x-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $categoriasInactivas ?? 0 }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Inactivas</p>
                </div>
            </div>
        </x-card>
    </div>

    <x-card>
        <div class="p-4">
            <form action="{{ route('categorias.index') }}" method="GET" class="grid gap-4 md:grid-cols-12 items-end" id="serverFilterForm">
                <div class="md:col-span-6">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        <i class="bi bi-search mr-1"></i> Buscar categoria
                    </label>
                    <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                        <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-tag"></i></span>
                        <input type="text" name="search" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" placeholder="Nombre o descripcion..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Filtrar por Estado</label>
                    <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                        <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-funnel"></i></span>
                        <select name="status_filter" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" onchange="this.form.submit()">
                            <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>Todos los estados</option>
                            <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>En uso (Activas)</option>
                            <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Desactivadas</option>
                        </select>
                    </div>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[var(--btn-bg)] px-3 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]"><i class="bi bi-search"></i></button>
                    @if(request()->has('search') || request()->has('status_filter'))
                        <a href="{{ route('categorias.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-[var(--border-color)] px-3 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]" title="Limpiar"><i class="bi bi-x-circle"></i></a>
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
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Nombre y Detalle</th>
                        <th class="px-4 py-3 text-center">Items Asociados</th>
                        <th class="px-4 py-3 text-center">Estado Operativo</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)]">
                    @forelse($categorias as $categoria)
                        <tr>
                            <td class="px-4 py-3">{{ ($categorias->currentPage() - 1) * $categorias->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="block font-medium text-[var(--text-primary)]">{{ $categoria->nombre }}</span>
                                @if($categoria->descripcion)
                                    <span class="text-xs text-[var(--text-muted)]"><i class="bi bi-text-left mr-1"></i>{{ $categoria->descripcion }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if(isset($categoria->productos_count) && $categoria->productos_count > 0)
                                    <a href="{{ route('categorias.show', $categoria->id) }}" class="inline-flex items-center gap-1 rounded-lg border border-[var(--border-color)] px-2 py-1 text-xs text-[var(--text-primary)] hover:bg-[var(--bg-input)]">
                                        <i class="bi bi-box"></i>{{ $categoria->productos_count }} Productos
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-lg border border-[var(--border-color)] px-2 py-1 text-xs text-[var(--text-muted)]">Categoria vacia</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($categoria->activo)
                                    <x-badge type="success"><i class="bi bi-circle-fill mr-1 text-[0.5rem]"></i>Activa</x-badge>
                                @else
                                    <x-badge type="danger"><i class="bi bi-circle mr-1 text-[0.5rem]"></i>Inactiva</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('categorias.show', $categoria->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100" title="Ver Productos">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" title="Editar Categoria">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="form-delete" data-confirm-text="¿Esta seguro de que desea eliminar la categoria {{ $categoria->nombre }}? Productos huerfanos se quedaran sin categoria.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-700 hover:bg-red-100" title="Eliminar Categoria">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state
                                    icon="bi-tags"
                                    title="No se encontraron categorias"
                                    description="Ajusta los filtros o agrega una nueva familia de productos."
                                >
                                    @if(request()->has('search') || request()->has('status_filter'))
                                        <a href="{{ route('categorias.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)] mt-3">Limpiar filtros</a>
                                    @else
                                        <a href="{{ route('categorias.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)] mt-3">
                                            <i class="bi bi-plus-lg"></i> Crear primera Categoria
                                        </a>
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categorias->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[var(--border-color)] px-4 py-3 text-xs text-[var(--text-muted)]">
                <div>
                    Mostrando <span class="font-semibold text-[var(--text-primary)]">{{ $categorias->firstItem() }}</span> a <span class="font-semibold text-[var(--text-primary)]">{{ $categorias->lastItem() }}</span> de <span class="font-semibold text-[var(--text-primary)]">{{ $categorias->total() }}</span> registros
                </div>
            </div>
            <div class="px-4 pb-4">
                {{ $categorias->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/categorias.js'])
@endpush
