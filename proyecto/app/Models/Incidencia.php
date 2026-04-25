<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    protected $fillable = [
        'cliente_id',
        'persona_contacto',
        'telefono_contacto',
        'descripcion',
        'email_contacto',
        'direccion',
        'poblacion',
        'codigo_postal',
        'provincia_codigo',
        'estado',
        'operario_id',
        'fecha_realizacion',
        'anotaciones_antes',
        'anotaciones_despues',
        'fichero_resumen',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'fecha_realizacion' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function operario()
    {
        return $this->belongsTo(Empleado::class, 'operario_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_codigo', 'codigo_ine');
    }
    /**
 * Scope para buscar incidencias por descripción o persona de contacto.
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @param string|null $texto
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeBuscar($query, $texto = null)
{
    if ($texto) {
        return $query->where('descripcion', 'like', '%' . $texto . '%')
                     ->orWhere('persona_contacto', 'like', '%' . $texto . '%');
    }
    return $query;
}
}