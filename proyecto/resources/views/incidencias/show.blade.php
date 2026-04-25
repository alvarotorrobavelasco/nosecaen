@extends('layouts.app')

@section('title', 'Detalle de Incidencia #' . $incidencia->id)

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Incidencia #{{ $incidencia->id }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Información del Cliente</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted">Cliente:</th>
                            <td>{{ $incidencia->cliente->nombre ?? 'Público (Sin registro)' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Persona de Contacto:</th>
                            <td>{{ $incidencia->persona_contacto }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Teléfono:</th>
                            <td>{{ $incidencia->telefono_contacto }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Email:</th>
                            <td>{{ $incidencia->email_contacto }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Detalles de la Incidencia</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted">Descripción:</th>
                            <td>{{ $incidencia->descripcion }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Dirección:</th>
                            <td>{{ $incidencia->direccion ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Población:</th>
                            <td>{{ $incidencia->poblacion ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Código Postal:</th>
                            <td>{{ $incidencia->codigo_postal ?? 'No especificado' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Estado y Asignación</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted">Estado:</th>
                            <td>
                                @if($incidencia->estado === 'P')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($incidencia->estado === 'R')
                                    <span class="badge bg-success">Realizada</span>
                                @else
                                    <span class="badge bg-danger">Cancelada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Operario Asignado:</th>
                            <td>{{ $incidencia->operario->nombre ?? 'Sin asignar' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Fecha Creación:</th>
                            <td>{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Fecha Realización:</th>
                            <td>{{ $incidencia->fecha_realizacion ?? 'Pendiente' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Anotaciones</h5>
                    <div class="mb-3">
                        <strong>Anotaciones anteriores:</strong>
                        <p class="text-muted">{{ $incidencia->anotaciones_antes ?? 'Sin anotaciones' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Anotaciones del operario:</strong>
                        <p class="text-muted">{{ $incidencia->anotaciones_despues ?? 'Sin anotaciones' }}</p>
                    </div>
                    @if($incidencia->fichero_resumen)
                    <div class="mb-3">
                        <strong>Fichero adjunto:</strong>
                        <p><a href="{{ route('incidencias.download', $incidencia->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">📄 Descargar fichero</a></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            @if(Auth::user()->tipo === 'administrador' || (Auth::user()->tipo === 'operario' && $incidencia->operario_id === Auth::id()))
                <a href="{{ route('incidencias.edit', $incidencia) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
            @endif
            <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection