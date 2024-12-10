@if (Auth::user()->hasRole("tesorero"))
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
                <h2>Reporte de Devoluciones {{ ucfirst($estado) }} - Tesorería Escolar</h2>
                <div class="info">
                    <div>
                        <strong>Fecha de Emisión:</strong> {{ date('d/m/Y') }}
                    </div>
                    <div>
                        <strong>Periodo:</strong> {{ date('Y') }}
                    </div>
                </div>
            </div>
            <div class="filters" style="margin-top: 20px; font-size: 14px; color: #333;">
                <h3>Filtros Aplicados:</h3>
                <ul>
                    @if($buscarCodigo)
                        <li><strong>ID Estudiante:</strong> {{ $buscarCodigo }}</li>
                    @endif
                    @if($dniEstudiante)
                        <li><strong>DNI Estudiante:</strong> {{ $dniEstudiante }}</li>
                    @endif
                    @if($busquedaNombreEstudiante)
                        <li><strong>Nombre Estudiante:</strong> {{ $busquedaNombreEstudiante }}</li>
                    @endif
                    @if($busquedaApellidoEstudiante)
                        <li><strong>Apellido Estudiante:</strong> {{ $busquedaApellidoEstudiante }}</li>
                    @endif
                    @if($busquedaGrado)
                        <li><strong>Grado:</strong> {{ $grados->firstWhere('gradoEstudiante', $busquedaGrado)->descripcionGrado ?? '' }}</li>
                    @endif
                    @if($busquedaSeccion)
                        <li><strong>Sección:</strong> {{ $secciones->firstWhere('seccionEstudiante', $busquedaSeccion)->descripcionSeccion ?? '' }}</li>
                    @endif
                    @if($fechaInicio)
                        <li><strong>Fecha Inicio:</strong> {{ $fechaInicio }}</li>
                    @endif
                    @if($fechaFin)
                        <li><strong>Fecha Fin:</strong> {{ $fechaFin }}</li>
                    @endif
                    @if(isset($DevolucionesHoy) && $DevolucionesHoy === 'si')
                        <li><strong>Filtro de Devoluciones del Día:</strong> Activado</li>
                    @endif
                </ul>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                        <th>Código Devolucion</th>
                        <th>Pago Total</th>
                        <th>Estudiante</th>
                        <th>Grado-Seccion</th>
                        <th>Fecha</th>
                        <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($filteredDatos) <= 0)
                            <tr>
                                <td colspan="6" class="text-center">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($filteredDatos as $dato)
                                <tr>
                                    <td>{{ $dato->idDevolucion }}</td>
                                    <td>{{ $dato->totalPago }}</td>
                                    <td>{{ $dato->nombre }} {{ $dato->apellidoP }}</td>
                                    <td>{{ $dato->descripcionGrado }} {{ $dato->descripcionSeccion }}</td>
                                    <td>{{ $dato->fechaDevolucion }}</td>
                                    <td>{{ $dato->motivoDevolucion }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </body>

    </html>
@else
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
@endif