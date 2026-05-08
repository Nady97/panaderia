<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Optimización: Extraer todas las estadísticas en UNA sola consulta
        $estadisticas = Producto::select(
            DB::raw('COUNT(*) as total_productos'),
            DB::raw('SUM(CASE WHEN stock <= IF(stock_minimo > 0, stock_minimo, 5) AND stock > 0 THEN 1 ELSE 0 END) as stock_bajo'),
            DB::raw('SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as productos_agotados'),
            DB::raw('SUM(precio_venta * stock) as valor_inventario'),
            DB::raw('SUM(stock) as stock_total'),
            DB::raw('AVG(precio_venta) as precio_promedio')
        )->first();

        // Asignar variables de la consulta optimizada (o 0 si no hay productos)
        $totalProductos = $estadisticas->total_productos ?? 0;
        $productosStockBajo = $estadisticas->stock_bajo ?? 0;
        $productosAgotados = $estadisticas->productos_agotados ?? 0;
        $valorInventario = $estadisticas->valor_inventario ?? 0;
        $stockTotal = $estadisticas->stock_total ?? 0;
        $precioPromedio = $estadisticas->precio_promedio ?? 0;

        // Últimos 5 productos agregados
        $ultimosProductos = Producto::latest()->take(5)->get();

        // Ventas - Temporalmente 0
        $totalVentas = 0;

        // Producción - Temporalmente 0
        $totalProduccion = 0;

        // Categorías activas
        $totalCategorias = Categoria::count();

        return view('dashboard', compact(
            'totalProductos',
            'totalVentas',
            'totalProduccion',
            'productosStockBajo',
            'productosAgotados',
            'valorInventario',
            'stockTotal',
            'precioPromedio',
            'ultimosProductos',
            'totalCategorias'
        ));
    }
}
