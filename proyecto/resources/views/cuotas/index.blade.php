@extends('layouts.app')

@section('title', 'Gestión de Cuotas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-file-invoice-dollar text-danger"></i> Cuotas</h2>
    <div>
        <form action="{{ route('cuotas.remesa') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning me-2" onclick="return confirm('¿Generar remesa mensual para todos los clientes?')">
                <i class="fas fa-calendar-plus"></i> Generar Remesa
            </button>
        </form>
        <a href="{{ route('cuotas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Cuota
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Concepto</th>
                        <th>Fecha Emisión</th>
                        <th>Importe</th>
                        <th>Estado</th>
                        <th>Fecha Pago</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotas as $cuota)
                        <tr>
                            <td>{{ $cuota->id }}</td>
                            <td>{{ $cuota->cliente->nombre ?? 'N/A' }}</td>
                            <td>{{ Str::limit($cuota->concepto, 30) }}</td>
                            <td>{{ $cuota->fecha_emision }}</td>
                            <td class="fw-bold">{{ number_format($cuota->importe, 2) }} €</td>
                            <td>
                                <span class="badge {{ $cuota->pagada === 'S' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $cuota->pagada === 'S' ? 'Pagada' : 'Pendiente' }}
                                </span>
                            </td>
                            <td>{{ $cuota->fecha_pago ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('cuotas.pdf', $cuota->id) }}" class="btn btn-sm btn-danger me-1" title="Descargar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('cuotas.destroy', $cuota) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta cuota?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No hay cuotas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $cuotas->links() }}
</div>
@endsection