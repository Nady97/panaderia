<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;

class Producto extends Model
{
    use AuditableChanges;
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'precio_venta',
        'precio_costo',
        'stock',
        'stock_minimo',
        'estado',
        'imagen',
        'categoria_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function recetas()
    {
        return $this->hasMany(Receta::class, 'producto_id');
    }
}
