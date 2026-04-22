@extends('layouts.app')

@section('title', 'Cuota #' . $cuota->id)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">Cuota #{{ $cuota->id }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">Información General</h5>
                <table class="table table-borderless">
                    <tr>
                        <th class="text-muted">Cliente:</th>
                        <td>{{ $cuota->cliente->nombre ?? 'Cliente no encontrado' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Concepto:</th>
                        <td>{{ $cuota->concepto }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Importe:</th>
                        <td class="text-primary fw-bold">{{ number_format($cuota->importe, 2) }} €</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3">Estado y Fechas</h5>
                <table class="table table-borderless">
                    <tr>
                        <th class="text-muted">Estado:</th>
                        <td>
                            @if($cuota->pagada === 'S')
                                <span class="badge bg-success">Pagada</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Fecha Emisión:</th>
                        <td>{{ $cuota->fecha_emision->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Fecha Pago:</th>
                        <td>
                            @if($cuota->pagada === 'S' && $cuota->fecha_pago)
                                {{ $cuota->fecha_pago->format('d/m/Y') }}
                            @else
                                <span class="text-muted">No pagada</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($cuota->notas)
        <hr>
        <div class="mt-3">
            <h5 class="text-muted">Notas:</h5>
            <p class="mb-0">{{ $cuota->notas }}</p>
        </div>
        @endif
    </div>
    <div class="card-footer bg-white">
        <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('cuotas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection