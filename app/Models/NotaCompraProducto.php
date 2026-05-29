<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaCompraProducto extends Model
{
    use HasFactory;

    protected $table = 'nota_compra_productos';
    
    protected $fillable = [
        'nota_compra_id',
        'producto_id',
        'cantidad',
        'precio_compra_unitario',
        'subtotal'
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_compra_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function notaCompra()
    {
        return $this->belongsTo(NotaCompra::class, 'nota_compra_id', 'id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}