@extends('layouts.app')
@section('title', 'Clientes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-building text-warning"></i> Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Cliente</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>CIF</th><th>Nombre</th><th>Email</th><th>País</th><th>Cuota</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($clientes as $cli)
                <tr>
                    <td>{{ $cli->cif }}</td><td>{{ $cli->nombre }}</td><td>{{ $cli->email }}</td><td>{{ $cli->pais }}</td>
                    <td>{{ number_format($cli->cuota_mensual, 2) }} €</td>
                    <td>
                        <a href="{{ route('clientes.edit', $cli) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('clientes.destroy', $cli) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No hay clientes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $clientes->links() }}</div>
@endsection