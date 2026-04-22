@extends('layouts.app')

@section('title', 'Incidencias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tools"></i> Incidencias</h2>
    @if(Auth::user()->tipo === 'administrador')
        <a href="{{ route('incidencias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Incidencia
        </a>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Contacto</th>
            <th>Teléfono</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Operario</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($incidencias as $inc)
        <tr>
            <td>{{ $inc->id }}</td>
            <td>{{ $inc->cliente->nombre ?? 'Cliente público' }}</td>
            <td>{{ $inc->persona_contacto }}</td>
            <td>{{ $inc->telefono_contacto }}</td>
            <td>{{ Str::limit($inc->descripcion, 50) }}</td>
            <td>
                @if($inc->estado === 'P')
                    <span class="badge bg-warning">Pendiente</span>
                @elseif($inc->estado === 'R')
                    <span class="badge bg-success">Realizada</span>
                @else
                    <span class="badge bg-danger">Cancelada</span>
                @endif
            </td>
            <td>{{ $inc->operario->nombre ?? 'Sin asignar' }}</td>
            <td>{{ $inc->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('incidencias.show', $inc) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                </a>
                
                {{-- EDITAR: Admin siempre puede, Operario solo si es SUYA --}}
                @if(Auth::user()->tipo === 'administrador' || (Auth::user()->tipo === 'operario' && $inc->operario_id === Auth::id()))
                    <a href="{{ route('incidencias.edit', $inc) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                @endif
                
                {{-- ELIMINAR: Solo admin --}}
                @if(Auth::user()->tipo === 'administrador')
                    <form action="{{ route('incidencias.destroy', $inc) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center">No hay incidencias registradas</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $incidencias->links() }}
@endsection