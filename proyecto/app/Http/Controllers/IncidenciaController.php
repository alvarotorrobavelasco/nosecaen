<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenciaController extends Controller
{
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
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|string',
            'descripcion' => 'required|string',
            'email_contacto' => 'required|email',
            'direccion' => 'nullable|string|max:255',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:5',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            'operario_id' => 'nullable|exists:empleados,id'
        ]);

        Incidencia::create($validated);

        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia creada correctamente');
    }

    public function show(Incidencia $incidencia)
    {
        return view('incidencias.show', compact('incidencia'));
    }

    public function edit(Incidencia $incidencia)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->get();
        
        return view('incidencias.edit', compact('incidencia', 'clientes', 'provincias', 'operarios'));
    }

    public function update(Request $request, Incidencia $incidencia)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|string',
            'descripcion' => 'required|string',
            'email_contacto' => 'required|email',
            'direccion' => 'nullable|string|max:255',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:5',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            'operario_id' => 'nullable|exists:empleados,id'
        ]);

        $incidencia->update($validated);

        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia actualizada');
    }

    public function destroy(Incidencia $incidencia)
    {
        $incidencia->delete();
        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia eliminada');
    }
}