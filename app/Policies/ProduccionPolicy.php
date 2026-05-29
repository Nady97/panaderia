<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Produccion;

class ProduccionPolicy
{
  /**
   * Determine if the user can view any models.
   */
  public function viewAny(Usuario $user): bool
  {
    return $user->hasPermission('produccion.view');
  }

  /**
   * Determine if the user can view the model.
   */
  public function view(Usuario $user, Produccion $produccion): bool
  {
    return $user->hasPermission('produccion.view');
  }

  /**
   * Determine if the user can create models.
   */
  public function create(Usuario $user): bool
  {
    return $user->hasPermission('produccion.create');
  }

  /**
   * Determine if the user can update the model.
   */
  public function update(Usuario $user, Produccion $produccion): bool
  {
    return $user->hasPermission('produccion.edit')
      && in_array($produccion->estado, ['planificado', 'en_proceso']);
  }

  /**
   * Determine if the user can delete the model.
   */
  public function delete(Usuario $user, Produccion $produccion): bool
  {
    return $user->hasPermission('produccion.delete')
      && $produccion->estado === 'planificado';
  }

  /**
   * Can mark production as in process
   */
  public function startProduction(Usuario $user, Produccion $produccion): bool
  {
    return $user->hasPermission('produccion.edit')
      && $produccion->estado === 'planificado';
  }

  /**
   * Can complete production (mark as completado)
   */
  public function completeProduction(Usuario $user, Produccion $produccion): bool
  {
    return $user->hasPermission('produccion.edit')
      && $produccion->estado === 'en_proceso';
  }

  /**
   * Can record waste/scrap
   */
  public function recordWaste(Usuario $user, Produccion $produccion): bool
  {
    return $user->hasPermission('produccion.edit')
      && in_array($produccion->estado, ['en_proceso', 'completado']);
  }

  /**
   * Can update quality observations
   */
  public function updateQuality(Usuario $user, Produccion $produccion): bool
  {
    return $user->hasPermission('produccion.edit');
  }
}
