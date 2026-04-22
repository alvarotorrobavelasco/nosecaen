@extends('layouts.app')

@section('title', 'Detalle Incidencia')

@section('content')
<h2><i class="fas fa-clipboard-list"></i> Incidencia #{{ $incidencia->id }}</h2>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Cliente:</strong> {{ $incidencia->cliente->nombre }}
            </div>
            <div class="col-md-6">
                <strong>Estado:</strong> 
                @if($incidencia->estado === 'P')
                    <span class="badge bg-warning">Pendiente</span>
                @elseif($incidencia->estado === 'R')
                    <span class="badge bg-success">Realizada</span>
                @else
                    <span class="badge bg-danger">Cancelada</span>
                @endif
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Contacto:</strong> {{ $incidencia->persona_contacto }}<br>
                <strong>Teléfono:</strong> {{ $incidencia->telefono_contacto }}<br>
                <strong>Email:</strong> {{ $incidencia->email_contacto }}
            </div>
            <div class="col-md-6">
                <strong>Dirección:</strong> {{ $incidencia->direccion }}<br>
                <strong>Población:</strong> {{ $incidencia->poblacion }}<br>
                <strong>CP:</strong> {{ $incidencia->codigo_postal }}<br>
                <strong>Provincia:</strong> {{ $incidencia->provincia_codigo }}
            </div>
        </div>

        <div class="mb-3">
            <strong>Descripción:</strong>
            <p>{{ $incidencia->descripcion }}</p>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Operario:</strong> {{ $incidencia->operario->nombre ?? 'Sin asignar' }}
            </div>
            <div class="col-md-6">
                <strong>Fecha Creación:</strong> {{ $incidencia->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        @if($incidencia->anotaciones_despues)
        <div class="alert alert-info">
            <strong>Anotaciones del operario:</strong>
            <p>{{ $incidencia->anotaciones_despues }}</p>
        </div>
        @endif
    </div>
</div>

<a href="{{ route('incidencias.index') }}" class="btn btn-secondary mt-3">
    <i class="fas fa-arrow-left"></i> Volver
</a>
@endsection