@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-building text-warning me-2"></i>Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nuevo Cliente
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>CIF</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>País</th>
                        <th class="text-end">Cuota</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cli)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $cli->cif }}</span></td>
                        <td class="fw-bold">{{ $cli->nombre }}</td>
                        <td>{{ $cli->email }}</td>
                        <td>{{ $cli->pais }}</td>
                        <td class="text-end">{{ number_format($cli->cuota_mensual, 2, ',', '.') }} €</td>
                        <td class="text-center">
                            <a href="{{ route('clientes.edit', $cli) }}" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('clientes.confirm-destroy', $cli->id) }}" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox me-2"></i>No hay clientes registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</table>

{{-- ✅ PAGINACIÓN LIMPIA Y ÚNICA --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 px-2">
    <div>
        {{ $clientes->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection