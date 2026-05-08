<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\Usuario;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // ----------------------------------------------------
        // DEFINICIÓN DE GATES Y PERMISOS (Punto 5 - Auditoría)
        // ----------------------------------------------------

        // Acceso exclusivo para administradores
        Gate::define('manage-users', function (Usuario $user) {
            return $user->isAdmin();
        });

        // Administrar inventario completo / catálogos
        Gate::define('manage-inventory', function (Usuario $user) {
            return $user->hasRole(['admin']);
        });

        // Registrar y gestionar Ventas (Cajeros)
        Gate::define('manage-sales', function (Usuario $user) {
            return $user->hasRole(['admin', 'cajero']);
        });

        // Registrar y gestionar Producción (Panaderos)
        Gate::define('manage-production', function (Usuario $user) {
            return $user->hasRole(['admin', 'panadero']);
        });
    }
}
