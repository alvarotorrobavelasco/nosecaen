@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-building"></i> Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Cliente
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>CIF</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>País</th>
            <th>Cuota Mensual</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clientes as $cliente)
        <tr>
            <td>{{ $cliente->cif }}</td>
            <td>{{ $cliente->nombre }}</td>
            <td>{{ $cliente->telefono ?? '-' }}</td>
            <td>{{ $cliente->email ?? '-' }}</td>
            <td>{{ $cliente->pais }}</td>
            <td>{{ number_format($cliente->cuota_mensual, 2) }} €</td>
            <td>
                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" 
                      onsubmit="return confirm('¿Eliminar cliente?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No hay clientes registrados</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $clientes->links() }}
@endsection