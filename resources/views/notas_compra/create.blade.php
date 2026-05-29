@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezdo -->
    <x-card class="mb-4 border-0 shadow-sm">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1 text-main">
                        <i class="bi bi-plus-circle-fill me-2 text-gold"></i> 
                        Nueva Nota de Compra
                    </h2>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-truck me-1"></i> 
                        Registra una nueva compra a proveedor
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark p-2">
                        <i class="bi bi-hourglass-split me-1 text-warning"></i>
                        Estado: Pendiente
                    </span>
                </div>
            </div>
        </div>
    </x-card>

    <x-card class="border-0 shadow-sm">
        <form action="{{ route('notas_compra.store') }}" method="POST" id="createForm">
            @csrf
            
            <div class="p-4">
                <!-- Información de ayuda -->
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div>
                            <strong>Información importante:</strong>
                            La fecha de pedido se asignará automáticamente. 
                            Después de crear la nota podrás agregar productos e insumos.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <!-- Datos del Proveedor -->
                <div class="bg-light p-4 rounded-3 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-building text-gold me-2"></i> 
                        Datos del Proveedor
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="proveedor_codigo" class="form-label fw-semibold">
                                <i class="bi bi-shop me-1 text-gold"></i> 
                                Seleccionar Proveedor 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="proveedor_codigo" id="proveedor_codigo" 
                                    class="form-select form-select-lg @error('proveedor_codigo') is-invalid @enderror" 
                                    required>
                                <option value="">-- Selecciona un proveedor --</option>
                                @foreach($proveedores ?? [] as $proveedor)
                                    <option value="{{ $proveedor->codigo }}" 
                                        data-empresa="{{ $proveedor->empresa }}"
                                        data-contacto="{{ $proveedor->nombre_contacto }}"
                                        @selected(old('proveedor_codigo') === $proveedor->codigo)>
                                        <strong>{{ $proveedor->empresa }}</strong>
                                        @if($proveedor->nombre_contacto)
                                            - {{ $proveedor->nombre_contacto }}
                                        @endif
                                        @if($proveedor->nit)
                                            (NIT: {{ $proveedor->nit }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_codigo')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                            
                            <!-- Tarjeta de información del proveedor seleccionado -->
                            <div id="proveedorInfo" class="mt-3" style="display: none;">
                                <div class="card border-gold">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 text-gold">
                                                    <i class="bi bi-building-check me-1"></i> Proveedor seleccionado
                                                </h6>
                                                <p class="mb-0 small" id="proveedorDetalle"></p>
                                            </div>
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-4">
                    <label for="observaciones" class="form-label fw-semibold">
                        <i class="bi bi-chat-text me-1 text-gold"></i> 
                        Observaciones
                    </label>
                    <textarea name="observaciones" id="observaciones" 
                              class="form-control @error('observaciones') is-invalid @enderror" 
                              rows="4"
                              placeholder="Notas adicionales sobre el pedido (instrucciones, condiciones de entrega, etc.)">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> 
                        Máximo 1000 caracteres. Este campo es opcional.
                    </div>
                </div>

                <!-- Resumen de la compra -->
                <div class="bg-light p-3 rounded-3 mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-calendar-check fs-2 text-gold"></i>
                                <p class="mb-0 small text-muted mt-1">Fecha automática</p>
                                <small class="fw-semibold">{{ now()->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-cash-stack fs-2 text-gold"></i>
                                <p class="mb-0 small text-muted mt-1">Monto inicial</p>
                                <small class="fw-semibold">Bs. 0.00</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-bag-plus fs-2 text-gold"></i>
                                <p class="mb-0 small text-muted mt-1">Próximo paso</p>
                                <small class="fw-semibold">Agregar productos</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-flex gap-3 justify-content-end flex-wrap border-top pt-4">
                    <a href="{{ route('notas_compra.index') }}" class="btn btn-light border px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary-panaderia px-4" id="btnCrear">
                        <i class="bi bi-check-circle me-1"></i> Crear Nota
                    </button>
                </div>
            </div>
        </form>
    </x-card>
</div>

@push('styles')
<style>
    .btn-primary-panaderia {
        background: linear-gradient(135deg, #d4af37 0%, #b8942e 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary-panaderia:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        background: linear-gradient(135deg, #e0bc4a 0%, #c4a032 100%);
        color: white;
    }
    
    .btn-light {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    
    .text-gold {
        color: #d4af37;
    }
    
    .border-gold {
        border-left: 3px solid #d4af37 !important;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
    }
    
    .bg-gold-light {
        background-color: rgba(212, 175, 55, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Mostrar información del proveedor seleccionado
    const proveedorSelect = document.getElementById('proveedor_codigo');
    const proveedorInfo = document.getElementById('proveedorInfo');
    const proveedorDetalle = document.getElementById('proveedorDetalle');
    
    if (proveedorSelect) {
        // Mostrar info al seleccionar
        proveedorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                const empresa = selectedOption.getAttribute('data-empresa') || selectedOption.textContent;
                const contacto = selectedOption.getAttribute('data-contacto');
                let html = `<strong>${empresa}</strong>`;
                if (contacto) {
                    html += `<br><i class="bi bi-person me-1"></i> Contacto: ${contacto}`;
                }
                proveedorDetalle.innerHTML = html;
                proveedorInfo.style.display = 'block';
            } else {
                proveedorInfo.style.display = 'none';
            }
        });
        
        // Trigger para mostrar si hay valor seleccionado (por si viene con error)
        if (proveedorSelect.value) {
            proveedorSelect.dispatchEvent(new Event('change'));
        }
    }
    
    // Prevenir doble envío
    const form = document.getElementById('createForm');
    const btnCrear = document.getElementById('btnCrear');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            btnCrear.disabled = true;
            btnCrear.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Creando...';
            
            // Habilitar de nuevo después de 5 segundos por si hay error
            setTimeout(() => {
                if (btnCrear.disabled) {
                    btnCrear.disabled = false;
                    btnCrear.innerHTML = '<i class="bi bi-check-circle me-1"></i> Crear Nota';
                }
            }, 5000);
        });
    }
    
    // Validación básica antes de enviar
    function validateForm() {
        const proveedor = document.getElementById('proveedor_codigo');
        if (!proveedor.value) {
            proveedor.classList.add('is-invalid');
            return false;
        }
        return true;
    }
    
    // Mejorar validación de campos
    const inputs = document.querySelectorAll('.form-select, .form-control');
    inputs.forEach(input => {
        input.addEventListener('invalid', function() {
            this.classList.add('is-invalid');
        });
        
        input.addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endpush
@endsection