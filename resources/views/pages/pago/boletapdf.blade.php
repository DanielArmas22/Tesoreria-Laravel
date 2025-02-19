<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Pago</title>
    <style>
        /* Ajustes generales */
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Contenedor principal */
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Cabecera */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .header .title {
            font-size: 1.8em;
            font-weight: bold;
            margin: 0;
        }

        .header .date {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }

        /* Cajas de información */
        .box {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }

        .box p {
            margin: 5px 0;
        }

        /* Tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        /* Total */
        .total {
            margin-top: 15px;
            font-size: 1.1em;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabecera -->
        <div class="header">
            <h1 class="title">Boleta de Pago</h1>
            <p class="date">Fecha de emisión: {{ date('d/m/Y') }}</p>
        </div>

        <!-- Información del pago -->
        <div class="box">
            <p><strong>Número de Operación:</strong> {{ $pago->nroOperacion }}</p>
            <p><strong>ID Estudiante:</strong> {{ $estudiante->idEstudiante }}</p>
            <p><strong>Nombre Completo:</strong> {{ $estudiante->nombre }} {{ $estudiante->apellidoP }} {{ $estudiante->apellidoM }}</p>
        </div>

        <!-- Detalle del pago -->
        <div class="box">
            <h3 class="subtitle">Detalle del Pago</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->nroOperacion }}-{{ $detalle->idDeuda }}</td>
                            <td>S/ {{ number_format($detalle->monto, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="total">Monto Total Pagado: S/ {{ number_format($montoTotal, 2) }}</p>
        </div>
    </div>
</body>
</html>
