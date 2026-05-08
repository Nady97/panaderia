<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta - {{ $receta->nombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #c9a87b;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2d1f1a;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #777;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th {
            background-color: #f7f7f7;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            width: 30%;
            font-size: 14px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .section-title {
            color: #c9a87b;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-top: 30px;
            font-size: 18px;
        }
        .ingredients-list {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .ingredients-list th {
            background-color: #f5e6d3;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .ingredients-list td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .instructions {
            background-color: #fcfcfc;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .no-ingredients-note {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $receta->nombre }}</h1>
        <p>Documento de Producción Oficial - Panadería</p>
    </div>

    <table class="info-table">
        <tr>
            <th>Producto Relacionado</th>
            <td>{{ $receta->producto ? $receta->producto->nombre : 'Ninguno' }}</td>
        </tr>
        <tr>
            <th>Rendimiento Estimado</th>
            <td>{{ $receta->rendimiento_estimado }} unidades</td>
        </tr>
        <tr>
            <th>Tiempo de Preparación</th>
            <td>{{ $receta->tiempo_preparacion_min }} minutos</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{ ucfirst($receta->estado) }}</td>
        </tr>
    </table>

    <h3 class="section-title">Ingredientes Requeridos</h3>
    @if($receta->insumos && $receta->insumos->count() > 0)
        <table class="ingredients-list">
            <thead>
                <tr>
                    <th>Insumo / Ingrediente</th>
                    <th>Cantidad Necesaria</th>
                    <th>Unidad de Medida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receta->insumos as $insumo)
                <tr>
                    <td>{{ $insumo->nombre }}</td>
                    <td>{{ floatval($insumo->pivot->cantidad_necesaria) }}</td>
                    <td>{{ $insumo->unidad_medida }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="no-ingredients-note">No se han especificado ingredientes para esta receta.</p>
    @endif

    <h3 class="section-title">Instrucciones de Preparación</h3>
    <div class="instructions">{{ $receta->instrucciones ?? 'No hay instrucciones detalladas.' }}</div>

    <div class="footer">
        Generado automáticamente por el Sistema de Panadería - {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>
