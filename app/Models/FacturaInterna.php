<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AuditableChanges;

class FacturaInterna extends Model
{
  use HasFactory, AuditableChanges;

  protected $table = 'facturas_internas';
  protected $primaryKey = 'id';

  protected $fillable = [
    'nro_factura',
    'total',
    'puntos_ganados',
    'fecha_emision',
    'estado',
    'motivo_anulacion',
    'usuario_codigo',
    'cliente_ci',
    'cliente_telefono',
    'cliente_direccion',
    'pedido_id'
  ];

  protected $casts = [
    'fecha_emision' => 'date',
    'total' => 'decimal:2',
    'puntos_ganados' => 'integer',
  ];

  protected $auditExclude = [
    'updated_at'
  ];

  // Relaciones
  // Relación con NotaCompra comentada - estructura de tabla no soporta esta relación
  // public function notaCompra()
  // {
  //   return $this->belongsTo(NotaCompra::class, 'nota_compra_id');
  // }

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'usuario_codigo', 'codigo');
  }

  // Relación con detalles de la factura
  public function detalles()
  {
    return $this->hasMany(DetalleFactura::class, 'factura_interna_id');
  }

  // Obtener todos los proveedores asociados a los productos de esta factura
  public function proveedoresAsociados()
  {
    return $this->detalles()
      ->with('producto')
      ->get()
      ->map(function ($detalle) {
        $proveedor = $detalle->producto->obtenerProveedorActual();
        return [
          'producto' => $detalle->producto,
          'proveedor' => $proveedor,
          'cantidad' => $detalle->cantidad
        ];
      })
      ->unique(function ($item) {
        return $item['proveedor']?->codigo;
      })
      ->values();
  }

  // Métodos auxiliares deshabilitados
  // public function generarNumeroFactura() { }
  // public function calcularMontos() { }
}
