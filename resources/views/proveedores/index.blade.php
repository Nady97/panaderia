@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Encabezado de Proveedores -->
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-truck me-2 text-gold"></i> Gestión de Proveedores
                </h2>
                <p class="mb-0 text-muted">Administra los proveedores y cadenas de suministro</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('proveedores.create') }}" class="btn btn-primary-panaderia text-nowrap">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Proveedor
                </a>
            </div>
        </div>
    </x-card>

    <!-- Tarjetas de resumen -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-success bg-opacity-10 text-success rounded-3">
                        <i class="bi bi-check-circle fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $proveedoresActivos ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Activos</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-warning bg-opacity-10 text-warning rounded-3">
                        <i class="bi bi-pause-circle fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $proveedoresSuspendidos ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Suspendidos</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-danger bg-opacity-10 text-danger rounded-3">
                        <i class="bi bi-x-circle fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $proveedoresInactivos ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Inactivos</p>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-3 col-sm-6">
            <x-card class="h-100 d-flex align-items-center bg-transparent-hover border-primary">
                <div class="d-flex align-items-center w-100">
                    <div class="p-3 me-3" class="bg-warning bg-opacity-10 text-warning rounded-3">
                        <i class="bi bi-buildings fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-main">{{ $totalProveedores ?? 0 }}</h3>
                        <p class="text-muted mb-0 small text-uppercase fw-semibold">Total Registros</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros Server-Side -->
    <x-card class="mb-4">
        <div class="card-body p-4">
            <form action="{{ route('proveedores.index') }}" method="GET" class="row g-3 align-items-end" id="serverFilterForm">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-2" class="text-main fs-6">
                        <i class="bi bi-search me-1"></i> Buscar proveedor
                    </label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-shop"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Empresa, contacto, NIT o código..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2" class="text-main fs-6">Filtrar por Estado</label>
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select name="estado_filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('estado_filter') == 'all' ? 'selected' : '' }}>Todos los estados</option>
                            <option value="activo" {{ request('estado_filter') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="suspendido" {{ request('estado_filter') == 'suspendido' ? 'selected' : '' }}>Suspendidos</option>
                            <option value="inactivo" {{ request('estado_filter') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end d-flex gap-2">
                    <button type="submit" class="btn btn-gold-panaderia w-100"><i class="bi bi-search"></i></button>
                    @if(request()->has('search') || request()->has('estado_filter'))
                        <a href="{{ route('proveedores.index') }}" class="btn btn-light-panaderia w-100" title="Limpiar"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </x-card>

    <!-- Tabla Principal de Proveedores Paginada -->
    <x-card>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <!-- <th class="py-3 px-4" class="text-muted text-uppercase fw-semibold small">Código</th> -->
                            <th class="py-3 px-4" class="text-muted text-uppercase fw-semibold small">Empresa / Contacto</th>
                            <th class="py-3 px-4" class="text-muted text-uppercase fw-semibold small">Detalles</th>
                            <th class="py-3 px-4 text-center" class="text-muted text-uppercase fw-semibold small">Estado Operativo</th>
                            <th class="py-3 px-4 text-end" class="text-muted text-uppercase fw-semibold small">Acciones</th>
                        </tr>
                    </thead>
                    <tbody >
                        @forelse($proveedores as $proveedor)
                        <tr class="border-bottom-modern" style="transition: background 0.2s;">
                            <!--
                            <td class="py-3 px-4">
                                <span class="fw-bold font-monospace text-muted">
                                    {{ $proveedor->codigo }}
                                </span>
                            </td>
                            -->
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded d-flex align-items-center justify-content-center" class="detail-box icon-box">
                                        <i class="bi bi-buildings" class="fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-main fs-6">
                                            {{ $proveedor->empresa }}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $proveedor->nombre_contacto }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex flex-column gap-1">
                                    <span class="fs-6" title="NIT / ID Empresa"><i class="bi bi-credit-card-2-front me-2 text-muted"></i>{{ $proveedor->nit ?? 'S/N' }}</span>
                                    <span class="small"><i class="bi bi-telephone me-2 text-muted"></i>{{ $proveedor->telefono }}</span>
                                    @if($proveedor->email)
                                    <span class="small text-muted"><i class="bi bi-envelope me-2 text-muted"></i><a href="mailto:{{ $proveedor->email }}" class="text-decoration-none text-muted">{{ $proveedor->email }}</a></span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($proveedor->estado == 'activo')
                                    <span class="badge bg-light text-success border border-success border-opacity-25" class="rounded"><i class="bi bi-circle-fill me-1" class="small"></i>Activa</span>
                                @elseif($proveedor->estado == 'suspendido')
                                    <span class="badge bg-light text-warning border border-warning border-opacity-25" class="rounded"><i class="bi bi-pause-fill me-1"></i>Suspendido</span>
                                @else
                                    <span class="badge bg-light text-danger border border-danger border-opacity-25" class="rounded"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('proveedores.show', $proveedor->codigo) }}" class="btn btn-sm btn-light text-gold border" title="Ver Detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('proveedores.edit', $proveedor->codigo) }}" class="btn btn-sm btn-light text-main border" title="Editar Proveedor">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('proveedores.destroy', $proveedor->codigo) }}" method="POST" class="d-inline p-0 m-0 form-delete" data-confirm-text="¿Está seguro de que desea eliminar el proveedor {{$proveedor->empresa}}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" title="Eliminar Proveedor">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-0 border-0">
                                <x-empty-state 
                                    icon="bi-truck" 
                                    title="No se encontraron proveedores" 
                                    description="Ajusta los filtros de búsqueda o agrega un nuevo asociado comercial para gestionar tus compras."
                                >
                                    @if(request()->has('search') || request()->has('estado_filter'))
                                        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary mt-3">Limpiar filtros</a>
                                    @else
                                        <a href="{{ route('proveedores.create') }}" class="btn btn-primary-panaderia mt-3 text-nowrap"><i class="bi bi-plus-lg me-1"></i>Crear primer Proveedor</a>
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enlaces de Paginación Nativos Bootstrap 5 -->
            @if($proveedores->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3" class="border-top-modern">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $proveedores->firstItem() }}</span> a <span class="fw-bold">{{ $proveedores->lastItem() }}</span> de <span class="fw-bold">{{ $proveedores->total() }}</span> registros
                    </div>
                </div>
                
                {{-- Contenedor especial de paginación --}}
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $proveedores->links() }}
                </div>
            @endif
        </div>
    </x-card>
</div>
@endsection



