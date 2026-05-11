<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;

class Receta extends Model
{
    use AuditableChanges;
    protected $table = 'recetas';

    protected $fillable = [
        'nombre',
        'rendimiento_estimado',
        'tiempo_preparacion_min',
        'instrucciones',
        'estado',
        'producto_id',
        'usuario_codigo'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_codigo', 'codigo');
    }

    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'detalle_receta', 'receta_id', 'insumo_id')
            ->withPivot('id', 'cantidad_necesaria');
    }
}
