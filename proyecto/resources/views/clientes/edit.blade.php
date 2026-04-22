@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<h2><i class="fas fa-edit"></i> Editar Cliente</h2>

<form action="{{ route('clientes.update', $cliente) }}" method="POST">
    @csrf @method('PUT')
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">CIF *</label>
            <input type="text" name="cif" class="form-control" 
                   value="{{ old('cif', $cliente->cif) }}" maxlength="9" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nombre/Razón Social *</label>
            <input type="text" name="nombre" class="form-control" 
                   value="{{ old('nombre', $cliente->nombre) }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" 
                   value="{{ old('telefono', $cliente->telefono) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" 
                   value="{{ old('email', $cliente->email) }}">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Cuenta Corriente (IBAN)</label>
        <input type="text" name="cuenta_corriente" class="form-control" 
               value="{{ old('cuenta_corriente', $cliente->cuenta_corriente) }}" maxlength="34">
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">País *</label>
            <input type="text" name="pais" class="form-control" 
                   value="{{ old('pais', $cliente->pais) }}" required>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Moneda *</label>
            <select name="moneda" class="form-select" required>
                <option value="EUR" {{ old('moneda', $cliente->moneda) === 'EUR' ? 'selected' : '' }}>EUR</option>
                <option value="USD" {{ old('moneda', $cliente->moneda) === 'USD' ? 'selected' : '' }}>USD</option>
                <option value="GBP" {{ old('moneda', $cliente->moneda) === 'GBP' ? 'selected' : '' }}>GBP</option>
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Cuota Mensual (€) *</label>
            <input type="number" step="0.01" name="cuota_mensual" class="form-control" 
                   value="{{ old('cuota_mensual', $cliente->cuota_mensual) }}" required>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Actualizar Cliente
        </button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>
</form>
@endsection