@extends('layouts.app')
@section('title', 'Nuevo Empleado')
@section('content')
<h2><i class="fas fa-user-plus"></i> Nuevo Empleado</h2>
<form action="{{ route('empleados.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>DNI *</label>
            <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}" required>
            @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label>Nombre *</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Email *</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label>Tipo *</label>
            <select name="tipo" class="form-select">
                <option value="operario" {{ old('tipo')==='operario' ? 'selected' : '' }}>Operario</option>
                <option value="administrador" {{ old('tipo')==='administrador' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label>Fecha Alta *</label>
            <input type="date" name="fecha_alta" class="form-control" value="{{ old('fecha_alta') }}" required>
        </div>
    </div>
    <div class="mb-3">
        <label>Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
    </div>
    <div class="mb-3">
        <label>Contraseña *</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection