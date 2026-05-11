<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolPermisoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// RUTAS PÚBLICAS
// ============================================

Route::get('/', function () {
    return view('welcome');
});

Route::resource('produccion', \App\Http\Controllers\ProduccionController::class)
    ->only(['index', 'show'])
    ->middleware('can:produccion.view');
Route::resource('produccion', \App\Http\Controllers\ProduccionController::class)
    ->only(['create', 'store'])
    ->middleware('can:produccion.create');
Route::resource('produccion', \App\Http\Controllers\ProduccionController::class)
    ->only(['edit', 'update'])
    ->middleware('can:produccion.edit');
Route::resource('produccion', \App\Http\Controllers\ProduccionController::class)
    ->only(['destroy'])
    ->middleware('can:produccion.delete');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// RUTAS PROTEGIDAS
// ============================================

Route::middleware(['auth'])->group(function () {

    // ============================================
    // DASHBOARD - CON DATOS REALES DE PRODUCTOS
    // ============================================
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // PRODUCTOS
    // ============================================
    Route::resource('productos', ProductoController::class)
        ->only(['index', 'show'])
        ->middleware('can:productos.view');
    Route::resource('productos', ProductoController::class)
        ->only(['create', 'store'])
        ->middleware('can:productos.create');
    Route::resource('productos', ProductoController::class)
        ->only(['edit', 'update'])
        ->middleware('can:productos.edit');
    Route::resource('productos', ProductoController::class)
        ->only(['destroy'])
        ->middleware('can:productos.delete');

    // ============================================
    // RECETAS
    // ============================================
    Route::resource('recetas', \App\Http\Controllers\RecetaController::class)
        ->only(['index', 'show'])
        ->middleware('can:recetas.view');
    Route::resource('recetas', \App\Http\Controllers\RecetaController::class)
        ->only(['create', 'store'])
        ->middleware('can:recetas.create');
    Route::resource('recetas', \App\Http\Controllers\RecetaController::class)
        ->only(['edit', 'update'])
        ->middleware('can:recetas.edit');
    Route::resource('recetas', \App\Http\Controllers\RecetaController::class)
        ->only(['destroy'])
        ->middleware('can:recetas.delete');
    Route::get('/recetas/{receta}/pdf', [\App\Http\Controllers\RecetaController::class, 'downloadPdf'])
        ->name('recetas.pdf')
        ->middleware('can:recetas.download');
    Route::post('/recetas/{receta}/insumos', [\App\Http\Controllers\RecetaController::class, 'addInsumo'])
        ->name('recetas.insumos.add')
        ->middleware('can:recetas.manage-insumos');
    Route::delete('/recetas/{receta}/insumos/{pivot}', [\App\Http\Controllers\RecetaController::class, 'removeInsumo'])
        ->name('recetas.insumos.remove')
        ->middleware('can:recetas.manage-insumos');

    // ============================================
    // CATEGORÍAS
    // ============================================
    Route::resource('categorias', \App\Http\Controllers\CategoriaController::class)
        ->only(['index', 'show'])
        ->middleware('can:categorias.view');
    Route::resource('categorias', \App\Http\Controllers\CategoriaController::class)
        ->only(['create', 'store'])
        ->middleware('can:categorias.create');
    Route::resource('categorias', \App\Http\Controllers\CategoriaController::class)
        ->only(['edit', 'update'])
        ->middleware('can:categorias.edit');
    Route::resource('categorias', \App\Http\Controllers\CategoriaController::class)
        ->only(['destroy'])
        ->middleware('can:categorias.delete');

    // ============================================
    // INSUMOS
    // ============================================
    Route::resource('insumos', \App\Http\Controllers\InsumoController::class)
        ->only(['index', 'show'])
        ->middleware('can:insumos.view');
    Route::resource('insumos', \App\Http\Controllers\InsumoController::class)
        ->only(['create', 'store'])
        ->middleware('can:insumos.create');
    Route::resource('insumos', \App\Http\Controllers\InsumoController::class)
        ->only(['edit', 'update'])
        ->middleware('can:insumos.edit');
    Route::resource('insumos', \App\Http\Controllers\InsumoController::class)
        ->only(['destroy'])
        ->middleware('can:insumos.delete');

    // ============================================
    // PROVEEDORES
    // ============================================
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)
        ->only(['index', 'show'])
        ->middleware('can:proveedores.view');
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)
        ->only(['create', 'store'])
        ->middleware('can:proveedores.create');
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)
        ->only(['edit', 'update'])
        ->middleware('can:proveedores.edit');
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class)
        ->only(['destroy'])
        ->middleware('can:proveedores.delete');
    // ============================================
    // PERFIL
    // ============================================
    Route::get('/perfil', [ProfileController::class, 'show'])
        ->name('perfil')
        ->middleware('can:perfil.view');
    Route::put('/perfil/update', [ProfileController::class, 'update'])
        ->name('perfil.update')
        ->middleware('can:perfil.edit');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])
        ->name('perfil.password')
        ->middleware('can:perfil.password');
    Route::delete('/perfil/delete', [ProfileController::class, 'destroy'])
        ->name('perfil.delete')
        ->middleware('can:perfil.delete');

    // ============================================
    // USUARIOS (Protegido con Gate 'manage-users')
    // ============================================
    Route::get('roles', [RolPermisoController::class, 'index'])
        ->name('roles.index')
        ->middleware('can:roles.view');
    Route::get('roles/{rol}/permisos', [RolPermisoController::class, 'edit'])
        ->name('roles.permisos.edit')
        ->middleware('can:roles.edit');
    Route::put('roles/{rol}/permisos', [RolPermisoController::class, 'update'])
        ->name('roles.permisos.update')
        ->middleware('can:roles.edit');

    Route::get('usuarios/{codigo}/historial', [UsuarioController::class, 'historial'])
        ->name('usuarios.historial')
        ->middleware('can:usuarios.historial');
    Route::get('usuarios/{codigo}/historial/pdf', [UsuarioController::class, 'historialPdf'])
        ->name('usuarios.historial.pdf')
        ->middleware('can:usuarios.historial');
    Route::post('usuarios/{codigo}/reset-password', [UsuarioController::class, 'resetPassword'])
        ->name('usuarios.reset-password')
        ->middleware('can:usuarios.reset-password');
    Route::resource('usuarios', UsuarioController::class)
        ->parameters(['usuarios' => 'codigo'])
        ->only(['index', 'show'])
        ->middleware('can:usuarios.view');
    Route::resource('usuarios', UsuarioController::class)
        ->parameters(['usuarios' => 'codigo'])
        ->only(['create', 'store'])
        ->middleware('can:usuarios.create');
    Route::resource('usuarios', UsuarioController::class)
        ->parameters(['usuarios' => 'codigo'])
        ->only(['edit', 'update'])
        ->middleware('can:usuarios.edit');
    Route::resource('usuarios', UsuarioController::class)
        ->parameters(['usuarios' => 'codigo'])
        ->only(['destroy'])
        ->middleware('can:usuarios.delete');
});
