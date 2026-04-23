@extends('layouts.app')

@section('title', 'Panel de Control')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt"></i> Panel de Control</h2>
    <span class="badge bg-primary fs-6">
        {{ Auth::user()->nombre }} ({{ ucfirst(Auth::user()->tipo) }})
    </span>

    
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- INCIDENCIAS (Visible para Admin y Operario) -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm border-start border-info border-4">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-3x text-info mb-3"></i>
                <h5 class="card-title">Incidencias</h5>
                <p class="card-text text-muted">
                    @if(Auth::user()->tipo === 'operario')
                        Ver y gestionar tus tareas asignadas
                    @else
                        Crear, asignar y gestionar todas las incidencias
                    @endif
                </p>
                <a href="{{ route('incidencias.index') }}" class="btn btn-info text-white">Acceder</a>
            </div>
        </div>
    </div>

    <!-- MÓDULOS EXCLUSIVOS PARA ADMINISTRADOR -->
    @if(Auth::user()->tipo === 'administrador')
        
        <!-- EMPLEADOS -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-start border-success border-4">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Empleados</h5>
                    <p class="card-text text-muted">Gestionar operarios y administradores</p>
                    <a href="{{ route('empleados.index') }}" class="btn btn-success">Gestionar</a>
                </div>
            </div>
        </div>

        <!-- CLIENTES -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-start border-warning border-4">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text text-muted">Cartera de clientes y datos de contacto</p>
                    <a href="{{ route('clientes.index') }}" class="btn btn-warning text-white">Gestionar</a>
                </div>
            </div>
        </div>

        <!-- CUOTAS -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-start border-danger border-4">
                <div class="card-body text-center">
                    <i class="fas fa-file-invoice-dollar fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">Cuotas</h5>
                    <p class="card-text text-muted">Generar remesas y enviar facturas PDF</p>
                    <a href="{{ route('cuotas.index') }}" class="btn btn-danger">Gestionar</a>
                </div>
            </div>
        </div>

    @endif
</div>

<!-- Enlace para editar perfil personal (visible para todos) -->
<div class="mt-4 text-end">
    <a href="{{ route('mi-perfil') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-user-cog"></i> Editar mi perfil
    </a>
</div>
@endsection