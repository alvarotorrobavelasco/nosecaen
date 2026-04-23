@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Mi Perfil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('mi-perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">DNI</label>
                        <input type="text" class="form-control" value="{{ $empleado->dni }}" disabled>
                        <small class="text-muted">No modificable</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $empleado->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Teléfono *</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $empleado->telefono) }}" required>
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $empleado->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h6 class="text-muted mb-3">Cambiar Contraseña (opcional)</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mínimo 6 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                        <a href="/" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection