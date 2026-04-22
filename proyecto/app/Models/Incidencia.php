<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para tabla incidencias.
 * Nota: created_at se gestiona vía trigger SQL. Laravel solo actualiza updated_at.
 * @author   Álvaro Torroba Velasco
 * @version  1.0.0
 * @date     2026-04-21
 */
class Incidencia extends Model
{
    /** @var string */
    protected $table = 'incidencias';

    /** @var array<int, string> */
    protected $fillable = [
        'cliente_id', 'persona_contacto', 'telefono_contacto', 'descripcion',
        'email_contacto', 'direccion', 'poblacion', 'codigo_postal', 'provincia_codigo',
        'estado', 'operario_id', 'fecha_realizacion', 'anotaciones_antes',
        'anotaciones_despues', 'archivo_resumen'
    ];

    /** @var array<string> Solo gestionamos updated_at automáticamente */
    public $timestamps = ['updated_at'];

    /**
     * Relación N:1 con cliente.
     * @return BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación N:1 con operario asignado.
     * @return BelongsTo
     */
    public function operario(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'operario_id');
    }
}