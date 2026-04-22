@extends('layouts.app')

@section('title', 'Nueva Cuota')

@section('content')
<h2><i class="fas fa-plus"></i> Nueva Cuota</h2>

<form action="{{ route('cuotas.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Cliente *</label>
            <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                <option value="">Seleccione cliente</option>
                @foreach(\App\Models\Cliente::orderBy('nombre')->get() as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre }}
                    </option>
                @endforeach
            </select>
            @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Concepto *</label>
            <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror" 
                   value="{{ old('concepto') }}" required>
            @error('concepto') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Importe (€) *</label>
            <input type="number" step="0.01" name="importe" class="form-control @error('importe') is-invalid @enderror" 
                   value="{{ old('importe') }}" required>
            @error('importe') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Fecha Emisión *</label>
            <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror" 
                   value="{{ old('fecha_emision', date('Y-m-d')) }}" required>
            @error('fecha_emision') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Pagada</label>
            <select name="pagada" class="form-select">
                <option value="N" {{ old('pagada') === 'N' ? 'selected' : '' }}>No</option>
                <option value="S" {{ old('pagada') === 'S' ? 'selected' : '' }}>Sí</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Notas</label>
        <textarea name="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
        <a href="{{ route('cuotas.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </div>
</form>
@endsection