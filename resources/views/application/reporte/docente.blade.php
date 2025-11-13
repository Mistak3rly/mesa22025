<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Docentes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        h2 {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
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
        <h1>Reporte de Docentes</h1>
        <h2>FACULTAD DE INGENIERIA EN CIENCIAS DE COMPUTACION Y TELECOMUNICACIONES</h2>
        <h5>Generado el {{ now()->format('d/m/Y H:i') }}</h5>
    </div>

    <table>
        <thead>
            <tr>
                <th>Carnet</th>
                <th>Nombre</th>
                <th>Sexo</th>
                <th>Código</th>
                <th>Correo</th>
                <th>Carga Horaria</th>
            </tr>
        </thead>
        <tbody>
            @foreach($docentes as $docente)
                <tr>
                    <td>{{ $docente->persona->carnet }}</td>
                    <td>{{ $docente->persona->nombre }}</td>
                    <td>{{ $docente->persona->sexo }}</td>
                    <td>{{ $docente->codigo }}</td>
                    <td>{{ $docente->correo }}</td>
                    <td>{{ $docente->carga_horaria }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el sistema académico</p>
    </div>
</body>
</html>
