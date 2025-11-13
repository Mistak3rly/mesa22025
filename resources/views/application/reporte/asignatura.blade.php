<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Materias</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
            font-size: 20px;
        }

        h2 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
        }

        th {
            background-color: #2980b9;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Materias</h1>
        <h4>Semestre: {{ $semestreActual->descripcion }} | Generado el {{ now()->format('d/m/Y H:i') }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th>Docente</th>
                <th>Sigla</th>
                <th>Materia</th>
                <th>Grupo</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grupoAsignaturas as $ga)
                <tr>
                    <td>{{ $ga->docente->persona->nombre }}</td>
                    <td>{{ $ga->asignatura->sigla }}</td>
                    <td>{{ $ga->asignatura->descripcion }}</td>
                    <td>{{ $ga->grupo->descripcion }}</td>
                    <td>{{ $ga->observacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el sistema académico</p>
    </div>
</body>
</html>
