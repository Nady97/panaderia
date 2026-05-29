<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\SolicitudProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudProduccionController extends Controller
{
  public function index(Request $request)
  {
    $query = SolicitudProduccion::with([
      'produccion.receta.producto',
      'usuarioSolicitante',
      'usuarioAprobador'
    ]);

    if ($request->filled('estado')) {
      $query->where('estado', $request->estado);
    }

    if ($request->filled('tipo_urgencia')) {
      $query->where('tipo_urgencia', $request->tipo_urgencia);
    }

    $solicitudes = $query->orderBy('fecha_solicitud', 'desc')->paginate(10)->withQueryString();

    $estadisticas = [
      'total' => SolicitudProduccion::count(),
      'solicitadas' => SolicitudProduccion::where('estado', 'solicitada')->count(),
      'urgentes' => SolicitudProduccion::whereIn('tipo_urgencia', ['urgente', 'muy_urgente'])->count(),
      'aprobadas' => SolicitudProduccion::where('estado', 'aprobada')->count(),
    ];

    return view('solicitudes_produccion.index', compact('solicitudes', 'estadisticas'));
  }

  public function show(SolicitudProduccion $solicitudProduccion)
  {
    $solicitud = $solicitudProduccion->load([
      'produccion.receta.producto',
      'usuarioSolicitante',
      'usuarioAprobador'
    ]);
    return view('solicitudes_produccion.show', compact('solicitud'));
  }

  public function crear(Request $request, Produccion $produccion)
  {
    // Validar que la producción exista y esté en estado planificado
    if ($produccion->estado !== 'planificado') {
      return back()->withErrors(['error' => 'La producción debe estar en estado planificado.']);
    }

    $data = $request->validate([
      'tipo_urgencia' => ['required', 'in:normal,urgente,muy_urgente'],
      'motivo_urgencia' => ['required_if:tipo_urgencia,urgente,muy_urgente', 'nullable', 'string', 'max:500'],
    ]);

    try {
      $solicitud = new SolicitudProduccion();
      $solicitud->produccion_id = $produccion->id;
      $solicitud->tipo_urgencia = $data['tipo_urgencia'];
      $solicitud->motivo_urgencia = $data['motivo_urgencia'] ?? null;
      $solicitud->usuario_solicitante = Auth::user()->codigo;
      $solicitud->estado = 'solicitada';
      $solicitud->fecha_solicitud = now();
      $solicitud->save();

      return redirect()->route('solicitudes_produccion.show', $solicitud->id)
        ->with('success', 'Solicitud de producción ' . ($data['tipo_urgencia'] !== 'normal' ? strtoupper($data['tipo_urgencia']) : '') . ' creada.');
    } catch (\Exception $e) {
      return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
  }

  public function aprobar(Request $request, SolicitudProduccion $solicitudProduccion)
  {
    if ($solicitudProduccion->estado !== 'solicitada') {
      return back()->withErrors(['error' => 'Solo se pueden aprobar solicitudes en estado "solicitada".']);
    }

    $data = $request->validate([
      'comentario_aprobador' => ['nullable', 'string', 'max:500'],
    ]);

    DB::beginTransaction();
    try {
      $solicitudProduccion->aprobar(
        Auth::user()->codigo,
        $data['comentario_aprobador'] ?? null
      );

      DB::commit();
      return back()->with('success', 'Solicitud aprobada. La producción iniciará su proceso.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
  }

  public function rechazar(Request $request, SolicitudProduccion $solicitudProduccion)
  {
    if ($solicitudProduccion->estado !== 'solicitada') {
      return back()->withErrors(['error' => 'Solo se pueden rechazar solicitudes en estado "solicitada".']);
    }

    $data = $request->validate([
      'comentario_aprobador' => ['required', 'string', 'max:500'],
    ]);

    try {
      $solicitudProduccion->rechazar(
        Auth::user()->codigo,
        $data['comentario_aprobador']
      );

      return back()->with('success', 'Solicitud rechazada.');
    } catch (\Exception $e) {
      return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
  }
}
