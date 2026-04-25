<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #0d6efd; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nosecaen S.L.</h2>
        </div>
        <div class="content">
            <p>Estimado/a <strong>{{ $cuota->cliente->nombre }}</strong>,</p>
            <p>Le informamos que se ha generado una nueva cuota/factura en su cuenta.</p>
            
            <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8f9fa; border: 1px solid #dee2e6; margin-bottom: 20px;">
                <tr>
                    <td><strong>Concepto:</strong></td>
                    <td>{{ $cuota->concepto }}</td>
                </tr>
                <tr>
                    <td><strong>Importe:</strong></td>
                    <td>{{ number_format($cuota->importe, 2) }} €</td>
                </tr>
                <tr>
                    <td><strong>Fecha de emisión:</strong></td>
                    <td>{{ $cuota->fecha_emision }}</td>
                </tr>
                <tr>
                    <td><strong>Estado:</strong></td>
                    <td>{{ $cuota->pagada === 'S' ? 'Pagada' : 'Pendiente' }}</td>
                </tr>
            </table>

            <p>Adjunto encontrará el detalle en formato PDF.</p>
            <br>
            <p>Atentamente,<br>El equipo de Administración de Nosecaen S.L.</p>
        </div>
        <div class="footer">
            <p>Este es un correo automático generado por el sistema de gestión de incidencias.</p>
        </div>
    </div>
</body>
</html>