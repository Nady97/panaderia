@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="bi bi-journal-plus text-[var(--gold-dark)]"></i> Nueva Receta
                    </h2>
                    <x-badge type="primary" class="px-3">Registro</x-badge>
                </div>
                <p class="text-sm text-[var(--text-muted)]">Define ingredientes y cantidades para produccion</p>
            </div>
            <a href="{{ route('recetas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>
    </x-card>

    <x-card>
        <div class="p-4 md:p-6 space-y-6">
            @if ($errors->any())
                <x-alert type="error">
                    <div class="space-y-2">
                        <p class="font-semibold">Se encontraron los siguientes problemas:</p>
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </x-alert>
            @endif

            <form action="{{ route('recetas.store') }}" method="POST" id="formCrearReceta" class="space-y-6">
                @csrf

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="bi bi-box-seam text-[var(--text-muted)]"></i> Producto Asociado
                    </h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Producto Final <span class="text-red-600">*</span></label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-bag"></i></span>
                            <select name="producto_id" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" required>
                                <option value="">Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('producto_id')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Rendimiento Esperado</label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-123"></i></span>
                            <input type="number" name="rendimiento" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" value="{{ old('rendimiento') }}" placeholder="Ej: 24">
                        </div>
                        <p class="mt-1 text-xs text-[var(--text-muted)]"><i class="bi bi-lightbulb mr-1"></i> Cantidad total producida por receta.</p>
                        @error('rendimiento')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2 border-t border-[var(--border-color)] pt-4">
                        <i class="bi bi-list-check text-[var(--text-muted)]"></i> Ingredientes Principales
                    </h5>
                </div>

                <div class="space-y-3" id="ingredientesContainer">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ingrediente-item">
                        <div>
                            <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Ingrediente <span class="text-red-600">*</span></label>
                            <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                                <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-bag"></i></span>
                                <select name="insumos[]" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" required>
                                    <option value="">Seleccione un insumo</option>
                                    @foreach($insumos as $insumo)
                                        <option value="{{ $insumo->id }}" {{ (old('insumos.0') == $insumo->id) ? 'selected' : '' }}>{{ $insumo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Cantidad <span class="text-red-600">*</span></label>
                            <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                                <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-123"></i></span>
                                <input type="number" name="cantidades[]" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" step="0.01" placeholder="Cantidad" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Unidad de Medida</label>
                            <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                                <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-rulers"></i></span>
                                <select name="unidades[]" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" required>
                                    <option value="">Seleccione unidad</option>
                                    @foreach($unidades as $unidad)
                                        <option value="{{ $unidad }}" {{ (old('unidades.0') == $unidad) ? 'selected' : '' }}>{{ $unidad }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-dashed border-[var(--border-color)] bg-[var(--bg-input)] px-4 py-3">
                    <div class="text-sm text-[var(--text-muted)]">
                        <i class="bi bi-plus-circle mr-1"></i> Agregue mas ingredientes si es necesario.
                    </div>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-3 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-white" onclick="agregarIngrediente()">
                        <i class="bi bi-plus-lg"></i> Agregar
                    </button>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2 border-t border-[var(--border-color)] pt-4">
                        <i class="bi bi-power text-[var(--text-muted)]"></i> Estado de la Receta
                    </h5>
                </div>

                <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-semibold text-[var(--text-secondary)] flex items-center gap-2" for="activa">
                            <i class="bi bi-toggle-on text-[var(--text-muted)]"></i> Receta activa
                        </label>
                        <input class="h-5 w-5 accent-[var(--btn-bg)]" type="checkbox" id="activa" name="activa" value="1" checked>
                    </div>
                    <p class="mt-2 text-xs text-[var(--text-muted)]"><i class="bi bi-info-circle mr-1"></i>Si la receta esta inactiva no aparecera en produccion.</p>
                </div>

                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]" onclick="window.history.back()">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]">
                        <i class="bi bi-cloud-arrow-up"></i> Guardar Receta
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/recetas.js'])
@endpush
