<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableChanges;

class Produccion extends Model
{
    use HasFactory, AuditableChanges;

    protected $table = 'producciones';

    // La tabla NO tiene timestamps updated_at
    public $timestamps = false;

    protected $fillable = [
        'lote_codigo',
        'descripcion',
        'fecha_programada',
        'hora_inicio',
        'hora_fin',
        'fecha_inicio_real',
        'fecha_fin_real',
        'estado',
        'cantidad_producida',
        'observaciones_calidad',
        'receta_id',
        'usuario_codigo',
        'usuario_responsable_codigo',
        'created_at'
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'fecha_inicio_real' => 'datetime',
        'fecha_fin_real' => 'datetime',
        'cantidad_producida' => 'decimal:2',
        'created_at' => 'datetime'
    ];

    protected $attributes = [
        'estado' => 'planificado'
    ];

    /**
     * Relación con Receta real de la base de datos
     */
    public function receta()
    {
        return $this->belongsTo(Receta::class, 'receta_id');
    }

    /**
     * Accessor o relación directa hacia Producto (atravesando receta)
     */
    public function producto()
    {
        // En Laravel 11/12 se puede usar hasOneThrough para BelongsTo pero la forma más segura 
        // y retrocompatible sin tocar la BD es apuntar a un BelongsTo falso si no queremos cambiar scopes,
        // O mejor: usar la relación anidada
        return $this->hasOneThrough(
            Producto::class,
            Receta::class,
            'id',          // Foreign key en recetas (local key de produccion apunta a esta)
            'id',          // Foreign key en productos (local key de receta apunta a esta)
            'receta_id',   // Local key en produccion
            'producto_id'  // Local key en receta
        );
    }

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_codigo', 'codigo');
    }

    /**
     * Relación con Usuario Responsable del Proceso
     */
    public function usuarioResponsable()
    {
        return $this->belongsTo(Usuario::class, 'usuario_responsable_codigo', 'codigo');
    }

    /**
     * Accessor para cantidad (compatibilidad)
     */
    public function getCantidadAttribute()
    {
        return $this->cantidad_producida;
    }

    /**
     * Accessor para fecha_produccion (compatibilidad)
     */
    public function getFechaProduccionAttribute()
    {
        return $this->fecha_programada;
    }

    /**
     * Accessor para observaciones (compatibilidad)
     */
    public function getObservacionesAttribute()
    {
        return $this->observaciones_calidad;
    }

    /**
     * Accessor para producto_id de la receta (compatibilidad)
     */
    public function getProductoIdAttribute()
    {
        return $this->receta ? $this->receta->producto_id : null;
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    public function scopeCompletado($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopePlanificado($query)
    {
        return $query->where('estado', 'planificado');
    }
}
