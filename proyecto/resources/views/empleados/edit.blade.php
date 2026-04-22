@extends('layouts.app')
@section('title', 'Editar Empleado')
@section('content')
<h2><i class="fas fa-user-edit"></i> Editar {{ $empleado->nombre }}</h2>
<form action="{{ route('empleados.update', $empleado) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $empleado->nombre) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $empleado->email) }}" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $empleado->telefono) }}">
        </div>
        @if(Auth::user()->tipo === 'administrador')
        <div class="col-md-6 mb-3">
            <label>Tipo *</label>
            <select name="tipo" class="form-select">
                <option value="operario" {{ old('tipo', $empleado->tipo)==='operario' ? 'selected' : '' }}>Operario</option>
                <option value="administrador" {{ old('tipo', $empleado->tipo)==='administrador' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>
        @endif
    </div>
    <div class="mb-3">
        <label>Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $empleado->direccion) }}">
    </div>
    @if(Auth::user()->tipo === 'administrador')
    <div class="mb-3">
        <label>Nueva Contraseña (dejar vacío para mantener)</label>
        <input type="password" name="password" class="form-control">
    </div>
    @endif
    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection