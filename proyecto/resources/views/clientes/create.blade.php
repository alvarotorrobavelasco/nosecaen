@extends('layouts.app')
@section('title', 'Nuevo Cliente')
@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><h5 class="mb-0">Nuevo Cliente</h5></div>
    <div class="card-body">
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">CIF/DNI *</label>
                    <input type="text" name="cif" class="form-control @error('cif') is-invalid @enderror" value="{{ old('cif') }}" required>
                    @error('cif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre/Razón Social *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Teléfono *</label>
                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" required>
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Cuenta Corriente (IBAN)</label>
                <input type="text" name="cuenta_corriente" class="form-control @error('cuenta_corriente') is-invalid @enderror" value="{{ old('cuenta_corriente') }}">
                @error('cuenta_corriente')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">País *</label>
                    <input type="text" name="pais" class="form-control @error('pais') is-invalid @enderror" value="{{ old('pais') }}" required>
                    @error('pais')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Moneda *</label>
                    <select name="moneda" class="form-select @error('moneda') is-invalid @enderror" required>
                        <option value="EUR" {{ old('moneda')=='EUR'?'selected':'' }}>EUR</option>
                        <option value="USD" {{ old('moneda')=='USD'?'selected':'' }}>USD</option>
                        <option value="GBP" {{ old('moneda')=='GBP'?'selected':'' }}>GBP</option>
                    </select>
                    @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Cuota Mensual *</label>
                    <input type="number" step="0.01" name="cuota_mensual" class="form-control @error('cuota_mensual') is-invalid @enderror" value="{{ old('cuota_mensual') }}" required>
                    @error('cuota_mensual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection