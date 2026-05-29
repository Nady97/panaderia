<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolPermisoController;
use App\Http\Controllers\NotaCompraController;
use App\Http\Controllers\FacturaInternaController;
use App\Http\Controllers\SolicitudProduccionController; 

Route::get('/', function () {
    return view('welcome');
});

// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // RECETAS
    // ============================================
    Route::get('/recetas', [\App\Http\Controllers\RecetaController::class, 'index'])
        ->name('recetas.index')->middleware('can:recetas.view');
    Route::get('/recetas/create', [\App\Http\Controllers\RecetaController::class, 'create'])
        ->name('recetas.create')->middleware('can:recetas.create');
    Route::post('/recetas', [\App\Http\Controllers\RecetaController::class, 'store'])
        ->name('recetas.store')->middleware('can:recetas.create');
    Route::get('/recetas/{receta}', [\App\Http\Controllers\RecetaController::class, 'show'])
        ->name('recetas.show')->middleware('can:recetas.view');
    Route::get('/recetas/{receta}/edit', [\App\Http\Controllers\RecetaController::class, 'edit'])
        ->name('recetas.edit')->middleware('can:recetas.edit');
    Route::put('/recetas/{receta}', [\App\Http\Controllers\RecetaController::class, 'update'])
        ->name('recetas.update')->middleware('can:recetas.edit');
    Route::delete('/recetas/{receta}', [\App\Http\Controllers\RecetaController::class, 'destroy'])
        ->name('recetas.destroy')->middleware('can:recetas.delete');
    Route::get('/recetas/{receta}/pdf', [\App\Http\Controllers\RecetaController::class, 'downloadPdf'])
        ->name('recetas.pdf')->middleware('can:recetas.download');
    Route::post('/recetas/{receta}/insumos', [\App\Http\Controllers\RecetaController::class, 'addInsumo'])
        ->name('recetas.insumos.add')->middleware('can:recetas.manage-insumos');
    Route::delete('/recetas/{receta}/insumos/{pivot}', [\App\Http\Controllers\RecetaController::class, 'removeInsumo'])
        ->name('recetas.insumos.remove')->middleware('can:recetas.manage-insumos');

    // ============================================
    // PRODUCTOS
    // ============================================
    Route::get('/productos', [ProductoController::class, 'index'])
        ->name('productos.index')->middleware('can:productos.view');
    Route::get('/productos/create', [ProductoController::class, 'create'])
        ->name('productos.create')->middleware('can:productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])
        ->name('productos.store')->middleware('can:productos.create');
    Route::get('/productos/{producto}', [ProductoController::class, 'show'])
        ->name('productos.show')->middleware('can:productos.view');
    Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])
        ->name('productos.edit')->middleware('can:productos.edit');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])
        ->name('productos.update')->middleware('can:productos.edit');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])
        ->name('productos.destroy')->middleware('can:productos.delete');

    // ============================================
    // INSUMOS
    // ============================================
    Route::get('/insumos', [\App\Http\Controllers\InsumoController::class, 'index'])
        ->name('insumos.index')->middleware('can:insumos.view');
    Route::get('/insumos/create', [\App\Http\Controllers\InsumoController::class, 'create'])
        ->name('insumos.create')->middleware('can:insumos.create');
    Route::post('/insumos', [\App\Http\Controllers\InsumoController::class, 'store'])
        ->name('insumos.store')->middleware('can:insumos.create');
    Route::get('/insumos/{insumo}', [\App\Http\Controllers\InsumoController::class, 'show'])
        ->name('insumos.show')->middleware('can:insumos.view');
    Route::get('/insumos/{insumo}/edit', [\App\Http\Controllers\InsumoController::class, 'edit'])
        ->name('insumos.edit')->middleware('can:insumos.edit');
    Route::put('/insumos/{insumo}', [\App\Http\Controllers\InsumoController::class, 'update'])
        ->name('insumos.update')->middleware('can:insumos.edit');
    Route::delete('/insumos/{insumo}', [\App\Http\Controllers\InsumoController::class, 'destroy'])
        ->name('insumos.destroy')->middleware('can:insumos.delete');

    // ============================================
    // CATEGORÍAS
    // ============================================
    Route::get('/categorias', [\App\Http\Controllers\CategoriaController::class, 'index'])
        ->name('categorias.index')->middleware('can:categorias.view');
    Route::get('/categorias/create', [\App\Http\Controllers\CategoriaController::class, 'create'])
        ->name('categorias.create')->middleware('can:categorias.create');
    Route::post('/categorias', [\App\Http\Controllers\CategoriaController::class, 'store'])
        ->name('categorias.store')->middleware('can:categorias.create');
    Route::get('/categorias/{categoria}', [\App\Http\Controllers\CategoriaController::class, 'show'])
        ->name('categorias.show')->middleware('can:categorias.view');
    Route::get('/categorias/{categoria}/edit', [\App\Http\Controllers\CategoriaController::class, 'edit'])
        ->name('categorias.edit')->middleware('can:categorias.edit');
    Route::put('/categorias/{categoria}', [\App\Http\Controllers\CategoriaController::class, 'update'])
        ->name('categorias.update')->middleware('can:categorias.edit');
    Route::delete('/categorias/{categoria}', [\App\Http\Controllers\CategoriaController::class, 'destroy'])
        ->name('categorias.destroy')->middleware('can:categorias.delete');

    // ============================================
    // PROVEEDORES
    // ============================================
    Route::get('/proveedores', [\App\Http\Controllers\ProveedorController::class, 'index'])
        ->name('proveedores.index')->middleware('can:proveedores.view');
    Route::get('/proveedores/create', [\App\Http\Controllers\ProveedorController::class, 'create'])
        ->name('proveedores.create')->middleware('can:proveedores.create');
    Route::post('/proveedores', [\App\Http\Controllers\ProveedorController::class, 'store'])
        ->name('proveedores.store')->middleware('can:proveedores.create');
    Route::get('/proveedores/{proveedore}', [\App\Http\Controllers\ProveedorController::class, 'show'])
        ->name('proveedores.show')->middleware('can:proveedores.view');
    Route::get('/proveedores/{proveedore}/edit', [\App\Http\Controllers\ProveedorController::class, 'edit'])
        ->name('proveedores.edit')->middleware('can:proveedores.edit');
    Route::put('/proveedores/{proveedore}', [\App\Http\Controllers\ProveedorController::class, 'update'])
        ->name('proveedores.update')->middleware('can:proveedores.edit');
    Route::delete('/proveedores/{proveedore}', [\App\Http\Controllers\ProveedorController::class, 'destroy'])
        ->name('proveedores.destroy')->middleware('can:proveedores.delete');

    // ============================================
    // PRODUCCIÓN
    // ============================================
    Route::get('/produccion', [\App\Http\Controllers\ProduccionController::class, 'index'])
        ->name('produccion.index')->middleware('can:produccion.view');
    Route::get('/produccion/create', [\App\Http\Controllers\ProduccionController::class, 'create'])
        ->name('produccion.create')->middleware('can:produccion.create');
    Route::post('/produccion', [\App\Http\Controllers\ProduccionController::class, 'store'])
        ->name('produccion.store')->middleware('can:produccion.create');
    Route::get('/produccion/{produccion}', [\App\Http\Controllers\ProduccionController::class, 'show'])
        ->name('produccion.show')->middleware('can:produccion.view');
    Route::get('/produccion/{produccion}/edit', [\App\Http\Controllers\ProduccionController::class, 'edit'])
        ->name('produccion.edit')->middleware('can:produccion.edit');
    Route::put('/produccion/{produccion}', [\App\Http\Controllers\ProduccionController::class, 'update'])
        ->name('produccion.update')->middleware('can:produccion.edit');
    Route::delete('/produccion/{produccion}', [\App\Http\Controllers\ProduccionController::class, 'destroy'])
        ->name('produccion.destroy')->middleware('can:produccion.delete');

    // CU-15: Iniciar Proceso de Producción
    Route::post('/produccion/{produccion}/iniciar', [\App\Http\Controllers\ProduccionController::class, 'iniciarProceso'])
        ->name('produccion.iniciar')->middleware('can:produccion.edit');

    // CU-16: Finalizar Proceso de Producción
    Route::post('/produccion/{produccion}/finalizar', [\App\Http\Controllers\ProduccionController::class, 'finalizarProceso'])
        ->name('produccion.finalizar')->middleware('can:produccion.edit');

    // CU-17: Asignar Responsable de Proceso
    Route::post('/produccion/{produccion}/asignar-responsable', [\App\Http\Controllers\ProduccionController::class, 'asignarResponsable'])
        ->name('produccion.asignar-responsable')->middleware('can:produccion.edit');

    // ============================================
    // NOTAS DE COMPRA (CU-10, CU-11)
    // ============================================
    Route::get('/notas-compra', [\App\Http\Controllers\NotaCompraController::class, 'index'])
        ->name('notas_compra.index')->middleware('can:notas_compra.view');
    Route::get('/notas-compra/create', [\App\Http\Controllers\NotaCompraController::class, 'create'])
        ->name('notas_compra.create')->middleware('can:notas_compra.create');
    Route::post('/notas-compra', [\App\Http\Controllers\NotaCompraController::class, 'store'])
        ->name('notas_compra.store')->middleware('can:notas_compra.create');
    Route::get('/notas-compra/{notaCompra}', [\App\Http\Controllers\NotaCompraController::class, 'show'])
        ->name('notas_compra.show')->middleware('can:notas_compra.view');
    Route::get('/notas-compra/{notaCompra}/edit', [\App\Http\Controllers\NotaCompraController::class, 'edit'])
        ->name('notas_compra.edit')->middleware('can:notas_compra.edit');
    Route::put('/notas-compra/{notaCompra}', [\App\Http\Controllers\NotaCompraController::class, 'update'])
        ->name('notas_compra.update')->middleware('can:notas_compra.edit');
    Route::delete('/notas-compra/{notaCompra}', [\App\Http\Controllers\NotaCompraController::class, 'destroy'])
        ->name('notas_compra.destroy')->middleware('can:notas_compra.delete');
    Route::post('/notas-compra/{notaCompra}/confirmar', [\App\Http\Controllers\NotaCompraController::class, 'confirmar'])
        ->name('notas_compra.confirmar')->middleware('can:notas_compra.edit');
    Route::post('/notas-compra/{notaCompra}/marcar-recibida', [\App\Http\Controllers\NotaCompraController::class, 'marcarRecibida'])
        ->name('notas_compra.marcar-recibida')->middleware('can:notas_compra.edit');
    Route::post('/notas-compra/{notaCompra}/agregar-detalle', [\App\Http\Controllers\NotaCompraController::class, 'agregarDetalle'])
        ->name('notas_compra.agregar-detalle')->middleware('can:notas_compra.edit');
    Route::delete('/notas-compra/detalle/{detalle}', [\App\Http\Controllers\NotaCompraController::class, 'eliminarDetalle'])
        ->name('notas_compra.eliminar-detalle')->middleware('can:notas_compra.edit');

    // ============================================
    // FACTURAS INTERNAS (CU-12)
    // ============================================
    Route::get('/facturas-internas', [\App\Http\Controllers\FacturaInternaController::class, 'index'])
        ->name('facturas_internas.index')->middleware('can:facturas_internas.view');
    Route::get('/facturas-internas/create', [FacturaInternaController::class, 'create'])->name('facturas_internas.create');
    Route::post('/facturas-internas', [FacturaInternaController::class, 'store'])->name('facturas_internas.store');  
    Route::get('/facturas-internas/{facturaInterna}', [\App\Http\Controllers\FacturaInternaController::class, 'show'])
        ->name('facturas_internas.show')->middleware('can:facturas_internas.view');
    Route::get('/facturas-internas/{facturaInterna}/edit', [FacturaInternaController::class, 'edit'])->name('facturas_internas.edit'); 
    Route::put('/facturas-internas/{facturaInterna}', [FacturaInternaController::class, 'update'])->name('facturas_internas.update');    // 👈 AGREGAR
    Route::delete('/facturas-internas/{facturaInterna}', [FacturaInternaController::class, 'destroy'])->name('facturas_internas.destroy');    
    Route::post('/notas-compra/{notaCompra}/emitir-factura', [\App\Http\Controllers\FacturaInternaController::class, 'emitirDesdeNota'])
        ->name('facturas_internas.emitir')->middleware('can:facturas_internas.create');
    Route::post('/facturas-internas/{facturaInterna}/marcar-pagada', [\App\Http\Controllers\FacturaInternaController::class, 'marcarPagada'])
        ->name('facturas_internas.marcar-pagada')->middleware('can:facturas_internas.edit');
    Route::post('/facturas-internas/{facturaInterna}/anular', [\App\Http\Controllers\FacturaInternaController::class, 'anular'])
        ->name('facturas_internas.anular')->middleware('can:facturas_internas.delete');
    Route::get('/facturas-internas/{facturaInterna}/pdf', [\App\Http\Controllers\FacturaInternaController::class, 'descargarPdf'])
        ->name('facturas_internas.pdf')->middleware('can:facturas_internas.download');

    // ============================================
    // SOLICITUDES DE PRODUCCIÓN (CU-26)
    // ============================================
    Route::get('/solicitudes-produccion', [\App\Http\Controllers\SolicitudProduccionController::class, 'index'])
        ->name('solicitudes_produccion.index')->middleware('can:solicitudes_produccion.view');
    Route::get('/solicitudes-produccion/{solicitudProduccion}', [\App\Http\Controllers\SolicitudProduccionController::class, 'show'])
        ->name('solicitudes_produccion.show')->middleware('can:solicitudes_produccion.view');
    Route::post('/produccion/{produccion}/solicitar-urgente', [\App\Http\Controllers\SolicitudProduccionController::class, 'crear'])
        ->name('solicitudes_produccion.crear')->middleware('can:solicitudes_produccion.create');
    Route::post('/solicitudes-produccion/{solicitudProduccion}/aprobar', [\App\Http\Controllers\SolicitudProduccionController::class, 'aprobar'])
        ->name('solicitudes_produccion.aprobar')->middleware('can:solicitudes_produccion.approve');
    Route::post('/solicitudes-produccion/{solicitudProduccion}/rechazar', [\App\Http\Controllers\SolicitudProduccionController::class, 'rechazar'])
        ->name('solicitudes_produccion.rechazar')->middleware('can:solicitudes_produccion.approve');

    // ============================================
    // USUARIOS
    // ============================================
    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->name('usuarios.index')->middleware('can:usuarios.view');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])
        ->name('usuarios.create')->middleware('can:usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])
        ->name('usuarios.store')->middleware('can:usuarios.create');
    Route::get('/usuarios/{codigo}', [UsuarioController::class, 'show'])
        ->name('usuarios.show')->middleware('can:usuarios.view');
    Route::get('/usuarios/{codigo}/edit', [UsuarioController::class, 'edit'])
        ->name('usuarios.edit')->middleware('can:usuarios.edit');
    Route::put('/usuarios/{codigo}', [UsuarioController::class, 'update'])
        ->name('usuarios.update')->middleware('can:usuarios.edit');
    Route::delete('/usuarios/{codigo}', [UsuarioController::class, 'destroy'])
        ->name('usuarios.destroy')->middleware('can:usuarios.delete');
    Route::get('usuarios/{codigo}/historial', [UsuarioController::class, 'historial'])
        ->name('usuarios.historial')->middleware('can:usuarios.historial');
    Route::get('usuarios/{codigo}/historial/pdf', [UsuarioController::class, 'historialPdf'])
        ->name('usuarios.historial.pdf')->middleware('can:usuarios.historial');
    Route::post('usuarios/{codigo}/reset-password', [UsuarioController::class, 'resetPassword'])
        ->name('usuarios.reset-password')->middleware('can:usuarios.reset-password');

    // Forzar cierre de sesión (solo admin)
    Route::post('/usuarios/{codigo}/force-logout', [UsuarioController::class, 'forceLogout'])
        ->name('usuarios.force-logout')
        ->middleware('can:usuarios.delete');

    // ============================================
    // PERFIL
    // ============================================
    Route::get('/perfil', [ProfileController::class, 'show'])
        ->name('perfil')->middleware('can:perfil.view');
    Route::put('/perfil/update', [ProfileController::class, 'update'])
        ->name('perfil.update')->middleware('can:perfil.edit');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])
        ->name('perfil.password')->middleware('can:perfil.password');
    Route::delete('/perfil/delete', [ProfileController::class, 'destroy'])
        ->name('perfil.delete')->middleware('can:perfil.delete');

    // ============================================
    // REPORTES
    // ============================================
    Route::get('/reportes', [\App\Http\Controllers\ReportController::class, 'index'])
        ->name('reportes.index')->middleware('can:reportes.view');
    Route::get('/reportes/dashboard', [\App\Http\Controllers\ReportController::class, 'dashboard'])
        ->name('reportes.dashboard')->middleware('can:reportes.view');
    Route::get('/reportes/ventas', [\App\Http\Controllers\ReportController::class, 'ventasReporte'])
        ->name('reportes.ventas')->middleware('can:reportes.view');
    Route::get('/reportes/produccion', [\App\Http\Controllers\ReportController::class, 'produccionReporte'])
        ->name('reportes.produccion')->middleware('can:reportes.view');
    Route::get('/reportes/inventario-critico', [\App\Http\Controllers\ReportController::class, 'inventarioCriticoReporte'])
        ->name('reportes.inventario-critico')->middleware('can:reportes.view');
    Route::get('/reportes/proveedores', [\App\Http\Controllers\ReportController::class, 'proveedoresReporte'])
        ->name('reportes.proveedores')->middleware('can:reportes.view');

    // ============================================
    // ROLES Y PERMISOS
    // ============================================
    Route::get('roles', [RolPermisoController::class, 'index'])
        ->name('roles.index')->middleware('can:roles.view');
    Route::get('roles/{rol}/permisos', [RolPermisoController::class, 'edit'])
        ->name('roles.permisos.edit')->middleware('can:roles.edit');
    Route::put('roles/{rol}/permisos', [RolPermisoController::class, 'update'])
        ->name('roles.permisos.update')->middleware('can:roles.edit');
});
