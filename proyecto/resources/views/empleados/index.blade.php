@extends('layouts.app')
@section('title', 'Empleados')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-users text-success"></i> Empleados</h2>
    <a href="{{ route('empleados.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Empleado</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>DNI</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Tipo</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($empleados as $emp)
                <tr>
                    <td>{{ $emp->dni }}</td><td>{{ $emp->nombre }}</td><td>{{ $emp->email }}</td><td>{{ $emp->telefono }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($emp->tipo) }}</span></td>
                    <td>
                        <a href="{{ route('empleados.edit', $emp) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        
                        <!-- ENLACE DE CONFIRMACIÓN (Sin JS) -->
                        <a href="{{ route('empleados.confirm-destroy', $emp->id) }}" class="btn btn-sm btn-danger" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No hay empleados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $empleados->links() }}</div>
@endsection