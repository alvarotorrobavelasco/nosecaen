<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para tabla cuotas.
 * @author   Álvaro Torroba Velasco
 * @version  1.0.0
 * @date     2026-04-21
 */
class Cuota extends Model
{
    /** @var string */
    protected $table = 'cuotas';

    /** @var array<int, string> */
    protected $fillable = ['cliente_id', 'concepto', 'fecha_emision', 'importe', 'pagada', 'fecha_pago', 'notas', 'archivo_pdf'];

    /**
     * Relación N:1 con cliente.
     * @return BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}