<?php

namespace App\Http\Controllers;

use App\Models\FacturaInterna;
use App\Models\Producto;
use App\Models\Insumo;
use App\Models\Produccion;
use App\Models\NotaCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
  public function index()
  {
    return view('reportes.index');
  }

  /**
   * Reporte de Ventas Diarias/Mensuales
   */
  public function ventasReporte(Request $request)
  {
    $periodo = $request->get('periodo', 'mes'); // mes, trimestre, año, hoy

    $query = FacturaInterna::where('estado', 'emitida')
      ->orWhere('estado', 'pagada');

    // Filtro por período
    if ($periodo === 'hoy') {
      $query->whereDate('fecha_emision', today());
    } elseif ($periodo === 'mes') {
      $query->whereYear('fecha_emision', now()->year)
        ->whereMonth('fecha_emision', now()->month);
    } elseif ($periodo === 'trimestre') {
      $trimestre = ceil(now()->month / 3);
      $mesInicio = ($trimestre - 1) * 3 + 1;
      $query->whereYear('fecha_emision', now()->year)
        ->whereBetween('fecha_emision', [
          now()->setMonth($mesInicio)->startOfMonth(),
          now()->setMonth($mesInicio + 2)->endOfMonth()
        ]);
    } elseif ($periodo === 'año') {
      $query->whereYear('fecha_emision', now()->year);
    }

    // Agrupar por fecha
    $ventasDiarias = $query->selectRaw('DATE(fecha_emision) as fecha, COUNT(*) as cantidad, SUM(total) as monto_total')
      ->groupBy('fecha')
      ->orderBy('fecha', 'desc')
      ->get();

    // Resumen general
    $resumen = [
      'cantidad_facturas' => FacturaInterna::where('estado', '!=', 'anulada')->count(),
      'monto_total_pagado' => FacturaInterna::where('estado', 'pagada')->sum('total'),
      'monto_total_emitido' => FacturaInterna::where('estado', 'emitida')->sum('total'),
      'promedio_venta' => FacturaInterna::where('estado', '!=', 'anulada')->avg('total'),
      'puntos_totales' => FacturaInterna::sum('puntos_ganados'),
    ];

    return view('reportes.ventas', compact('ventasDiarias', 'resumen', 'periodo'));
  }

  /**
   * Reporte de Producción: Realizada vs Planificada
   */
  public function produccionReporte(Request $request)
  {
    $periodo = $request->get('periodo', 'mes');

    $query = Produccion::with('receta');

    // Filtro por período
    if ($periodo === 'hoy') {
      $query->whereDate('fecha_programada', today());
    } elseif ($periodo === 'mes') {
      $query->whereYear('fecha_programada', now()->year)
        ->whereMonth('fecha_programada', now()->month);
    } elseif ($periodo === 'año') {
      $query->whereYear('fecha_programada', now()->year);
    }

    $producciones = $query->orderBy('fecha_programada', 'desc')->get();

    // Estadísticas
    $estadisticas = [
      'total_producciones' => $producciones->count(),
      'completadas' => $producciones->where('estado', 'finalizada')->count(),
      'en_proceso' => $producciones->where('estado', 'en_proceso')->count(),
      'planificadas' => $producciones->where('estado', 'planificado')->count(),
      'cantidad_producida_total' => $producciones->sum('cantidad_producida'),
      'cantidad_planificada_total' => $producciones->sum(function ($p) {
        return $p->receta ? $p->receta->cantidad_producida : 0;
      }),
      'eficiencia' => $producciones->count() > 0
        ? ($producciones->where('estado', 'finalizada')->count() / $producciones->count() * 100)
        : 0,
    ];

    return view('reportes.produccion', compact('producciones', 'estadisticas', 'periodo'));
  }

  /**
   * Reporte de Inventario Crítico
   */
  public function inventarioCriticoReporte()
  {
    // Productos en nivel crítico
    $productosCriticos = Producto::where('stock', '<=', DB::raw('stock_minimo'))
      ->with('categoria')
      ->orderBy('stock', 'asc')
      ->get();

    // Insumos en nivel crítico
    $insumosCriticos = Insumo::where('cantidad_disponible', '<=', DB::raw('cantidad_minima'))
      ->orderBy('cantidad_disponible', 'asc')
      ->get();

    // Resumen de inventario
    $resumenInventario = [
      'productos_totales' => Producto::count(),
      'productos_criticos' => $productosCriticos->count(),
      'productos_sin_stock' => Producto::where('stock', '=', 0)->count(),
      'insumos_totales' => Insumo::count(),
      'insumos_criticos' => $insumosCriticos->count(),
      'valor_inventario_productos' => Producto::sum(DB::raw('stock * precio_costo')),
      'valor_inventario_insumos' => Insumo::sum(DB::raw('cantidad_disponible * precio_unitario')),
    ];

    return view('reportes.inventario-critico', compact('productosCriticos', 'insumosCriticos', 'resumenInventario'));
  }

  /**
   * Reporte de Proveedores más usados
   */
  public function proveedoresReporte()
  {
    $proveedoresUsados = NotaCompra::selectRaw('proveedor_codigo, COUNT(*) as cantidad_compras, SUM(monto_total) as monto_total')
      ->with('proveedor')
      ->where('estado', '!=', 'cancelado')
      ->groupBy('proveedor_codigo')
      ->orderByRaw('COUNT(*) DESC')
      ->limit(10)
      ->get();

    // Estadísticas generales
    $estadisticas = [
      'total_proveedores' => NotaCompra::distinct('proveedor_codigo')->count(),
      'total_compras' => NotaCompra::where('estado', '!=', 'cancelado')->count(),
      'monto_total_compras' => NotaCompra::where('estado', '!=', 'cancelado')->sum('monto_total'),
      'promedio_compra' => NotaCompra::where('estado', '!=', 'cancelado')->avg('monto_total'),
    ];

    return view('reportes.proveedores', compact('proveedoresUsados', 'estadisticas'));
  }

  /**
   * Reporte General de Dashboard
   */
  public function dashboard()
  {
    $hoy = today();
    $thisMonth = now()->month;
    $thisYear = now()->year;

    $kpis = [
      // Ventas
      'ventas_hoy' => FacturaInterna::whereDate('fecha_emision', $hoy)->sum('total'),
      'ventas_mes' => FacturaInterna::whereYear('fecha_emision', $thisYear)
        ->whereMonth('fecha_emision', $thisMonth)
        ->sum('total'),
      'facturas_pendientes' => FacturaInterna::where('estado', 'emitida')->count(),
      'facturas_pagadas' => FacturaInterna::where('estado', 'pagada')->count(),

      // Producción
      'producciones_hoy' => Produccion::whereDate('fecha_programada', $hoy)->count(),
      'producciones_completadas_mes' => Produccion::where('estado', 'finalizada')
        ->whereYear('fecha_programada', $thisYear)
        ->whereMonth('fecha_programada', $thisMonth)
        ->count(),
      'producciones_en_proceso' => Produccion::where('estado', 'en_proceso')->count(),
      'cantidad_producida_mes' => Produccion::where('estado', 'finalizada')
        ->whereYear('fecha_programada', $thisYear)
        ->whereMonth('fecha_programada', $thisMonth)
        ->sum('cantidad_producida'),

      // Inventario
      'productos_criticos' => Producto::whereRaw('stock <= stock_minimo')->count(),
      'insumos_criticos' => Insumo::whereRaw('cantidad_disponible <= cantidad_minima')->count(),
      'productos_sin_stock' => Producto::where('stock', 0)->count(),
      'valor_inventario_productos' => Producto::sum(DB::raw('stock * precio_costo')),

      // Compras
      'compras_mes' => NotaCompra::whereYear('fecha_pedido', $thisYear)
        ->whereMonth('fecha_pedido', $thisMonth)
        ->count(),
      'monto_compras_mes' => NotaCompra::whereYear('fecha_pedido', $thisYear)
        ->whereMonth('fecha_pedido', $thisMonth)
        ->sum('monto_total'),
    ];

    // Top 5 productos más vendidos (por factura, aproximado)
    $topProductos = Producto::orderBy('stock', 'desc')
      ->limit(5)
      ->get(['id', 'nombre', 'stock', 'precio_venta']);

    // Top 5 proveedores
    $topProveedores = NotaCompra::selectRaw('proveedor_codigo, COUNT(*) as compras')
      ->with('proveedor')
      ->where('estado', '!=', 'cancelado')
      ->groupBy('proveedor_codigo')
      ->orderByRaw('COUNT(*) DESC')
      ->limit(5)
      ->get();

    return view('reportes.dashboard', compact('kpis', 'topProductos', 'topProveedores'));
  }
}
