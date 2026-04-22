<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador para la gestión de Empleados.
 * 
 * Gestiona el personal del sistema (administradores y operarios).
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 * @package App\Http\Controllers
 */
class EmpleadoController extends Controller
{
    /**
     * Verificar si el usuario es administrador.
     * 
     * @return bool
     */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    /**
     * Listado de empleados.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!$this->esAdmin()) abort(403);
        $empleados = Empleado::latest()->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Formulario de creación.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.create');
    }

    /**
     * Guardar empleado.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:empleados,email',
            'password' => 'required|min:6|confirmed',
            'tipo' => 'required|in:administrador,operario',
        ]);

        Empleado::create([
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo' => $validated['tipo'],
        ]);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado.');
    }

    /**
     * Detalle de empleado.
     * 
     * @param \App\Models\Empleado $empleado
     * @return \Illuminate\View\View
     */
    public function show(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Formulario de edición.
     * 
     * @param \App\Models\Empleado $empleado
     * @return \Illuminate\View\View
     */
    public function edit(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Actualizar empleado.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Empleado $empleado
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:empleados,email,' . $empleado->id,
            'tipo' => 'required|in:administrador,operario',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'tipo' => $validated['tipo'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $empleado->update($data);
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado.');
    }

    /**
     * Eliminar empleado.
     * 
     * @param \App\Models\Empleado $empleado
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        if ($empleado->id === Auth::id()) return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }
}