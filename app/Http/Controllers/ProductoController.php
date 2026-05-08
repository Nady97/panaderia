<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

class ProductoController extends Controller
{
    public function index()
    {
        // Optimización: Eager Loading de la relación categoría para evitar defecto de N+1 Consultas.
        $productos = Producto::with('categoria')->get();
        return view('productos.index', compact('productos'));
    }
    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }
    // Método para almacenar un nuevo producto en la base de datos
    public function store(StoreProductoRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create(array_merge($validated, [
            'estado' => 'activo'
        ]));

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente');
    }
    // Método para mostrar los detalles de un producto específico
    // MEJORA 1: Route Model Binding. En vez de recibir un $id, Laravel inyecta el objeto $producto automáticamente resuelto desde la BD.
    public function show(Producto $producto)
    {
        // Ya no necesitamos $producto = Producto::findOrFail($id); ¡Magia pura!
        return view('productos.show', compact('producto'));
    }
    // Método para mostrar el formulario de edición de un producto
    // Aplicando la misma mejora de inyección de dependencia (Route Model Binding)
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $validated = $request->validated();

        if ($request->hasFile('imagen')) {
            $this->deleteStoredImage($producto->imagen);
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    // Método para eliminar un producto específico
    public function destroy(Producto $producto)
    {
        try {
            $this->deleteStoredImage($producto->imagen);

            // Se elimina directamente, ya que el modelo Producto ya viene instanciado gracias al Route Model Binding
            $producto->delete();

            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado correctamente');
        } catch (\Illuminate\Database\QueryException $e) {
            // Verificar si el error es por violación de integridad (código 23000)
            if ($e->getCode() == 23000) {
                return redirect()->route('productos.index')
                    ->with('error', 'No se puede eliminar este producto porque tiene compras o ventas asociadas. Intenta cambiar su estado a "inactivo".');
            }

            // Si es otro error de base de datos, mostrar un mensaje genérico
            return redirect()->route('productos.index')
                ->with('error', 'Ocurrió un error en la base de datos al intentar eliminar el producto.');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'Ocurrió un error inesperado al intentar eliminar el producto.');
        }
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
