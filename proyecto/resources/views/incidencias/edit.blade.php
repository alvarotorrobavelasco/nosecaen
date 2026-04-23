@extends('layouts.app')
@section('title', 'Editar Incidencia')
@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark"><h5 class="mb-0">Editar Incidencia #{{ $incidencia->id }}</h5></div>
    <div class="card-body">
        <form action="{{ route('incidencias.update', $incidencia) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Cliente *</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id', $incidencia->cliente_id)==$c->id?'selected':'' }}>{{ $c->nombre }}</option>@endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Persona de Contacto *</label>
                    <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto', $incidencia->persona_contacto) }}" required>
                    @error('persona_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Teléfono Contacto *</label>
                    <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto', $incidencia->telefono_contacto) }}" required>
                    @error('telefono_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Contacto *</label>
                    <input type="email" name="email_contacto" class="form-control @error('email_contacto') is-invalid @enderror" value="{{ old('email_contacto', $incidencia->email_contacto) }}" required>
                    @error('email_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Descripción *</label>
                <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Dirección</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $incidencia->direccion) }}">
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Población</label>
                    <input type="text" name="poblacion" class="form-control @error('poblacion') is-invalid @enderror" value="{{ old('poblacion', $incidencia->poblacion) }}">
                    @error('poblacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Código Postal</label>
                    <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" value="{{ old('codigo_postal', $incidencia->codigo_postal) }}">
                    @error('codigo_postal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Provincia *</label>
                    <select name="provincia_codigo" class="form-select @error('provincia_codigo') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach($provincias as $p)<option value="{{ $p->codigo_ine }}" {{ old('provincia_codigo', $incidencia->provincia_codigo)==$p->codigo_ine?'selected':'' }}>{{ $p->nombre }}</option>@endforeach
                    </select>
                    @error('provincia_codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Estado *</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        <option value="P" {{ old('estado', $incidencia->estado)=='P'?'selected':'' }}>Pendiente</option>
                        <option value="R" {{ old('estado', $incidencia->estado)=='R'?'selected':'' }}>Realizada</option>
                        <option value="C" {{ old('estado', $incidencia->estado)=='C'?'selected':'' }}>Cancelada</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Operario Asignado *</label>
                    <select name="operario_id" class="form-select @error('operario_id') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach($operarios as $o)<option value="{{ $o->id }}" {{ old('operario_id', $incidencia->operario_id)==$o->id?'selected':'' }}>{{ $o->nombre }}</option>@endforeach
                    </select>
                    @error('operario_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Fecha Realización</label>
                    <input type="date" name="fecha_realizacion" class="form-control @error('fecha_realizacion') is-invalid @enderror" value="{{ old('fecha_realizacion', $incidencia->fecha_realizacion) }}">
                    @error('fecha_realizacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Anotaciones del Operario</label>
                <textarea name="anotaciones_despues" class="form-control @error('anotaciones_despues') is-invalid @enderror" rows="3">{{ old('anotaciones_despues', $incidencia->anotaciones_despues) }}</textarea>
                @error('anotaciones_despues')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Fichero Resumen (PDF/Word/Img)</label>
                <input type="file" name="fichero_resumen" class="form-control @error('fichero_resumen') is-invalid @enderror">
                @error('fichero_resumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($incidencia->fichero_resumen)
                    <small class="text-muted">Fichero actual: <a href="{{ Storage::url($incidencia->fichero_resumen) }}" target="_blank">Ver/Descargar</a></small>
                @endif
            </div>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Actualizar</button>
            <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection