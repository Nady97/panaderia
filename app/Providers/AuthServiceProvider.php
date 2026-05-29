<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\NotaCompra;
use App\Models\Producto;
use App\Models\Produccion;
use App\Models\FacturaInterna;
use App\Models\Proveedor;
use App\Policies\NotaCompraPolicy;
use App\Policies\ProductoPolicy;
use App\Policies\ProduccionPolicy;
use App\Policies\FacturaInternaPolicy;
use App\Policies\ProveedorPolicy;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The model to policy mappings for the application.
   *
   * @var array<class-string, class-string>
   */
  protected $policies = [
    NotaCompra::class => NotaCompraPolicy::class,
    Producto::class => ProductoPolicy::class,
    Produccion::class => ProduccionPolicy::class,
    FacturaInterna::class => FacturaInternaPolicy::class,
    Proveedor::class => ProveedorPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   */
  public function boot(): void
  {
    $this->registerPolicies();

    // Define any gates if needed (currently using permissions)
    Gate::define('is-admin', function ($user) {
      return $user->roles()
        ->where('nombre', 'Administrador')
        ->exists();
    });
  }
}
