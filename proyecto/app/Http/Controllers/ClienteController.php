<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Clientes.
 * 
 * Permite realizar operaciones CRUD sobre los clientes del sistema.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 * @package App\Http\Controllers
 */
class ClienteController extends Controller
{
    /**
     * Mostrar listado de clientes.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clientes = Cliente::latest()->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar formulario de creación.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guardar un nuevo cliente.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cif' => 'required|string|max:20|unique:clientes,cif',
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:clientes,email',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'required|string|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0',
        ]);

        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
    }

    /**
     * Mostrar detalle de un cliente.
     * 
     * @param \App\Models\Cliente $cliente
     * @return \Illuminate\View\View
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Mostrar formulario de edición.
     * 
     * @param \App\Models\Cliente $cliente
     * @return \Illuminate\View\View
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar un cliente.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Cliente $cliente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'cif' => 'required|string|max:20|unique:clientes,cif,' . $cliente->id,
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id,
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'required|string|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0',
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    /**
     * Eliminar un cliente.
     * 
     * @param \App\Models\Cliente $cliente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}