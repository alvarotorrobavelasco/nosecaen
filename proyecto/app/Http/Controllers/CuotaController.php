<?php
namespace App\Http\Controllers;

use App\Models\Cuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuotaController extends Controller
{
    private function esAdmin() {
        return Auth::check() && Auth::user()->tipo === 'administrador';
    }

    public function index() {
        if (!$this->esAdmin()) abort(403);
        $cuotas = Cuota::with('cliente')->latest()->paginate(10);
        return view('cuotas.index', compact('cuotas'));
    }
    
    // Métodos básicos vacíos para que no falle (create, store, show, edit, update, destroy)
    public function create() { if (!$this->esAdmin()) abort(403); return view('cuotas.create'); }
    public function store(Request $request) { if (!$this->esAdmin()) abort(403); /* Validar y guardar */ return redirect()->route('cuotas.index'); }
    public function show(Cuota $cuota) { if (!$this->esAdmin()) abort(403); return view('cuotas.show', compact('cuota')); }
    public function edit(Cuota $cuota) { if (!$this->esAdmin()) abort(403); return view('cuotas.edit', compact('cuota')); }
    public function update(Request $request, Cuota $cuota) { if (!$this->esAdmin()) abort(403); /* Validar y actualizar */ return redirect()->route('cuotas.index'); }
    public function destroy(Cuota $cuota) { if (!$this->esAdmin()) abort(403); $cuota->delete(); return redirect()->route('cuotas.index'); }
}