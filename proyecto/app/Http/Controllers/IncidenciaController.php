<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la gestión de Incidencias.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class IncidenciaController extends Controller
{
    /**
     * Listado de incidencias.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tipo === 'operario') {
            $incidencias = Incidencia::where('operario_id', $user->id)
                ->with(['cliente', 'operario'])
                ->latest()
                ->paginate(10);
        } else {
            $incidencias = Incidencia::with(['cliente', 'operario'])
                ->latest()
                ->paginate(10);
        }
        return view('incidencias.index', compact('incidencias'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->get();
        return view('incidencias.create', compact('clientes', 'provincias', 'operarios'));
    }

    /**
     * Guardar incidencia.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'descripcion' => 'required|string|min:10',
            'email_contacto' => 'required|email',
            'direccion' => 'nullable|string|max:255',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            'operario_id' => 'nullable|exists:empleados,id',
        ], [
            'telefono_contacto.regex' => 'Teléfono inválido. Solo números y caracteres básicos.',
            'codigo_postal.regex' => 'Código postal inválido. Debe tener 5 dígitos.',
            'estado.in' => 'Estado inválido. Use P, R o C.',
            'email_contacto.email' => 'Email inválido.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
        ]);

        Incidencia::create($validated);
        return redirect()->route('incidencias.index')->with('success', 'Incidencia creada.');
    }

    /**
     * Detalle de incidencia.
     */
    public function show(Incidencia $incidencia)
    {
        return view('incidencias.show', compact('incidencia'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Incidencia $incidencia)
    {
        if (Auth::user()->tipo === 'operario' && $incidencia->operario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta incidencia');
        }
        
        $clientes = Cliente::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->get();
        
        return view('incidencias.edit', compact('incidencia', 'clientes', 'provincias', 'operarios'));
    }

    /**
     * Actualizar incidencia.
     */
    public function update(Request $request, Incidencia $incidencia)
    {
        if (Auth::user()->tipo === 'operario' && $incidencia->operario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para modificar esta incidencia');
        }

        if (Auth::user()->tipo === 'administrador') {
            $rules = [
                'cliente_id' => 'required|exists:clientes,id',
                'persona_contacto' => 'required|string|max:100',
                'telefono_contacto' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
                'descripcion' => 'required|string|min:10',
                'email_contacto' => 'required|email',
                'direccion' => 'nullable|string|max:255',
                'poblacion' => 'nullable|string|max:100',
                'codigo_postal' => 'nullable|regex:/^\d{5}$/',
                'provincia_codigo' => 'required|exists:provincias,codigo_ine',
                'estado' => 'required|in:P,R,C',
                'operario_id' => 'required|exists:empleados,id',
                'fecha_realizacion' => 'nullable|date',
                'anotaciones_despues' => 'nullable|string|max:500',
            ];
        } else {
            $rules = [
                'estado' => 'required|in:P,R,C',
                'fecha_realizacion' => 'nullable|date',
                'anotaciones_despues' => 'nullable|string|max:500',
            ];
        }

        $messages = [
            'telefono_contacto.regex' => 'Teléfono inválido. Solo números y caracteres básicos.',
            'codigo_postal.regex' => 'Código postal inválido. Debe tener 5 dígitos.',
            'estado.in' => 'Estado inválido. Use P, R o C.',
            'email_contacto.email' => 'Email inválido.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'fecha_realizacion.date' => 'Fecha inválida.',
        ];

        $request->validate($rules, $messages);

        if (Auth::user()->tipo === 'administrador') {
            $incidencia->update($request->all());
        } else {
            $incidencia->update([
                'estado' => $request->estado,
                'fecha_realizacion' => $request->fecha_realizacion,
                'anotaciones_despues' => $request->anotaciones_despues,
            ]);
        }

        return redirect()->route('incidencias.index')->with('success', 'Incidencia actualizada.');
    }

    /**
     * Eliminar incidencia.
     */
    public function destroy(Incidencia $incidencia)
    {
        $incidencia->delete();
        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada.');
    }

    /**
     * Registro público de incidencias.
     */
    public function createCliente()
    {
        $provincias = Provincia::orderBy('nombre')->get();
        return view('clientes.registro', compact('provincias'));
    }

    /**
     * Guardar incidencia pública.
     */
    public function storeCliente(Request $request)
    {
        $validated = $request->validate([
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email_contacto' => 'required|email',
            'descripcion' => 'required|string|min:10',
            'direccion' => 'nullable|string|max:255',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
        ], [
            'telefono_contacto.regex' => 'Teléfono inválido.',
            'codigo_postal.regex' => 'Código postal debe tener 5 dígitos.',
            'descripcion.min' => 'Descripción muy corta (mínimo 10 caracteres).',
        ]);

        Incidencia::create([
            'persona_contacto' => $validated['persona_contacto'],
            'telefono_contacto' => $validated['telefono_contacto'],
            'email_contacto' => $validated['email_contacto'],
            'descripcion' => $validated['descripcion'],
            'direccion' => $validated['direccion'],
            'poblacion' => $validated['poblacion'],
            'codigo_postal' => $validated['codigo_postal'],
            'provincia_codigo' => $validated['provincia_codigo'],
            'estado' => 'P',
            'cliente_id' => null,
        ]);

        return redirect()->route('cliente.registro')->with('success', 'Incidencia registrada. Nos pondremos en contacto.');
    }
}