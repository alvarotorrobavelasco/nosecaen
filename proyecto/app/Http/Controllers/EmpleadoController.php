<?php
namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador para gestión de empleados.
 * Solo administradores pueden gestionar la lista completa.
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class EmpleadoController extends Controller
{
    /** Verifica que el usuario sea administrador */
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
            'dni' => 'required|string|max:9|unique:empleados,dni',
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:empleados,email',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'fecha_alta' => 'required|date',
            'tipo' => 'required|in:administrador,operario',
            'password' => 'required|min:6'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        Empleado::create($validated);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado.');
    }

    public function edit(Empleado $empleado)
    {
        // Un empleado puede editar sus propios datos, el admin puede editar cualquiera
        if (!$this->esAdmin() && Auth::id() !== $empleado->id) abort(403);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        if (!$this->esAdmin() && Auth::id() !== $empleado->id) abort(403);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:empleados,email,' . $empleado->id,
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
        ]);

        // Solo admin puede cambiar tipo y password
        if ($this->esAdmin()) {
            $validated['tipo'] = $request->tipo;
            if ($request->password) {
                $validated['password'] = Hash::make($request->password);
            }
        }

        $empleado->update($validated);
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado.');
    }

    public function destroy(Empleado $empleado)
    {
        if (!$this->esAdmin()) abort(403);
        if ($empleado->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }
}