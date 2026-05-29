<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProductoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Producto::class);

        // Optimización: Eager Loading de la relación categoría para evitar defecto de N+1 Consultas.
        $productos = Producto::with('categoria')->paginate(10)->withQueryString();

        $totalProductos = Producto::count();
        $totalActivos = Producto::where('estado', 'activo')->count();
        $agotados = Producto::where('stock', 0)->count();
        $descontinuados = Producto::where('estado', 'descontinuado')->count();
        $stockBajo = Producto::where('stock', '>', 0)
            ->whereRaw('stock <= COALESCE(NULLIF(stock_minimo, 0), 5)')
            ->count();

        return view('productos.index', compact(
            'productos',
            'totalProductos',
            'totalActivos',
            'stockBajo',
            'agotados',
            'descontinuados'
        ));
    }

    public function create()
    {
        $this->authorize('create', Producto::class);

        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    // Método para almacenar un nuevo producto en la base de datos
    public function store(StoreProductoRequest $request)
    {
        $this->authorize('create', Producto::class);

        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombre = time() . '.' . $imagen->getClientOriginalExtension();
            
            // Crear directorio si no existe
            if (!Storage::disk('public')->exists('productos')) {
                Storage::disk('public')->makeDirectory('productos');
            }
            
            $ruta = storage_path('app/public/productos/' . $nombre);

            // Redimensionar imagen (usando Intervention Image correctamente)
            $image = Image::make($imagen)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save($ruta);

            $data['imagen'] = 'productos/' . $nombre;
        }

        $producto = Producto::create($data);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    // Método para mostrar los detalles de un producto específico
    public function show(Producto $producto)
    {
        $this->authorize('view', $producto);

        return view('productos.show', compact('producto'));
    }

    // Método para mostrar el formulario de edición de un producto
    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);

        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    // CORREGIDO: Usar Route Model Binding consistentemente
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $this->authorize('update', $producto);

        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $imagen = $request->file('imagen');
            $nombre = time() . '.' . $imagen->getClientOriginalExtension();
            
            // Crear directorio si no existe
            if (!Storage::disk('public')->exists('productos')) {
                Storage::disk('public')->makeDirectory('productos');
            }
            
            $ruta = storage_path('app/public/productos/' . $nombre);

            // CORREGIDO: Usar la sintaxis correcta de Intervention Image
            $image = Image::make($imagen)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save($ruta);

            $data['imagen'] = 'productos/' . $nombre;
        }

        $producto->update($data);

        return redirect()->route('productos.edit', $producto->id)->with('success', 'Producto actualizado exitosamente.');
    }

    // Método para eliminar un producto específico
    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);

        try {
            $this->deleteStoredImage($producto->imagen);

            // Se elimina directamente, ya que el modelo Producto ya viene instanciado
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
