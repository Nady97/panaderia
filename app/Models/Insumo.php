<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;

class Insumo extends Model
{
    use AuditableChanges;
    protected $table = 'insumos';
    public $timestamps = false; // Only updated_at exists, but easier to turn off if no created_at

    protected $fillable = [
        'nombre',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
        'precio_compra_promedio'
    ];

    public function recetas()
    {
        return $this->belongsToMany(Receta::class, 'detalle_receta', 'insumo_id', 'receta_id')
            ->withPivot('id', 'cantidad_necesaria');
    }
}
