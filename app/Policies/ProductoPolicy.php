<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Producto;

class ProductoPolicy
{
  /**
   * Determine if the user can view any models.
   */
  public function viewAny(Usuario $user): bool
  {
    return $user->hasPermission('productos.view');
  }

  /**
   * Determine if the user can view the model.
   */
  public function view(Usuario $user, Producto $producto): bool
  {
    return $user->hasPermission('productos.view');
  }

  /**
   * Determine if the user can create models.
   */
  public function create(Usuario $user): bool
  {
    return $user->hasPermission('productos.create');
  }

  /**
   * Determine if the user can update the model.
   */
  public function update(Usuario $user, Producto $producto): bool
  {
    return $user->hasPermission('productos.edit');
  }

  /**
   * Determine if the user can delete the model.
   */
  public function delete(Usuario $user, Producto $producto): bool
  {
    return $user->hasPermission('productos.delete')
      && $producto->estado !== 'descontinuado'; // Safety: avoid deleting discontinued items
  }

  /**
   * Can update stock
   */
  public function updateStock(Usuario $user, Producto $producto): bool
  {
    return $user->hasPermission('productos.edit');
  }

  /**
   * Can export product list
   */
  public function export(Usuario $user): bool
  {
    return $user->hasPermission('productos.view');
  }
}
