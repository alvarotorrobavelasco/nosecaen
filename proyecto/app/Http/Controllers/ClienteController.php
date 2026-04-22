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
     */
    public function index()
    {
        $clientes = Cliente::latest()->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guardar un nuevo cliente.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cif' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,cif',
                'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z]|[A-HJ-NP-SU-V][0-9]{7}[A-Z0-9])$/i'
            ],
            'nombre' => 'required|string|min:3|max:150',
            'telefono' => 'required|regex:/^[0-9\s\.\-]{9,15}$/',
            'email' => 'required|email|max:100|unique:clientes,email',
            'cuenta_corriente' => [
                'nullable',
                'regex:/^[A-Z]{2}\d{2}[A-Z0-9\s]{1,30}$/i',
                'max:34'
            ],
            'pais' => 'required|string|min:2|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0|max:999999.99',
        ], [
            'cif.regex' => 'Formato inválido. Use DNI, NIE o CIF válido.',
            'cif.unique' => 'Este identificador ya existe.',
            'nombre.min' => 'Mínimo 3 caracteres.',
            'telefono.regex' => 'Teléfono inválido. Use 9-15 dígitos numéricos.',
            'email.email' => 'Email inválido.',
            'email.unique' => 'Este email ya existe.',
            'cuenta_corriente.regex' => 'IBAN inválido. Debe empezar por código de país (2 letras) + 2 dígitos + caracteres alfanuméricos.',
            'cuenta_corriente.max' => 'El IBAN no puede superar 34 caracteres.',
            'pais.min' => 'Mínimo 2 caracteres.',
            'moneda.in' => 'Moneda inválida. Use EUR, USD o GBP.',
            'cuota_mensual.numeric' => 'Debe ser un número válido.',
            'cuota_mensual.min' => 'No puede ser negativo.',
        ]);

        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
    }

    /**
     * Detalle de cliente.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar un cliente.
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
            'nombre' => 'required|string|min:3|max:150',
            'telefono' => 'required|regex:/^[0-9\s\.\-]{9,15}$/',
            'email' => 'required|email|max:100|unique:clientes,email,' . $cliente->id,
            'cuenta_corriente' => [
                'nullable',
                'regex:/^[A-Z]{2}\d{2}[A-Z0-9\s]{1,30}$/i',
                'max:34'
            ],
            'pais' => 'required|string|min:2|max:100',
            'moneda' => 'required|in:EUR,USD,GBP',
            'cuota_mensual' => 'required|numeric|min:0|max:999999.99',
        ], [
            'cif.regex' => 'Formato inválido. Use DNI, NIE o CIF válido.',
            'cif.unique' => 'Este identificador ya existe.',
            'nombre.min' => 'Mínimo 3 caracteres.',
            'telefono.regex' => 'Teléfono inválido. Use 9-15 dígitos numéricos.',
            'email.email' => 'Email inválido.',
            'email.unique' => 'Este email ya existe.',
            'cuenta_corriente.regex' => 'IBAN inválido. Formato: ES12 3456 7890...',
            'cuenta_corriente.max' => 'El IBAN no puede superar 34 caracteres.',
            'pais.min' => 'Mínimo 2 caracteres.',
            'moneda.in' => 'Moneda inválida. Use EUR, USD o GBP.',
            'cuota_mensual.numeric' => 'Debe ser un número válido.',
            'cuota_mensual.min' => 'No puede ser negativo.',
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    /**
     * Eliminar un cliente.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}