<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Categoria::query();

        // Búsqueda por nombre o descripción
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por estado activo/inactivo
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('activo', $request->status_filter === 'active' ? 1 : 0);
        }

        // Paginar sumando el conteo de productos de cada categoría
        $categorias = $query->withCount('productos')->paginate(10)->withQueryString();

        // Contadores para las tarjetas globales de la vista
        $totalCategorias = Categoria::count();
        $categoriasActivas = Categoria::where('activo', 1)->count();
        $categoriasInactivas = Categoria::where('activo', 0)->count();

        return view('categorias.index', compact('categorias', 'request', 'totalCategorias', 'categoriasActivas', 'categoriasInactivas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['nombre']);
        }
        if (!isset($data['activo'])) {
            $data['activo'] = true;
        }

        Categoria::create($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Categoria $categoria)
    {
        $query = $categoria->productos();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        $productos = $query->paginate(10)->withQueryString();

        return view('categorias.show', compact('categoria', 'productos', 'request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['nombre']);
        }
        if (!isset($data['activo'])) {
            $data['activo'] = false; // Checkboxes only send if checked
        }

        $categoria->update($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        // Prevent deleting if it has related products
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('categorias.index')->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
