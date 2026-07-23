<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td style="padding: 24px; text-align: center; background-color: #1f2937; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px;">{{ $subjectLine }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px; color: #111827;">
                            <p>Hola {{ $client->name }},</p>
                            <p style="white-space: pre-line;">{{ $messageBody }}</p>
                            <p>Si deseas agendar una cita o conocer nuestras promociones, estamos listos para atenderte.</p>
                            <p>Saludos cordiales,</p>
                            <p><strong>Tu Salón de Belleza</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px; background-color: #f9fafb; color: #6b7280; font-size: 14px; text-align: center;">
                            <p>Gracias por confiar en nosotros.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
