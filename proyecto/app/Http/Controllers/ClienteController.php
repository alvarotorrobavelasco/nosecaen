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
    public function index()
    {
        $clientes = Cliente::latest()->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cif' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,cif',
                'regex:/^[A-Z0-9][0-9]{7}[A-Z0-9]$/', // CIF válido español
            ],
            'nombre' => 'required|string|max:150',
            'telefono' => [
                'required',
                'regex:/^[\d\s\.\-\(\)]+$/',
                'min:9',
            ],
            'email' => 'required|email|unique:clientes,email',
            'cuenta_corriente' => [
                'nullable',
                'string',
                'max:34',
                'regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9\s]{11,30}$/', // IBAN básico
            ],
            'pais' => 'required|string|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0',
        ], [
            'cif.regex' => 'El CIF debe tener formato válido (ej: B12345678).',
            'cif.unique' => 'Este CIF ya está registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'telefono.min' => 'El teléfono debe tener al menos 9 dígitos.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser válido.',
            'email.unique' => 'Este email ya está registrado.',
            'cuenta_corriente.regex' => 'El IBAN debe tener formato válido (ej: ES12 3456 7890 1234 5678 9012).',
            'moneda.in' => 'La moneda solo puede ser EUR, USD o GBP.',
        ]);

        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'cif' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,cif,' . $cliente->id,
                'regex:/^[A-Z0-9][0-9]{7}[A-Z0-9]$/',
            ],
            'nombre' => 'required|string|max:150',
            'telefono' => [
                'required',
                'regex:/^[\d\s\.\-\(\)]+$/',
                'min:9',
            ],
            'email' => 'required|email|unique:clientes,email,' . $cliente->id,
            'cuenta_corriente' => [
                'nullable',
                'string',
                'max:34',
                'regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9\s]{11,30}$/',
            ],
            'pais' => 'required|string|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0',
        ], [
            'cif.regex' => 'El CIF debe tener formato válido (ej: B12345678).',
            'cif.unique' => 'Este CIF ya está registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'telefono.min' => 'El teléfono debe tener al menos 9 dígitos.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser válido.',
            'email.unique' => 'Este email ya está registrado.',
            'cuenta_corriente.regex' => 'El IBAN debe tener formato válido.',
            'moneda.in' => 'La moneda solo puede ser EUR, USD o GBP.',
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}