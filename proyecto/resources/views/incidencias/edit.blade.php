@extends('layouts.app')

@section('title', 'Editar Incidencia #' . $incidencia->id)

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-edit"></i> Editar Incidencia #{{ $incidencia->id }}</h4>
            <a href="{{ route('incidencias.index') }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        
        <div class="card-body">
            <!-- enctype es OBLIGATORIO para subir archivos -->
            <form action="{{ route('incidencias.update', $incidencia->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- 🔴 SECCIÓN ADMIN: Datos del Cliente y Contacto -->
                @if(Auth::user()->tipo === 'administrador')
                <h5 class="mb-3 text-primary border-bottom pb-2">Datos del Cliente y Contacto</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Cliente *</label>
                        <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($clientes as $c)
                                <option value="{{ $c->id }}" {{ old('cliente_id', $incidencia->cliente_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Persona de Contacto *</label>
                        <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto', $incidencia->persona_contacto) }}" required>
                        @error('persona_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Teléfono *</label>
                        <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto', $incidencia->telefono_contacto) }}" required>
                        @error('telefono_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email_contacto" class="form-control @error('email_contacto') is-invalid @enderror" value="{{ old('email_contacto', $incidencia->email_contacto) }}" required>
                        @error('email_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción *</label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $incidencia->direccion) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Población</label>
                        <input type="text" name="poblacion" class="form-control" value="{{ old('poblacion', $incidencia->poblacion) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">C.P.</label>
                        <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', $incidencia->codigo_postal) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Provincia *</label>
                        <select name="provincia_codigo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($provincias as $p)
                                <option value="{{ $p->codigo_ine }}" {{ old('provincia_codigo', $incidencia->provincia_codigo) == $p->codigo_ine ? 'selected' : '' }}>
                                    {{ $p->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- 🟢 SECCIÓN COMPARTIDA: Estado y Asignación -->
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Estado *</label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="P" {{ old('estado', $incidencia->estado) == 'P' ? 'selected' : '' }}>Pendiente</option>
                            <option value="R" {{ old('estado', $incidencia->estado) == 'R' ? 'selected' : '' }}>Realizada</option>
                            <option value="C" {{ old('estado', $incidencia->estado) == 'C' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                @if(Auth::user()->tipo === 'administrador')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Operario Asignado *</label>
                        <select name="operario_id" class="form-select @error('operario_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($operarios as $op)
                                <option value="{{ $op->id }}" {{ old('operario_id', $incidencia->operario_id) == $op->id ? 'selected' : '' }}>
                                    {{ $op->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('operario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                @endif

                <!-- 🟢 SECCIÓN OPERARIO: Cierre y Fichero -->
                <h5 class="mb-3 text-primary border-bottom pb-2 mt-2">Cierre de Tarea</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Realización</label>
                        <input type="date" name="fecha_realizacion" class="form-control @error('fecha_realizacion') is-invalid @enderror" value="{{ old('fecha_realizacion', $incidencia->fecha_realizacion) }}">
                        @error('fecha_realizacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Anotaciones del Operario</label>
                    <textarea name="anotaciones_despues" class="form-control @error('anotaciones_despues') is-invalid @enderror" rows="3">{{ old('anotaciones_despues', $incidencia->anotaciones_despues) }}</textarea>
                    @error('anotaciones_despues') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4 p-3 bg-light border rounded">
                    <label class="form-label fw-bold"><i class="fas fa-paperclip"></i> Adjuntar Fichero Resumen</label>
                    <input type="file" name="fichero_resumen" class="form-control @error('fichero_resumen') is-invalid @enderror">
                    <small class="text-muted d-block mt-1">PDF, DOC, JPG, PNG. Máx 5MB.</small>
                    @error('fichero_resumen') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    @if($incidencia->fichero_resumen)
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span class="badge bg-success"><i class="fas fa-check"></i> Fichero actual</span>
                            <a href="{{ route('incidencias.download', $incidencia->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                        </div>
                    @endif
                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('incidencias.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection