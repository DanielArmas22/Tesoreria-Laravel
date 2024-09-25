<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Pagos - Tesorería Escolar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .report-header {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .report-header h2 {
            font-size: 24px;
            color: #333333;
            margin: 0;
        }

        .report-header .info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 14px;
            color: #666666;
        }

        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f1f1f1;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        th {
            font-size: 14px;
            color: #333333;
        }

        td {
            font-size: 14px;
            color: #666666;
        }

        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            padding: 20px;
            color: #333333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="report-header">
            <h2>Reporte de Pagos - Tesorería Escolar</h2>
            <div class="info">
                <div>
                    <strong>Fecha de Emisión:</strong> {{ date('d/m/Y') }}
                </div>
                <div>
                    <strong>Periodo:</strong> {{ date('Y') }}
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Periodo</th>
                        <th>Id Estudiante</th>
                        <th>Nombre Estudiante</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($pagos) <= 0)
                        <tr>
                            <td colspan="6" class="text-center">No hay registros</td>
                        </tr>
                    @else
                        @foreach ($pagos as $itempagos)
                            <tr>
                                <td>{{ $itempagos->nroOperacion }}</td>
                                <td>{{ $itempagos->fechaPago }}</td>
                                <td>{{ number_format($itempagos->totalMonto, 2) }}</td>
                                <td>{{ $itempagos->periodo }}</td>
                                <td>{{ $itempagos->idEstudiante }}</td>
                                <td>{{ $itempagos->apellidoP }} {{ $itempagos->apellidoM }} {{ $itempagos->nombre }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="total">
            TOTAL: {{ number_format($totalPago, 2) }}
        </div>
    </div>
</body>

</html>
