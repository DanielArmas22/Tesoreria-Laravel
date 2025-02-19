<!DOCTYPE html>
<html>

<head>
    <title>Ingresos Detallados</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center; /* Centrar los títulos */
            color: #4A90E2; /* Color principal para los títulos */
        }

        h3 {
            color: #2D3E50; /* Color para subtítulos */
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
            background-color: #f9f9f9; /* Alternar colores de fondo en las filas */
        }

        tr:nth-child(odd) td {
            background-color: #ffffff;
        }

        td:hover {
            background-color: #d1e0e0; /* Efecto hover en las celdas */
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
    <h2>Reporte de Ingresos Detallados</h2>
    <p style="text-align: center; color: #7F8C8D;">Período: {{ $periodo ?? 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Mes: {{ $mes && is_numeric($mes) && $mes >= 1 && $mes <= 12 ? \Carbon\Carbon::create()->month((int)$mes)->format('F') : 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Grado: {{ $grado ?? 'Todos' }}</p>
    <p style="text-align: center; color: #7F8C8D;">Sección: {{ $seccion ?? 'Todas' }}</p>

    <table>
        <thead>
            <tr>
                <th>Fecha de Pago</th>
                <th>Estudiante</th>
                <th>Monto ($)</th>
                <th>Método de Pago</th>
                <th>Grado</th>
                <th>Sección</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingresosDetallados as $ingreso)
            <tr>
                <td>{{ \Carbon\Carbon::parse($ingreso->fechaPago)->format('d/m/Y') }}</td>
                <td>{{ $ingreso->nombre }} {{ $ingreso->apellidoP }} {{ $ingreso->apellidoM }}</td>
                <td>${{ number_format($ingreso->monto, 2) }}</td>
                <td>{{ $ingreso->metodoPago }}</td>
                <td>{{ $ingreso->descripcionGrado }}</td>
                <td>{{ $ingreso->descripcionSeccion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="total-general">Total Ingresos: ${{ number_format($totalIngresos, 2) }}</h3>

</body>

</html>
