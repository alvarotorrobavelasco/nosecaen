<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para tabla provincias (códigos INE).
 * @author   Álvaro Torroba Velasco
 * @version  1.0.0
 * @date     2026-04-21
 */
class Provincia extends Model
{
    /** @var string */
    protected $table = 'provincias';

    /** @var bool Sin timestamps automáticos */
    public $timestamps = false;

    /** @var array<int, string> */
    protected $fillable = ['codigo_ine', 'nombre'];
}