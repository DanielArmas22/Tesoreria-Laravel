<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Deudores</title>
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
    <h2>Reporte de Deudores</h2>
    <p style="text-align: center; color: #7F8C8D;">Período: {{ $periodo ?? 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Mes: {{ $mes && is_numeric($mes) && $mes >= 1 && $mes <= 12 ? \Carbon\Carbon::create()->month((int)$mes)->format('F') : 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Trimestre: {{ $trimestre ?? 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Grado: {{ $grado ?? 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Sección: {{ $seccion ?? 'Todas' }}</p>

    <table>
        <thead>
            <tr>
                <th>Nombre del Estudiante</th>
                <th>Grado</th>
                <th>Sección</th>
                <th>Monto Adeudado</th>
                <th>Fecha de Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deudores as $deudor)
            <tr>
                <td>{{ $deudor->nombre }} {{ $deudor->apellidoP }} {{ $deudor->apellidoM }}</td>
                <td>{{ $deudor->descripcionGrado }}</td>
                <td>{{ $deudor->descripcionSeccion }}</td>
                <td>${{ number_format($deudor->monto, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($deudor->fechaLimite)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
