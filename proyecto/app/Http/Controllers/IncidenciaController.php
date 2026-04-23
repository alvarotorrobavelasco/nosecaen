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
     * Guardar incidencia (ADMINISTRADOR).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // CAMPOS OBLIGATORIOS
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|min:3|max:100',
            'telefono_contacto' => 'required|regex:/^[0-9]{9,15}$/',
            'descripcion' => 'required|string|min:10|max:500',
            'email_contacto' => 'required|email|max:100',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            
            // CAMPOS OPCIONALES (nullable)
            'direccion' => 'nullable|string|min:5|max:255',
            'poblacion' => 'nullable|string|min:3|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
            'operario_id' => 'nullable|exists:empleados,id',
        ], [
            'persona_contacto.min' => 'El nombre de contacto debe tener al menos 3 caracteres.',
            'telefono_contacto.regex' => 'El teléfono debe tener entre 9 y 15 dígitos numéricos.',
            'codigo_postal.regex' => 'El código postal debe tener exactamente 5 dígitos.',
            'poblacion.min' => 'El nombre de la población es demasiado corto.',
            'direccion.min' => 'La dirección es demasiado corta.',
            'descripcion.min' => 'La descripción debe ser más detallada (mínimo 10 caracteres).',
            'estado.in' => 'Estado inválido. Use P, R o C.',
        ]);

        Incidencia::create($validated);
        return redirect()->route('incidencias.index')->with('success', 'Incidencia creada correctamente.');
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
                // OBLIGATORIOS
                'cliente_id' => 'required|exists:clientes,id',
                'persona_contacto' => 'required|string|min:3|max:100',
                'telefono_contacto' => 'required|regex:/^[0-9]{9,15}$/',
                'descripcion' => 'required|string|min:10|max:500',
                'email_contacto' => 'required|email|max:100',
                'provincia_codigo' => 'required|exists:provincias,codigo_ine',
                'estado' => 'required|in:P,R,C',
                
                // OPCIONALES
                'direccion' => 'nullable|string|min:5|max:255',
                'poblacion' => 'nullable|string|min:3|max:100',
                'codigo_postal' => 'nullable|regex:/^\d{5}$/',
                'operario_id' => 'nullable|exists:empleados,id',
                'fecha_realizacion' => 'nullable|date',
                'anotaciones_despues' => 'nullable|string|max:500',
            ];
        } else {
            // Operario: solo puede cambiar estado y anotaciones
            $rules = [
                'estado' => 'required|in:P,R,C',
                'fecha_realizacion' => 'nullable|date',
                'anotaciones_despues' => 'nullable|string|max:500',
            ];
        }

        $messages = [
            'telefono_contacto.regex' => 'Teléfono inválido (9-15 dígitos).',
            'codigo_postal.regex' => 'Código postal inválido (5 dígitos).',
            'estado.in' => 'Estado inválido.',
            'persona_contacto.min' => 'Nombre de contacto muy corto.',
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
     * Registro público de incidencias (sin login).
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
            // OBLIGATORIOS
            'persona_contacto' => 'required|string|min:3|max:100',
            'telefono_contacto' => 'required|regex:/^[0-9]{9,15}$/',
            'email_contacto' => 'required|email',
            'descripcion' => 'required|string|min:10',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            
            // OPCIONALES
            'direccion' => 'nullable|string|min:5|max:255',
            'poblacion' => 'nullable|string|min:3|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
        ], [
            'telefono_contacto.regex' => 'Teléfono inválido (mínimo 9 dígitos).',
            'codigo_postal.regex' => 'El código postal debe tener 5 dígitos.',
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

        return redirect()->route('cliente.registro')->with('success', 'Incidencia registrada correctamente.');
    }
}