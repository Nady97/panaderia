<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    use AuditableChanges;
    protected $table = 'productos';

    protected $fillable = [

        'codigo', 'nombre', 'descripcion', 'precio_venta', 'precio_costo',
        'stock_actual', 'stock_minimo', 'imagen', 'estado', 'categoria_id'

    ];
     protected $casts = [
        'precio_venta' => 'decimal:2',
        'precio_costo' => 'decimal:2',
        'stock_actual' => 'decimal:2'
    ];

    
    public function notasCompra()
    {
        return $this->belongsToMany(NotaCompra::class, 'nota_compra_productos')
                    ->withPivot('cantidad', 'precio_compra_unitario', 'subtotal')
                    ->withTimestamps();
    }

    
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
