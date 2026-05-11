<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Insumo;

class InsumoController extends Controller
{
    public function index(Request $request)
    {
        $query = Insumo::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nombre', 'like', "%{$search}%");
        }

        if ($request->filled('stock_filter') && $request->stock_filter !== 'all') {
            if ($request->stock_filter === 'low') {
                $query->where('stock_actual', '>', 0)
                    ->whereNotNull('stock_minimo')
                    ->whereColumn('stock_actual', '<=', 'stock_minimo');
            } elseif ($request->stock_filter === 'out') {
                $query->where('stock_actual', '<=', 0);
            } elseif ($request->stock_filter === 'ok') {
                $query->where(function ($q) {
                    $q->whereNull('stock_minimo')
                        ->orWhereColumn('stock_actual', '>', 'stock_minimo');
                });
            }
        }

        $insumos = $query->orderBy('nombre')->paginate(10)->withQueryString();

        $totalInsumos = Insumo::count();
        $stockBajo = Insumo::where('stock_actual', '>', 0)
            ->whereNotNull('stock_minimo')
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->count();
        $sinStock = Insumo::where('stock_actual', '<=', 0)->count();

        return view('insumos.index', compact('insumos', 'totalInsumos', 'stockBajo', 'sinStock'));
    }

    public function create()
    {
        return view('insumos.create');
    }

    public function show(Insumo $insumo)
    {
        $insumo->load('recetas');

        return view('insumos.show', compact('insumo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:insumos,nombre'],
            'unidad_medida' => ['required', 'string', 'max:20'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => ['nullable', 'numeric', 'min:0'],
            'precio_compra_promedio' => ['nullable', 'numeric', 'min:0'],
        ]);

        Insumo::create($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo registrado correctamente.');
    }

    public function edit(Insumo $insumo)
    {
        return view('insumos.edit', compact('insumo'));
    }

    public function update(Request $request, Insumo $insumo)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('insumos', 'nombre')->ignore($insumo->id)],
            'unidad_medida' => ['required', 'string', 'max:20'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => ['nullable', 'numeric', 'min:0'],
            'precio_compra_promedio' => ['nullable', 'numeric', 'min:0'],
        ]);

        $insumo->update($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo actualizado correctamente.');
    }

    public function destroy(Insumo $insumo)
    {
        $enProduccionActiva = DB::table('detalle_receta')
            ->join('producciones', 'detalle_receta.receta_id', '=', 'producciones.receta_id')
            ->where('detalle_receta.insumo_id', $insumo->id)
            ->whereIn('producciones.estado', ['planificado', 'en_proceso'])
            ->exists();

        if ($enProduccionActiva) {
            return redirect()->route('insumos.index')->with('error', 'No se puede eliminar el insumo porque esta en una produccion activa.');
        }

        $enReceta = DB::table('detalle_receta')
            ->where('insumo_id', $insumo->id)
            ->exists();

        if ($enReceta) {
            return redirect()->route('insumos.index')->with('error', 'No se puede eliminar el insumo porque esta asociado a una receta.');
        }

        $insumo->delete();

        return redirect()->route('insumos.index')->with('success', 'Insumo eliminado correctamente.');
    }
}
