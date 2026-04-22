@extends('layouts.app')

@section('title', 'Editar Cuota #' . $cuota->id)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">Editar Cuota #{{ $cuota->id }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('cuotas.update', $cuota) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Cliente *</label>
                <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $cuota->cliente_id) == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Concepto *</label>
                <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror" value="{{ old('concepto', $cuota->concepto) }}" required>
                @error('concepto') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Fecha Emisión *</label>
                    <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror" value="{{ old('fecha_emision', $cuota->fecha_emision) }}" required>
                    @error('fecha_emision') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Importe (€) *</label>
                    <input type="number" step="0.01" name="importe" class="form-control @error('importe') is-invalid @enderror" value="{{ old('importe', $cuota->importe) }}" required min="0.01">
                    @error('importe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Estado de Pago *</label>
                <select name="pagada" class="form-select @error('pagada') is-invalid @enderror" required>
                    <option value="N" {{ old('pagada', $cuota->pagada) === 'N' ? 'selected' : '' }}>Pendiente</option>
                    <option value="S" {{ old('pagada', $cuota->pagada) === 'S' ? 'selected' : '' }}>Pagada</option>
                </select>
                @error('pagada') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Fecha de Pago</label>
                <input type="date" name="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror" value="{{ old('fecha_pago', $cuota->fecha_pago) }}">
                <small class="text-muted">Rellenar solo si está pagada. Debe ser igual o posterior a la emisión.</small>
                @error('fecha_pago') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Notas</label>
                <textarea name="notas" class="form-control @error('notas') is-invalid @enderror" rows="3">{{ old('notas', $cuota->notas) }}</textarea>
                @error('notas') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Actualizar Cuota
                </button>
                <a href="{{ route('cuotas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection