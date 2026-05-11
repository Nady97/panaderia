<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;

class Categoria extends Model
{
    use AuditableChanges;
    protected $fillable = ['nombre', 'slug', 'descripcion', 'imagen', 'activo'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
