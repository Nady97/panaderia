@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-card class="mb-4">
        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-main">
                    <i class="bi bi-clock-history me-2 text-gold"></i>Historial de Acceso
                </h2>
                <p class="mb-0 text-muted">Usuario: <strong>{{ $usuario->nombre }}</strong> ({{ $usuario->codigo }})</p>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="{{ route('usuarios.historial.pdf', $usuario->codigo) }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Exportar PDF
                </a>
                <a href="{{ route('usuarios.show', $usuario->codigo) }}" class="btn btn-light-panaderia text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </x-card>

    <x-card class="mb-4">
        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0 text-main">Historial de accesos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Fecha</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Hora</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Accion</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">IP</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Dispositivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bitacoras as $bitacora)
                            <tr class="border-bottom-modern" style="transition: background 0.2s;">
                                <td class="py-3 px-4">
                                    {{ $bitacora->created_at ? $bitacora->created_at->timezone('America/La_Paz')->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $bitacora->created_at ? $bitacora->created_at->timezone('America/La_Paz')->format('h:i A') : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($bitacora->accion === 'login')
                                        <x-badge type="success"><i class="bi bi-box-arrow-in-right me-1"></i>Ingreso</x-badge>
                                    @elseif($bitacora->accion === 'logout')
                                        <x-badge type="secondary"><i class="bi bi-box-arrow-right me-1"></i>Salida</x-badge>
                                    @else
                                        <x-badge type="light">{{ $bitacora->accion }}</x-badge>
                                    @endif
                                </td>
                                <td class="py-3 px-4">{{ $bitacora->ip_address ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="text-muted">{{ $bitacora->user_agent ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-0 border-0">
                                    <x-empty-state
                                        icon="bi-clock-history"
                                        title="Sin historial"
                                        description="Todavia no hay registros de acceso para este usuario."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bitacoras->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $bitacoras->firstItem() }}</span> a <span class="fw-bold">{{ $bitacoras->lastItem() }}</span> de <span class="fw-bold">{{ $bitacoras->total() }}</span> registros
                    </div>
                </div>
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $bitacoras->links() }}
                </div>
            @endif
        </div>
    </x-card>

    <x-card>
        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0 text-main">Cambios realizados</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-main">
                    <thead class="border-bottom-modern border-2">
                        <tr>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Fecha</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Hora</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Accion</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Modulo</th>
                            <th class="py-3 px-4" style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cambios as $cambio)
                            <tr class="border-bottom-modern" style="transition: background 0.2s;">
                                <td class="py-3 px-4">
                                    {{ $cambio->created_at ? $cambio->created_at->timezone('America/La_Paz')->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $cambio->created_at ? $cambio->created_at->timezone('America/La_Paz')->format('h:i A') : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <x-badge type="light">{{ $cambio->accion }}</x-badge>
                                </td>
                                <td class="py-3 px-4">{{ $cambio->modulo }}</td>
                                <td class="py-3 px-4">{{ $cambio->descripcion ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-0 border-0">
                                    <x-empty-state
                                        icon="bi-pencil"
                                        title="Sin cambios"
                                        description="Todavia no hay cambios registrados para este usuario."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($cambios->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 pb-3 border-top pt-3">
                    <div class="text-muted small">
                        Mostrando de <span class="fw-bold">{{ $cambios->firstItem() }}</span> a <span class="fw-bold">{{ $cambios->lastItem() }}</span> de <span class="fw-bold">{{ $cambios->total() }}</span> registros
                    </div>
                </div>
                <div class="px-4 pb-4 paginacion-personalizada">
                    {{ $cambios->links() }}
                </div>
            @endif
        </div>
    </x-card>
</div>
@endsection
