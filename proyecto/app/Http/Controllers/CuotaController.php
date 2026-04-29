<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\FacturaEnviada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


/**
 * Controlador para la gestión de Cuotas.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class CuotaController extends Controller
{
    public function index()
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);
        $cuotas = Cuota::with('cliente')->latest()->paginate(10);
        return view('cuotas.index', compact('cuotas'));
    }

    public function create()
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.create', compact('clientes'));
    }

       /**
     * Guardar nueva cuota y enviar email automático con PDF.
     */
    public function store(Request $request)
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);
    
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|min:5|max:150',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0.01',
            'pagada' => 'required|in:S,N',
            'fecha_pago' => 'nullable|date',
            'notas' => 'nullable|string|max:500',
        ]);
    
        $cuota = Cuota::create($validated);
    
        // --- ENVÍO DE CORREO ---
        try {
            $cuota->load('cliente'); 
            Mail::to($cuota->cliente->email)->send(new FacturaEnviada($cuota));
            
            return redirect()->route('cuotas.index')
                ->with('success', 'Cuota creada y factura enviada por correo correctamente.');
        } catch (\Exception $e) {
            Log::error('Error enviando factura: ' . $e->getMessage());
            
            return redirect()->route('cuotas.index')
                ->with('warning', 'Cuota creada, pero hubo un error al enviar el correo: ' . $e->getMessage());
        }
    }
    /**
     * DESCARGAR FACTURA EN PDF
     */
    public function descargarPdf($id)
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);

        $cuota = Cuota::with('cliente')->findOrFail($id);
        $pdf = Pdf::loadView('cuotas.pdf', compact('cuota'));
        
        // Descarga directa
        return $pdf->download('factura-' . $cuota->id . '.pdf');
    }

    /**
     * GENERAR REMESA MENSUAL (Masivo)
     */
    public function generarRemesa()
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);

        $clientes = Cliente::all();
        $creadas = 0;
        $hoy = now()->format('Y-m-d');

        foreach ($clientes as $cliente) {
            if ($cliente->cuota_mensual > 0) {
                Cuota::create([
                    'cliente_id' => $cliente->id,
                    'concepto' => 'Mensualidad ' . date('F Y'),
                    'fecha_emision' => $hoy,
                    'importe' => $cliente->cuota_mensual,
                    'pagada' => 'N',
                    'notas' => 'Generado automáticamente por Remesa'
                ]);
                $creadas++;
            }
        }

        return redirect()->route('cuotas.index')
            ->with('success', "Remesa generada correctamente: $creadas cuotas creadas.");
    }

    public function edit(Cuota $cuota)
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('cuotas.edit', compact('cuota', 'clientes'));
    }

    public function update(Request $request, Cuota $cuota)
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);
        
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|min:5|max:150',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0.01',
            'pagada' => 'required|in:S,N',
            'fecha_pago' => 'nullable|date',
            'notas' => 'nullable|string|max:500',
        ]);

        $cuota->update($validated);
        return redirect()->route('cuotas.index')->with('success', 'Cuota actualizada.');
    }

    public function destroy(Cuota $cuota)
    {
        if (Auth::user()->tipo !== 'administrador') abort(403);
        $cuota->delete();
        return redirect()->route('cuotas.index')->with('success', 'Cuota eliminada.');
    }
}