@extends('layouts.app')
@section('title', 'Gestión de Incidencias')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-tools text-info"></i> Incidencias</h2>
    @if(Auth::user()->tipo === 'administrador')
    <a href="{{ route('incidencias.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva Incidencia</a>
    @endif
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <!-- Formulario de Búsqueda -->
<form action="{{ route('incidencias.index') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="busqueda" class="form-control" placeholder="Buscar por descripción o persona..." value="{{ request('busqueda') }}">
        <button class="btn btn-primary" type="submit">🔍 Buscar</button>
        @if(request('busqueda'))
            <a href="{{ route('incidencias.index') }}" class="btn btn-outline-secondary">✖ Limpiar</a>
        @endif
    </div>
</form>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th><th>Cliente</th><th>Contacto</th><th>Estado</th><th>Operario</th><th>Fecha</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidencias as $inc)
                    <tr>
                        <td>{{ $inc->id }}</td>
                        <td>{{ $inc->cliente->nombre ?? 'Público' }}</td>
                        <td>{{ $inc->persona_contacto }}</td>
                        <td>
                            @if($inc->estado === 'P') <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($inc->estado === 'R') <span class="badge bg-success">Realizada</span>
                            @else <span class="badge bg-danger">Cancelada</span> @endif
                        </td>
                        <td>{{ $inc->operario->nombre ?? 'Sin asignar' }}</td>
                        <td>{{ $inc->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('incidencias.show', $inc) }}" class="btn btn-sm btn-info text-white me-1"><i class="fas fa-eye"></i></a>
                            
                            @if(Auth::user()->tipo === 'administrador' || (Auth::user()->tipo === 'operario' && $inc->operario_id === Auth::id()))
                                <a href="{{ route('incidencias.edit', $inc) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                
                                <!-- ENLACE DE CONFIRMACIÓN (Solo Admin) -->
                                @if(Auth::user()->tipo === 'administrador')
                                <a href="{{ route('incidencias.confirm-destroy', $inc->id) }}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No hay incidencias.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $incidencias->links() }}
    </div>
</div>
@endsection