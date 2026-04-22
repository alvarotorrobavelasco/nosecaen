@extends('layouts.app')
@section('title', 'Empleados')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2><i class="fas fa-users"></i> Empleados</h2>
    @if(Auth::user()->tipo === 'administrador')
        <a href="{{ route('empleados.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</a>
    @endif
</div>
<table class="table table-striped">
    <thead><tr><th>DNI</th><th>Nombre</th><th>Email</th><th>Tipo</th><th>Acciones</th></tr></thead>
    <tbody>
        @foreach($empleados as $emp)
        <tr>
            <td>{{ $emp->dni }}</td>
            <td>{{ $emp->nombre }}</td>
            <td>{{ $emp->email }}</td>
            <td>{{ ucfirst($emp->tipo) }}</td>
            <td>
                <a href="{{ route('empleados.edit', $emp) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                @if(Auth::user()->tipo === 'administrador')
                <form action="{{ route('empleados.destroy', $emp) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection