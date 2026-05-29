<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AuditableChanges;

class NotaCompra extends Model
{
  use HasFactory, AuditableChanges;

  protected $table = 'notas_compra';
  protected $primaryKey = 'id';

  protected $fillable = [
    'nro_comprobante',
    'proveedor_codigo',
    'fecha_pedido',
    'fecha_recepcion',
    'monto_total',
    'estado',
    'observaciones',
    'usuario_codigo'
  ];

  protected $casts = [
    'fecha_pedido' => 'datetime',
    'fecha_recepcion' => 'datetime',
    'monto_total' => 'decimal:2',
  ];

  protected $auditExclude = [
    'updated_at'
  ];

  // Relaciones
  public function proveedor()
  {
    return $this->belongsTo(Proveedor::class, 'proveedor_codigo', 'codigo');
  }

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'usuario_codigo', 'codigo');
  }

  public function detalles()
  {
    return $this->hasMany(DetalleNotaCompra::class, 'nota_compra_id');
  }

  // Relación con productos comprados
  public function productos()
  {
    return $this->hasMany(CompraProducto::class, 'nota_compra_id');
  }

  // Métodos auxiliares deshabilitados - estructura de tabla no soporta
  // public function actualizarMontoTotal() { }
  // public function generarNumeroNota() { }
}
