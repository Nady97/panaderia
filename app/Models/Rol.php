<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación con usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol_id', 'id');
    }
    
    //  Comenta esta línea si no existe el modelo Permiso
    // public function permisos()
    // {
    //     return $this->belongsToMany(Permiso::class, 'permiso_rol', 'rol_id', 'permiso_id');
    // }
}