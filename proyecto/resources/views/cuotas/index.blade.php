@extends('layouts.app')

@section('title', 'Cuotas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice-dollar"></i> Cuotas y Facturas</h2>
    <a href="{{ route('cuotas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Cuota
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Concepto</th>
            <th>Importe</th>
            <th>Fecha Emisión</th>
            <th>Pagada</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($cuotas as $cuota)
        <tr>
            <td>{{ $cuota->id }}</td>
            <td>{{ $cuota->cliente->nombre }}</td>
            <td>{{ $cuota->concepto }}</td>
            <td>{{ number_format($cuota->importe, 2) }} €</td>
            <td>{{ $cuota->fecha_emision }}</td>
            <td>
                @if($cuota->pagada === 'S')
                    <span class="badge bg-success">Sí</span>
                @else
                    <span class="badge bg-warning">No</span>
                @endif
            </td>
            <td>
                <a href="{{ route('cuotas.show', $cuota) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('cuotas.destroy', $cuota) }}" method="POST" class="d-inline" 
                      onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No hay cuotas registradas</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $cuotas->links() }}
@endsection