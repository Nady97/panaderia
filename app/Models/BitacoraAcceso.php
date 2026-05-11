<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraAcceso extends Model
{
  protected $table = 'bitacora_accesos';
  public $timestamps = false;

  protected $fillable = [
    'usuario_codigo',
    'accion',
    'ip_address',
    'user_agent',
    'created_at',
  ];

  protected $casts = [
    'created_at' => 'datetime',
  ];

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'usuario_codigo', 'codigo');
  }
}
