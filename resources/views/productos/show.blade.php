@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-full bg-[var(--bg-input)] text-[var(--gold-dark)] flex items-center justify-center">
                    <i class="bi bi-box-seam text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-[var(--text-primary)]">Detalle del Producto</h2>
                    <p class="text-sm text-[var(--text-secondary)]">Vista detallada del inventario</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('productos.edit', $producto->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]">
                    <i class="bi bi-pencil-square"></i> Editar
                </a>
                <a href="{{ route('productos.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]">
                    <i class="bi bi-arrow-left"></i> Volver a la Lista
                </a>
            </div>
        </div>
    </x-card>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <div class="lg:col-span-8">
            <x-card class="h-full">
                <div class="p-4 space-y-6">
                    <h5 class="text-lg font-bold text-[var(--text-primary)] border-b border-[var(--border-color)] pb-3 flex items-center gap-2">
                        <i class="bi bi-info-circle text-[var(--gold-dark)]"></i> Informacion Principal
                    </h5>

                    <div class="space-y-4">
                        <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                            <p class="text-xs uppercase tracking-wide text-[var(--text-muted)] mb-1">Nombre del Producto</p>
                            <p class="text-lg font-bold text-[var(--text-primary)]">{{ $producto->nombre }}</p>
                        </div>
                        <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                            <p class="text-xs uppercase tracking-wide text-[var(--text-muted)] mb-1">Descripcion</p>
                            <p class="text-sm text-[var(--text-secondary)]">{{ $producto->descripcion ?? 'Sin descripcion adicional para este producto.' }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                                <p class="text-xs uppercase tracking-wide text-[var(--text-muted)] mb-1">Estado</p>
                                @if($producto->estado === 'activo')
                                    <x-badge type="success"><i class="bi bi-check-circle mr-1"></i>Activo</x-badge>
                                @elseif($producto->estado === 'agotado')
                                    <x-badge type="danger"><i class="bi bi-x-circle mr-1"></i>Agotado</x-badge>
                                @else
                                    <x-badge type="secondary"><i class="bi bi-dash-circle mr-1"></i>Descontinuado</x-badge>
                                @endif
                            </div>
                            <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                                <p class="text-xs uppercase tracking-wide text-[var(--text-muted)] mb-1">Categoria</p>
                                <p class="text-sm font-semibold text-[var(--text-primary)]"><i class="bi bi-tag-fill text-[var(--gold-dark)] mr-1"></i>{{ $producto->categoria ? $producto->categoria->nombre : 'Sin Categoria' }}</p>
                            </div>
                            <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                                <p class="text-xs uppercase tracking-wide text-[var(--text-muted)] mb-1">Tipo de Origen</p>
                                @if($producto->es_producido)
                                    <p class="text-sm font-semibold text-[var(--gold-dark)]"><i class="bi bi-tools mr-1"></i>Produccion Propia</p>
                                @else
                                    <p class="text-sm font-semibold text-[var(--gold-light)]"><i class="bi bi-box-arrow-in-down mr-1"></i>Comprado/Revendido</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5 class="text-lg font-bold text-[var(--text-primary)] border-b border-[var(--border-color)] pb-3 flex items-center gap-2">
                        <i class="bi bi-currency-dollar text-[var(--gold-dark)]"></i> Informacion de Precios
                    </h5>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4 text-center">
                            <p class="text-xs uppercase tracking-wide text-[var(--text-muted)] mb-2">Precio de Venta</p>
                            <p class="text-2xl font-bold text-green-600">${{ number_format($producto->precio_venta, 2) }}</p>
                        </div>
                        <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4 text-center">
                            <p class="text-xs uppercase tracking-wide text-[var(--text-muted)] mb-2">Costo Base</p>
                            <p class="text-xl font-bold text-[var(--text-primary)]">${{ number_format($producto->precio_costo, 2) }}</p>
                            <span class="inline-flex items-center gap-1 rounded-full border border-green-200 bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 mt-2">
                                <i class="bi bi-graph-up-arrow"></i> Margen bruto: ${{ number_format($producto->precio_venta - $producto->precio_costo, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-4">
            <x-card class="h-full">
                <div class="p-4 space-y-6">
                    <div class="text-center border-b border-[var(--border-color)] pb-4">
                        <div class="mx-auto mb-3 h-28 w-28 rounded-full bg-[var(--bg-input)] border-4 border-[var(--bg-card)] shadow-[0_0_0_2px_var(--gold-dark)] flex items-center justify-center overflow-hidden">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
                            @else
                                <i class="bi bi-image text-3xl text-[var(--gold-dark)]"></i>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-[var(--text-primary)]">Imagen del Producto</p>
                    </div>

                    <div>
                        <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2">
                            <i class="bi bi-box text-[var(--gold-dark)]"></i> Control de Inventario
                        </h5>
                        <div class="mt-3 rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs uppercase tracking-wide text-[var(--text-muted)]">Stock Actual</span>
                                <span class="text-lg font-bold text-[var(--text-primary)]">{{ $producto->stock }} und.</span>
                            </div>
                            @php
                                $minimo = (float)$producto->stock_minimo > 0 ? (float)$producto->stock_minimo : 5;
                                $stockPercent = min(100, ($producto->stock / ($minimo * 3)) * 100);
                                $stockColor = $producto->stock <= 0 ? '#dc2626' : ($producto->stock <= $minimo ? '#f59e0b' : '#16a34a');
                            @endphp
                            <div class="h-2 w-full rounded-full bg-[var(--bg-card)] overflow-hidden">
                                <div class="h-full" style="width: {{ $stockPercent }}%; background-color: {{ $stockColor }};"></div>
                            </div>
                            @if($producto->stock <= 0)
                                <p class="mt-2 text-xs text-red-600"><i class="bi bi-x-circle-fill mr-1"></i> Producto agotado.</p>
                            @elseif($producto->stock <= $minimo)
                                <p class="mt-2 text-xs text-yellow-600"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Stock en nivel critico (Debajo de {{ $minimo }}).</p>
                            @else
                                <p class="mt-2 text-xs text-green-600"><i class="bi bi-check-circle-fill mr-1"></i> Stock en niveles saludables.</p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-center justify-between border-b border-[var(--border-color)] pb-2">
                                <span class="text-[var(--text-muted)]"><i class="bi bi-flag mr-1 text-[var(--gold-dark)]"></i>Stock Minimo</span>
                                <span class="font-semibold text-[var(--text-primary)]">{{ $producto->stock_minimo }} und.</span>
                            </li>
                            <li class="flex items-center justify-between border-b border-[var(--border-color)] pb-2">
                                <span class="text-[var(--text-muted)]"><i class="bi bi-clock-history mr-1 text-[var(--gold-dark)]"></i>Creado</span>
                                <span class="text-[var(--text-secondary)]">{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-[var(--text-muted)]"><i class="bi bi-arrow-repeat mr-1 text-[var(--gold-dark)]"></i>Actualizado</span>
                                <span class="text-[var(--text-secondary)]">{{ $producto->updated_at ? $producto->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/productos.js'])
@endpush
