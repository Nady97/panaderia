<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleNotaCompra extends Model
{
  use HasFactory;

  protected $table = 'detalle_notas_compra';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nota_compra_id',
    'insumo_id',
    'cantidad',
    'precio_unitario',
    'subtotal'
  ];

  protected $casts = [
    'precio_unitario' => 'decimal:2',
    'subtotal' => 'decimal:2',
  ];

  // Relaciones
  public function notaCompra()
  {
    return $this->belongsTo(NotaCompra::class, 'nota_compra_id');
  }

  public function insumo()
  {
    return $this->belongsTo(Insumo::class, 'insumo_id');
  }
}
