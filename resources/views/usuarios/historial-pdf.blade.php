<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Acceso</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        .header { margin-bottom: 16px; }
        .title { font-size: 16px; font-weight: bold; }
        .sub { color: #555; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">HISTORIAL DE USUARIO - PDF</div>
        <div class="sub">USUARIO: {{ $usuario->nombre }}</div>
        <div class="sub">EMAIL: {{ $usuario->email }}</div>
        <div class="sub">ROL: {{ $usuario->rol ? $usuario->rol->nombre : 'Sin rol' }}</div>
        <div class="sub">FECHA GENERACION: {{ now()->timezone('America/La_Paz')->format('d/m/Y h:i A') }}</div>
    </div>

    <div class="sub">HISTORIAL DE ACCESOS</div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Accion</th>
                <th>IP</th>
                <th>Dispositivo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bitacoras as $bitacora)
                <tr>
                    <td>{{ $bitacora->created_at ? $bitacora->created_at->timezone('America/La_Paz')->format('d/m/Y') : '-' }}</td>
                    <td>{{ $bitacora->created_at ? $bitacora->created_at->timezone('America/La_Paz')->format('h:i A') : '-' }}</td>
                    <td>{{ $bitacora->accion === 'login' ? 'Ingreso' : ($bitacora->accion === 'logout' ? 'Salida' : $bitacora->accion) }}</td>
                    <td>{{ $bitacora->ip_address ?? '-' }}</td>
                    <td>{{ $bitacora->user_agent ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Sin registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="sub" style="margin-top: 16px;">CAMBIOS REALIZADOS</div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Accion</th>
                <th>Modulo</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cambios as $cambio)
                <tr>
                    <td>{{ $cambio->created_at ? $cambio->created_at->timezone('America/La_Paz')->format('d/m/Y') : '-' }}</td>
                    <td>{{ $cambio->created_at ? $cambio->created_at->timezone('America/La_Paz')->format('h:i A') : '-' }}</td>
                    <td>{{ $cambio->accion }}</td>
                    <td>{{ $cambio->modulo }}</td>
                    <td>{{ $cambio->descripcion ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Sin registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
