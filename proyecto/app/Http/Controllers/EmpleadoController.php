<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    public function index()
    {
        if (!$this->esAdmin()) abort(403);
        $empleados = Empleado::latest()->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:empleados,email',
            'password' => 'required|min:6|confirmed',
            'tipo' => 'required|in:administrador,operario',
        ], [
            'telefono.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'email.unique' => 'Este email ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        Empleado::create([
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo' => $validated['tipo'],
        ]);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado.');
    }

    public function show(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        
        // Validación completa (incluyendo apellidos y password opcional)
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'telefono' => 'required|regex:/^[\d\s\.\-\(\)]+$/',
            'email' => 'required|email|unique:empleados,email,' . $empleado->id,
            'tipo' => 'required|in:administrador,operario',
            'password' => 'nullable|min:6|confirmed', // Contraseña opcional al editar
        ], [
            'telefono.regex' => 'El teléfono solo puede contener números y caracteres básicos.',
            'email.unique' => 'Este email ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Preparar datos básicos
        $data = [
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'tipo' => $validated['tipo'],
        ];

        // Solo actualizar contraseña si se ha escrito una nueva
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $empleado->update($data);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado.');
    }

    public function destroy(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        
        if ($empleado->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }
}