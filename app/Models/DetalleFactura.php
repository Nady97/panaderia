<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
  public $timestamps = false;
  protected $table = 'detalle_factura';
  protected $primaryKey = null;
  public $incrementing = false;

  protected $fillable = [
    'factura_interna_id',
    'producto_id',
    'cantidad',
    'precio_unitario',
    'subtotal',
    'descuento',
    'total_linea'
  ];

  protected $casts = [
    'cantidad' => 'decimal:2',
    'precio_unitario' => 'decimal:2',
    'subtotal' => 'decimal:2',
    'descuento' => 'decimal:2',
    'total_linea' => 'decimal:2',
  ];

  // Relaciones
  public function factura()
  {
    return $this->belongsTo(FacturaInterna::class, 'factura_interna_id');
  }

  public function producto()
  {
    return $this->belongsTo(Producto::class, 'producto_id');
  }

  // Obtener el proveedor más reciente que suministró este producto
  public function proveedorActual()
  {
    return $this->producto()
      ->with(['recetas' => function ($q) {
        $q->select('id', 'producto_id');
      }])
      ->first()
      ?->obtenerProveedorActual();
  }
}
