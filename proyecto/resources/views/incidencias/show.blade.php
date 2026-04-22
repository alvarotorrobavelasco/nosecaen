@extends('layouts.app')

@section('title', 'Detalle Incidencia')

@section('content')
<h2><i class="fas fa-clipboard-list"></i> Incidencia #{{ $incidencia->id }}</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-8">
                <h4 class="text-primary">Cliente: {{ $incidencia->cliente->nombre }}</h4>
            </div>
            <div class="col-md-4 text-end">
                @if($incidencia->estado === 'P')
                    <span class="badge bg-warning fs-6">Pendiente</span>
                @elseif($incidencia->estado === 'R')
                    <span class="badge bg-success fs-6">Realizada</span>
                @else
                    <span class="badge bg-danger fs-6">Cancelada</span>
                @endif
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-md-6">
                <h6 class="text-muted">Datos de Contacto</h6>
                <p><strong>Contacto:</strong> {{ $incidencia->persona_contacto }}</p>
                <p><strong>Teléfono:</strong> {{ $incidencia->telefono_contacto }}</p>
                <p><strong>Email:</strong> {{ $incidencia->email_contacto }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Ubicación</h6>
                <p><strong>Dirección:</strong> {{ $incidencia->direccion ?? 'No disponible' }}</p>
                <p><strong>Población:</strong> {{ $incidencia->poblacion ?? 'No disponible' }}</p>
                <p><strong>CP:</strong> {{ $incidencia->codigo_postal ?? 'No disponible' }}</p>
                <p><strong>Provincia:</strong> {{ $incidencia->provincia->nombre ?? $incidencia->provincia_codigo }}</p>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="text-muted">Descripción</h6>
            <div class="alert alert-light border">
                {{ $incidencia->descripcion }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted">Asignación</h6>
                <p><strong>Operario:</strong> {{ $incidencia->operario->nombre ?? 'Sin asignar' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Fechas</h6>
                <p><strong>Fecha Creación:</strong> {{ $incidencia->created_at->format('d/m/Y H:i') }}</p>
                @if($incidencia->fecha_realizacion)
                    <p><strong>Fecha Realización:</strong> {{ $incidencia->fecha_realizacion }}</p>
                @endif
            </div>
        </div>

        @if($incidencia->anotaciones_despues)
        <hr>
        <div class="alert alert-info">
            <strong><i class="fas fa-sticky-note"></i> Anotaciones del operario:</strong>
            <p class="mb-0">{{ $incidencia->anotaciones_despues }}</p>
        </div>
        @endif
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    @if(Auth::user()->tipo === 'administrador')
        <a href="{{ route('incidencias.edit', $incidencia) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST" class="d-inline" 
              onsubmit="return confirm('¿Eliminar esta incidencia?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
        </form>
    @endif
    <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection