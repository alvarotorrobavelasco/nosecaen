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
            'dni' => [
                'required',
                'string',
                'max:20',
                'unique:empleados,dni',
                'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z])$/i'
            ],
            'nombre' => 'required|string|min:3|max:100',
            'telefono' => 'required|regex:/^[0-9]{9,15}$/',
            'email' => 'required|email|max:100|unique:empleados,email',
            'password' => 'required|min:6|confirmed',
            'tipo' => 'required|in:administrador,operario',
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
        
        $validated = $request->validate([
            'dni' => [
                'required',
                'string',
                'max:20',
                'unique:empleados,dni,' . $empleado->id,
                'regex:/^([0-9]{8}[A-Z]|[XYZ][0-9]{7}[A-Z])$/i'
            ],
            'nombre' => 'required|string|min:3|max:100',
            'telefono' => 'required|regex:/^[0-9]{9,15}$/',
            'email' => 'required|email|max:100|unique:empleados,email,' . $empleado->id,
            'tipo' => 'required|in:administrador,operario',
            'password' => 'nullable|min:6|confirmed',
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

    public function destroy(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        if ($empleado->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }

    // NUEVO: Perfil del usuario logueado (admin O operario)
    public function miPerfil()
    {
        return view('empleados.perfil', ['empleado' => Auth::user()]);
    }

    // NUEVO: Actualizar perfil del usuario logueado
    public function updatePerfil(Request $request)
    {
        $empleado = Auth::user();
        
        $validated = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'telefono' => 'required|regex:/^[0-9]{9,15}$/',
            'email' => 'required|email|max:100|unique:empleados,email,' . $empleado->id,
            'password' => 'nullable|min:6|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'Teléfono inválido. Debe tener 9-15 dígitos numéricos.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        Empleado::where('id', $empleado->id)->update($data);

        return redirect('/')->with('success', 'Perfil actualizado.');
    }
}