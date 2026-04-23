<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuotaController;

// ==========================================
// Rutas Públicas
// ==========================================

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registro público de incidencias (sin login)
Route::get('/cliente/registro', [IncidenciaController::class, 'createCliente'])->name('cliente.registro');
Route::post('/cliente/registro', [IncidenciaController::class, 'storeCliente'])->name('cliente.registrar');

// ==========================================
// Rutas Protegidas (Requieren Auth)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Página de inicio
    Route::get('/', [InicioController::class, 'index'])->name('inicio');
    
    // Perfil personal (Admin y Operario)
    Route::get('/mi-perfil', [EmpleadoController::class, 'miPerfil'])->name('mi-perfil');
    Route::put('/mi-perfil', [EmpleadoController::class, 'updatePerfil'])->name('mi-perfil.update');
    
    // CRUD Empleados (solo admin - controlado en controller)
    Route::resource('empleados', EmpleadoController::class);
    
    // CRUD Clientes (solo admin)
    Route::resource('clientes', ClienteController::class);
    
    // CRUD Incidencias (admin y operario)
    Route::resource('incidencias', IncidenciaController::class);
    
    // CRUD Cuotas (solo admin)
    Route::resource('cuotas', CuotaController::class);
    
    // ➕ RUTAS ADICIONALES PARA CUOTAS (PDF + REMESA)
    Route::get('/cuotas/{id}/pdf', [CuotaController::class, 'descargarPdf'])->name('cuotas.pdf');
    Route::post('/cuotas/remesa', [CuotaController::class, 'generarRemesa'])->name('cuotas.remesa');
});