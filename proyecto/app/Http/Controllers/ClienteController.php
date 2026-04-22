<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Clientes.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class ClienteController extends Controller
{
    /**
     * Listado de clientes.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clientes = Cliente::latest()->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Formulario de creación.
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
            // Regex flexible: acepta DNI (8 números + letra), NIE (letra inicial + 7 números + letra) o CIF (letra + 7 números + letra/dígito)
            'cif' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,cif',
                'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z]|[A-HJ-NP-SU-V][0-9]{7}[A-Z0-9])$/i'
            ],
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:clientes,email',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'required|string|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0',
        ], [
            'cif.regex' => 'El identificador debe ser un DNI, NIE o CIF válido.',
            'cif.unique' => 'Este identificador ya está registrado.',
            'telefono.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'email.unique' => 'Este email ya está registrado.',
            'moneda.in' => 'La moneda solo puede ser EUR, USD o GBP.',
        ]);

        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
    }

    /**
     * Detalle de cliente.
     * 
     * @param \App\Models\Cliente $cliente
     * @return \Illuminate\View\View
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Formulario de edición.
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
            'cif' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,cif,' . $cliente->id,
                'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z]|[A-HJ-NP-SU-V][0-9]{7}[A-Z0-9])$/i'
            ],
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id,
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'required|string|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0',
        ], [
            'cif.regex' => 'El identificador debe ser un DNI, NIE o CIF válido.',
            'cif.unique' => 'Este identificador ya está registrado.',
            'telefono.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'email.unique' => 'Este email ya está registrado.',
            'moneda.in' => 'La moneda solo puede ser EUR, USD o GBP.',
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