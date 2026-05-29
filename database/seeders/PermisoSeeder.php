<?php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
  public function run(): void
  {
    $permisos = [
      [
        'nombre' => 'Gestionar usuarios (global)',
        'slug' => 'manage-users',
        'modulo' => 'Usuarios',
        'descripcion' => 'Acceso global a gestion de usuarios.',
      ],
      [
        'nombre' => 'Gestionar inventario (global)',
        'slug' => 'manage-inventory',
        'modulo' => 'Inventario',
        'descripcion' => 'Acceso global a inventario.',
      ],
      [
        'nombre' => 'Gestionar ventas (global)',
        'slug' => 'manage-sales',
        'modulo' => 'Ventas',
        'descripcion' => 'Acceso global a ventas.',
      ],
      [
        'nombre' => 'Gestionar produccion (global)',
        'slug' => 'manage-production',
        'modulo' => 'Produccion',
        'descripcion' => 'Acceso global a produccion.',
      ],
      [
        'nombre' => 'Ver perfil',
        'slug' => 'perfil.view',
        'modulo' => 'Perfil',
        'descripcion' => 'Ver perfil personal.',
      ],
      [
        'nombre' => 'Editar perfil',
        'slug' => 'perfil.edit',
        'modulo' => 'Perfil',
        'descripcion' => 'Actualizar datos del perfil personal.',
      ],
      [
        'nombre' => 'Cambiar contrasena',
        'slug' => 'perfil.password',
        'modulo' => 'Perfil',
        'descripcion' => 'Actualizar la contrasena personal.',
      ],
      [
        'nombre' => 'Eliminar cuenta propia',
        'slug' => 'perfil.delete',
        'modulo' => 'Perfil',
        'descripcion' => 'Eliminar la cuenta propia.',
      ],
      [
        'nombre' => 'Gestionar ventas (global)',
        'slug' => 'manage-sales',
        'modulo' => 'Ventas',
        'descripcion' => 'Acceso global a ventas.',
      ],
      [
        'nombre' => 'Gestionar produccion (global)',
        'slug' => 'manage-production',
        'modulo' => 'Produccion',
        'descripcion' => 'Acceso global a produccion.',
      ],
      [
        'nombre' => 'Ver usuarios',
        'slug' => 'usuarios.view',
        'modulo' => 'Usuarios',
        'descripcion' => 'Ver lista y detalle de usuarios.',
      ],
      [
        'nombre' => 'Crear usuarios',
        'slug' => 'usuarios.create',
        'modulo' => 'Usuarios',
        'descripcion' => 'Registrar nuevos usuarios.',
      ],
      [
        'nombre' => 'Editar usuarios',
        'slug' => 'usuarios.edit',
        'modulo' => 'Usuarios',
        'descripcion' => 'Modificar datos de usuarios.',
      ],
      [
        'nombre' => 'Eliminar usuarios',
        'slug' => 'usuarios.delete',
        'modulo' => 'Usuarios',
        'descripcion' => 'Eliminar usuarios del sistema.',
      ],
      [
        'nombre' => 'Ver historial de usuarios',
        'slug' => 'usuarios.historial',
        'modulo' => 'Usuarios',
        'descripcion' => 'Ver historial de accesos y cambios.',
      ],
      [
        'nombre' => 'Restablecer contrasena de usuarios',
        'slug' => 'usuarios.reset-password',
        'modulo' => 'Usuarios',
        'descripcion' => 'Restablecer la contrasena de otros usuarios.',
      ],
      [
        'nombre' => 'Ver roles',
        'slug' => 'roles.view',
        'modulo' => 'Roles',
        'descripcion' => 'Ver roles existentes.',
      ],
      [
        'nombre' => 'Editar permisos de roles',
        'slug' => 'roles.edit',
        'modulo' => 'Roles',
        'descripcion' => 'Asignar o quitar permisos a roles.',
      ],
      [
        'nombre' => 'Ver productos',
        'slug' => 'productos.view',
        'modulo' => 'Productos',
        'descripcion' => 'Ver productos y detalles.',
      ],
      [
        'nombre' => 'Crear productos',
        'slug' => 'productos.create',
        'modulo' => 'Productos',
        'descripcion' => 'Registrar nuevos productos.',
      ],
      [
        'nombre' => 'Editar productos',
        'slug' => 'productos.edit',
        'modulo' => 'Productos',
        'descripcion' => 'Modificar productos existentes.',
      ],
      [
        'nombre' => 'Eliminar productos',
        'slug' => 'productos.delete',
        'modulo' => 'Productos',
        'descripcion' => 'Eliminar productos.',
      ],
      [
        'nombre' => 'Ver categorias',
        'slug' => 'categorias.view',
        'modulo' => 'Categorias',
        'descripcion' => 'Ver categorias disponibles.',
      ],
      [
        'nombre' => 'Crear categorias',
        'slug' => 'categorias.create',
        'modulo' => 'Categorias',
        'descripcion' => 'Registrar categorias.',
      ],
      [
        'nombre' => 'Editar categorias',
        'slug' => 'categorias.edit',
        'modulo' => 'Categorias',
        'descripcion' => 'Modificar categorias.',
      ],
      [
        'nombre' => 'Eliminar categorias',
        'slug' => 'categorias.delete',
        'modulo' => 'Categorias',
        'descripcion' => 'Eliminar categorias.',
      ],
      [
        'nombre' => 'Ver insumos',
        'slug' => 'insumos.view',
        'modulo' => 'Insumos',
        'descripcion' => 'Ver lista de insumos.',
      ],
      [
        'nombre' => 'Crear insumos',
        'slug' => 'insumos.create',
        'modulo' => 'Insumos',
        'descripcion' => 'Registrar insumos.',
      ],
      [
        'nombre' => 'Editar insumos',
        'slug' => 'insumos.edit',
        'modulo' => 'Insumos',
        'descripcion' => 'Modificar insumos.',
      ],
      [
        'nombre' => 'Eliminar insumos',
        'slug' => 'insumos.delete',
        'modulo' => 'Insumos',
        'descripcion' => 'Eliminar insumos.',
      ],
      [
        'nombre' => 'Ver recetas',
        'slug' => 'recetas.view',
        'modulo' => 'Recetas',
        'descripcion' => 'Ver recetas y detalles.',
      ],
      [
        'nombre' => 'Crear recetas',
        'slug' => 'recetas.create',
        'modulo' => 'Recetas',
        'descripcion' => 'Registrar nuevas recetas.',
      ],
      [
        'nombre' => 'Editar recetas',
        'slug' => 'recetas.edit',
        'modulo' => 'Recetas',
        'descripcion' => 'Modificar recetas.',
      ],
      [
        'nombre' => 'Eliminar recetas',
        'slug' => 'recetas.delete',
        'modulo' => 'Recetas',
        'descripcion' => 'Eliminar recetas.',
      ],
      [
        'nombre' => 'Gestionar insumos de recetas',
        'slug' => 'recetas.manage-insumos',
        'modulo' => 'Recetas',
        'descripcion' => 'Agregar o quitar insumos en recetas.',
      ],
      [
        'nombre' => 'Descargar recetas',
        'slug' => 'recetas.download',
        'modulo' => 'Recetas',
        'descripcion' => 'Descargar recetas en PDF.',
      ],
      [
        'nombre' => 'Ver proveedores',
        'slug' => 'proveedores.view',
        'modulo' => 'Proveedores',
        'descripcion' => 'Ver proveedores.',
      ],
      [
        'nombre' => 'Crear proveedores',
        'slug' => 'proveedores.create',
        'modulo' => 'Proveedores',
        'descripcion' => 'Registrar proveedores.',
      ],
      [
        'nombre' => 'Editar proveedores',
        'slug' => 'proveedores.edit',
        'modulo' => 'Proveedores',
        'descripcion' => 'Modificar proveedores.',
      ],
      [
        'nombre' => 'Eliminar proveedores',
        'slug' => 'proveedores.delete',
        'modulo' => 'Proveedores',
        'descripcion' => 'Eliminar proveedores.',
      ],
      [
        'nombre' => 'Ver produccion',
        'slug' => 'produccion.view',
        'modulo' => 'Produccion',
        'descripcion' => 'Ver registros de produccion.',
      ],
      [
        'nombre' => 'Crear produccion',
        'slug' => 'produccion.create',
        'modulo' => 'Produccion',
        'descripcion' => 'Registrar produccion.',
      ],
      [
        'nombre' => 'Editar produccion',
        'slug' => 'produccion.edit',
        'modulo' => 'Produccion',
        'descripcion' => 'Modificar produccion.',
      ],
      [
        'nombre' => 'Eliminar produccion',
        'slug' => 'produccion.delete',
        'modulo' => 'Produccion',
        'descripcion' => 'Eliminar produccion.',
      ],
      // ============================================
      // NOTAS DE COMPRA (CU-10, CU-11)
      // ============================================
      [
        'nombre' => 'Ver notas de compra',
        'slug' => 'notas_compra.view',
        'modulo' => 'Compras',
        'descripcion' => 'Visualizar notas de compra.',
      ],
      [
        'nombre' => 'Crear notas de compra',
        'slug' => 'notas_compra.create',
        'modulo' => 'Compras',
        'descripcion' => 'Crear notas de compra a proveedores.',
      ],
      [
        'nombre' => 'Editar notas de compra',
        'slug' => 'notas_compra.edit',
        'modulo' => 'Compras',
        'descripcion' => 'Modificar notas de compra en borrador.',
      ],
      [
        'nombre' => 'Eliminar notas de compra',
        'slug' => 'notas_compra.delete',
        'modulo' => 'Compras',
        'descripcion' => 'Eliminar notas de compra.',
      ],
      // ============================================
      // FACTURAS INTERNAS (CU-12)
      // ============================================
      [
        'nombre' => 'Ver facturas internas',
        'slug' => 'facturas_internas.view',
        'modulo' => 'Facturación',
        'descripcion' => 'Visualizar facturas internas.',
      ],
      [
        'nombre' => 'Crear facturas internas',
        'slug' => 'facturas_internas.create',
        'modulo' => 'Facturación',
        'descripcion' => 'Emitir facturas internas desde notas de compra.',
      ],
      [
        'nombre' => 'Editar facturas internas',
        'slug' => 'facturas_internas.edit',
        'modulo' => 'Facturación',
        'descripcion' => 'Modificar facturas internas.',
      ],
      [
        'nombre' => 'Eliminar facturas internas',
        'slug' => 'facturas_internas.delete',
        'modulo' => 'Facturación',
        'descripcion' => 'Anular facturas internas.',
      ],
      [
        'nombre' => 'Descargar facturas',
        'slug' => 'facturas_internas.download',
        'modulo' => 'Facturación',
        'descripcion' => 'Descargar facturas en PDF.',
      ],
      // ============================================
      // SOLICITUDES DE PRODUCCIÓN (CU-26)
      // ============================================
      [
        'nombre' => 'Ver solicitudes de producción',
        'slug' => 'solicitudes_produccion.view',
        'modulo' => 'Produccion',
        'descripcion' => 'Visualizar solicitudes de producción urgente.',
      ],
      [
        'nombre' => 'Crear solicitudes de producción',
        'slug' => 'solicitudes_produccion.create',
        'modulo' => 'Produccion',
        'descripcion' => 'Solicitar producción urgente.',
      ],
      [
        'nombre' => 'Aprobar solicitudes de producción',
        'slug' => 'solicitudes_produccion.approve',
        'modulo' => 'Produccion',
        'descripcion' => 'Aprobar o rechazar solicitudes de producción urgente.',
      ],
      [
        'nombre' => 'Cambiar contraseña',
        'slug' => 'perfil.password',
        'modulo' => 'Cuenta',
        'descripcion' => 'Actualizar la contraseña propia.',
      ],
      [
        'nombre' => 'Eliminar cuenta',
        'slug' => 'perfil.delete',
        'modulo' => 'Cuenta',
        'descripcion' => 'Eliminar la cuenta propia.',
      ],
      [
        'nombre' => 'Ver reportes',
        'slug' => 'reportes.view',
        'modulo' => 'Reportes',
        'descripcion' => 'Acceso a reportes y análisis del sistema.',
      ],
      [
        'nombre' => 'Ver dashboard',
        'slug' => 'reportes.dashboard',
        'modulo' => 'Reportes',
        'descripcion' => 'Ver dashboard de métricas clave.',
      ],
      [
        'nombre' => 'Ver reporte de ventas',
        'slug' => 'reportes.ventas',
        'modulo' => 'Reportes',
        'descripcion' => 'Ver reporte detallado de ventas.',
      ],
      [
        'nombre' => 'Ver reporte de producción',
        'slug' => 'reportes.produccion',
        'modulo' => 'Reportes',
        'descripcion' => 'Ver reporte de producción realizada vs planificada.',
      ],
      [
        'nombre' => 'Ver inventario crítico',
        'slug' => 'reportes.inventario',
        'modulo' => 'Reportes',
        'descripcion' => 'Ver alertas de inventario en nivel crítico.',
      ],
      [
        'nombre' => 'Ver reporte de proveedores',
        'slug' => 'reportes.proveedores',
        'modulo' => 'Reportes',
        'descripcion' => 'Ver reporte de proveedores y compras.',
      ],
    ];

    foreach ($permisos as $permiso) {
      Permiso::updateOrCreate(
        ['slug' => $permiso['slug']],
        $permiso
      );
    }

    $adminRole = Rol::where('slug', 'admin')
      ->orWhere('nombre', 'Administrador')
      ->first();

    if ($adminRole) {
      $adminRole->permisos()->sync(Permiso::pluck('id')->all());
    }
  }
}
