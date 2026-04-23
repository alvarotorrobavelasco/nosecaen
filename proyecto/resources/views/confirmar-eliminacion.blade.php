@extends('layouts.app')

@section('title', 'Confirmar Eliminación')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h5>
            </div>
            <div class="card-body text-center py-4">
                <p class="lead mb-3">¿Estás seguro de que deseas eliminar este {{ $titulo }}?</p>
                
                <div class="alert alert-light border mb-4 p-3">
                    <p class="mb-0">{!! $mensaje !!}</p>
                </div>

                <p class="text-muted small mb-4">Esta acción no se puede deshacer.</p>
                
                <div class="d-flex justify-content-center gap-3">
                    <!-- Botón Cancelar (Vuelve atrás) -->
                    <a href="{{ $ruta_volver }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    
                    <!-- Botón Confirmar (Envía el formulario DELETE) -->
                    <form action="{{ $ruta_eliminar }}" method="POST" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-trash"></i> Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection