<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receta;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Insumo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RecetaController extends Controller
{
    public function index(Request $request)
    {
        $query = Receta::with(['producto', 'usuario']);

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhereHas('producto', function ($q2) use ($request) {
                      $q2->where('nombre', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('estado_filter') && $request->estado_filter !== 'all') {
            $query->where('estado', $request->estado_filter);
        }

        $recetas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        $totalRecetas = Receta::count();
        $recetasActivas = Receta::where('estado', 'activa')->count();
        $recetasBorrador = Receta::where('estado', 'borrador')->count();
        $recetasObsoletas = Receta::where('estado', 'obsoleta')->count();

        return view('recetas.index', compact(
            'recetas', 
            'totalRecetas', 
            'recetasActivas', 
            'recetasBorrador', 
            'recetasObsoletas'
        ));
    }

    public function create()
    {
        $productos = Producto::where('es_producido', true)->orWhere('es_producido', 1)->get();
        if ($productos->isEmpty()) {
            $productos = Producto::all();
        }
        return view('recetas.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'rendimiento_estimado' => 'required|numeric|min:0.01',
            'tiempo_preparacion_min' => 'required|integer|min:1',
            'instrucciones' => 'nullable|string',
            'estado' => 'required|in:activa,borrador,obsoleta',
            'producto_id' => 'required|exists:productos,id',
        ]);

        $receta = new Receta($request->all());
        $receta->usuario_codigo = Auth::user() ? Auth::user()->codigo : Usuario::first()->codigo;
        $receta->save();

        return redirect()->route('recetas.index')->with('success', 'Receta creada exitosamente.');
    }

    public function show($id)
    {
        $receta = Receta::with(['producto', 'usuario', 'insumos'])->findOrFail($id);
        $insumosDisponibles = Insumo::all();
        return view('recetas.show', compact('receta', 'insumosDisponibles'));
    }

    public function edit($id)
    {
        $receta = Receta::with('insumos')->findOrFail($id);
        $productos = Producto::all();
        $insumosDisponibles = Insumo::orderBy('nombre')->get();

        return view('recetas.edit', compact('receta', 'productos', 'insumosDisponibles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'rendimiento_estimado' => 'required|numeric|min:0.01',
            'tiempo_preparacion_min' => 'required|integer|min:1',
            'instrucciones' => 'nullable|string',
            'estado' => 'required|in:activa,borrador,obsoleta',
            'producto_id' => 'required|exists:productos,id',
        ]);

        $receta = Receta::findOrFail($id);
        $receta->update($request->all());

        return redirect()->route('recetas.edit', $id)->with('success', 'Receta actualizada exitosamente.');
    }

    public function addInsumo(Request $request, $recetaId)
    {
        $request->validate([
            'insumo_id' => 'required|exists:insumos,id',
            'cantidad_necesaria' => 'required|numeric|min:0.0001'
        ]);

        DB::table('detalle_receta')->insert([
            'receta_id' => $recetaId,
            'insumo_id' => $request->insumo_id,
            'cantidad_necesaria' => $request->cantidad_necesaria
        ]);

        return back()->with('success', 'Insumo agregado a la receta exitosamente.');
    }

    public function removeInsumo($recetaId, $pivotId)
    {
        DB::table('detalle_receta')->where('id', $pivotId)->delete();
        return back()->with('success', 'Insumo quitado de la receta.');
    }

    public function downloadPdf($id)
    {
        $receta = Receta::with(['producto', 'usuario', 'insumos'])->findOrFail($id);
        $pdf = app('dompdf.wrapper')->loadView('recetas.pdf', compact('receta'));
        return $pdf->download('receta-' . str_replace(' ', '-', strtolower($receta->nombre)) . '.pdf');
    }

    public function destroy($id)
    {
        $receta = Receta::findOrFail($id);
        $receta->delete();

        return redirect()->route('recetas.index')->with('success', 'Receta eliminada exitosamente.');
    }
}
