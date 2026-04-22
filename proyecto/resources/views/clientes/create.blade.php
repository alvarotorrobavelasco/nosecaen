@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
<h2><i class="fas fa-plus"></i> Nuevo Cliente</h2>

<form action="{{ route('clientes.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">CIF *</label>
            <input type="text" name="cif" class="form-control @error('cif') is-invalid @enderror" 
                   value="{{ old('cif') }}" maxlength="9" required>
            @error('cif') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nombre/Razón Social *</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                   value="{{ old('nombre') }}" required>
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" 
                   value="{{ old('telefono') }}">
            @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Cuenta Corriente (IBAN)</label>
        <input type="text" name="cuenta_corriente" class="form-control @error('cuenta_corriente') is-invalid @enderror" 
               value="{{ old('cuenta_corriente') }}" maxlength="34">
        @error('cuenta_corriente') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">País *</label>
            <input type="text" name="pais" class="form-control @error('pais') is-invalid @enderror" 
                   value="{{ old('pais', 'España') }}" required>
            @error('pais') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Moneda *</label>
            <select name="moneda" class="form-select" required>
                <option value="EUR" {{ old('moneda') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                <option value="USD" {{ old('moneda') === 'USD' ? 'selected' : '' }}>USD - Dólar</option>
                <option value="GBP" {{ old('moneda') === 'GBP' ? 'selected' : '' }}>GBP - Libra</option>
            </select>
            @error('moneda') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Cuota Mensual (€) *</label>
            <input type="number" step="0.01" name="cuota_mensual" class="form-control @error('cuota_mensual') is-invalid @enderror" 
                   value="{{ old('cuota_mensual', 0) }}" required>
            @error('cuota_mensual') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Guardar Cliente
        </button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>
</form>
@endsection