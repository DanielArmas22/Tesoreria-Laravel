<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Devoluciones</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f3f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container for the report */
        .report-container {
            max-width: 1000px;
            width: 90%;
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Date and time styling */
        .date-time {
            font-size: 12px;
            color: #6c757d;
            text-align: right;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 15px;
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
            border: 1px solid #dee2e6;
        }

        thead th {
            background-color: #343a40;
            color: #ffffff;
            padding: 6px;
            text-align: center;
            border-bottom: 2px solid #495057;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            border-right: 1px solid #dee2e6;
            font-size: 11px;
        }

        thead th:last-child {
            border-right: none;
        }

        tbody td {
            padding: 6px;
            border-bottom: 1px solid #dee2e6;
            color: #495057;
            text-align: center;
            vertical-align: middle;
            border-right: 1px solid #dee2e6;
            font-size: 11px;
        }

        tbody td:last-child {
            border-right: none;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        /* "No records" styling */
        .no-records {
            text-align: center;
            color: #888888;
            font-style: italic;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        /* Button styling */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .button {
            padding: 6px 12px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
            font-size: 12px;
            transition: background-color 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button-secondary {
            background-color: #28a745;
        }

        .button-secondary:hover {
            background-color: #218838;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .report-container {
                padding: 15px;
            }

            table, tbody, th, td {
                font-size: 10px;
            }

            h2 {
                font-size: 18px;
            }

            .button {
                font-size: 10px;
                padding: 5px 10px;
            }

            .date-time {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="date-time">
            {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </div>
        <h2>Reporte de Devoluciones</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID Devolución</th>
                    <th>Nro Operación</th>
                    <th>Pago Total</th>
                    <th>ID Estudiante</th>
                    <th>Estudiante</th>
                    <th>Fecha</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @if (count($datos) <= 0)
                    <tr class="no-records">
                        <td colspan="7">No hay registros</td>
                    </tr>
                @else
                    @foreach ($datos as $dato)
                        <tr>
                            <td>{{ $dato->idDevolucion }}</td>
                            <td>{{ $dato->nroOperacion }}</td>
                            <td>{{ $dato->totalPago }}</td>
                            <td>{{ $dato->idEstudiante }}</td>
                            <td>{{ $dato->nombre }} {{ $dato->apellidoP }}</td>
                            <td>{{ $dato->fechaDevolucion }}</td>
                            <td>{{ $dato->observacion }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>


    </div>
</body>
</html>
