<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AuditableChanges;

class SolicitudProduccion extends Model
{
  use HasFactory, AuditableChanges;

  protected $table = 'solicitudes_produccion';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'produccion_id',
    'tipo_urgencia',
    'motivo_urgencia',
    'estado',
    'usuario_solicitante',
    'usuario_aprobador',
    'comentario_aprobador',
    'fecha_solicitud',
    'fecha_aprobacion'
  ];

  protected $casts = [
    'fecha_solicitud' => 'datetime',
    'fecha_aprobacion' => 'datetime',
  ];

  // Relaciones
  public function produccion()
  {
    return $this->belongsTo(Produccion::class, 'produccion_id');
  }

  public function usuarioSolicitante()
  {
    return $this->belongsTo(Usuario::class, 'usuario_solicitante', 'codigo');
  }

  public function usuarioAprobador()
  {
    return $this->belongsTo(Usuario::class, 'usuario_aprobador', 'codigo');
  }

  // Métodos auxiliares
  public function esUrgente()
  {
    return in_array($this->tipo_urgencia, ['urgente', 'muy_urgente']);
  }

  public function aprobar($usuarioCodigoAprobador, $comentario = null)
  {
    $this->estado = 'aprobada';
    $this->usuario_aprobador = $usuarioCodigoAprobador;
    $this->fecha_aprobacion = now();
    $this->comentario_aprobador = $comentario;
    $this->save();

    // Si es urgente, cambiar estado de producción a en_proceso
    if ($this->esUrgente()) {
      $this->produccion->update(['estado' => 'en_proceso']);
    }
  }

  public function rechazar($usuarioCodigoAprobador, $comentario = null)
  {
    $this->estado = 'rechazada';
    $this->usuario_aprobador = $usuarioCodigoAprobador;
    $this->fecha_aprobacion = now();
    $this->comentario_aprobador = $comentario;
    $this->save();
  }
}
