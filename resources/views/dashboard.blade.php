@extends('layouts.app')

@section('content')
<div class="space-y-5" style="background-color: var(--bg-primary);">

    {{-- ENCABEZADO CON SALUDO --}}
    <x-card>
        <div class="flex justify-between items-center flex-wrap gap-3 p-4">
            <div>
                <h2 class="text-xl md:text-2xl font-extrabold mb-1" style="color: var(--text-primary); letter-spacing: -0.01em;">
                    ¡Bienvenida, {{ auth()->user()->nombre ?? 'Usuario' }}!
                </h2>
                <p class="text-sm" style="color: var(--text-muted); font-size: 0.85rem;">Panel de control · Gestión de producción</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium"
                 style="background: rgba(210, 150, 75, 0.06); border: 1px solid var(--border-color); color: var(--text-secondary);">
                <i class="bi bi-calendar3" style="color: var(--gold-dark); font-size: 0.9rem;"></i>
                <span style="font-size: 0.85rem;">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
            </div>
        </div>
    </x-card>

    {{-- TARJETAS DE ESTADÍSTICAS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Productos --}}
        <x-card>
            <div class="flex items-center gap-4 p-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     style="background: rgba(212, 175, 55, 0.12); color: var(--gold-dark);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-semibold mb-0.5" style="color: var(--text-muted);">Productos</p>
                    <p class="text-2xl font-extrabold" style="color: var(--text-primary);">{{ $totalProductos ?? 0 }}</p>
                </div>
            </div>
        </x-card>
        
        {{-- Ventas --}}
        <x-card>
            <div class="flex items-center gap-4 p-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     style="background: rgba(42, 123, 79, 0.1); color: var(--success);">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-semibold mb-0.5" style="color: var(--text-muted);">Ventas</p>
                    <p class="text-2xl font-extrabold" style="color: var(--text-primary);">{{ $totalVentas ?? 0 }}</p>
                </div>
            </div>
        </x-card>
        
        {{-- Producción --}}
        <x-card>
            <div class="flex items-center gap-4 p-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     style="background: rgba(129, 87, 45, 0.1); color: var(--primary-brown);">
                    <i class="bi bi-cup-hot"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-semibold mb-0.5" style="color: var(--text-muted);">Producción</p>
                    <p class="text-2xl font-extrabold" style="color: var(--text-primary);">{{ $totalProduccion ?? 0 }}</p>
                </div>
            </div>
        </x-card>
        
        {{-- Inventario --}}
        <x-card>
            <div class="flex items-center gap-4 p-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     style="background: rgba(212, 175, 55, 0.12); color: var(--gold-dark);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-semibold mb-0.5" style="color: var(--text-muted);">Inventario</p>
                    <p class="text-xl font-extrabold" style="color: var(--text-primary);">Bs {{ number_format($valorInventario ?? 0, 2) }}</p>
                </div>
            </div>
        </x-card>
    </div>

    {{-- PANELES INFERIORES --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        {{-- ALERTAS DE STOCK --}}
        <x-card>
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold flex items-center gap-2" style="color: var(--text-primary);">
                        <i class="bi bi-bell" style="color: #D4A34B;"></i>Alertas de Stock
                    </h3>
                    @php $totalAlertas = ($productosStockBajo ?? 0) + ($productosAgotados ?? 0); @endphp
                    @if($totalAlertas > 0)
                        <span class="px-3 py-1.5 rounded-full text-sm font-semibold border"
                              style="color: #D46B5E; border-color: rgba(212, 107, 94, 0.25);">
                            {{ $totalAlertas }} {{ $totalAlertas == 1 ? 'Alerta' : 'Alertas' }}
                        </span>
                    @endif
                </div>
                
                @if($totalAlertas > 0)
                    <div class="space-y-3">
                        @if(($productosStockBajo ?? 0) > 0)
                        <div class="flex items-start gap-3 p-4 rounded-xl transition-all duration-200"
                             style="background: rgba(212, 107, 94, 0.04); border: 1px solid rgba(212, 107, 94, 0.1);">
                            <i class="bi bi-exclamation-triangle-fill text-xl flex-shrink-0" style="color: var(--warning);"></i>
                            <div>
                                <p class="font-semibold text-sm" style="color: #D46B5E;">Stock bajo</p>
                                <p class="text-sm" style="color: #D46B5E; opacity: 0.75;">{{ $productosStockBajo }} {{ $productosStockBajo == 1 ? 'producto necesita' : 'productos necesitan' }} revisión.</p>
                            </div>
                        </div>
                        @endif
                        
                        @if(($productosAgotados ?? 0) > 0)
                        <div class="flex items-start gap-3 p-4 rounded-xl transition-all duration-200"
                             style="background: rgba(212, 107, 94, 0.04); border: 1px solid rgba(212, 107, 94, 0.1);">
                            <i class="bi bi-x-circle-fill text-xl flex-shrink-0" style="color: var(--danger);"></i>
                            <div>
                                <p class="font-semibold text-sm" style="color: #D46B5E;">Productos agotados</p>
                                <p class="text-sm" style="color: #D46B5E; opacity: 0.75;">{{ $productosAgotados }} {{ $productosAgotados == 1 ? 'producto debe' : 'productos deben' }} reponerse.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <a href="/productos" class="flex items-center justify-center gap-2 w-full mt-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200"
                       style="color: #D46B5E; border: 1px solid rgba(212, 107, 94, 0.2);"
                       onmouseover="this.style.backgroundColor='rgba(212,107,94,0.05)'; this.style.borderColor='rgba(212,107,94,0.3)'"
                       onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='rgba(212,107,94,0.2)'">
                        Revisar inventario <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-3xl"
                             style="background: rgba(16, 185, 129, 0.08); color: var(--success);">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-1" style="color: var(--text-primary);">¡Todo en orden!</h4>
                        <p class="text-sm" style="color: var(--text-muted);">No hay productos con stock bajo o agotados.</p>
                    </div>
                @endif
            </div>
        </x-card>
        
        {{-- RESUMEN DEL INVENTARIO --}}
        <x-card>
            <div class="p-4">
                <h3 class="text-lg font-bold flex items-center gap-2 mb-4" style="color: var(--text-primary);">
                    <i class="bi bi-pie-chart" style="color: var(--gold-dark);"></i>Resumen del Inventario
                </h3>
                
                <div class="space-y-1">
                    <div class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200"
                         onmouseover="this.style.backgroundColor='rgba(210,150,75,0.04)'"
                         onmouseout="this.style.backgroundColor='transparent'">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background: rgba(212, 175, 55, 0.1); color: var(--gold-dark);">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="text-sm" style="color: var(--text-secondary);">Unidades en stock</span>
                            <span class="text-sm font-bold" style="color: var(--text-primary);">{{ $stockTotal ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200"
                         onmouseover="this.style.backgroundColor='rgba(210,150,75,0.04)'"
                         onmouseout="this.style.backgroundColor='transparent'">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background: rgba(129, 87, 45, 0.1); color: var(--primary-brown);">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="text-sm" style="color: var(--text-secondary);">Precio promedio</span>
                            <span class="text-sm font-bold" style="color: var(--text-primary);">Bs {{ number_format($precioPromedio ?? 0, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200"
                         onmouseover="this.style.backgroundColor='rgba(210,150,75,0.04)'"
                         onmouseout="this.style.backgroundColor='transparent'">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background: rgba(212, 175, 55, 0.1); color: var(--gold-dark);">
                            <i class="bi bi-cup-hot"></i>
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="text-sm" style="color: var(--text-secondary);">Total productos distintos</span>
                            <span class="text-sm font-bold" style="color: var(--text-primary);">{{ $totalProductos ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    {{-- ÚLTIMOS PRODUCTOS --}}
    <x-card>
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold flex items-center gap-2" style="color: var(--text-primary);">
                    <i class="bi bi-box-seam" style="color: var(--gold-dark);"></i>Últimos Productos Agregados
                </h3>
                <a href="/productos" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2"
                   style="color: var(--text-secondary); border: 1px solid var(--border-color);"
                   onmouseover="this.style.backgroundColor='var(--bg-input)'; this.style.borderColor='var(--gold-dark)'"
                   onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='var(--border-color)'">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            @if(isset($ultimosProductos) && count($ultimosProductos) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <th class="text-left py-3 px-3 text-xs uppercase tracking-wider font-semibold" style="color: var(--text-muted);">Producto</th>
                                <th class="text-left py-3 px-3 text-xs uppercase tracking-wider font-semibold" style="color: var(--text-muted);">Precio</th>
                                <th class="text-center py-3 px-3 text-xs uppercase tracking-wider font-semibold" style="color: var(--text-muted);">Stock</th>
                                <th class="text-center py-3 px-3 text-xs uppercase tracking-wider font-semibold" style="color: var(--text-muted);">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosProductos as $producto)
                            <tr style="border-bottom: 1px solid var(--border-color);" 
                                onmouseover="this.style.backgroundColor='rgba(210,150,75,0.03)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <td class="py-3 px-3 font-medium" style="color: var(--text-primary);">
                                    <i class="bi bi-cup-hot mr-2" style="color: var(--text-muted);"></i>{{ $producto->nombre }}
                                </td>
                                <td class="py-3 px-3 font-semibold" style="color: var(--gold-dark);">Bs {{ number_format($producto->precio_venta, 2) }}</td>
                                <td class="py-3 px-3 text-center">
                                    @if($producto->stock <= 0)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(207,59,59,0.1); color: var(--danger);">Agotado</span>
                                    @elseif($producto->stock <= $producto->stock_minimo)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(232,149,30,0.1); color: var(--warning);">{{ $producto->stock }} uds</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(42,123,79,0.1); color: var(--success);">{{ $producto->stock }} uds</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                          style="background: {{ $producto->estado == 'activo' ? 'rgba(42,123,79,0.1)' : 'rgba(207,59,59,0.1)' }}; color: {{ $producto->estado == 'activo' ? 'var(--success)' : 'var(--danger)' }};">
                                        {{ $producto->estado == 'activo' ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10">
                    <i class="bi bi-inbox text-4xl block mb-3" style="color: var(--text-muted); opacity: 0.4;"></i>
                    <p class="font-semibold mb-1" style="color: var(--text-primary);">No hay productos nuevos</p>
                    <p class="text-sm mb-4" style="color: var(--text-muted);">Aún no se han registrado productos en el inventario.</p>
                    <a href="/productos/create" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200"
                       style="background-color: var(--btn-bg); color: var(--btn-text);"
                       onmouseover="this.style.backgroundColor='var(--btn-hover)'; this.style.transform='translateY(-1px)'"
                       onmouseout="this.style.backgroundColor='var(--btn-bg)'; this.style.transform='translateY(0)'">
                        <i class="bi bi-plus-circle"></i> Agregar primer producto
                    </a>
                </div>
            @endif
        </div>
    </x-card>

</div>
@endsection

@push('scripts')
    @vite(['resources/js/dashboard.js'])
@endpush