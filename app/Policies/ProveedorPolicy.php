<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Proveedor;

class ProveedorPolicy
{
  /**
   * Determine if the user can view any models.
   */
  public function viewAny(Usuario $user): bool
  {
    return $user->hasPermission('proveedores.view');
  }

  /**
   * Determine if the user can view the model.
   */
  public function view(Usuario $user, Proveedor $proveedor): bool
  {
    return $user->hasPermission('proveedores.view');
  }

  /**
   * Determine if the user can create models.
   */
  public function create(Usuario $user): bool
  {
    return $user->hasPermission('proveedores.create');
  }

  /**
   * Determine if the user can update the model.
   */
  public function update(Usuario $user, Proveedor $proveedor): bool
  {
    return $user->hasPermission('proveedores.edit');
  }

  /**
   * Determine if the user can delete the model.
   */
  public function delete(Usuario $user, Proveedor $proveedor): bool
  {
    return $user->hasPermission('proveedores.delete')
      && $proveedor->estado === 'activo'; // Only deactivate, don't hard delete
  }

  /**
   * Can activate/deactivate supplier
   */
  public function toggleStatus(Usuario $user, Proveedor $proveedor): bool
  {
    return $user->hasPermission('proveedores.edit');
  }

  /**
   * Can view purchase history for supplier
   */
  public function viewHistory(Usuario $user, Proveedor $proveedor): bool
  {
    return $user->hasPermission('proveedores.view');
  }

  /**
   * Can export supplier list
   */
  public function export(Usuario $user): bool
  {
    return $user->hasPermission('proveedores.view');
  }
}
