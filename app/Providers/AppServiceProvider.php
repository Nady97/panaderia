<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

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
            'perfil.edit',
            'perfil.password',
            'perfil.delete',
        ];
        Gate::define('perfil.view', function ($user) {
            return true; // Cualquier usuario autenticado puede ver su perfil
        });
        foreach ($permissionGates as $gate) {
            Gate::define($gate, function ($user) use ($gate) {
                if (!$user) return false;

                $usuario = ($user instanceof Usuario) ? $user : Usuario::find($user->codigo);

                if (!$usuario || !$usuario->rol) return false;

                if ($usuario->rol->slug === 'admin') return true;

                if (str_ends_with($gate, '.delete')) return false;

                // Verificar por rol
                $tienePorRol = DB::table('permiso_rol')
                    ->join('permisos', 'permiso_rol.permiso_id', '=', 'permisos.id')
                    ->where('permiso_rol.rol_id', $usuario->rol_id)
                    ->where('permisos.slug', $gate)
                    ->exists();

                if ($tienePorRol) return true;

                // Verificar permisos directos del usuario
                return DB::table('usuario_permiso')
                    ->join('permisos', 'usuario_permiso.permiso_id', '=', 'permisos.id')
                    ->where('usuario_permiso.usuario_codigo', $usuario->codigo)
                    ->where('permisos.slug', $gate)
                    ->exists();
            });
        }
        // Atajos: permisos globales
        Gate::define('manage-users', function ($user) {
            return Gate::check('usuarios.view') && Gate::check('usuarios.create') && Gate::check('usuarios.edit');
        });

        Gate::define('manage-production', function ($user) {
            return Gate::check('produccion.view') && Gate::check('produccion.create') && Gate::check('produccion.edit');
        });
    }
}
