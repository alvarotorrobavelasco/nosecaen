@extends('layouts.app')

@section('title', 'Editar Incidencia')

@section('content')
<h2><i class="fas fa-edit"></i> Editar Incidencia #{{ $incidencia->id }}</h2>

<form action="{{ route('incidencias.update', $incidencia) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Cliente -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Cliente *</label>
            <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                <option value="">Seleccione cliente</option>
                @foreach($clientes as $cli)
                    <option value="{{ $cli->id }}" {{ old('cliente_id', $incidencia->cliente_id) == $cli->id ? 'selected' : '' }}>
                        {{ $cli->nombre }}
                    </option>
                @endforeach
            </select>
            @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Persona de Contacto -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Persona de Contacto *</label>
            <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" 
                   value="{{ old('persona_contacto', $incidencia->persona_contacto) }}" required>
            @error('persona_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <!-- Teléfono -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono Contacto *</label>
            <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" 
                   value="{{ old('telefono_contacto', $incidencia->telefono_contacto) }}" required>
            @error('telefono_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Email -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Email Contacto *</label>
            <input type="email" name="email_contacto" class="form-control @error('email_contacto') is-invalid @enderror" 
                   value="{{ old('email_contacto', $incidencia->email_contacto) }}" required>
            @error('email_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <!-- Descripción -->
    <div class="mb-3">
        <label class="form-label">Descripción *</label>
        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>
        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <!-- Dirección -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $incidencia->direccion) }}">
        </div>

        <!-- Población -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Población</label>
            <input type="text" name="poblacion" class="form-control" value="{{ old('poblacion', $incidencia->poblacion) }}">
        </div>
    </div>

    <div class="row">
        <!-- CP -->
        <div class="col-md-4 mb-3">
            <label class="form-label">Código Postal</label>
            <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" 
                   value="{{ old('codigo_postal', $incidencia->codigo_postal) }}" maxlength="5">
            @error('codigo_postal') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Provincia -->
        <div class="col-md-4 mb-3">
            <label class="form-label">Provincia *</label>
            <select name="provincia_codigo" class="form-select @error('provincia_codigo') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                @foreach($provincias as $prov)
                    <option value="{{ $prov->codigo_ine }}" {{ old('provincia_codigo', $incidencia->provincia_codigo) == $prov->codigo_ine ? 'selected' : '' }}>
                        {{ $prov->nombre }}
                    </option>
                @endforeach
            </select>
            @error('provincia_codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Estado -->
        <div class="col-md-4 mb-3">
            <label class="form-label">Estado *</label>
            <select name="estado" class="form-select" required>
                <option value="P" {{ old('estado', $incidencia->estado) === 'P' ? 'selected' : '' }}>Pendiente</option>
                <option value="R" {{ old('estado', $incidencia->estado) === 'R' ? 'selected' : '' }}>Realizada</option>
                <option value="C" {{ old('estado', $incidencia->estado) === 'C' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
    </div>

    <!-- Operario (solo admin) -->
    @if(Auth::user()->tipo === 'administrador')
    <div class="mb-3">
        <label class="form-label">Operario Asignado *</label>
        <select name="operario_id" class="form-select @error('operario_id') is-invalid @enderror" required>
            <option value="">Seleccione operario</option>
            @foreach($operarios as $op)
                <option value="{{ $op->id }}" {{ old('operario_id', $incidencia->operario_id) == $op->id ? 'selected' : '' }}>
                    {{ $op->nombre }}
                </option>
            @endforeach
        </select>
        @error('operario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @endif

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