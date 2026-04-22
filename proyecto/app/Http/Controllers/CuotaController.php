<?php
namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuotaController extends Controller
{
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    public function index()
    {
        if (!$this->esAdmin()) abort(403);
        $cuotas = Cuota::with('cliente')->latest()->paginate(10);
        return view('cuotas.index', compact('cuotas'));
    }

    public function create()
    {
        if (!$this->esAdmin()) abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.create', compact('clientes'));
    }

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
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente',
            'concepto.required' => 'El concepto es obligatorio',
            'fecha_emision.required' => 'La fecha es obligatoria',
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser un número válido',
        ]);

        try {
            Cuota::create($validated);
            return redirect()->route('cuotas.index')->with('success', 'Cuota creada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    public function show(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        return view('cuotas.show', compact('cuota'));
    }

    public function edit(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.edit', compact('cuota', 'clientes'));
    }

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

    public function destroy(Cuota $cuota)
    {
        if (!$this->esAdmin()) abort(403);
        $cuota->delete();
        return redirect()->route('cuotas.index')->with('success', 'Cuota eliminada.');
    }
}