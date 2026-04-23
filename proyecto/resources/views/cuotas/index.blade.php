@extends('layouts.app')
@section('title', 'Gestión de Cuotas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-file-invoice-dollar text-danger"></i> Cuotas</h2>
    <div>
        <form action="{{ route('cuotas.remesa') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning"><i class="fas fa-calendar-plus"></i> Remesa</button>
        </form>
        <a href="{{ route('cuotas.create') }}" class="btn btn-primary ms-2"><i class="fas fa-plus"></i> Nueva</a>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>ID</th><th>Cliente</th><th>Concepto</th><th>Emisión</th><th>Importe</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($cuotas as $cuota)
                    <tr>
                        <td>{{ $cuota->id }}</td>
                        <td>{{ $cuota->cliente->nombre ?? 'N/A' }}</td>
                        <td>{{ Str::limit($cuota->concepto, 20) }}</td>
                        <td>{{ $cuota->fecha_emision }}</td>
                        <td class="fw-bold">{{ number_format($cuota->importe, 2) }} €</td>
                        <td>
                            @if($cuota->pagada === 'S') <span class="badge bg-success">Pagada</span>
                            @else <span class="badge bg-warning text-dark">Pendiente</span> @endif
                        </td>
                        <td>
                            <a href="{{ route('cuotas.pdf', $cuota->id) }}" class="btn btn-sm btn-danger me-1"><i class="fas fa-file-pdf"></i></a>
                            <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                            
                            <!-- ENLACE DE CONFIRMACIÓN -->
                            <a href="{{ route('cuotas.confirm-destroy', $cuota->id) }}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No hay cuotas.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $cuotas->links() }}
    </div>
</div>
@endsection