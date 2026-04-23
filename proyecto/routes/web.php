<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuotaController;

// Rutas Públicas (Login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rutas Protegidas
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // CRUD Cuotas (solo administradores)
Route::resource('cuotas', CuotaController::class);
    // Página de inicio
    Route::get('/', [InicioController::class, 'index'])->name('inicio');
    
    // CRUD Incidencias
    Route::resource('incidencias', IncidenciaController::class);
    
    // CRUD Empleados
    Route::resource('empleados', EmpleadoController::class);
    
    // CRUD Clientes
    Route::resource('clientes', ClienteController::class);
    
    // Perfil personal rápido
    Route::get('/mi-perfil', fn() => redirect()->route('empleados.edit', Auth::id()))->name('mi-perfil');
});

// Rutas para clientes no registrados
Route::get('/cliente/registro', [IncidenciaController::class, 'createCliente'])->name('cliente.registro');
Route::post('/cliente/registro', [IncidenciaController::class, 'storeCliente'])->name('cliente.registrar');

Route::get('/mi-perfil', [App\Http\Controllers\EmpleadoController::class, 'miPerfil'])->name('mi-perfil');
Route::put('/mi-perfil', [App\Http\Controllers\EmpleadoController::class, 'updatePerfil'])->name('mi-perfil.update');