<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProveedorRequest;
use App\Http\Requests\UpdateProveedorRequest;

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
    public function store(StoreProveedorRequest $request)
    {
        $data = $request->validated();
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

    public function update(UpdateProveedorRequest $request, string $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->validated());
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
