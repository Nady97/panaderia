@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <i class="bi bi-journal-text text-[var(--gold-dark)]"></i> Receta: {{ $receta->producto->nombre ?? 'Producto' }}
                </h2>
                <p class="text-sm text-[var(--text-muted)]">Detalles de formulacion y costos estimados</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('recetas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]">
                    <i class="bi bi-arrow-left"></i> Volver a recetas
                </a>
                <a href="{{ route('recetas.edit', $receta->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-yellow-500 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-600">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            </div>
        </div>
    </x-card>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <x-card class="lg:col-span-1">
            <div class="p-4 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-[var(--bg-input)] text-[var(--gold-dark)] flex items-center justify-center border border-[var(--border-color)]">
                        <i class="bi bi-bag"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Producto</p>
                        <p class="text-lg font-semibold text-[var(--text-primary)]">{{ $receta->producto->nombre ?? 'Sin producto' }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm text-[var(--text-secondary)]">
                    <div class="flex items-center justify-between">
                        <span>Rendimiento</span>
                        <span class="font-semibold text-[var(--text-primary)]">{{ $receta->rendimiento ?? 'N/D' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Costo Total</span>
                        <span class="font-semibold text-green-600">${{ number_format($receta->costo_total ?? 0, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Costo Unitario</span>
                        <span class="font-semibold text-[var(--text-primary)]">${{ number_format($receta->costo_unitario ?? 0, 2) }}</span>
                    </div>
                </div>

                <div>
                    @if($receta->activa)
                        <x-badge type="success"><i class="bi bi-circle-fill mr-1 text-[0.5rem]"></i>Receta Activa</x-badge>
                    @else
                        <x-badge type="secondary"><i class="bi bi-circle mr-1 text-[0.5rem]"></i>Receta Inactiva</x-badge>
                    @endif
                </div>

                <div class="rounded-xl border border-dashed border-[var(--border-color)] bg-[var(--bg-input)] p-4 text-xs text-[var(--text-muted)]">
                    <i class="bi bi-info-circle mr-1"></i> Estos valores se recalculan segun los insumos registrados.
                </div>
            </div>
        </x-card>

        <x-card class="lg:col-span-2">
            <div class="p-4">
                <h3 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2 mb-4">
                    <i class="bi bi-list-check text-[var(--text-muted)]"></i> Ingredientes y Cantidades
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-[var(--text-primary)]">
                        <thead class="border-b border-[var(--border-color)] text-xs uppercase tracking-wide text-[var(--text-muted)]">
                            <tr>
                                <th class="px-4 py-3 text-left">Insumo</th>
                                <th class="px-4 py-3 text-center">Cantidad</th>
                                <th class="px-4 py-3 text-center">Unidad</th>
                                <th class="px-4 py-3 text-right">Costo Estimado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)]">
                            @forelse($receta->insumos as $insumo)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-lg bg-[var(--bg-input)] text-[var(--gold-dark)] flex items-center justify-center border border-[var(--border-color)]">
                                                <i class="bi bi-box"></i>
                                            </div>
                                            <span class="font-semibold">{{ $insumo->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ $insumo->pivot->cantidad }}</td>
                                    <td class="px-4 py-3 text-center">{{ $insumo->pivot->unidad }}</td>
                                    <td class="px-4 py-3 text-right text-green-600 font-semibold">${{ number_format($insumo->pivot->costo_total ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <x-empty-state
                                            icon="bi-basket"
                                            title="Sin ingredientes"
                                            description="Agrega insumos para calcular el costo de la receta."
                                        >
                                            <a href="{{ route('recetas.edit', $receta->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)] mt-3">
                                                <i class="bi bi-pencil"></i> Editar receta
                                            </a>
                                        </x-empty-state>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/recetas.js'])
@endpush
