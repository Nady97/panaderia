<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        // ----------------------------------------------------
        // DEFINICIÓN DE GATES Y PERMISOS (Punto 5 - Auditoría)
        // ----------------------------------------------------

        $permissionGates = [
            'manage-users',
            'manage-inventory',
            'manage-sales',
            'manage-production',
            'usuarios.view',
            'usuarios.create',
            'usuarios.edit',
            'usuarios.delete',
            'usuarios.historial',
            'usuarios.reset-password',
            'roles.view',
            'roles.edit',
            'productos.view',
            'productos.create',
            'productos.edit',
            'productos.delete',
            'categorias.view',
            'categorias.create',
            'categorias.edit',
            'categorias.delete',
            'insumos.view',
            'insumos.create',
            'insumos.edit',
            'insumos.delete',
            'recetas.view',
            'recetas.create',
            'recetas.edit',
            'recetas.delete',
            'recetas.manage-insumos',
            'recetas.download',
            'proveedores.view',
            'proveedores.create',
            'proveedores.edit',
            'proveedores.delete',
            'produccion.view',
            'produccion.create',
            'produccion.edit',
            'produccion.delete',
            'perfil.view',
            'perfil.edit',
            'perfil.password',
            'perfil.delete',
        ];

        foreach ($permissionGates as $gate) {
            Gate::define($gate, function (Usuario $user) use ($gate) {
                if (str_ends_with($gate, '.delete')) {
                    return $user->isAdmin();
                }

                return $user->isAdmin() || $user->hasPermission($gate);
            });
        }
    }
}
