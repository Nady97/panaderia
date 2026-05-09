@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <i class="bi bi-journal-text text-[var(--gold-dark)]"></i> Recetas Registradas
                </h2>
                <p class="text-sm text-[var(--text-muted)]">Gestiona formulaciones y costos de produccion</p>
            </div>
            <a href="{{ route('recetas.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]">
                <i class="bi bi-plus-circle"></i> Nueva Receta
            </a>
        </div>
    </x-card>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="bi bi-journal-check text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $totalRecetas ?? 0 }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Recetas</p>
                </div>
            </div>
        </x-card>
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="bi bi-star text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $recetasActivas ?? 0 }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Activas</p>
                </div>
            </div>
        </x-card>
        <x-card class="h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center">
                    <i class="bi bi-pause-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-[var(--text-primary)]">{{ $recetasInactivas ?? 0 }}</p>
                    <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Inactivas</p>
                </div>
            </div>
        </x-card>
    </div>

    <x-card>
        <div class="p-4">
            <form action="{{ route('recetas.index') }}" method="GET" class="grid gap-4 md:grid-cols-12 items-end">
                <div class="md:col-span-6">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        <i class="bi bi-search mr-1"></i> Buscar receta
                    </label>
                    <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                        <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-journal"></i></span>
                        <input type="text" name="search" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" placeholder="Nombre del producto..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Filtrar por Estado</label>
                    <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                        <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-funnel"></i></span>
                        <select name="status_filter" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" onchange="this.form.submit()">
                            <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>Todos los estados</option>
                            <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Activas</option>
                            <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactivas</option>
                        </select>
                    </div>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[var(--btn-bg)] px-3 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]"><i class="bi bi-search"></i></button>
                    @if(request()->has('search') || request()->has('status_filter'))
                        <a href="{{ route('recetas.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-[var(--border-color)] px-3 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]" title="Limpiar"><i class="bi bi-x-circle"></i></a>
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
                        <th class="px-4 py-3 text-center">Ingredientes</th>
                        <th class="px-4 py-3 text-center">Costo Estimado</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)]">
                    @forelse($recetas as $receta)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-[var(--bg-input)] text-[var(--gold-dark)] flex items-center justify-center border border-[var(--border-color)]">
                                        <i class="bi bi-bag"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold">{{ $receta->producto->nombre ?? 'Sin producto' }}</div>
                                        <div class="text-xs text-[var(--text-muted)]">Rinde: {{ $receta->rendimiento ?? 'N/D' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 rounded-lg border border-[var(--border-color)] px-2 py-1 text-xs text-[var(--text-primary)]">
                                    <i class="bi bi-list-check"></i>{{ $receta->insumos->count() ?? 0 }} items
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold text-green-600">${{ number_format($receta->costo_total ?? 0, 2) }}</span>
                                <div class="text-xs text-[var(--text-muted)]">Costo unitario estimado</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($receta->activa)
                                    <x-badge type="success"><i class="bi bi-circle-fill mr-1 text-[0.5rem]"></i>Activa</x-badge>
                                @else
                                    <x-badge type="secondary"><i class="bi bi-circle mr-1 text-[0.5rem]"></i>Inactiva</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('recetas.show', $receta->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100" title="Ver Receta">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('recetas.edit', $receta->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" title="Editar Receta">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('recetas.destroy', $receta->id) }}" method="POST" class="form-delete" data-confirm-text="¿Desea eliminar la receta para {{ $receta->producto->nombre ?? 'este producto' }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-700 hover:bg-red-100" title="Eliminar Receta">
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
                                    icon="bi-journal-text"
                                    title="No hay recetas disponibles"
                                    description="Registra la primera receta para controlar costos."
                                >
                                    @if(request()->has('search') || request()->has('status_filter'))
                                        <a href="{{ route('recetas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)] mt-3">Limpiar filtros</a>
                                    @else
                                        <a href="{{ route('recetas.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)] mt-3">
                                            <i class="bi bi-plus-lg"></i> Registrar receta
                                        </a>
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recetas->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[var(--border-color)] px-4 py-3 text-xs text-[var(--text-muted)]">
                <div>
                    Mostrando <span class="font-semibold text-[var(--text-primary)]">{{ $recetas->firstItem() }}</span> a <span class="font-semibold text-[var(--text-primary)]">{{ $recetas->lastItem() }}</span> de <span class="font-semibold text-[var(--text-primary)]">{{ $recetas->total() }}</span> recetas
                </div>
            </div>
            <div class="px-4 pb-4">
                {{ $recetas->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/recetas.js'])
@endpush
