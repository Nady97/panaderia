@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="bi bi-box-seam text-[var(--gold-dark)]"></i> Registro de Producto
                    </h2>
                    <x-badge type="primary" class="px-3">Nuevo Registro</x-badge>
                </div>
                <p class="text-sm text-[var(--text-secondary)]">Agregue un nuevo pan o articulo al catalogo de operaciones</p>
            </div>
            <a href="{{ route('productos.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]">
                <i class="bi bi-arrow-left"></i> Regresar al Directorio
            </a>
        </div>
    </x-card>

    <x-card>
        <div class="p-4 md:p-6 space-y-6">
            @if ($errors->any())
                <x-alert type="error">
                    <div class="space-y-2">
                        <p class="font-semibold">Se encontraron problemas en la validacion:</p>
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </x-alert>
            @endif

            <form method="POST" action="{{ route('productos.store') }}" id="formCrearProducto" class="space-y-6">
                @csrf

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="bi bi-info-circle text-[var(--text-muted)]"></i> Identificacion y Clasificacion
                    </h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                            Nombre del Pan o Producto <span class="text-red-600">*</span>
                        </label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-cup-hot"></i></span>
                            <input type="text" name="nombre" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" required value="{{ old('nombre') }}" placeholder="Ej: Pan Frances Molde..." autocomplete="off">
                        </div>
                        @error('nombre')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Categoria</label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-tags"></i></span>
                            <select name="categoria_id" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none">
                                <option value="">-- Seleccionar Categoria (Opcional) --</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', request('categoria_id')) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('categoria_id')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2 border-t border-[var(--border-color)] pt-4">
                        <i class="bi bi-currency-dollar text-[var(--text-muted)]"></i> Costos y Valoracion
                    </h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Precio de Produccion (Costo)</label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]">Bs.</span>
                            <input type="number" step="0.01" name="precio_costo" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" value="{{ old('precio_costo') }}" placeholder="0.00">
                        </div>
                        @error('precio_costo')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Precio Publico (Venta) <span class="text-red-600">*</span></label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-green-600">Bs.</span>
                            <input type="number" step="0.01" name="precio_venta" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" required value="{{ old('precio_venta') }}" placeholder="0.00">
                        </div>
                        @error('precio_venta')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2 border-t border-[var(--border-color)] pt-4">
                        <i class="bi bi-truck text-[var(--text-muted)]"></i> Operativa de Almacen
                    </h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Stock Fisico Inicial <span class="text-red-600">*</span></label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-boxes"></i></span>
                            <input type="number" step="0.01" name="stock" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" required value="{{ old('stock') }}" placeholder="Unidades actuales">
                        </div>
                        @error('stock')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Alerta de Stock Minimo</label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-yellow-600"><i class="bi bi-exclamation-triangle"></i></span>
                            <input type="number" step="0.01" name="stock_minimo" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" value="{{ old('stock_minimo', 5) }}">
                        </div>
                        <p class="mt-1 text-xs text-[var(--text-muted)]"><i class="bi bi-lightbulb mr-1"></i> Notificara cuando queden pocas unidades.</p>
                        @error('stock_minimo')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]" onclick="window.history.back()">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[var(--btn-bg)] px-4 py-2 text-sm font-semibold text-[var(--btn-text)] hover:bg-[var(--btn-hover)]">
                        <i class="bi bi-cloud-arrow-up"></i> Guardar e Ingresar Producto
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/productos.js'])
@endpush
