@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center bg-light">
                <p class="mb-2 text-muted">¿Eres cliente y tienes una incidencia?</p>
                <a href="{{ route('cliente.registro') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-user-plus"></i> Registrar Incidencia (Sin registro)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection