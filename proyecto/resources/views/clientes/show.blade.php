@extends('layouts.app')

@section('title', 'Detalle Cliente')

@section('content')
<h2><i class="fas fa-building"></i> {{ $cliente->nombre }}</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <h5 class="text-muted">Datos Fiscales</h5>
                <p><strong>CIF:</strong> {{ $cliente->cif }}</p>
                <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
                <p><strong>País:</strong> {{ $cliente->pais }}</p>
                <p><strong>Moneda:</strong> {{ $cliente->moneda }}</p>
            </div>
            <div class="col-md-6">
                <h5 class="text-muted">Datos de Contacto</h5>
                <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'No disponible' }}</p>
                <p><strong>Email:</strong> {{ $cliente->email ?? 'No disponible' }}</p>
                <p><strong>Cuenta Corriente:</strong></p>
                <p class="text-muted">{{ $cliente->cuenta_corriente ?? 'No disponible' }}</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <h5 class="text-muted">Información Económica</h5>
                <p><strong>Cuota Mensual:</strong> 
                    <span class="fs-4 text-primary">{{ number_format($cliente->cuota_mensual, 2) }} €</span>
                </p>
            </div>
            <div class="col-md-6">
                <h5 class="text-muted">Registro</h5>
                <p><strong>Alta:</strong> {{ $cliente->created_at->format('d/m/Y') }}</p>
                <p><strong>Última actualización:</strong> {{ $cliente->updated_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Editar
    </a>
    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" 
          onsubmit="return confirm('¿Eliminar cliente? Esta acción no se puede deshacer.')">
        @csrf @method('DELETE')
        <button class="btn btn-danger">
            <i class="fas fa-trash"></i> Eliminar
        </button>
    </form>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection