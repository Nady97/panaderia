<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Obtener el proveedor más reciente que suministró este producto
    public function obtenerProveedorActual()
    {
        // Busca en notas de compra ordenadas por fecha desc
        $notaCompra = \DB::table('notas_compra')
            ->join('compra_producto', 'notas_compra.id', '=', 'compra_producto.nota_compra_id')
            ->where('compra_producto.producto_id', $this->id)
            ->select('notas_compra.proveedor_codigo')
            ->orderBy('notas_compra.fecha_pedido', 'desc')
            ->first();

        if ($notaCompra) {
            return Proveedor::where('codigo', $notaCompra->proveedor_codigo)->first();
        }

        return null;
    }
}
