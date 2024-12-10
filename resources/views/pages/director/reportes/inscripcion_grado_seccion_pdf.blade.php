<!DOCTYPE html>
<html>

<head>
    <title>Inscripción por Grado y Sección</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #4A90E2; /* Color azul suave para títulos */
        }

        h3 {
            color: #2D3E50;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #e1e1e1;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4A90E2; /* Azul suave */
            color: white;
            font-size: 16px;
        }

        td {
            background-color: #f2f2f2;
            font-size: 14px;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) td {
            background-color: #ffffff;
        }

        td:hover {
            background-color: #d1e0e0;
            cursor: pointer;
        }

        .total-general {
            font-size: 18px;
            font-weight: bold;
            color: #2D3E50;
            margin-top: 20px;
            text-align: center;
        }

    </style>
</head>

<body>
    <h1>Institución Educativa Sideral Carrion</h1>
    <h2>Inscripción por Grado y Sección</h2>
    <p style="text-align: center; color: #7F8C8D;">Período: {{ $periodo ?? 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Mes: {{ $mes && is_numeric($mes) && $mes >= 1 && $mes <= 12 ? \Carbon\Carbon::create()->month((int)$mes)->format('F') : 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Grado: {{ $grado ?? 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Sección: {{ $seccion ?? 'Todos' }}</p>

    <table>
        <thead>
            <tr>
                <th>Grado</th>
                <th>Sección</th>
                <th>Total Inscritos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscripciones as $inscripcion)
            <tr>
                <td>{{ $inscripcion->descripcionGrado }}</td>
                <td>{{ $inscripcion->descripcionSeccion }}</td>
                <td>{{ $inscripcion->total_inscritos }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total General de Inscritos: {{ $totalInscritos }}</h3>

</body>

</html>
