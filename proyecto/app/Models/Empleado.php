<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Modelo Empleado.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class Empleado extends Authenticatable
{
    protected $fillable = [
        'dni',
        'nombre',
        'telefono',
        'email',
        'password',
        'tipo',
        'fecha_alta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'fecha_alta' => 'datetime',
    ];
}