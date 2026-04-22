@extends('layouts.app')

@section('title', 'Editar Incidencia')

@section('content')
<h2><i class="fas fa-edit"></i> Editar Incidencia #{{ $incidencia->id }}</h2>

<form action="{{ route('incidencias.update', $incidencia) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- MISMO FORMULARIO QUE CREATE pero con los valores existentes -->
    <!-- Copia el formulario de create y cambia: -->
    <!-- value="{{ old('campo') }}" por value="{{ old('campo', $incidencia->campo) }}" -->
    <!-- Y en selects: selected="{{ old('campo', $incidencia->campo) == 'valor' }}" -->
    
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> 
        Rellena igual que el formulario de creación pero con los datos existentes
    </div>
    
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Actualizar
        </button>
        <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>
</form>
@endsection