<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador para la gestión de Empleados.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class EmpleadoController extends Controller
{
    /**
     * Verificar si es administrador.
     */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    /**
     * Listado de empleados.
     */
    public function index()
    {
        if (!$this->esAdmin()) abort(403);
        $empleados = Empleado::latest()->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.create');
    }

    /**
     * Guardar empleado.
     */
    public function store(Request $request)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'dni' => [
                'required',
                'string',
                'max:20',
                'unique:empleados,dni',
                'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z])$/i'
            ],
            'nombre' => 'required|string|min:3|max:100',
            'telefono' => 'required|regex:/^[0-9\s\.\-]{9,15}$/',
            'email' => 'required|email|max:100|unique:empleados,email',
            'password' => 'required|min:6|confirmed',
            'tipo' => 'required|in:administrador,operario',
        ], [
            'dni.required' => 'El DNI/NIE es obligatorio.',
            'dni.regex' => 'DNI/NIE inválido. Formato: 12345678A o X1234567L.',
            'dni.unique' => 'Este DNI/NIE ya está registrado.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'telefono.regex' => 'Teléfono inválido. Use 9-15 dígitos numéricos.',
            'email.unique' => 'Este email ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        Empleado::create([
            'dni' => strtoupper($validated['dni']),
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo' => $validated['tipo'],
            'fecha_alta' => now(),
        ]);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado.');
    }

    /**
     * Detalle de empleado.
     */
    public function show(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Actualizar empleado.
     */
    public function update(Request $request, Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        
        $validated = $request->validate([
            'dni' => [
                'required',
                'string',
                'max:20',
                'unique:empleados,dni,' . $empleado->id,
                'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z])$/i'
            ],
            'nombre' => 'required|string|min:3|max:100',
            'telefono' => 'required|regex:/^[0-9\s\.\-]{9,15}$/',
            'email' => 'required|email|max:100|unique:empleados,email,' . $empleado->id,
            'tipo' => 'required|in:administrador,operario',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'dni.required' => 'El DNI/NIE es obligatorio.',
            'dni.regex' => 'DNI/NIE inválido. Formato: 12345678A o X1234567L.',
            'dni.unique' => 'Este DNI/NIE ya está registrado.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'telefono.regex' => 'Teléfono inválido. Use 9-15 dígitos numéricos.',
            'email.unique' => 'Este email ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $data = [
            'dni' => strtoupper($validated['dni']),
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
     */
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