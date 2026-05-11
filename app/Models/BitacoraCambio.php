<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraCambio extends Model
{
  protected $table = 'bitacora_cambios';
  public $timestamps = false;

  protected $fillable = [
    'usuario_codigo',
    'modulo',
    'accion',
    'descripcion',
    'datos_antes',
    'datos_despues',
    'created_at',
  ];

  protected $casts = [
    'datos_antes' => 'array',
    'datos_despues' => 'array',
    'created_at' => 'datetime',
  ];

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'usuario_codigo', 'codigo');
  }
}
