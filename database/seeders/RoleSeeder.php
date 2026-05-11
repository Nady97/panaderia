<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar caché de permisos para evitar errores
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear permisos para Recetas (panadero)
        Permission::create(['name' => 'ver recetas']);
        Permission::create(['name' => 'crear recetas']);
        Permission::create(['name' => 'editar recetas']);
        Permission::create(['name' => 'eliminar recetas']);

        // 3. Crear permisos para Pedidos (proveedor)
        Permission::create(['name' => 'ver pedidos propios']);
        Permission::create(['name' => 'crear pedidos']);
        Permission::create(['name' => 'ver todos los pedidos']);
        Permission::create(['name' => 'gestionar pedidos']);

        // 4. Permisos adicionales para administración
        Permission::create(['name' => 'gestionar usuarios']);
        Permission::create(['name' => 'gestionar productos']);
        Permission::create(['name' => 'ver reportes']);

        // 5. Crear roles y asignarles permisos --------------------------------
        
        // Rol: panadero (solo recetas)
        $panadero = Role::create(['name' => 'panadero']);
        $panadero->givePermissionTo([
            'ver recetas',
            'crear recetas',
            'editar recetas',
            'eliminar recetas'
        ]);

        // Rol: proveedor (solo sus pedidos)
        $proveedor = Role::create(['name' => 'proveedor']);
        $proveedor->givePermissionTo([
            'ver pedidos propios',
            'crear pedidos'
        ]);

        // Rol: admin (todos los permisos)
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // 6. (Opcional) Crear usuarios de prueba para desarrollo ---------------
        // Si no quieres esto aún, coméntalo. Pero ayuda a probar.
        
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@panaderia.com',
            'password' => bcrypt('password123')
        ])->assignRole('admin');

        User::create([
            'name' => 'Panadero López',
            'email' => 'panadero@panaderia.com',
            'password' => bcrypt('password123')
        ])->assignRole('panadero');

        User::create([
            'name' => 'Proveedor Harinas',
            'email' => 'proveedor@panaderia.com',
            'password' => bcrypt('password123')
        ])->assignRole('proveedor');
    }
}