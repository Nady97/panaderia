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

    public function productos()
    {
        return $this->hasMany(NotaCompraProducto::class, 'nota_compra_id');
    }

    // Scopes con tipo de dato
    public function scopeSolicitado($query)
    {
        return $query->where('estado', 'solicitado');
    }

    public function scopeRecibido($query)
    {
        return $query->where('estado', 'recibido');
    }

    public function scopeCancelado($query)
    {
        return $query->where('estado', 'cancelado');
    }

    // Método para actualizar monto total

// En app/Models/NotaCompra.php

protected static function boot()
{
    parent::boot();
    
    static::creating(function ($nota) {
        if (!$nota->fecha_pedido) {
            $nota->fecha_pedido = now();
        }
        if (!$nota->estado) {
            $nota->estado = 'solicitado';
        }
        if (!$nota->monto_total) {
            $nota->monto_total = 0;
        }
    });
    
    static::created(function ($nota) {
        if (!$nota->nro_comprobante) {
            // Generar número correlativo simple
            $numero = str_pad($nota->id, 4, '0', STR_PAD_LEFT);
            $nota->nro_comprobante = "NC-{$numero}";
            $nota->saveQuietly();
        }
    });
}
public function actualizarMontoTotal()
{
    $totalProductos = $this->productos()->sum('subtotal') ?? 0;
    $totalDetalles = $this->detalles()->sum('subtotal') ?? 0;
    $this->monto_total = $totalProductos + $totalDetalles;
    $this->saveQuietly();
    return $this->monto_total;
}

}