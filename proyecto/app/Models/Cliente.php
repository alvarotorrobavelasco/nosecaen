<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para tabla clientes.
 * @author   Álvaro Torroba Velasco
 * @version  1.0.0
 * @date     2026-04-21
 */
class Cliente extends Model
{
    /** @var string */
    protected $table = 'clientes';

    /** @var array<int, string> */
    protected $fillable = ['cif', 'nombre', 'telefono', 'email', 'cuenta_corriente', 'pais', 'moneda', 'cuota_mensual'];

    /**
     * Relación 1:N con incidencias.
     * @return HasMany
     */
    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }

    /**
     * Relación 1:N con cuotas.
     * @return HasMany
     */
    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class);
    }
}