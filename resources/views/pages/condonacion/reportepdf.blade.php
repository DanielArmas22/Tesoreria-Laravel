<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Condonación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container {
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .section-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .info-box {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .info-box h2 {
            font-size: 16px;
            margin: 0;
            color: #333;
            margin-bottom: 10px;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #f4f4f4;
        }

        table th,
        table td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        table th {
            background-color: #e2e2e2;
            color: #333;
            text-transform: uppercase;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Reporte de Condonación</h1>
        </div>

        <div class="info-box">
            <h2>Estudiante</h2>
            <p><strong>DNI:</strong> {{ $estudiante->DNI }}</p>
            <p><strong>Nombre:</strong> {{ $estudiante->nombre }}</p>
            <p><strong>Apellido Paterno:</strong> {{ $estudiante->apellidoP }}</p>
            <p><strong>Apellido Materno:</strong> {{ $estudiante->apellidoM }}</p>
            <p><strong>Aula:</strong> {{ $estudiante->descripcionGrado }} - {{ $estudiante->descripcionSeccion }}</p>
        </div>

        <div class="info-box">
            <h2>Condonación</h2>
            <p><strong>Código:</strong> {{ $condonacion->idCondonacion }}</p>
            <p><strong>Monto Condonado:</strong> {{ $condonacion->totalMonto }}</p>
            <p><strong>Fecha:</strong> {{ $condonacion->fecha }}</p>
        </div>

        <h2 class="section-title">Deudas Condonadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Concepto Escala</th>
                    <th>Monto Escala</th>
                    <th>Monto Mora</th>
                    <th>Fecha Límite</th>
                    <th>Adelanto</th>
                    <th>Monto Condonado</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($detalle))
                    @foreach ($detalle as $deuda)
                        <tr>
                            <td>{{ $deuda->deuda->idDeuda }}</td>
                            <td>{{ $deuda->deuda->conceptoEscala->descripcion }}</td>
                            <td>{{ $deuda->deuda->conceptoEscala->escala->monto }}</td>
                            <td>{{ $deuda->deuda->montoMora }}</td>
                            <td>{{ $deuda->deuda->fechaLimite }}</td>
                            <td>{{ $deuda->deuda->adelanto }}</td>
                            <td>{{ $deuda->monto ?? '0.0000' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" style="text-align: center;">No se encontraron deudas</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</body>

</html>
