<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\NotaCompra;

class NotaCompraPolicy
{
  /**
   * Determine if the user can view any models.
   */
  public function viewAny(Usuario $user): bool
  {
    return $user->hasPermission('notas_compra.view');
  }

  /**
   * Determine if the user can view the model.
   */
  public function view(Usuario $user, NotaCompra $notaCompra): bool
  {
    return $user->hasPermission('notas_compra.view');
  }

  /**
   * Determine if the user can create models.
   */
  public function create(Usuario $user): bool
  {
    return $user->hasPermission('notas_compra.create');
  }

  /**
   * Determine if the user can update the model.
   */
  public function update(Usuario $user, NotaCompra $notaCompra): bool
  {
    return $user->hasPermission('notas_compra.edit')
      && $notaCompra->estado === 'solicitado';
  }

  /**
   * Determine if the user can delete the model.
   */
  public function delete(Usuario $user, NotaCompra $notaCompra): bool
  {
    return $user->hasPermission('notas_compra.delete')
      && $notaCompra->estado === 'solicitado';
  }

  /**
   * Can mark nota as received (transition to recibido state)
   */
  public function markAsReceived(Usuario $user, NotaCompra $notaCompra): bool
  {
    return $user->hasPermission('notas_compra.edit')
      && in_array($notaCompra->estado, ['solicitado', 'en_transito']);
  }

  /**
   * Can add items to nota (only if solicitado)
   */
  public function addItems(Usuario $user, NotaCompra $notaCompra): bool
  {
    return $user->hasPermission('notas_compra.edit')
      && $notaCompra->estado === 'solicitado';
  }
}
