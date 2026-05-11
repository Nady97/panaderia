<?php

namespace App\Traits;

use App\Models\BitacoraCambio;
use Illuminate\Support\Facades\Auth;

/**
 * @method static void created(\Closure $callback)
 * @method static void updated(\Closure $callback) 
 * @method static void deleted(\Closure $callback)
 * 
 * @uses \Illuminate\Support\Facades\Auth::check()
 * @uses \Illuminate\Support\Facades\Auth::user()
*/

trait AuditableChanges
{
  public static function bootAuditableChanges(): void
  {
    static::created(function ($model) {
      $model->registrarCambio('crear');
    });

    static::updated(function ($model) {
      $model->registrarCambio('actualizar');
    });

    static::deleted(function ($model) {
      $model->registrarCambio('eliminar');
    });
  }

  protected function registrarCambio(string $accion): void
  {
    if (!auth()->check()) {
      return;
    }

    $usuario = auth()->user();
    if (!$usuario || empty($usuario->codigo)) {
      return;
    }

    $excluir = $this->obtenerCamposExcluidos();

    if ($accion === 'actualizar') {
      $cambios = array_diff_key($this->getChanges(), array_flip($excluir));
      if (empty($cambios)) {
        return;
      }
      $antes = array_intersect_key($this->getOriginal(), $cambios);
      $despues = array_intersect_key($this->getAttributes(), $cambios);
    } elseif ($accion === 'crear') {
      $antes = null;
      $despues = array_diff_key($this->getAttributes(), array_flip($excluir));
    } else {
      $antes = array_diff_key($this->getOriginal(), array_flip($excluir));
      $despues = null;
    }

    BitacoraCambio::create([
      'usuario_codigo' => $usuario->codigo,
      'modulo' => $this->resolverModulo(),
      'accion' => $accion,
      'descripcion' => $this->resolverDescripcion($accion),
      'datos_antes' => $antes,
      'datos_despues' => $despues,
      'created_at' => now(),
    ]);
  }

  protected function obtenerCamposExcluidos(): array
  {
    $base = ['created_at', 'updated_at', 'password', 'remember_token'];

    if (property_exists($this, 'auditExclude') && is_array($this->auditExclude)) {
      $base = array_merge($base, $this->auditExclude);
    }

    return array_values(array_unique($base));
  }

  protected function resolverModulo(): string
  {
    $mapa = [
      'Producto' => 'Inventario',
      'Categoria' => 'Inventario',
      'Insumo' => 'Inventario',
      'Receta' => 'Recetas',
      'Produccion' => 'Produccion',
      'Proveedor' => 'Proveedores',
      'Usuario' => 'Usuarios',
      'Rol' => 'Usuarios',
    ];

    $modelo = class_basename($this);

    return $mapa[$modelo] ?? 'Sistema';
  }

  protected function resolverDescripcion(string $accion): string
  {
    $verbo = match ($accion) {
      'crear' => 'Creo',
      'actualizar' => 'Modifico',
      'eliminar' => 'Elimino',
      default => ucfirst($accion),
    };

    $modelo = strtolower(class_basename($this));
    $nombre = $this->resolverNombreVisible();

    if ($nombre !== '') {
      return $verbo . ' ' . $modelo . ' "' . $nombre . '"';
    }

    return $verbo . ' ' . $modelo . ' #' . $this->getKey();
  }

  protected function resolverNombreVisible(): string
  {
    $candidatos = ['nombre', 'titulo', 'descripcion', 'codigo', 'empresa', 'lote_codigo'];

    foreach ($candidatos as $campo) {
      if (!empty($this->{$campo})) {
        return (string) $this->{$campo};
      }
    }

    return '';
  }
}
