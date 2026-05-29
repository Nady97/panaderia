<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->nro_factura ?? 'N/A' }}</title>
    <style>
        @page { size: A4; margin: 20mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; font-size: 12px; }
        .wrapper { width: 100%; max-width: 800px; margin: 0 auto; }
        .header { overflow: hidden; margin-bottom: 12px; border-bottom: 2px solid #8B7355; padding-bottom: 8px; }
        .brand { float: left; }
        .brand h1 { color: #8B7355; font-size: 20px; margin: 0 0 4px 0; }
        .meta { float: right; text-align: right; }
        .meta h2 { margin: 0; color: #8B7355; font-size: 18px; }
        .clear { clear: both; }

        .info { width: 100%; margin-top: 8px; }
        .info .left, .info .right { display: inline-block; vertical-align: top; width: 49%; }
        .box { padding: 6px; background: #f7f7f7; margin-bottom: 8px; }
        .box strong { color: #8B7355; display: block; margin-bottom: 4px; }

        table.items { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.items th, table.items td { border: 1px solid #ddd; padding: 6px; }
        table.items th { background: #f0e6dd; color: #333; font-weight: bold; }
        .text-right { text-align: right; }

        .totals { margin-top: 8px; width: 100%; }
        .totals .line { width: 300px; float: right; }
        .totals .line .label { display: inline-block; width: 60%; }
        .totals .line .value { display: inline-block; width: 40%; text-align: right; }
        .totals .total { font-weight: bold; font-size: 14px; margin-top: 6px; border-top: 2px solid #8B7355; padding-top: 6px; }

        .footer { clear: both; margin-top: 30px; text-align: center; color: #666; font-size: 11px; }
        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="brand">
                <h1>PANADERÍA</h1>
                <div>Factura Interna</div>
            </div>
            <div class="meta">
                <h2>{{ $factura->nro_factura ?? 'S/N' }}</h2>
                <div>Emitida: {{ optional($factura->fecha_emision)->format('d/m/Y') ?? '' }}</div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="info">
            <div class="left">
                <div class="box no-break">
                    <strong>Cliente / CI</strong>
                    <div>{{ $factura->cliente_ci ?? 'N/A' }}</div>
                    <strong style="margin-top:6px;">Usuario</strong>
                    <div>{{ $factura->usuario->nombre ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="right">
                <div class="box no-break">
                    <strong>Estado</strong>
                    <div>{{ ucfirst($factura->estado) }}</div>
                    <strong style="margin-top:6px;">Puntos</strong>
                    <div>{{ $factura->puntos_ganados ?? 0 }}</div>
                </div>
            </div>
        </div>

        <table class="items no-break">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th style="width:120px;" class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Importe</td>
                    <td class="text-right">Bs. {{ number_format($factura->total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="totals no-break">
            <div class="line">
                <div class="label">TOTAL A PAGAR</div>
                <div class="value">Bs. {{ number_format($factura->total, 2) }}</div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="footer">
            <div>Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}</div>
            <div>Documento interno - válido sin firma</div>
        </div>
    </div>
</body>
</html>
