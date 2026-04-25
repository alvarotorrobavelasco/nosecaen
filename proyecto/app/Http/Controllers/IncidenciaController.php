<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class IncidenciaController extends Controller
{
    public function index()
{
    $query = Incidencia::query();

    // Si es operario, solo ve las suyas
    if (Auth::user()->tipo === 'operario') {
        $query->where('operario_id', Auth::id());
    }

    // Aplicamos búsqueda (llama al scope del Modelo)
    $incidencias = $query->buscar(request('busqueda'))
                         ->with(['cliente', 'operario'])
                         ->latest()
                         ->paginate(10);

    return view('incidencias.index', compact('incidencias'));
}

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->get();
        return view('incidencias.create', compact('clientes', 'provincias', 'operarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|min:3|max:100',
            'telefono_contacto' => 'required|regex:/^[0-9]{9,15}$/',
            'descripcion' => 'required|string|min:10|max:500',
            'email_contacto' => 'required|email|max:100',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            'direccion' => 'nullable|string|min:5|max:255',
            'poblacion' => 'nullable|string|min:3|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
            'operario_id' => 'nullable|exists:empleados,id',
        ]);

        Incidencia::create($validated);
        return redirect()->route('incidencias.index')->with('success', 'Incidencia creada correctamente.');
    }

    public function show(Incidencia $incidencia)
    {
        $incidencia->load('cliente', 'operario');
        return view('incidencias.show', compact('incidencia'));
    }

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

    public function update(Request $request, Incidencia $incidencia)
{
    // Permiso básico: solo admin o el operario asignado
    if (Auth::user()->tipo === 'operario' && $incidencia->operario_id !== Auth::id()) {
        abort(403, 'No tienes permiso para editar esta incidencia');
    }

    // 🟢 VALIDACIÓN SEGÚN ROL
    if (Auth::user()->tipo === 'administrador') {
        // Admin puede editar TODOS los campos
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|string',
            'email_contacto' => 'required|email',
            'descripcion' => 'required|string|min:10',
            'direccion' => 'nullable|string',
            'poblacion' => 'nullable|string',
            'codigo_postal' => 'nullable|string|size:5',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            'operario_id' => 'required|exists:empleados,id',
            'fecha_realizacion' => 'nullable|date',
            'anotaciones_despues' => 'nullable|string|max:500',
            'fichero_resumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);
    } else {
        // Operario SOLO puede editar estos 4 campos
        $validated = $request->validate([
            'estado' => 'required|in:P,R,C',
            'fecha_realizacion' => 'nullable|date',
            'anotaciones_despues' => 'nullable|string|max:500',
            'fichero_resumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);
    }

    // 📂 LÓGICA DE FICHERO (Igual para ambos)
    if ($request->hasFile('fichero_resumen')) {
        if ($incidencia->fichero_resumen && Storage::exists($incidencia->fichero_resumen)) {
            Storage::delete($incidencia->fichero_resumen);
        }
        $validated['fichero_resumen'] = $request->file('fichero_resumen')->store('ficheros-incidencias');
    }

    $incidencia->update($validated);
    return redirect()->route('incidencias.index')->with('success', 'Incidencia actualizada.');
}
    public function destroy(Incidencia $incidencia)
    {
        if ($incidencia->fichero_resumen && Storage::exists($incidencia->fichero_resumen)) {
            Storage::delete($incidencia->fichero_resumen);
        }
        $incidencia->delete();
        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada.');
    }

    public function descargarFichero($id)
    {
        $incidencia = Incidencia::findOrFail($id);

        if (Auth::user()->tipo === 'operario' && $incidencia->operario_id !== Auth::id()) {
            abort(403);
        }

        if (!$incidencia->fichero_resumen || !Storage::exists($incidencia->fichero_resumen)) {
            abort(404, 'El archivo no existe');
        }

        return Storage::download($incidencia->fichero_resumen);
    }

    public function createCliente()
    {
        $provincias = Provincia::orderBy('nombre')->get();
        return view('clientes.registro', compact('provincias'));
    }

    public function storeCliente(Request $request)
    {
        $validated = $request->validate([
            'persona_contacto' => 'required|string|min:3|max:100',
            'telefono_contacto' => 'required|regex:/^[0-9]{9,15}$/',
            'email_contacto' => 'required|email',
            'descripcion' => 'required|string|min:10',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'direccion' => 'nullable|string|min:5|max:255',
            'poblacion' => 'nullable|string|min:3|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
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