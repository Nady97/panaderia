@extends('layouts.app')

@section('content')
<div class="space-y-5">
    <x-card>
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="bi bi-pencil-square text-[var(--gold-dark)]"></i> Editar Categoria
                    </h2>
                    <x-badge type="warning" class="px-3">Modo Edicion</x-badge>
                </div>
                <p class="text-sm text-[var(--text-muted)]">Actualiza los datos de "{{ $categoria->nombre }}"</p>
            </div>
            <a href="{{ route('categorias.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]">
                <i class="bi bi-arrow-left"></i> Regresar al Directorio
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

            <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" id="formEditarCategoria" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2">
                        <i class="bi bi-info-circle text-[var(--text-muted)]"></i> Informacion Basica
                    </h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Nombre de la Coleccion <span class="text-red-600">*</span></label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-fonts"></i></span>
                            <input type="text" name="nombre" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" value="{{ old('nombre', $categoria->nombre) }}" required autocomplete="off">
                        </div>
                        @error('nombre')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">URL Amigable</label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-muted)]"><i class="bi bi-link-45deg"></i></span>
                            <input type="text" name="slug" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" value="{{ old('slug', $categoria->slug) }}">
                        </div>
                        @error('slug')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-[var(--text-primary)] flex items-center gap-2 border-t border-[var(--border-color)] pt-4">
                        <i class="bi bi-justify-left text-[var(--text-muted)]"></i> Detalles Extendidos
                    </h5>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Descripcion o Notas Adicionales</label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-start px-3 pt-2 text-[var(--text-muted)]"><i class="bi bi-card-text"></i></span>
                            <textarea name="descripcion" class="w-full bg-transparent px-3 py-2 text-sm text-[var(--text-primary)] outline-none" rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                        </div>
                        @error('descripcion')
                            <p class="mt-1 text-xs text-red-600"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)] p-4">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-[var(--text-secondary)] flex items-center gap-2" for="activo">
                                <i class="bi bi-power text-[var(--text-muted)]"></i> Estado de la Categoria
                            </label>
                            <input class="h-5 w-5 accent-[var(--btn-bg)]" type="checkbox" id="activo" name="activo" value="1" {{ old('activo', $categoria->activo) ? 'checked' : '' }}>
                        </div>
                        <p class="mt-2 text-xs text-[var(--text-muted)]"><i class="bi bi-info-circle mr-1"></i>Si desactiva esta categoria, sus productos podrian perder visibilidad.</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] px-4 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-input)]" onclick="window.history.back()">
                        <i class="bi bi-x-circle"></i> Cancelar Cambios
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-yellow-500 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-600">
                        <i class="bi bi-save"></i> Actualizar Datos
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/categorias.js'])
@endpush
