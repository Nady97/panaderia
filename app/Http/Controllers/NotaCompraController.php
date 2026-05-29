<?php

namespace App\Http\Controllers;

use App\Models\NotaCompra;
use App\Models\DetalleNotaCompra;
use App\Models\Insumo;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotaCompraController extends Controller
{
  public function index(Request $request)
  {
    $this->authorize('viewAny', NotaCompra::class);

    $query = NotaCompra::with(['proveedor', 'usuario', 'detalles.insumo', 'productos.producto']);

    if ($request->filled('search')) {
      $search = trim($request->search);
      $query->where(function ($q) use ($search) {
        $q->where('nro_comprobante', 'like', "%{$search}%")
          ->orWhere('observaciones', 'like', "%{$search}%")
          ->orWhereHas('proveedor', function ($q2) use ($search) {
            $q2->where('nombre', 'like', "%{$search}%");
          });
      });
    }

    if ($request->filled('estado')) {
      $query->where('estado', $request->estado);
    }

    if ($request->filled('proveedor_codigo')) {
      $query->where('proveedor_codigo', $request->proveedor_codigo);
    }

    $notas = $query->orderBy('fecha_pedido', 'desc')->paginate(10)->withQueryString();
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

    $notaCompra->load(['proveedor', 'usuario', 'detalles.insumo', 'productos.producto']);
    return view('notas_compra.show', ['nota' => $notaCompra]);
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
      $notaCompra->estado = 'recibido';
      $notaCompra->fecha_recepcion = now();
      $notaCompra->save();

      DB::commit();
      return back()->with('success', 'Nota marcada como recibida.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
  }

  public function agregarDetalle(Request $request, NotaCompra $notaCompra)
  {
    $this->authorize('addItems', $notaCompra);

    if ($notaCompra->estado !== 'solicitado') {
      return response()->json(['error' => 'La nota debe estar solicitada'], 403);
    }

    $data = $request->validate([
      'insumo_id' => ['required', 'exists:insumos,id'],
      'cantidad' => ['required', 'numeric', 'min:0.01'],
      'precio_unitario' => ['required', 'numeric', 'min:0.01'],
    ]);

    $detalle = new DetalleNotaCompra();
    $detalle->nota_compra_id = $notaCompra->id;
    $detalle->insumo_id = $data['insumo_id'];
    $detalle->cantidad = $data['cantidad'];
    $detalle->precio_unitario = $data['precio_unitario'];
    $detalle->subtotal = $data['cantidad'] * $data['precio_unitario'];
    $detalle->save();

    $notaCompra->actualizarMontoTotal();

    return response()->json(['success' => 'Detalle agregado correctamente', 'detalle' => $detalle]);
  }

  public function eliminarDetalle(DetalleNotaCompra $detalle)
  {
    $nota = $detalle->notaCompra;

    $this->authorize('addItems', $nota);

    if ($nota->estado !== 'borrador') {
      return response()->json(['error' => 'La nota debe estar en borrador'], 403);
    }

    $detalle->delete();
    $nota->actualizarMontoTotal();

    return response()->json(['success' => 'Detalle eliminado correctamente']);
  }
}
