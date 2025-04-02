<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\PerfilController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde se registran las rutas web para tu aplicación.
|
*/

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard - página principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas para gestión de usuarios (solo para administradores y gerencia)
    Route::middleware(['can:ver,Usuarios'])->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create')->middleware('can:crear,Usuarios');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store')->middleware('can:crear,Usuarios');
        Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit')->middleware('can:editar,Usuarios');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update')->middleware('can:editar,Usuarios');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy')->middleware('can:eliminar,Usuarios');
    });
    
    // Rutas para gestión de empleados
    Route::middleware(['can:ver,Empleados'])->group(function () {
        Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
        Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create')->middleware('can:crear,Empleados');
        Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store')->middleware('can:crear,Empleados');
        Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show'])->name('empleados.show');
        Route::get('/empleados/{empleado}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit')->middleware('can:editar,Empleados');
        Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update')->middleware('can:editar,Empleados');
        Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy')->middleware('can:eliminar,Empleados');
    });
    
    // Rutas para gestión de evidencias fotográficas
    Route::middleware(['can:ver,Evidencias'])->group(function () {
        Route::get('/evidencias', [EvidenciaController::class, 'index'])->name('evidencias.index');
        Route::get('/evidencias/create', [EvidenciaController::class, 'create'])->name('evidencias.create')->middleware('can:crear,Evidencias');
        Route::post('/evidencias', [EvidenciaController::class, 'store'])->name('evidencias.store')->middleware('can:crear,Evidencias');
        Route::get('/evidencias/{evidencia}', [EvidenciaController::class, 'show'])->name('evidencias.show');
        Route::get('/evidencias/{evidencia}/edit', [EvidenciaController::class, 'edit'])->name('evidencias.edit')->middleware('can:editar,Evidencias');
        Route::put('/evidencias/{evidencia}', [EvidenciaController::class, 'update'])->name('evidencias.update')->middleware('can:editar,Evidencias');
        Route::delete('/evidencias/{evidencia}', [EvidenciaController::class, 'destroy'])->name('evidencias.destroy')->middleware('can:eliminar,Evidencias');
    });
    
    // Rutas para gestión de reportes
    Route::middleware(['can:ver,Reportes'])->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/generar', [ReporteController::class, 'create'])->name('reportes.create');
        Route::post('/reportes/generar', [ReporteController::class, 'generate'])->name('reportes.generate');
        Route::get('/reportes/{reporte}', [ReporteController::class, 'show'])->name('reportes.show');
        Route::get('/reportes/{reporte}/download', [ReporteController::class, 'download'])->name('reportes.download');
    });
    
    // Rutas para configuración del sistema
    Route::middleware(['can:ver,Configuración'])->group(function () {
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update')->middleware('can:editar,Configuración');
    });
    
    // Rutas para perfil de usuario
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/password', [PerfilController::class, 'updatePassword'])->name('perfil.password');
    
    // Rutas para AJAX (pueden ser usadas por diferentes módulos)
    Route::prefix('ajax')->group(function () {
        Route::get('/usuarios/list', [UsuarioController::class, 'ajaxList'])->name('ajax.usuarios.list');
        Route::get('/empleados/list', [EmpleadoController::class, 'ajaxList'])->name('ajax.empleados.list');
        Route::get('/evidencias/list', [EvidenciaController::class, 'ajaxList'])->name('ajax.evidencias.list');
    });
});