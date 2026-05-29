<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Compra #{{ $venta->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            width: 280px;
            margin: 0 auto;
            padding: 10px;
            color: #000;
            background-color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .header {
            margin-bottom: 15px;
        }

        .logo {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .info-table, .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px 0;
        }

        .items-table th {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            text-align: left;
        }

        .items-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .totals-table {
            width: 100%;
            margin-top: 10px;
        }

        .totals-table td {
            padding: 2px 0;
        }

        .footer {
            margin-top: 25px;
            font-size: 10px;
        }

        /* Auto print when page loads */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 8px;
            background-color: #f43f5e;
            color: white;
            text-align: center;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 15px;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 13px;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Imprimir Ticket</button>
    </div>

    <div class="header text-center">
        <div class="logo">SALÓN ANITA</div>
        <div>Belleza Premium</div>
        <div>Nit: 4892019022</div>
        <div>Cel: +591 76543210</div>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td class="bold">Ticket:</td>
            <td>#PROD-{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="bold">Fecha:</td>
            <td>{{ $venta->fecha_venta->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="bold">Cliente:</td>
            <td>{{ $venta->cliente_nombre }}</td>
        </tr>
        <tr>
            <td class="bold">Vendedor:</td>
            <td>{{ $venta->vendedor->name }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Desc.</th>
                <th class="text-center">Cant.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td>
                    {{ $detalle->producto->nombre }}<br>
                    <span style="font-size: 10px;">P.U: Bs{{ number_format($detalle->precio_unitario, 2) }}</span>
                    @if($detalle->descuento > 0)
                        <span style="font-size: 10px; display: block; color: #555;">(Dcto: -Bs{{ number_format($detalle->descuento, 2) }})</span>
                    @endif
                </td>
                <td class="text-center">{{ $detalle->cantidad }}</td>
                <td class="text-right">Bs{{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">Bs{{ number_format($venta->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Descuento Promos:</td>
            <td class="text-right">-Bs{{ number_format($venta->descuento, 2) }}</td>
        </tr>
        <tr class="bold">
            <td style="font-size: 13px;">TOTAL A PAGAR:</td>
            <td class="text-right" style="font-size: 13px;">Bs{{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer text-center">
        <p class="bold">Método Pago: {{ $venta->metodo_pago === 'stripe' ? 'TARJETA EN LÍNEA (STRIPE)' : strtoupper($venta->metodo_pago) }}</p>
        <p class="bold">Estado Pago: {{ strtoupper($venta->estado_pago) }}</p>
        <p>¡Gracias por su compra!</p>
        <p>Conserve su boleta para reclamos.</p>
        <p>Desarrollado por Antigravity</p>
    </div>

</body>
</html>
