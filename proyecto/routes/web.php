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
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/cliente/registro', [IncidenciaController::class, 'createCliente'])->name('cliente.registro');
Route::post('/cliente/registro', [IncidenciaController::class, 'storeCliente'])->name('cliente.registrar');

// ==========================================
// Rutas Protegidas (Requieren Auth)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Inicio
    Route::get('/', [InicioController::class, 'index'])->name('inicio');
    
    // Perfil
    Route::get('/mi-perfil', [EmpleadoController::class, 'miPerfil'])->name('mi-perfil');
    Route::put('/mi-perfil', [EmpleadoController::class, 'updatePerfil'])->name('mi-perfil.update');
    
    // --- CRUD Empleados ---
    Route::resource('empleados', EmpleadoController::class);
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
    Route::get('/incidencias/{id}/confirmar', function($id) {
        $inc = App\Models\Incidencia::findOrFail($id);
        return view('confirmar-eliminacion', [
            'titulo' => 'Incidencia',
            'mensaje' => "Estás a punto de eliminar la incidencia #{$id} ({$inc->descripcion}).",
            'ruta_eliminar' => route('incidencias.destroy', $id),
            'ruta_volver' => route('incidencias.index')
        ]);
    })->name('incidencias.confirm-destroy');

    // --- CRUD Cuotas ---
    Route::resource('cuotas', CuotaController::class);
    
    // Rutas adicionales Cuotas
    Route::get('/cuotas/{id}/pdf', [CuotaController::class, 'descargarPdf'])->name('cuotas.pdf');
    Route::post('/cuotas/remesa', [CuotaController::class, 'generarRemesa'])->name('cuotas.remesa');
    
    Route::get('/cuotas/{id}/confirmar', function($id) {
        $cuota = App\Models\Cuota::findOrFail($id);
        return view('confirmar-eliminacion', [
            'titulo' => 'Cuota',
            'mensaje' => "Estás a punto de eliminar la cuota #{$id} de <strong>{$cuota->importe}€</strong> ({$cuota->concepto}).",
            'ruta_eliminar' => route('cuotas.destroy', $id),
            'ruta_volver' => route('cuotas.index')
        ]);
    })->name('cuotas.confirm-destroy');

});