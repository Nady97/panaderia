<!-- MEJORA 1: Componentes Blade Reutilizables -->
@props([
    'name', 
    'label', 
    'type' => 'text', 
    'value' => null, 
    'placeholder' => '',
    'required' => false,
    'icon' => null,
    'step' => null,
    'accept' => null
])

@php
    $wrapperBase = 'flex items-stretch rounded border';
    $wrapperClass = $errors->has($name) ? $wrapperBase . ' border-red-300' : $wrapperBase . ' border-gray-300';
    $inputClass = 'block w-full px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400';
    $asteriskClass = 'text-red-600';
@endphp

<div class="mb-4">
    <label for="{{ $name }}" class="block mb-2 text-sm font-semibold text-[var(--text-primary)]">
        {{ $label }}
        @if($required) <span class="{{ $asteriskClass }}">*</span> @endif
    </label>

    <div class="{{ $wrapperClass }}">
        @if($icon)
            <span class="inline-flex items-center px-3 text-gray-500 bg-[var(--bg-primary)]">{!! $icon !!}</span>
        @endif

        <input
            type="{{ $type }}"
            class="{{ $inputClass }}"
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            @if($type !== 'file')
                value="{{ old($name, $value) }}"
            @endif
            @if($step)
                step="{{ $step }}"
            @endif
            @if($accept)
                accept="{{ $accept }}"
            @endif
            {{ $attributes }}
        >
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-600">
            <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror
</div>