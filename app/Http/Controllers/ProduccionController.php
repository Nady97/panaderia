<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\Producto;
use App\Models\Receta;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProduccionRequest;
use App\Http\Requests\UpdateProduccionRequest;

class ProduccionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Produccion::class);

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
        $this->authorize('create', Produccion::class);

        $productos = Producto::where("estado", "activo")->whereHas("recetas")->orderBy("nombre")->get();
        return view("produccion.create", compact("productos"));
    }

    public function store(StoreProduccionRequest $request)
    {
        $this->authorize('create', Produccion::class);

        $validated = $request->validated();
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
        $this->authorize('view', $produccion);

        $produccion->load(['producto', 'receta.insumos', 'usuario']);
        return view("produccion.show", compact("produccion"));
    }

    public function edit(Produccion $produccion)
    {
        $this->authorize('update', $produccion);

        $produccion->load("producto");
        $productos = Producto::where("estado", "activo")
            ->whereHas("recetas")
            ->orWhere("id", optional($produccion->producto)->id)
            ->orderBy("nombre")
            ->get();
        return view("produccion.edit", compact("produccion", "productos"));
    }

    public function update(UpdateProduccionRequest $request, Produccion $produccion)
    {
        $this->authorize('update', $produccion);

        $validated = $request->validated();

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
        $this->authorize('delete', $produccion);

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

    // ============================================
    // CU-15: Iniciar Proceso de Producción
    // ============================================
    public function iniciarProceso(Produccion $produccion)
    {
        if ($produccion->estado !== 'planificado') {
            return back()->withErrors(['error' => 'Solo se pueden iniciar procesos en estado planificado.']);
        }

        try {
            $produccion->estado = 'en_proceso';
            $produccion->fecha_inicio_real = now();
            $produccion->usuario_responsable_codigo = Auth::user()->codigo;
            $produccion->save();

            return back()->with('success', 'Proceso de producción iniciado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ============================================
    // CU-16: Finalizar Proceso de Producción
    // ============================================
    public function finalizarProceso(Request $request, Produccion $produccion)
    {
        if ($produccion->estado !== 'en_proceso') {
            return back()->withErrors(['error' => 'Solo se pueden finalizar procesos en estado "en proceso".']);
        }

        $data = $request->validate([
            'cantidad_producida' => ['required', 'numeric', 'min:0.01'],
            'observaciones_calidad' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::beginTransaction();
        try {
            // Actualizar inventario del producto final
            if ($produccion->receta && $produccion->receta->producto_id) {
                Producto::where('id', $produccion->receta->producto_id)
                    ->increment('stock', $data['cantidad_producida']);
            }

            // Reducir stock de insumos según receta
            if ($produccion->receta) {
                $rendimiento = max($produccion->receta->rendimiento_estimado, 1);
                foreach ($produccion->receta->insumos as $insumo) {
                    $cantidadUsar = ($insumo->pivot->cantidad_necesaria / $rendimiento) * $data['cantidad_producida'];
                    $insumo->decrement('stock_actual', $cantidadUsar);
                }
            }

            // Actualizar producción
            $produccion->estado = 'completado';
            $produccion->cantidad_producida = $data['cantidad_producida'];
            $produccion->observaciones_calidad = $data['observaciones_calidad'] ?? $produccion->observaciones_calidad;
            $produccion->fecha_fin_real = now();
            $produccion->save();

            DB::commit();
            return back()->with('success', 'Proceso de producción finalizado y stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ============================================
    // CU-17: Asignar Responsable de Producción
    // ============================================
    public function asignarResponsable(Request $request, Produccion $produccion)
    {
        $data = $request->validate([
            'usuario_responsable_codigo' => ['required', 'exists:usuarios,codigo'],
        ]);

        try {
            $produccion->usuario_responsable_codigo = $data['usuario_responsable_codigo'];
            $produccion->save();

            $usuario = \App\Models\Usuario::where('codigo', $data['usuario_responsable_codigo'])->first();
            return back()->with('success', 'Responsable asignado: ' . $usuario->nombre);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
}
