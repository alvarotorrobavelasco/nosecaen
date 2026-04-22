<?php
namespace App\Models;

// 👇 CAMBIO CLAVE: Extender de Authenticatable en lugar de Model
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo de Empleado para autenticación.
 * @author   Álvaro Torroba Velasco
 * @version  1.0.0
 * @date     2026-04-21
 */
class Empleado extends Authenticatable
{
    use Notifiable;

    /** @var string */
    protected $table = 'empleados';

    /** @var array<int, string> */
    protected $fillable = [
        'dni', 'nombre', 'email', 'telefono', 'direccion', 
        'fecha_alta', 'tipo', 'password'
    ];

    /** @var array<string> */
    protected $hidden = ['password', 'remember_token'];

    /** @var array<string, string> */
    protected $casts = [
        'password' => 'hashed', // 👈 Laravel 11+: hash automático de contraseñas
        'fecha_alta' => 'date',
    ];
}