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
            $incidencias = Incidencia::where('operario_id', $user->id)->with(['cliente', 'operario'])->latest()->paginate(10);
        } else {
            $incidencias = Incidencia::with(['cliente', 'operario'])->latest()->paginate(10);
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
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'descripcion' => 'required|string',
            'email_contacto' => 'required|email',
            'direccion' => 'nullable|string|max:255',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            'operario_id' => 'nullable|exists:empleados,id',
        ], [
            'telefono_contacto.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'codigo_postal.regex' => 'El código postal debe ser de 5 dígitos.',
            'estado.in' => 'El estado solo puede ser P, R o C.',
        ]);

        Incidencia::create($request->all());
        return redirect()->route('incidencias.index')->with('success', 'Incidencia creada.');
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
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'descripcion' => 'required|string',
            'email_contacto' => 'required|email',
            'direccion' => 'nullable|string|max:255',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
            'estado' => 'required|in:P,R,C',
            'operario_id' => 'nullable|exists:empleados,id',
        ], [
            'telefono_contacto.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'codigo_postal.regex' => 'El código postal debe ser de 5 dígitos.',
            'estado.in' => 'El estado solo puede ser P, R o C.',
        ]);

        $incidencia->update($request->all());
        return redirect()->route('incidencias.index')->with('success', 'Incidencia actualizada.');
    }

    public function destroy(Incidencia $incidencia)
    {
        $incidencia->delete();
        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada.');
    }

    // ========================================
    // MÉTODOS NUEVOS: Registro público cliente
    // ========================================
    
    public function createCliente()
    {
        $provincias = Provincia::orderBy('nombre')->get();
        return view('clientes.registro', compact('provincias'));
    }

    public function storeCliente(Request $request)
    {
        $request->validate([
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email_contacto' => 'required|email',
            'descripcion' => 'required|string',
            'direccion' => 'nullable|string|max:255',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|regex:/^\d{5}$/',
            'provincia_codigo' => 'required|exists:provincias,codigo_ine',
        ], [
            'telefono_contacto.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'codigo_postal.regex' => 'El código postal debe ser de 5 dígitos.',
        ]);

        Incidencia::create([
            'persona_contacto' => $request->persona_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'email_contacto' => $request->email_contacto,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'poblacion' => $request->poblacion,
            'codigo_postal' => $request->codigo_postal,
            'provincia_codigo' => $request->provincia_codigo,
            'estado' => 'P',
            'cliente_id' => null,
        ]);

        return redirect()->route('cliente.registro')->with('success', 'Incidencia registrada correctamente. Nos pondremos en contacto.');
    }
}