<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\CuotaController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas y Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro público de incidencias (sin login)
Route::get('/cliente/registro', [IncidenciaController::class, 'createCliente'])->name('cliente.registro');
Route::post('/cliente/registro', [IncidenciaController::class, 'storeCliente'])->name('cliente.registro.store');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Panel principal
    Route::get('/', fn () => view('welcome'))->name('inicio');

    // Perfil de usuario (Admin y Operario)
    Route::get('/mi-perfil', [EmpleadoController::class, 'miPerfil'])->name('mi-perfil');
    Route::put('/mi-perfil', [EmpleadoController::class, 'updatePerfil'])->name('mi-perfil.update');

    // CRUD Empleados (Solo Admin - controlado en controller)
    Route::resource('empleados', EmpleadoController::class);

    // CRUD Clientes (Solo Admin)
    Route::resource('clientes', ClienteController::class);

    // CRUD Incidencias (Admin y Operario)
    Route::resource('incidencias', IncidenciaController::class);

    // CRUD Cuotas (Solo Admin)
    Route::resource('cuotas', CuotaController::class);

    // Rutas adicionales para Cuotas (PDF y Remesa)
    Route::get('/cuotas/{id}/pdf', [CuotaController::class, 'descargarPdf'])->name('cuotas.pdf');
    Route::post('/cuotas/remesa', [CuotaController::class, 'generarRemesa'])->name('cuotas.remesa');
});