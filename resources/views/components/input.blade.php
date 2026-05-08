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

<div class="mb-4">
    <label for="{{ $name }}" class="form-label fw-semibold mb-2 text-main">
        {{ $label }} 
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    
    <div class="input-group input-group-modern @error($name) is-invalid @enderror">
        @if($icon)
            <span class="input-group-text">{!! $icon !!}</span>
        @endif
        
        <input 
            type="{{ $type }}" 
            class="form-control @error($name) is-invalid @enderror" 
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
        <div class="invalid-feedback d-block mt-1">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>