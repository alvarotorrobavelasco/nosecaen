@extends('layouts.app')

@section('title', 'Detalle Empleado')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="card-title mb-4">{{ $empleado->nombre }}</h4>
        
        <div class="row">
            <div class="col-md-6">
                <p><strong>DNI:</strong></p>
                <p><strong>Email:</strong></p>
                <p><strong>Teléfono:</strong></p>
                <p><strong>Dirección:</strong></p>
            </div>
            <div class="col-md-6">
                <p>{{ $empleado->dni }}</p>
                <p>{{ $empleado->email }}</p>
                <p>{{ $empleado->telefono ?? 'No disponible' }}</p>
                <p>{{ $empleado->direccion ?? 'No disponible' }}</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Tipo:</strong></p>
                <p><strong>Fecha Alta:</strong></p>
            </div>
            <div class="col-md-6">
                <p>
                    <span class="badge bg-{{ $empleado->tipo === 'administrador' ? 'danger' : 'primary' }}">
                        {{ ucfirst($empleado->tipo) }}
                    </span>
                </p>
                <p>{{ $empleado->fecha_alta }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Editar
    </a>
    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection