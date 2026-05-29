<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraProducto extends Model
{
  public $timestamps = false;
  protected $table = 'compra_producto';
  protected $primaryKey = null;
  public $incrementing = false;

  protected $fillable = [
    'nota_compra_id',
    'producto_id',
    'cantidad',
    'precio_compra_unitario',
    'subtotal'
  ];

  protected $casts = [
    'cantidad' => 'integer',
    'precio_compra_unitario' => 'decimal:2',
    'subtotal' => 'decimal:2',
  ];

  // Relaciones
  public function notaCompra()
  {
    return $this->belongsTo(NotaCompra::class, 'nota_compra_id');
  }

  public function producto()
  {
    return $this->belongsTo(Producto::class, 'producto_id');
  }
}
