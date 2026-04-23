<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $cuota->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 20px; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .info { margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { text-align: right; font-size: 1.2em; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nosecaen S.L.</h1>
        <p>Factura de Mantenimiento #{{ $cuota->id }}</p>
    </div>

    <div class="info">
        <strong>Cliente:</strong> {{ $cuota->cliente->nombre }}<br>
        <strong>CIF:</strong> {{ $cuota->cliente->cif }}<br>
        <strong>Email:</strong> {{ $cuota->cliente->email }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Fecha Emisión</th>
                <th>Importe</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $cuota->concepto }}</td>
                <td>{{ $cuota->fecha_emision }}</td>
                <td>{{ number_format($cuota->importe, 2) }} €</td>
                <td>{{ $cuota->pagada === 'S' ? 'Pagada' : 'Pendiente' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        TOTAL A PAGAR: {{ number_format($cuota->importe, 2) }} €
    </div>
</body>
</html>