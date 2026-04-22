<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'cliente_id', 'persona_contacto', 'telefono_contacto', 'descripcion',
        'email_contacto', 'direccion', 'poblacion', 'codigo_postal', 'provincia_codigo',
        'estado', 'operario_id', 'fecha_realizacion', 'anotaciones_antes',
        'anotaciones_despues', 'archivo_resumen'
    ];

    // El trigger SQL gestiona created_at, pero necesitamos que Laravel lo castee a fecha
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Forzamos que estos campos sean tratados como fechas (Carbon)
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
}