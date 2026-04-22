@extends('layouts.app')

@section('title', 'Editar Cuota')

@section('content')
<h2><i class="fas fa-edit"></i> Editar Cuota #{{ $cuota->id }}</h2>

<form action="{{ route('cuotas.update', $cuota) }}" method="POST">
    @csrf @method('PUT')
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Cliente *</label>
            <select name="cliente_id" class="form-select" required>
                @foreach(\App\Models\Cliente::orderBy('nombre')->get() as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $cuota->cliente_id) == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Concepto *</label>
            <input type="text" name="concepto" class="form-control" 
                   value="{{ old('concepto', $cuota->concepto) }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Importe (€) *</label>
            <input type="number" step="0.01" name="importe" class="form-control" 
                   value="{{ old('importe', $cuota->importe) }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Fecha Emisión *</label>
            <input type="date" name="fecha_emision" class="form-control" 
                   value="{{ old('fecha_emision', $cuota->fecha_emision) }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Pagada</label>
            <select name="pagada" class="form-select">
                <option value="N" {{ old('pagada', $cuota->pagada) === 'N' ? 'selected' : '' }}>No</option>
                <option value="S" {{ old('pagada', $cuota->pagada) === 'S' ? 'selected' : '' }}>Sí</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Notas</label>
        <textarea name="notas" class="form-control" rows="3">{{ old('notas', $cuota->notas) }}</textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
        <a href="{{ route('cuotas.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
    </div>
</form>
@endsection