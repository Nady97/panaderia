<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;

class Proveedor extends Model
{
    use HasFactory, AuditableChanges;

    protected $table = 'proveedores';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo',
        'nombre_contacto',
        'empresa',
        'nit',
        'telefono',
        'email',
        'direccion',
        'estado'
    ];

    // Relaciones
    public function notasCompra()
    {
        return $this->hasMany(NotaCompra::class, 'proveedor_codigo', 'codigo');
    }
}
