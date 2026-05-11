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

Route::resource('produccion', \App\Http\Controllers\ProduccionController::class)->middleware('auth');

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
    Route::resource('productos', ProductoController::class);

    // ============================================
    // RECETAS
    // ============================================
    Route::resource('recetas', \App\Http\Controllers\RecetaController::class);
    Route::get('/recetas/{receta}/pdf', [\App\Http\Controllers\RecetaController::class, 'downloadPdf'])->name('recetas.pdf');
    Route::post('/recetas/{receta}/insumos', [\App\Http\Controllers\RecetaController::class, 'addInsumo'])->name('recetas.insumos.add');
    Route::delete('/recetas/{receta}/insumos/{pivot}', [\App\Http\Controllers\RecetaController::class, 'removeInsumo'])->name('recetas.insumos.remove');

    // ============================================
    // CATEGORÍAS
    // ============================================
    Route::resource('categorias', \App\Http\Controllers\CategoriaController::class);

    // ============================================
    // INSUMOS
    // ============================================
    Route::resource('insumos', \App\Http\Controllers\InsumoController::class);

    // ============================================
    // PROVEEDORES
    // ============================================
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class);
    // ============================================
    // PERFIL
    // ============================================
    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil');
    Route::put('/perfil/update', [ProfileController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('perfil.password');
    Route::delete('/perfil/delete', [ProfileController::class, 'destroy'])->name('perfil.delete');

    // ============================================
    // USUARIOS (Protegido con Gate 'manage-users')
    // ============================================
    Route::get('usuarios/{codigo}/historial', [UsuarioController::class, 'historial'])
        ->name('usuarios.historial')
        ->middleware('can:manage-users');
    Route::get('usuarios/{codigo}/historial/pdf', [UsuarioController::class, 'historialPdf'])
        ->name('usuarios.historial.pdf')
        ->middleware('can:manage-users');
    Route::resource('usuarios', UsuarioController::class)
        ->parameters(['usuarios' => 'codigo'])
        ->middleware('can:manage-users');
});

Route::resource('produccion', \App\Http\Controllers\ProduccionController::class)->middleware('auth');
