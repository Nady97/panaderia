<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Proveedor::query();

        // Búsqueda por empresa, nombre_contacto o nit
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('empresa', 'like', "%{$search}%")
                  ->orWhere('nombre_contacto', 'like', "%{$search}%")
                  ->orWhere('nit', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        // Filtro por Estado
        if ($request->filled('estado_filter') && $request->estado_filter !== 'all') {
            $query->where('estado', $request->estado_filter);
        }

        // Paginación con queries
        $proveedores = $query->paginate(10)->withQueryString();

        // Estadísticas
        $totalProveedores = Proveedor::count();
        $proveedoresActivos = Proveedor::where('estado', 'activo')->count();
        $proveedoresInactivos = Proveedor::where('estado', 'inactivo')->count();
        $proveedoresSuspendidos = Proveedor::where('estado', 'suspendido')->count();

        return view('proveedores.index', compact(
            'proveedores', 
            'totalProveedores', 
            'proveedoresActivos', 
            'proveedoresInactivos',
            'proveedoresSuspendidos'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:proveedores,codigo',
            'nombre_contacto' => 'required|string|max:60',
            'empresa' => 'required|string|max:60',
            'nit' => 'nullable|string|max:20|unique:proveedores,nit',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string',
            'estado' => 'nullable|in:activo,suspendido,inactivo'
        ]);

        $data = $request->all();
        if (empty($data['estado'])) {
            $data['estado'] = 'activo';
        }

        Proveedor::create($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, string $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nombre_contacto' => 'required|string|max:60',
            'empresa' => 'required|string|max:60',
            'nit' => 'nullable|string|max:20|unique:proveedores,nit,' . $id . ',codigo',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string',
            'estado' => 'required|in:activo,suspendido,inactivo'
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
