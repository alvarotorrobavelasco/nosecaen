@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">Editar Empleado</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('empleados.update', $empleado) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">DNI/NIE *</label>
                    <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni', $empleado->dni) }}" required>
                    @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre Completo *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $empleado->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Teléfono *</label>
                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $empleado->telefono) }}" required>
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $empleado->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tipo de Usuario *</label>
                <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                    <option value="operario" {{ old('tipo', $empleado->tipo) === 'operario' ? 'selected' : '' }}>Operario</option>
                    <option value="administrador" {{ old('tipo', $empleado->tipo) === 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <p class="text-muted small">Dejar en blanco para mantener la contraseña actual.</p>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nueva Contraseña</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirmar Nueva</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Actualizar Empleado
                </button>
                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection