<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal (Dashboard) con todas las métricas del sistema.
     * 
     * ============================================================================
     * OPTIMIZACIONES IMPLEMENTADAS:
     * ----------------------------------------------------------------------------
     * ✅ Consulta agregada única para estadísticas de productos
     * ✅ Cálculo de productos activos en la misma consulta
     * ✅ Datos preparados para el gráfico de distribución
     * ✅ Variables con nombres consistentes para la vista
     * ============================================================================
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ========================================================================
        // ESTADÍSTICAS PRINCIPALES (CONSULTA OPTIMIZADA)
        // ========================================================================
        $estadisticas = Producto::select(
            DB::raw('COUNT(*) as total_productos'),
            DB::raw('SUM(CASE WHEN estado = "activo" THEN 1 ELSE 0 END) as productos_activos'),
            DB::raw('SUM(CASE WHEN stock <= IF(stock_minimo > 0, stock_minimo, 5) AND stock > 0 THEN 1 ELSE 0 END) as stock_bajo'),
            DB::raw('SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as productos_agotados'),
            DB::raw('SUM(precio_venta * stock) as valor_inventario'),
            DB::raw('SUM(precio_costo * stock) as costo_inventario'),
            DB::raw('SUM(stock) as stock_total'),
            DB::raw('AVG(precio_venta) as precio_promedio')
        )->first();

        // ========================================================================
        // ASIGNACIÓN DE VARIABLES (con valores por defecto)
        // ========================================================================
        $totalProductos = $estadisticas->total_productos ?? 0;
        $productosActivos = $estadisticas->productos_activos ?? 0;
        $productosStockBajo = $estadisticas->stock_bajo ?? 0;
        $productosAgotados = $estadisticas->productos_agotados ?? 0;
        $valorInventario = $estadisticas->valor_inventario ?? 0;
        $stockTotal = $estadisticas->stock_total ?? 0;
        $precioPromedio = $estadisticas->precio_promedio ?? 0;

        // ========================================================================
        // DATOS PARA EL GRÁFICO DE DISTRIBUCIÓN DE INVENTARIO
        // ========================================================================
        $inventoryChartData = $this->getDistribucionInventario();

        // ========================================================================
        // MÉTRICAS ADICIONALES
        // ========================================================================
        
        // Categorías
        $totalCategorias = Categoria::count();
        $categoriasActivas = Categoria::where('activo', true)->count();
        
        // Últimos productos agregados
        $ultimosProductos = Producto::with('categoria')
            ->latest()
            ->take(5)
            ->get();
        
        // ========================================================================
        // MÉTRICAS PENDIENTES (MÓDULOS FUTUROS)
        // ========================================================================
        // TODO: Implementar cuando exista el módulo de ventas
        $totalVentas = 0;
        $tendenciaVentas = null;
        
        // TODO: Implementar cuando exista el módulo de producción
        $totalProduccion = 0;
        $produccionPendiente = 0;
        
        // TODO: Implementar cuando exista el módulo de clientes/usuarios
        $clientesNuevos = 0;
        $tendenciaClientes = null;

        // ========================================================================
        // RETORNAR VISTA CON TODAS LAS VARIABLES
        // ========================================================================
        return view('dashboard', compact(
            // KPIs principales
            'totalProductos',
            'productosActivos',
            'totalVentas',
            'tendenciaVentas',
            'totalProduccion',
            'produccionPendiente',
            'valorInventario',
            'productosStockBajo',
            'productosAgotados',
            'totalCategorias',
            'categoriasActivas',
            'clientesNuevos',
            'tendenciaClientes',
            'stockTotal',
            'precioPromedio',
            
            // Datos para tablas y gráficos
            'ultimosProductos',
            'inventoryChartData'
        ));
    }

    /**
     * Calcula la distribución de productos por categoría para el gráfico donut.
     * 
     * Agrupa los productos en categorías principales y retorna:
     * - labels: Nombres de las categorías (máximo 4 para legibilidad)
     * - data: Cantidad de stock en cada categoría
     *
     * @return array
     */
    private function getDistribucionInventario(): array
    {
        // Obtener stock agrupado por categoría
        $categoriasStock = Producto::join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select(
                'categorias.nombre as categoria',
                DB::raw('SUM(productos.stock) as total_stock')
            )
            ->where('productos.estado', 'activo')
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('total_stock')
            ->limit(4) // Máximo 4 categorías para no saturar el gráfico
            ->get();
        
        // Si no hay datos, retornar valores por defecto
        if ($categoriasStock->isEmpty()) {
            return [
                'labels' => ['Sin categorizar'],
                'data' => [1]
            ];
        }
        
        // Formatear para Chart.js
        $labels = $categoriasStock->pluck('categoria')->toArray();
        $data = $categoriasStock->pluck('total_stock')->map(fn($val) => (int) $val)->toArray();
        
        // Si hay menos de 4 categorías, no pasa nada, Chart.js lo maneja bien
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Calcula la tendencia de ventas comparando el mes actual con el anterior.
     * 
     * TODO: Implementar cuando exista el módulo de ventas
     *
     * @return float|null Porcentaje de cambio o null si no hay datos
     */
    private function calcularTendenciaVentas(): ?float
    {
        // Placeholder para futuro módulo de ventas
        return null;
        
        /* Ejemplo de implementación futura:
        $ventasMesActual = Venta::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
            
        $ventasMesAnterior = Venta::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');
            
        if ($ventasMesAnterior == 0) {
            return $ventasMesActual > 0 ? 100 : 0;
        }
        
        return round((($ventasMesActual - $ventasMesAnterior) / $ventasMesAnterior) * 100, 1);
        */
    }

    /**
     * Calcula la tendencia de clientes nuevos comparando el mes actual con el anterior.
     * 
     * TODO: Implementar cuando exista el módulo de usuarios/clientes
     *
     * @return float|null Porcentaje de cambio o null si no hay datos
     */
    private function calcularTendenciaClientes(): ?float
    {
        // Placeholder para futuro módulo de clientes
        return null;
    }

    /**
     * Obtiene los productos con stock bajo (para la sección de alertas detalladas).
     * 
     * Útil si se quiere mostrar una lista de productos específicos en alerta.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getProductosStockBajoDetallado()
    {
        return Producto::where('stock', '>', 0)
            ->whereRaw('stock <= IF(stock_minimo > 0, stock_minimo, 5)')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
    }
}