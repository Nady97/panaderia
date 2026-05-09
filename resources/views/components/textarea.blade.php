<!-- Componente Blade Reutilizable para Textareas -->
@props([
    'name', 
    'label', 
    'required' => false,
    'icon' => null,
    'value' => null,
    'placeholder' => '',
    'rows' => 3
])

@php
    $wrapperBase = 'flex items-stretch rounded border';
    $wrapperClass = $errors->has($name) ? $wrapperBase . ' border-red-300' : $wrapperBase . ' border-gray-300';
    $textareaClass = 'block w-full px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400';
@endphp

<div class="mb-4">
    <label for="{{ $name }}" class="block mb-2 text-sm font-semibold text-[var(--text-primary)]">
        {{ $label }}
        @if($required) <span class="text-red-600">*</span> @endif
    </label>

    <div class="{{ $wrapperClass }}">
        @if($icon)
            <span class="inline-flex items-center px-3 text-gray-500 bg-[var(--bg-primary)]">{!! $icon !!}</span>
        @endif

        <textarea
            class="{{ $textareaClass }}"
            id="{{ $name }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >{{ old($name, $value) }}</textarea>
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-600">
            <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror
</div>