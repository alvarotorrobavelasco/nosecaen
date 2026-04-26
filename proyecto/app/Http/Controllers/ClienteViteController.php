<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteViteController extends Controller
{
    public function index() {
        return Inertia::render('Clientes/Index', [
            'clientes' => Cliente::latest()->get()
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'cif' => 'required|string|size:9', 'nombre' => 'required|string',
            'telefono' => 'required|string|min:9', 'email' => 'required|email',
            'pais' => 'required|string', 'moneda' => 'required|string|size:3',
            'importe_cuota_mensual' => 'required|numeric|min:0'
        ]);
        Cliente::create($request->all());
        return redirect()->route('clientes.vite.index');
    }

    public function update(Request $request, Cliente $cliente) {
        $request->validate([
            'cif' => 'required|string|size:9', 'nombre' => 'required|string',
            'telefono' => 'required|string|min:9', 'email' => 'required|email',
            'pais' => 'required|string', 'moneda' => 'required|string|size:3',
            'importe_cuota_mensual' => 'required|numeric|min:0'
        ]);
        $cliente->update($request->all());
        return redirect()->route('clientes.vite.index');
    }

    public function destroy(Cliente $cliente) {
        $cliente->delete();
        return redirect()->route('clientes.vite.index');
    }
}