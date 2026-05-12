<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolPermisoController;

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
    // ROLES Y PERMISOS
    // ============================================
    Route::get('roles', [RolPermisoController::class, 'index'])
        ->name('roles.index')->middleware('can:roles.view');
    Route::get('roles/{rol}/permisos', [RolPermisoController::class, 'edit'])
        ->name('roles.permisos.edit')->middleware('can:roles.edit');
    Route::put('roles/{rol}/permisos', [RolPermisoController::class, 'update'])
        ->name('roles.permisos.update')->middleware('can:roles.edit');
});