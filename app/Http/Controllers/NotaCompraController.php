<?php

namespace App\Http\Controllers;

use App\Models\NotaCompra;
use App\Models\DetalleNotaCompra;
use App\Models\Insumo;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\NotaCompraProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotaCompraController extends Controller
{
public function index(Request $request)
{
    $this->authorize('viewAny', NotaCompra::class);

    // ✅ Agregar 'productos' al with()
    $query = NotaCompra::with(['proveedor', 'productos']);

    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where(function ($q) use ($search) {
            $q->where('nro_comprobante', 'like', "%{$search}%")
              ->orWhere('observaciones', 'like', "%{$search}%")
              ->orWhereHas('proveedor', function ($q2) use ($search) {
                  $q2->where('empresa', 'like', "%{$search}%")
                     ->orWhere('nombre_contacto', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    if ($request->filled('proveedor_codigo')) {
        $query->where('proveedor_codigo', $request->proveedor_codigo);
    }

    $notas = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
    
    //  También puedes calcular los conteos si la relación no funciona
    // foreach ($notas as $nota) {
    //     $nota->total_productos = $nota->productos->count();
    // }
    
    $proveedores = Proveedor::all();
    $estadisticas = [
        'total' => NotaCompra::count(),
        'solicitadas' => NotaCompra::where('estado', 'solicitado')->count(),
        'recibidas' => NotaCompra::where('estado', 'recibido')->count(),
        'canceladas' => NotaCompra::where('estado', 'cancelado')->count(),
        'monto_total_solicitado' => NotaCompra::where('estado', 'solicitado')->sum('monto_total'),
    ];

    return view('notas_compra.index', compact('notas', 'proveedores', 'estadisticas'));
}

  public function create()
  {
    $this->authorize('create', NotaCompra::class);

    $proveedores = Proveedor::all();
    $insumos = Insumo::all();
    return view('notas_compra.create', compact('proveedores', 'insumos'));
  }

  public function store(Request $request)
  {
    $this->authorize('create', NotaCompra::class);

    $data = $request->validate([
      'proveedor_codigo' => ['required', 'exists:proveedores,codigo'],
      'observaciones' => ['nullable', 'string', 'max:1000'],
    ]);

    DB::beginTransaction();
    try {
      $nota = new NotaCompra();
      $nota->proveedor_codigo = $data['proveedor_codigo'];
      $nota->usuario_codigo = Auth::user()->codigo;
      $nota->observaciones = $data['observaciones'] ?? null;
      $nota->estado = 'solicitado';
      $nota->monto_total = 0;
      $nota->save();

      DB::commit();
      return redirect()->route('notas_compra.show', $nota->id)
        ->with('success', 'Nota de compra creada correctamente.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Error al crear la nota: ' . $e->getMessage()]);
    }
  }

public function show(NotaCompra $notaCompra)
{
    $this->authorize('view', $notaCompra);
    
    // Cargar relaciones
    $notaCompra->load(['proveedor', 'usuario', 'detalles.insumo', 'productos.producto']);
    
    // Obtener datos para los selects de los modales
    $insumos = Insumo::where('estado', '=', 'activo')->orderBy('nombre')->get();
    $productos = \App\Models\Producto::where('estado', '=', 'activo')->orderBy('nombre')->get();
    $proveedores = Proveedor::where('estado', '=', 'activo')->orderBy('empresa')->get();
    
    // IMPORTANTE: Pasar la variable como 'nota' (no como 'notaCompra')
    return view('notas_compra.show', [
        'nota' => $notaCompra,  // ← Esto es lo que espera la vista
        'insumos' => $insumos,
        'productos' => $productos,
        'proveedores' => $proveedores
    ]);
}

  public function edit(NotaCompra $notaCompra)
  {
    $this->authorize('update', $notaCompra);

    if ($notaCompra->estado !== 'solicitado') {
      return back()->withErrors(['error' => 'Solo se pueden editar notas solicitadas.']);
    }

    $proveedores = Proveedor::all();
    $insumos = Insumo::all();
    return view('notas_compra.edit', ['nota' => $notaCompra, 'proveedores' => $proveedores, 'insumos' => $insumos]);
  }

  public function update(Request $request, NotaCompra $notaCompra)
  {
    $this->authorize('update', $notaCompra);

    if ($notaCompra->estado !== 'solicitado') {
      return back()->withErrors(['error' => 'Solo se pueden editar notas solicitadas.']);
    }

    $data = $request->validate([
      'proveedor_codigo' => ['required', 'exists:proveedores,codigo'],
      'observaciones' => ['nullable', 'string', 'max:1000'],
    ]);

    $notaCompra->update($data);
    return redirect()->route('notas_compra.show', $notaCompra->id)
      ->with('success', 'Nota de compra actualizada correctamente.');
  }

  public function destroy(NotaCompra $notaCompra)
  {
    $this->authorize('delete', $notaCompra);

    if ($notaCompra->estado !== 'solicitado') {
      return back()->withErrors(['error' => 'Solo se pueden eliminar notas solicitadas.']);
    }

    $notaCompra->detalles()->delete();
    $notaCompra->delete();

    return redirect()->route('notas_compra.index')
      ->with('success', 'Nota de compra eliminada correctamente.');
  }

  public function confirmar(NotaCompra $notaCompra)
  {
    // Método deshabilitado - estructura de tabla no soporta este flujo
    return back()->withErrors(['error' => 'Esta función no está disponible.']);
  }

public function marcarRecibida(NotaCompra $notaCompra)
{
    $this->authorize('markAsReceived', $notaCompra);

    if ($notaCompra->estado !== 'solicitado') {
        return back()->withErrors(['error' => 'La nota debe estar solicitada para marcar como recibida.']);
    }

    DB::beginTransaction();
    try {
        //  Actualizar stock de productos
        foreach ($notaCompra->productos as $item) {
            $producto = $item->producto;
            
            // Aumentar stock actual
            $stockAnterior = $producto->stock_actual ?? 0;
            $producto->stock_actual = $stockAnterior + $item->cantidad;
            
            // Calcular nuevo precio costo promedio
            $inventarioAnterior = $producto->precio_costo * $stockAnterior;
            $costoNuevaCompra = $item->precio_compra_unitario * $item->cantidad;
            $stockTotal = $producto->stock_actual;
            
            if ($stockTotal > 0) {
                $producto->precio_costo = ($inventarioAnterior + $costoNuevaCompra) / $stockTotal;
            }
            
            $producto->save();
        }
        
        $notaCompra->estado = 'recibido';
        $notaCompra->fecha_recepcion = now();
        $notaCompra->save();

        DB::commit();
        return back()->with('success', 'Nota marcada como recibida. Stock actualizado.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}
public function agregarDetalle(Request $request, NotaCompra $notaCompra)
{
    $this->authorize('addItems', $notaCompra);

    if ($notaCompra->estado !== 'solicitado') {
        return redirect()->back()->with('error', 'La nota debe estar solicitada');
    }

    $data = $request->validate([
        'insumo_id' => ['required', 'exists:insumos,id'],
        'cantidad' => ['required', 'numeric', 'min:0.01'],
        'precio_unitario' => ['required', 'numeric', 'min:0.01'],
    ]);

    DB::beginTransaction();
    try {
        $detalle = new DetalleNotaCompra();
        $detalle->nota_compra_id = $notaCompra->id;
        $detalle->insumo_id = $data['insumo_id'];
        $detalle->cantidad = $data['cantidad'];
        $detalle->precio_unitario = $data['precio_unitario'];
        $detalle->subtotal = $data['cantidad'] * $data['precio_unitario'];
        $detalle->save();

        $notaCompra->actualizarMontoTotal();
        
        DB::commit();
        
        // Redirigir de vuelta a la página de la nota
        return redirect()->route('notas_compra.show', $notaCompra->id)
            ->with('success', 'Insumo agregado correctamente');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

 public function eliminarDetalle(DetalleNotaCompra $detalle)
{
    $nota = $detalle->notaCompra;
    
    $this->authorize('addItems', $nota);
    
    if ($nota->estado !== 'solicitado') {
        return redirect()->back()->with('error', 'La nota debe estar solicitada para eliminar detalles.');
    }
    
    DB::beginTransaction();
    try {
        $detalle->delete();
        $nota->actualizarMontoTotal();
        DB::commit();
        
        return redirect()->route('notas_compra.show', $nota->id)
            ->with('success', 'Insumo eliminado correctamente.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
    }
}
public function eliminarProducto(NotaCompraProducto $producto)
{
    $nota = $producto->notaCompra;
    
    $this->authorize('addItems', $nota);
    
    if ($nota->estado !== 'solicitado') {
        return redirect()->back()->with('error', 'La nota debe estar solicitada para eliminar productos.');
    }
    
    DB::beginTransaction();
    try {
        $producto->delete();
        $nota->actualizarMontoTotal();
        DB::commit();
        
        return redirect()->route('notas_compra.show', $nota->id)
            ->with('success', 'Producto eliminado correctamente.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
    }
}
public function agregarProducto(Request $request, NotaCompra $notaCompra)
{
    $this->authorize('addItems', $notaCompra);
    
    if ($notaCompra->estado !== 'solicitado') {
        return redirect()->back()->with('error', 'La nota debe estar solicitada');
    }
    
    $data = $request->validate([
        'producto_id' => ['required', 'exists:productos,id'],
        'cantidad' => ['required', 'numeric', 'min:0.01'],
        'precio_compra_unitario' => ['required', 'numeric', 'min:0.01'],
    ]);
    
    DB::beginTransaction();
    try {
        $producto = new NotaCompraProducto();
        $producto->nota_compra_id = $notaCompra->id;
        $producto->producto_id = $data['producto_id'];
        $producto->cantidad = $data['cantidad'];
        $producto->precio_compra_unitario = $data['precio_compra_unitario'];
        $producto->subtotal = $data['cantidad'] * $data['precio_compra_unitario'];
        $producto->save();
        
        $notaCompra->actualizarMontoTotal();
        DB::commit();
        
        return redirect()->route('notas_compra.show', $notaCompra->id)
            ->with('success', 'Producto agregado correctamente');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

}
