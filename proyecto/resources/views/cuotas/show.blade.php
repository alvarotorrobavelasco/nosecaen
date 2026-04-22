@extends('layouts.app')

@section('title', 'Detalle Cuota')

@section('content')
<h2><i class="fas fa-file-invoice-dollar"></i> Cuota #{{ $cuota->id }}</h2>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Cliente:</strong> {{ $cuota->cliente->nombre }}
            </div>
            <div class="col-md-6">
                <strong>Estado:</strong> 
                @if($cuota->pagada === 'S')
                    <span class="badge bg-success">Pagada</span>
                @else
                    <span class="badge bg-warning">Pendiente</span>
                @endif
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Concepto:</strong>
                <p>{{ $cuota->concepto }}</p>
            </div>
            <div class="col-md-6">
                <strong>Importe:</strong>
                <p class="fs-4 text-primary">{{ number_format($cuota->importe, 2) }} €</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Fecha Emisión:</strong>
                <p>{{ $cuota->fecha_emision }}</p>
            </div>
            <div class="col-md-4">
                <strong>Fecha Pago:</strong>
                <p>{{ $cuota->fecha_pago ?? 'No pagada' }}</p>
            </div>
            <div class="col-md-4">
                <strong>Notas:</strong>
                <p>{{ $cuota->notas ?? 'Sin notas' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Editar
    </a>
    <a href="{{ route('cuotas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection