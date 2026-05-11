<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permiso extends Model
{
  use HasFactory;

  protected $table = 'permisos';

  protected $fillable = [
    'nombre',
    'slug',
    'descripcion',
    'modulo',
    'activo',
  ];

  public function roles()
  {
    return $this->belongsToMany(Rol::class, 'permiso_rol', 'permiso_id', 'rol_id')
      ->withTimestamps();
  }
}
