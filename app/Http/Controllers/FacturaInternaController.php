<?php

namespace App\Http\Controllers;

use App\Models\FacturaInterna;
use App\Models\NotaCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaInternaController extends Controller
{
  public function index(Request $request)
  {
    $this->authorize('viewAny', FacturaInterna::class);

    $query = FacturaInterna::with(['usuario', 'detalles.producto']);

    if ($request->filled('search')) {
      $search = trim($request->search);
      $query->where(function ($q) use ($search) {
        $q->where('nro_factura', 'like', "%{$search}%")
          ->orWhere('cliente_ci', 'like', "%{$search}%");
      });
    }

    if ($request->filled('estado')) {
      $query->where('estado', $request->estado);
    }

    $facturas = $query->orderBy('fecha_emision', 'asc')->paginate(10)->withQueryString();
    $estadisticas = [
      'total' => FacturaInterna::count(),
      'emitidas' => FacturaInterna::where('estado', 'emitida')->count(),
      'pagadas' => FacturaInterna::where('estado', 'pagada')->count(),
      'anuladas' => FacturaInterna::where('estado', 'anulada')->count(),
      'monto_pendiente' => FacturaInterna::where('estado', 'emitida')->sum('total'),
    ];

    return view('facturas_internas.index', compact('facturas', 'estadisticas'));
  }

  public function show(FacturaInterna $facturaInterna)
  {
    $this->authorize('view', $facturaInterna);

    $facturaInterna->load(['usuario', 'detalles.producto']);
    return view('facturas_internas.show', compact('facturaInterna'));
  }

  public function create()
  {
    $this->authorize('create', FacturaInterna::class);

    return view('facturas_internas.create');
  }

  public function store(Request $request)
  {
    $this->authorize('create', FacturaInterna::class);

    $validated = $request->validate([
      'cliente_ci' => 'required|string|max:12',
      'cliente_telefono' => 'nullable|string|max:20',
      'cliente_direccion' => 'nullable|string|max:255',
      'fecha_emision' => 'required|date',
      'total' => 'required|numeric|min:0.01',
      'puntos_ganados' => 'nullable|integer|min:0',
      'productos' => 'required|array|min:1',
      'productos.*.producto_id' => 'required|exists:productos,id',
      'productos.*.cantidad' => 'required|integer|min:1',
      'productos.*.precio_unitario' => 'required|numeric|min:0',
      'productos.*.descuento' => 'nullable|numeric|min:0',
    ], [
      'cliente_ci.required' => 'El CI/NIT del cliente es obligatorio.',
      'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
      'total.required' => 'El total es obligatorio.',
      'productos.required' => 'Debe agregar al menos un producto.',
    ]);

    try {
      DB::beginTransaction();

      $factura = FacturaInterna::create([
        'nro_factura' => $this->generarNumeroFactura(),
        'cliente_ci' => $validated['cliente_ci'],
        'cliente_telefono' => $validated['cliente_telefono'] ?? null,
        'cliente_direccion' => $validated['cliente_direccion'] ?? null,
        'fecha_emision' => $validated['fecha_emision'],
        'total' => $validated['total'],
        'puntos_ganados' => $validated['puntos_ganados'] ?? 0,
        'estado' => 'valida',
        'usuario_codigo' => Auth::user()->codigo,
      ]);

      // Agregar detalles de productos
      foreach ($validated['productos'] as $producto) {
        $descuento = $producto['descuento'] ?? 0;
        $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
        $total_linea = $subtotal - $descuento;

        \App\Models\DetalleFactura::create([
          'factura_interna_id' => $factura->id,
          'producto_id' => $producto['producto_id'],
          'cantidad' => $producto['cantidad'],
          'precio_unitario' => $producto['precio_unitario'],
          'descuento' => $descuento,
          'total_linea' => $total_linea,
        ]);

        // Actualizar stock del producto (restar cantidad vendida)
        $prod = \App\Models\Producto::find($producto['producto_id']);
        $prod->stock -= $producto['cantidad'];
        $prod->save();
      }

      DB::commit();

      return redirect()->route('facturas_internas.show', $factura)
        ->with('success', 'Factura creada correctamente.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Error al crear factura: ' . $e->getMessage()]);
    }
  }

  public function edit(FacturaInterna $facturaInterna)
  {
    $this->authorize('update', $facturaInterna);

    if ($facturaInterna->estado !== 'valida') {
      return back()->withErrors(['error' => 'Solo puedes editar facturas válidas (no pagadas ni anuladas).']);
    }

    $facturaInterna->load('detalles.producto');
    return view('facturas_internas.edit', compact('facturaInterna'));
  }

  public function update(Request $request, FacturaInterna $facturaInterna)
  {
    $this->authorize('update', $facturaInterna);

    if ($facturaInterna->estado !== 'valida') {
      return back()->withErrors(['error' => 'No puedes editar una factura que no está válida.']);
    }

    $validated = $request->validate([
      'cliente_ci' => 'required|string|max:12',
      'cliente_telefono' => 'nullable|string|max:20',
      'cliente_direccion' => 'nullable|string|max:255',
      'fecha_emision' => 'required|date',
      'total' => 'required|numeric|min:0.01',
      'puntos_ganados' => 'nullable|integer|min:0',
      'productos' => 'required|array|min:1',
      'productos.*.producto_id' => 'required|exists:productos,id',
      'productos.*.cantidad' => 'required|integer|min:1',
      'productos.*.precio_unitario' => 'required|numeric|min:0',
      'productos.*.descuento' => 'nullable|numeric|min:0',
    ]);

    try {
      DB::beginTransaction();

      // Revertir stock de detalles anteriores
      foreach ($facturaInterna->detalles as $detalle) {
        $prod = $detalle->producto;
        $prod->stock += $detalle->cantidad;
        $prod->save();
      }

      // Actualizar factura
      $facturaInterna->update([
        'cliente_ci' => $validated['cliente_ci'],
        'cliente_telefono' => $validated['cliente_telefono'] ?? null,
        'cliente_direccion' => $validated['cliente_direccion'] ?? null,
        'fecha_emision' => $validated['fecha_emision'],
        'total' => $validated['total'],
        'puntos_ganados' => $validated['puntos_ganados'] ?? 0,
      ]);

      // Eliminar detalles anteriores
      $facturaInterna->detalles()->delete();

      // Agregar nuevos detalles
      foreach ($validated['productos'] as $producto) {
        $descuento = $producto['descuento'] ?? 0;
        $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
        $total_linea = $subtotal - $descuento;

        \App\Models\DetalleFactura::create([
          'factura_interna_id' => $facturaInterna->id,
          'producto_id' => $producto['producto_id'],
          'cantidad' => $producto['cantidad'],
          'precio_unitario' => $producto['precio_unitario'],
          'descuento' => $descuento,
          'total_linea' => $total_linea,
        ]);

        // Restar cantidad del nuevo stock
        $prod = \App\Models\Producto::find($producto['producto_id']);
        $prod->stock -= $producto['cantidad'];
        $prod->save();
      }

      DB::commit();

      return redirect()->route('facturas_internas.show', $facturaInterna)
        ->with('success', 'Factura actualizada correctamente.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Error al actualizar factura: ' . $e->getMessage()]);
    }
  }

  public function destroy(FacturaInterna $facturaInterna)
  {
    $this->authorize('delete', $facturaInterna);

    if ($facturaInterna->estado === 'anulada') {
      return back()->withErrors(['error' => 'No puedes eliminar una factura anulada.']);
    }

    try {
      DB::beginTransaction();

      // Revertir stock
      foreach ($facturaInterna->detalles as $detalle) {
        $prod = $detalle->producto;
        $prod->stock += $detalle->cantidad;
        $prod->save();
      }

      // Eliminar detalles
      $facturaInterna->detalles()->delete();

      // Eliminar factura
      $facturaInterna->delete();

      DB::commit();

      return redirect()->route('facturas_internas.index')
        ->with('success', 'Factura eliminada correctamente.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Error al eliminar factura: ' . $e->getMessage()]);
    }
  }

  private function generarNumeroFactura(): string
  {
    $ultimo = FacturaInterna::orderBy('id', 'asc')->first();
    $numero = ($ultimo ? $ultimo->id : 0) + 1;
    return 'FI-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
  }

  public function marcarPagada(FacturaInterna $facturaInterna)
  {
    $this->authorize('recordPayment', $facturaInterna);

    if ($facturaInterna->estado !== 'emitida') {
      return back()->withErrors(['error' => 'La factura debe estar emitida para marcar como pagada.']);
    }

    $facturaInterna->estado = 'pagada';
    $facturaInterna->save();

    return back()->with('success', 'Factura marcada como pagada.');
  }

  public function anular(FacturaInterna $facturaInterna)
  {
    $this->authorize('cancel', $facturaInterna);

    if ($facturaInterna->estado === 'anulada') {
      return back()->withErrors(['error' => 'La factura ya está anulada.']);
    }

    $facturaInterna->estado = 'anulada';
    $facturaInterna->save();

    return back()->with('success', 'Factura anulada correctamente.');
  }

  public function descargarPdf(FacturaInterna $facturaInterna)
  {
    $this->authorize('generatePDF', $facturaInterna);

    $facturaInterna->load(['usuario']);

    $pdf = Pdf::loadView('facturas_internas.pdf', ['factura' => $facturaInterna]);
    return $pdf->download('factura-' . $facturaInterna->nro_factura . '.pdf');
  }
}
