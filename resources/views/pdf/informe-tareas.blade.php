<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Tareas Realizadas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #222;
            padding: 20px 30px;
        }

        /* ── Cabecera ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 4px 8px;
        }
        .logo-cell {
            width: 120px;
        }
        .logo-cell img {
            max-width: 110px;
            height: auto;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a3764;
        }
        .info-box {
            border: 1px solid #999;
            padding: 3px 8px;
            font-size: 11px;
            white-space: nowrap;
        }
        .info-label {
            font-weight: bold;
            color: #1a3764;
            font-size: 10px;
        }
        .info-value {
            background-color: #eaf0d8;
            padding: 2px 6px;
            border: 1px solid #ccc;
            font-size: 11px;
        }

        /* ── Título ── */
        .titulo {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #1a3764;
            border-bottom: 3px solid #c8b400;
            padding-bottom: 6px;
            margin: 15px 0 20px 0;
        }

        /* ── Nombre del Proyecto ── */
        .proyecto-nombre {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #222;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        /* ── Tabla de tareas ── */
        .tareas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .tareas-table thead th {
            background-color: #1a3764;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #0e264a;
        }
        .tareas-table tbody td {
            padding: 5px 6px;
            text-align: center;
            border: 1px solid #bbb;
            font-size: 11px;
        }
        .tareas-table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        .tareas-table tbody tr:hover {
            background-color: #e8eef5;
        }
        .td-descripcion {
            text-align: left !important;
        }

        /* ── Total ── */
        .total-row {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
        }
        .total-label {
            font-weight: bold;
            color: #1a3764;
        }
        .total-value {
            display: inline-block;
            border: 2px solid #1a3764;
            padding: 3px 14px;
            font-weight: bold;
            font-size: 14px;
            min-width: 60px;
        }

        /* ── Fechas cabecera ── */
        .fechas-proyecto-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .fechas-proyecto-table td {
            padding: 2px 6px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    {{-- ══════════ CABECERA ══════════ --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell" rowspan="2">
                {{-- Logo: si tienes logo en public/img/logo.png descomenta la línea de abajo --}}
                {{-- <img src="{{ public_path('img/logo.png') }}" alt="Logo"> --}}
                <span style="font-size:22px;font-weight:bold;color:#1a3764;">SIMJ</span><br>
                <span style="font-size:9px;color:#6a9e1f;">SOFTWARE</span>
            </td>
            <td class="company-name">
                {{ $proyecto->id }} - {{ $proyecto->nombre }}
            </td>
            <td style="text-align:right;">
                <table style="float:right;border-collapse:collapse;">
                    <tr>
                        <td class="info-label" style="text-align:right;padding-right:4px;">PROYECTO:</td>
                        <td class="info-value">{{ $proyecto->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="info-label" style="text-align:right;padding-right:4px;">USUARIO:</td>
                        <td class="info-value">{{ $usuario->name }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="fechas-proyecto-table">
                    <tr>
                        <td>
                            <span class="info-label">DESDE FECHA</span>
                            <span class="info-value">{{ $fechaDesde }}</span>
                            &nbsp;&nbsp;
                            <span class="info-label">HASTA FECHA</span>
                            <span class="info-value">{{ $fechaHasta }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ══════════ TÍTULO ══════════ --}}
    <div class="titulo">INFORME DE TAREAS REALIZADAS</div>

    {{-- ══════════ NOMBRE PROYECTO ══════════ --}}
    <div class="proyecto-nombre">{{ $proyecto->nombre }}</div>

    {{-- ══════════ TABLA DE TAREAS ══════════ --}}
    <table class="tareas-table">
        <thead>
            <tr>
                <th style="width:30px;">ID</th>
                <th>INICIO</th>
                <th>FIN</th>
                <th style="width:50px;">MIN.</th>
                <th>USUARIO</th>
                <th>TAREA REALIZADA</th>
            </tr>
        </thead>
        <tbody>
            @php $totalMinutos = 0; @endphp
            @forelse($tareas as $i => $tarea)
                @php
                    $inicio = \Carbon\Carbon::parse($tarea->tiempo_inicio);
                    $fin    = \Carbon\Carbon::parse($tarea->tiempo_fin);
                    $min    = $inicio->diffInMinutes($fin);
                    $totalMinutos += $min;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $inicio->format('d/m/Y H:i') }}</td>
                    <td>{{ $fin->format('d/m/Y H:i') }}</td>
                    <td>{{ $min }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td class="td-descripcion">{{ $tarea->descripcion ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:10px;">No hay tareas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ══════════ TOTAL ══════════ --}}
    <div class="total-row">
        <span class="total-label">TOTAL MINS :</span>
        <span class="total-value">{{ $totalMinutos }}</span>
    </div>

</body>
</html>
