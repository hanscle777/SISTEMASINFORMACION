<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de cita</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="padding: 20px; text-align: center; background-color: #1f2937; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px;">Recordatorio de cita</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px; color: #1f2937;">
                            <p>Hola {{ optional($cita->cliente)->name ?? 'cliente' }},</p>
                            <p>Le recordamos que tiene una cita próxima en nuestro salón:</p>
                            <ul style="list-style: none; padding: 0;">
                                <li><strong>Servicio:</strong> {{ optional($cita->servicio)->nombre ?? 'Sin servicio asignado' }}</li>
                                <li><strong>Fecha:</strong> {{ $cita->fecha }}</li>
                                <li><strong>Hora:</strong> {{ $cita->hora }}</li>
                                <li><strong>Estilista:</strong> {{ optional($cita->estilista)->name ?? 'Por asignar' }}</li>
                            </ul>
                            <p>Por favor, llegue puntual o avísenos con anticipación si necesita reprogramar.</p>
                            <p>Gracias por elegirnos,</p>
                            <p><strong>Su salón de belleza</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px; background-color: #f9fafb; color: #6b7280; font-size: 14px; text-align: center;">
                            <p>Si necesita asistencia, contáctenos a través del sistema.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
