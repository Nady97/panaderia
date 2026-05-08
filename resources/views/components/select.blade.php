<!-- Componente Blade Reutilizable para Selects -->
@props([
    'name', 
    'label', 
    'required' => false,
    'icon' => null,
])

<div class="mb-4">
    <label for="{{ $name }}" class="form-label fw-semibold mb-2 text-main">
        {{ $label }} 
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    
    <div class="input-group input-group-modern @error($name) is-invalid @enderror">
        @if($icon)
            <span class="input-group-text">{!! $icon !!}</span>
        @endif
        
        <select 
            class="form-select @error($name) is-invalid @enderror" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
            {{ $slot }}
        </select>
    </div>
    
    @error($name)
        <div class="invalid-feedback d-block mt-1">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>