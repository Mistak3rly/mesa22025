<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>Reporte Dinámico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats-box {
            display: inline-block;
            width: 30%;
            padding: 15px;
            margin: 10px 1%;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .stats-box h3 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 14px;
        }
        .stats-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e5e7eb;
            border-left: 4px solid #3b82f6;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
        }
        .badge-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Exportacion de Reportes</h1>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
        @if($request->filled('semestre_id'))
            <p>Semestre: {{ \App\Models\Semestre::find($request->semestre_id)->descripcion }}</p>
        @endif
    </div>

    <!-- Estadísticas Generales -->
    <div style="margin-bottom: 30px;">
        <div class="stats-box">
            <h3>Total Horarios</h3>
            <div class="value">{{ $reporteData['total_horarios'] }}</div>
        </div>
        <div class="stats-box">
            <h3>Asistencia</h3>
            <div class="value">{{ $reporteData['porcentaje_asistencia'] }}%</div>
        </div>
        <div class="stats-box">
            <h3>Ausencias</h3>
            <div class="value">{{ $reporteData['total_ausencias'] }}</div>
        </div>
    </div>

    <!-- Horas por Docente -->
    @if(count($reporteData['horas_por_docente']) > 0)
        <div class="section-title">Horas por Docente</div>
        <table>
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Total Horas</th>
                    <th>Materias</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reporteData['horas_por_docente'] as $data)
                    <tr>
                        <td>{{ $data['docente'] }}</td>
                        <td><span class="badge badge-blue">{{ $data['total_horas'] }} horas</span></td>
                        <td>{{ $data['materias'] }} materia(s)</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Horas por Materia -->
    @if(count($reporteData['horas_por_materia']) > 0)
        <div class="section-title">Horas por Materia</div>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Materia</th>
                    <th>Total Horas</th>
                    <th>Grupos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reporteData['horas_por_materia'] as $data)
                    <tr>
                        <td>{{ $data['codigo'] }}</td>
                        <td>{{ $data['materia'] }}</td>
                        <td><span class="badge badge-green">{{ $data['total_horas'] }} horas</span></td>
                        <td>{{ $data['grupos'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Sistema de Gestion Facultad - Reporte generado automaticamente</p>
    </div>
</body>
</html>
