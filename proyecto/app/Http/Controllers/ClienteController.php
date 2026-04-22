<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para gestión de clientes.
 * Solo administradores pueden gestionar.
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class ClienteController extends Controller
{
    /** Verifica que el usuario sea administrador */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    public function index()
    {
        if (!$this->esAdmin()) abort(403);
        $clientes = Cliente::latest()->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        if (!$this->esAdmin()) abort(403);
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'cif' => 'required|string|max:9|unique:clientes,cif',
            'nombre' => 'required|string|max:100',
            'telefono' => 'nullable|string',
            'email' => 'nullable|email',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'required|string|max:50',
            'moneda' => 'required|string|max:3',
            'cuota_mensual' => 'required|numeric|min:0'
        ]);

        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
    }

    public function show(Cliente $cliente)
    {
        if (!$this->esAdmin()) abort(403);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        if (!$this->esAdmin()) abort(403);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'cif' => 'required|string|max:9|unique:clientes,cif,' . $cliente->id,
            'nombre' => 'required|string|max:100',
            'telefono' => 'nullable|string',
            'email' => 'nullable|email',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'required|string|max:50',
            'moneda' => 'required|string|max:3',
            'cuota_mensual' => 'required|numeric|min:0'
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        if (!$this->esAdmin()) abort(403);
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}