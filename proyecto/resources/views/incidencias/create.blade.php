@extends('layouts.app')

@section('title', 'Nueva Incidencia')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Registrar Nueva Incidencia</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('incidencias.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Cliente -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Cliente *</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach($clientes as $cli)
                            <option value="{{ $cli->id }}" {{ old('cliente_id') == $cli->id ? 'selected' : '' }}>
                                {{ $cli->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Persona de Contacto -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Persona de Contacto *</label>
                    <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto') }}" required>
                    @error('persona_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <!-- Teléfono -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Teléfono Contacto *</label>
                    <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto') }}" required>
                    @error('telefono_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Contacto *</label>
                    <input type="email" name="email_contacto" class="form-control @error('email_contacto') is-invalid @enderror" value="{{ old('email_contacto') }}" required>
                    @error('email_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label class="form-label fw-bold">Descripción *</label>
                <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion') }}</textarea>
                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <!-- Dirección -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Dirección</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                    @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Población -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Población</label>
                    <input type="text" name="poblacion" class="form-control @error('poblacion') is-invalid @enderror" value="{{ old('poblacion') }}">
                    @error('poblacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <!-- CP -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Código Postal</label>
                    <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" value="{{ old('codigo_postal') }}" maxlength="5">
                    @error('codigo_postal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Provincia -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Provincia *</label>
                    <select name="provincia_codigo" class="form-select @error('provincia_codigo') is-invalid @enderror" required>
                        <option value="">Seleccione</option>
                        @foreach($provincias as $prov)
                            <option value="{{ $prov->codigo_ine }}" {{ old('provincia_codigo') == $prov->codigo_ine ? 'selected' : '' }}>
                                {{ $prov->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('provincia_codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Estado -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Estado *</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        <option value="P" {{ old('estado') === 'P' ? 'selected' : '' }}>Pendiente</option>
                        <option value="R" {{ old('estado') === 'R' ? 'selected' : '' }}>Realizada</option>
                        <option value="C" {{ old('estado') === 'C' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Operario (Solo visible si es admin) -->
            @if(Auth::user()->tipo === 'administrador')
            <div class="mb-3">
                <label class="form-label fw-bold">Operario Asignado *</label>
                <select name="operario_id" class="form-select @error('operario_id') is-invalid @enderror" required>
                    <option value="">Seleccione operario</option>
                    @foreach($operarios as $op)
                        <option value="{{ $op->id }}" {{ old('operario_id') == $op->id ? 'selected' : '' }}>
                            {{ $op->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('operario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            @endif

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save"></i> Guardar Incidencia
                </button>
                <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection