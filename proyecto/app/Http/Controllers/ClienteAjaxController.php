<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Clientes vía AJAX (Problema 3.1).
 * Permite operaciones CRUD sin recarga de página usando JSON.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0
 * @date 2026-04-26
 */
class ClienteAjaxController extends Controller
{
    /**
     * Muestra la vista principal con la tabla de DataTables.
     */
    public function index()
    {
        return view('clientes-ajax.index');
    }

    /**
 * Mostrar vista del CRUD con Vue/Quasar CDN.
 */
public function indexVue()
{
    return view('clientes-vue.index');
}


    /**
     * Lista todos los clientes en formato JSON para DataTables.
     */
    public function list()
    {
        // Devolvemos los clientes ordenados por nombre
        return response()->json(Cliente::orderBy('nombre')->get());
    }

    /**
     * Almacena un nuevo cliente.
     */
    public function store(Request $request)
    {
        // Validación con mensajes personalizados
        $validated = $request->validate([
            'cif' => 'required|string|size:9|unique:clientes,cif',
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|min:9',
            'email' => 'required|email|max:100',
            'pais' => 'required|string|max:50',
        ], [
            'cif.size' => 'El CIF debe tener exactamente 9 caracteres.',
            'telefono.min' => 'El teléfono debe tener al menos 9 dígitos.',
        ]);

        // Creamos el cliente
        $cliente = Cliente::create($validated);

        // Respondemos con éxito
        return response()->json([
            'status' => 'success',
            'message' => 'Cliente creado correctamente.',
            'data' => $cliente
        ], 201);
    }

    /**
     * Actualiza un cliente existente.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'cif' => 'required|string|size:9|unique:clientes,cif,' . $cliente->id,
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|min:9',
            'email' => 'required|email|max:100',
            'pais' => 'required|string|max:50',
        ]);

        $cliente->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Cliente actualizado correctamente.',
            'data' => $cliente
        ]);
    }

    /**
     * Elimina un cliente.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Cliente eliminado correctamente.'
        ]);
    }
}