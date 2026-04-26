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
    
    // Inicio / Dashboard
    Route::get('/', [InicioController::class, 'index'])->name('inicio');
    
    // Perfil de usuario
    Route::get('/mi-perfil', [EmpleadoController::class, 'miPerfil'])->name('mi-perfil');
    Route::put('/mi-perfil', [EmpleadoController::class, 'updatePerfil'])->name('mi-perfil.update');
    
    // --- CRUD Empleados ---
    Route::resource('empleados', EmpleadoController::class);
    
    // Ruta de confirmación de eliminación (sin JS)
    Route::get('/empleados/{id}/confirmar', function($id) {
        $emp = App\Models\Empleado::findOrFail($id);
        return view('confirmar-eliminacion', [
            'titulo' => 'Empleado',
            'mensaje' => "Estás a punto de eliminar al empleado <strong>{$emp->nombre}</strong> ({$emp->dni}).",
            'ruta_eliminar' => route('empleados.destroy', $id),
            'ruta_volver' => route('empleados.index')
        ]);
    })->name('empleados.confirm-destroy');

    // --- CRUD Clientes ---
    Route::resource('clientes', ClienteController::class);
    
    // Ruta de confirmación de eliminación (sin JS)
    Route::get('/clientes/{id}/confirmar', function($id) {
        $cli = App\Models\Cliente::findOrFail($id);
        return view('confirmar-eliminacion', [
            'titulo' => 'Cliente',
            'mensaje' => "Estás a punto de eliminar al cliente <strong>{$cli->nombre}</strong> ({$cli->cif}).",
            'ruta_eliminar' => route('clientes.destroy', $id),
            'ruta_volver' => route('clientes.index')
        ]);
    })->name('clientes.confirm-destroy');

    // --- CRUD Incidencias ---
    Route::resource('incidencias', IncidenciaController::class);
    
    // Ruta de confirmación de eliminación (sin JS)
    Route::get('/incidencias/{id}/confirmar', function($id) {
        $inc = App\Models\Incidencia::findOrFail($id);
        return view('confirmar-eliminacion', [
            'titulo' => 'Incidencia',
            'mensaje' => "Estás a punto de eliminar la incidencia #{$id} ({$inc->descripcion}).",
            'ruta_eliminar' => route('incidencias.destroy', $id),
            'ruta_volver' => route('incidencias.index')
        ]);
    })->name('incidencias.confirm-destroy');
    
    // Ruta para descargar fichero adjunto de incidencia (privado)
    Route::get('/incidencias/{incidencia}/download', [IncidenciaController::class, 'descargarFichero'])->name('incidencias.download');

    // --- CRUD Cuotas ---
    Route::resource('cuotas', CuotaController::class);
    
    // Rutas adicionales para Cuotas
    Route::get('/cuotas/{id}/pdf', [CuotaController::class, 'descargarPdf'])->name('cuotas.pdf');
    Route::post('/cuotas/remesa', [CuotaController::class, 'generarRemesa'])->name('cuotas.remesa');
    
    // Ruta de confirmación de eliminación (sin JS)
    Route::get('/cuotas/{id}/confirmar', function($id) {
        $cuota = App\Models\Cuota::findOrFail($id);
        return view('confirmar-eliminacion', [
            'titulo' => 'Cuota',
            'mensaje' => "Estás a punto de eliminar la cuota #{$id} de <strong>{$cuota->importe}€</strong> ({$cuota->concepto}).",
            'ruta_eliminar' => route('cuotas.destroy', $id),
            'ruta_volver' => route('cuotas.index')
        ]);
    })->name('cuotas.confirm-destroy');

    // --- Problema 3.1: CRUD Clientes con AJAX ---
Route::prefix('clientes-ajax')->group(function () {
    Route::get('/', [App\Http\Controllers\ClienteAjaxController::class, 'index'])->name('clientes.ajax.index');
    Route::get('/list', [App\Http\Controllers\ClienteAjaxController::class, 'list'])->name('clientes.ajax.list');
    Route::post('/', [App\Http\Controllers\ClienteAjaxController::class, 'store'])->name('clientes.ajax.store');
    Route::put('/{id}', [App\Http\Controllers\ClienteAjaxController::class, 'update'])->name('clientes.ajax.update');
    Route::delete('/{id}', [App\Http\Controllers\ClienteAjaxController::class, 'destroy'])->name('clientes.ajax.destroy');
});

});