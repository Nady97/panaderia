<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\FacturaInterna;

class FacturaInternaPolicy
{
  /**
   * Determine if the user can view any models.
   */
  public function viewAny(Usuario $user): bool
  {
    return $user->hasPermission('facturas_internas.view');
  }

  /**
   * Determine if the user can view the model.
   */
  public function view(Usuario $user, FacturaInterna $factura): bool
  {
    return $user->hasPermission('facturas_internas.view');
  }

  /**
   * Determine if the user can create models.
   */
  public function create(Usuario $user): bool
  {
    return $user->hasPermission('facturas_internas.create');
  }

  /**
   * Determine if the user can update the model.
   */
  public function update(Usuario $user, FacturaInterna $factura): bool
  {
    return $user->hasPermission('facturas_internas.edit')
      && !in_array($factura->estado, ['cancelada', 'anulada']);
  }

  /**
   * Determine if the user can delete the model.
   */
  public function delete(Usuario $user, FacturaInterna $factura): bool
  {
    return $user->hasPermission('facturas_internas.delete')
      && $factura->estado !== 'anulada';
  }

  /**
   * Can generate PDF
   */
  public function generatePDF(Usuario $user, FacturaInterna $factura): bool
  {
    return $user->hasPermission('facturas_internas.view');
  }

  /**
   * Can mark invoice as cancelled/annulled
   */
  public function cancel(Usuario $user, FacturaInterna $factura): bool
  {
    return $user->hasPermission('facturas_internas.edit')
      && $factura->estado !== 'anulada';
  }

  /**
   * Can record payment
   */
  public function recordPayment(Usuario $user, FacturaInterna $factura): bool
  {
    return $user->hasPermission('facturas_internas.edit');
  }
}
