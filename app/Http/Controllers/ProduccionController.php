<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\Producto;
use App\Models\Receta;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduccionController extends Controller
{
    public function index(Request $request)
    {
        $query = Produccion::with("producto")->orderBy("fecha_programada", "desc");
        
        if ($request->has("estado") && $request->estado != "") {
            $estadoMap = ["En Proceso" => "en_proceso", "Completado" => "completado", "Cancelado" => "fallido", "Planificado" => "planificado"];
            $query->where("estado", $estadoMap[$request->estado] ?? $request->estado);
        }

        // Buscador Inteligente: Busca por Lote, Descripción o Nombre del Producto
        if ($request->filled('search')) {
            $searchTerm = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('lote_codigo', 'like', $searchTerm)
                  ->orWhere('descripcion', 'like', $searchTerm)
                  ->orWhereHas('producto', function ($qProd) use ($searchTerm) {
                      $qProd->where('nombre', 'like', $searchTerm);
                  });
            });
        }

        $producciones = $query->paginate(10);
        return view("produccion.index", compact("producciones"));
    }

    public function create()
    {
        $productos = Producto::where("estado", "activo")->whereHas("recetas")->orderBy("nombre")->get();
        return view("produccion.create", compact("productos"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "producto_id" => "required|exists:productos,id",
            "cantidad" => "required|numeric|min:0.01",
            "fecha_produccion" => "required|date",
            "estado" => "required|in:planificado,en_proceso,completado,fallido",
            "observaciones" => "nullable|string"
        ]);

        $receta = Receta::where("producto_id", $validated["producto_id"])->first();

        if (!$receta) {
            return back()->with("error", "No hay receta activa asignada a este producto.");
        }

        $datos = [
            "lote_codigo" => "LOT-" . date("Ymd") . "-" . rand(100, 999),
            "descripcion" => "Producción",
            "cantidad_producida" => $validated["cantidad"],
            "fecha_programada" => $validated["fecha_produccion"],
            "estado" => $validated["estado"],
            "observaciones_calidad" => $validated["observaciones"] ?? "",
            "receta_id" => $receta->id,
            "usuario_codigo" => Auth::user()->codigo,
            "created_at" => now()
        ];
        
        if (in_array($validated["estado"], ["en_proceso", "completado"])) $datos["hora_inicio"] = now();
        if ($validated["estado"] === "completado") $datos["hora_fin"] = now();

        try {
            DB::transaction(function () use ($datos, $receta) {
                // Guarda la producción
                Produccion::create($datos);
                // Impacta el inventario atómicamente si procede (estado: completado)
                $this->manejarInventario(null, $receta, 0, $datos["cantidad_producida"], null, $datos["estado"]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->with("error", "Error de base de datos al guardar la producción: " . $e->getMessage());
        }

        return redirect()->route("produccion.index")->with("success", "Producción registrada exitosamente.");
    }

    public function show(Produccion $produccion)
    {
        $produccion->load(['producto', 'receta.insumos', 'usuario']);
        return view("produccion.show", compact("produccion"));
    }

    public function edit(Produccion $produccion)
    {
        $produccion->load("producto");
        $productos = Producto::where("estado", "activo")
            ->whereHas("recetas")
            ->orWhere("id", optional($produccion->producto)->id)
            ->orderBy("nombre")
            ->get();
        return view("produccion.edit", compact("produccion", "productos"));
    }

    public function update(Request $request, Produccion $produccion)
    {
        $validated = $request->validate([
            "producto_id" => "required|exists:productos,id",
            "cantidad" => "required|numeric|min:0.01",
            "fecha_produccion" => "required|date",
            "estado" => "required|in:planificado,en_proceso,completado,fallido",
            "observaciones" => "nullable|string"
        ]);

        $recetaNueva = Receta::where("producto_id", $validated["producto_id"])->first();
        if (!$recetaNueva) return back()->with("error", "No hay receta activa para el producto.");

        $datosActualizar = [
            "receta_id" => $recetaNueva->id,
            "cantidad_producida" => $validated["cantidad"],
            "fecha_programada" => $validated["fecha_produccion"],
            "estado" => $validated["estado"],
            "observaciones_calidad" => $validated["observaciones"] ?? "",
        ];

        if ($produccion->estado !== "completado" && $validated["estado"] === "completado") $datosActualizar["hora_fin"] = now();

        try {
            DB::transaction(function () use ($produccion, $recetaNueva, $validated, $datosActualizar) {
                // Hacer Rollback/Modificar inventario atómicamente primero
                $this->manejarInventario(
                    Receta::find($produccion->receta_id), 
                    $recetaNueva, 
                    $produccion->cantidad_producida, 
                    $validated["cantidad"], 
                    $produccion->estado, 
                    $validated["estado"]
                );

                // Actualizar la producción
                $produccion->update($datosActualizar);
            });
        } catch (\Exception $e) {
            return back()->withInput()->with("error", "Error de base de datos al actualizar la producción: " . $e->getMessage());
        }

        return redirect()->route("produccion.index")->with("success", "Producción actualizada.");
    }

    public function destroy(Produccion $produccion)
    {
        try {
            DB::transaction(function () use ($produccion) {
                // Revertir inventario si estaba completado, usando estado destino "fallido" que es neutro
                $this->manejarInventario(Receta::find($produccion->receta_id), null, $produccion->cantidad_producida, 0, $produccion->estado, "fallido");
                
                // Realizar el borrado de la orden
                $produccion->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route("produccion.index")->with("error", "Error crítico al intentar eliminar la orden: " . $e->getMessage());
        }

        return redirect()->route("produccion.index")->with("success", "Producción eliminada y saldos reversados.");
    }

    private function manejarInventario($vieja_receta, $nueva_receta, $vieja_cantidad, $nueva_cantidad, $estado_anterior, $estado_nuevo)
    {
        if ($estado_anterior === "completado" && $vieja_receta) {
            if ($vieja_receta->producto_id) Producto::where("id", $vieja_receta->producto_id)->decrement("stock", $vieja_cantidad);
            $rendimiento_v = max($vieja_receta->rendimiento_estimado, 1);
            foreach ($vieja_receta->insumos as $insumo) {
                $insumo->increment("stock_actual", ($insumo->pivot->cantidad_necesaria / $rendimiento_v) * $vieja_cantidad);
            }
        }

        if ($estado_nuevo === "completado" && $nueva_receta) {
            if ($nueva_receta->producto_id) Producto::where("id", $nueva_receta->producto_id)->increment("stock", $nueva_cantidad);
            $rendimiento_n = max($nueva_receta->rendimiento_estimado, 1);
            foreach ($nueva_receta->insumos as $insumo) {
                $insumo->decrement("stock_actual", ($insumo->pivot->cantidad_necesaria / $rendimiento_n) * $nueva_cantidad);
            }
        }
    }
}

