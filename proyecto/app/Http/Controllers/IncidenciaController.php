<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para gestión de incidencias.
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class IncidenciaController extends Controller
{
    /**
     * Listar incidencias.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Si es operario, solo ve las suyas
        if ($user->tipo === 'operario') {
            $incidencias = Incidencia::where('operario_id', $user->id)
                ->with(['cliente', 'operario'])
                ->latest()
                ->paginate(10);
        } else {
            // Admin ve todas
            $incidencias = Incidencia::with(['cliente', 'operario'])
                ->latest()
                ->paginate(10);
        }
        
        return view('incidencias.index', compact('incidencias'));
    }

    /**
     * Formulario crear incidencia.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->get();
        
        return view('incidencias.create', compact('clientes', 'provincias', 'operarios'));
    }

    /**
     * Guardar nueva incidencia.
     */
    public function store(Request $request)
    {
        // Validación básica
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|string',
            'descripcion' => 'required|string',
            'email_contacto' => 'required|email',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
        ]);

        // Si es admin, requiere operario asignado
        if (Auth::user()->tipo === 'administrador') {
            $validated['operario_id'] = $request->operario_id;
        }

        Incidencia::create($validated);

        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia creada correctamente');
    }

    /**
     * Mostrar incidencia.
     */
    public function show(Incidencia $incidencia)
    {
        return view('incidencias.show', compact('incidencia'));
    }

    /**
     * Formulario editar.
     */
    public function edit(Incidencia $incidencia)
    {
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
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|string',
            'descripcion' => 'required|string',
            'email_contacto' => 'required|email',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
        ]);

        if (Auth::user()->tipo === 'administrador') {
            $validated['operario_id'] = $request->operario_id;
        }

        $incidencia->update($validated);

        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia actualizada');
    }

    /**
     * Eliminar incidencia.
     */
    public function destroy(Incidencia $incidencia)
    {
        $incidencia->delete();
        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia eliminada');
    }
}