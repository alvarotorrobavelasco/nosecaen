<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la gestión de Cuotas.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class CuotaController extends Controller
{
    /**
     * Verificar si es administrador.
     */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    /**
     * Listado de cuotas.
     */
    public function index()
    {
        if (!$this->esAdmin()) abort(403);
        $cuotas = Cuota::with('cliente')->latest()->paginate(10);
        return view('cuotas.index', compact('cuotas'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        if (!$this->esAdmin()) abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.create', compact('clientes'));
    }

    /**
     * Guardar cuota.
     */
    public function store(Request $request)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|min:5|max:150',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0.01|max:999999.99',
            'pagada' => 'required|in:S,N',
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:500',
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'Cliente no válido.',
            'concepto.required' => 'El concepto es obligatorio.',
            'concepto.min' => 'El concepto debe tener al menos 5 caracteres.',
            'concepto.max' => 'El concepto no puede superar 150 caracteres.',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
            'fecha_emision.date' => 'Fecha inválida.',
            'importe.required' => 'El importe es obligatorio.',
            'importe.numeric' => 'Debe ser un número válido.',
            'importe.min' => 'El importe mínimo es 0.01 €.',
            'pagada.required' => 'Debe indicar si está pagada.',
            'pagada.in' => 'El estado solo puede ser S (Sí) o N (No).',
            'fecha_pago.after_or_equal' => 'La fecha de pago no puede ser anterior a la emisión.',
        ]);

        Cuota::create($validated);
        return redirect()->route('cuotas.index')->with('success', 'Cuota creada.');
    }

    /**
     * Detalle de cuota.
     */
    public function show(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        return view('cuotas.show', compact('cuota'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.edit', compact('cuota', 'clientes'));
    }

    /**
     * Actualizar cuota.
     */
    public function update(Request $request, Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|min:5|max:150',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0.01|max:999999.99',
            'pagada' => 'required|in:S,N',
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:500',
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'concepto.required' => 'El concepto es obligatorio.',
            'concepto.min' => 'El concepto debe tener al menos 5 caracteres.',
            'importe.required' => 'El importe es obligatorio.',
            'importe.min' => 'El importe mínimo es 0.01 €.',
            'pagada.in' => 'El estado solo puede ser S o N.',
            'fecha_pago.after_or_equal' => 'La fecha de pago no puede ser anterior a la emisión.',
        ]);

        $cuota->update($validated);
        return redirect()->route('cuotas.index')->with('success', 'Cuota actualizada.');
    }

    /**
     * Eliminar cuota.
     */
    public function destroy(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        $cuota->delete();
        return redirect()->route('cuotas.index')->with('success', 'Cuota eliminada.');
    }
}