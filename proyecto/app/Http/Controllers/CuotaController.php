<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la gestión de Cuotas.
 * 
 * Gestiona las cuotas mensuales de los clientes.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 * @package App\Http\Controllers
 */
class CuotaController extends Controller
{
    /**
     * Verificar si es administrador.
     * 
     * @return bool
     */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    /**
     * Listado de cuotas.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!$this->esAdmin()) abort(403);
        $cuotas = Cuota::with('cliente')->latest()->paginate(10);
        return view('cuotas.index', compact('cuotas'));
    }

    /**
     * Formulario de creación.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!$this->esAdmin()) abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.create', compact('clientes'));
    }

    /**
     * Guardar cuota.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:150',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0',
            'pagada' => 'required|in:S,N',
            'notas' => 'nullable|string'
        ]);

        Cuota::create($validated);
        return redirect()->route('cuotas.index')->with('success', 'Cuota creada.');
    }

    /**
     * Detalle de cuota.
     * 
     * @param \App\Models\Cuota $cuota
     * @return \Illuminate\View\View
     */
    public function show(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        return view('cuotas.show', compact('cuota'));
    }

    /**
     * Formulario de edición.
     * 
     * @param \App\Models\Cuota $cuota
     * @return \Illuminate\View\View
     */
    public function edit(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.edit', compact('cuota', 'clientes'));
    }

    /**
     * Actualizar cuota.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Cuota $cuota
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:150',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0',
            'pagada' => 'required|in:S,N',
            'notas' => 'nullable|string'
        ]);

        $cuota->update($validated);
        return redirect()->route('cuotas.index')->with('success', 'Cuota actualizada.');
    }

    /**
     * Eliminar cuota.
     * 
     * @param \App\Models\Cuota $cuota
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        $cuota->delete();
        return redirect()->route('cuotas.index')->with('success', 'Cuota eliminada.');
    }
}