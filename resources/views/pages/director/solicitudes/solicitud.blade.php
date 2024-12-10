<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Condonación</title>
    <style>
        /* General */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        h1, h2 {
            color: #2d3748;
            font-weight: bold;
        }

        h1 {
            margin-top: 30px;
            font-size: 2.5em;
            text-align: center;
        }

        h2 {
            font-size: 1.4em;
            margin-top: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
            color: #4a5568;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
            font-size: 1.1em;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        table td {
            background-color: #fff;
        }

        /* Estilo de celdas y texto */
        p {
            font-size: 1.1em;
            line-height: 1.6;
        }

        strong {
            color: #2d3748;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Estilo de mensajes */
        .no-datos {
            color: #e53e3e;
            font-weight: bold;
        }

        /* Encabezados adicionales */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-section .logo {
            width: 120px;
        }

        .header-section .date {
            font-size: 1em;
            color: #2d3748;
            font-style: italic;
        }

        .info-box {
            background-color: #ebf4ff;
            padding: 10px;
            border-radius: 5px;
            border-left: 5px solid #3182ce;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">

        <!-- Encabezado -->
        <div class="header-section">
            <img src="logo.png" alt="Logo" class="logo">
            <div class="date">
                Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
            </div>
        </div>

        <h1>Solicitud de Condonación de Deuda</h1>

        <!-- Datos del Estudiante -->
        <h2>Datos del Estudiante</h2>
        @if ($condonacion->detalleCondonaciones->first()->deuda->estudiante)
            <p><strong>Nombre del Estudiante:</strong> {{ $condonacion->detalleCondonaciones->first()->deuda->estudiante->nombre }} 
               {{ $condonacion->detalleCondonaciones->first()->deuda->estudiante->apellidoP }} 
               {{ $condonacion->detalleCondonaciones->first()->deuda->estudiante->apellidoM }}</p>
            <p><strong>DNI:</strong> {{ $condonacion->detalleCondonaciones->first()->deuda->estudiante->DNI }}</p>
            <p><strong>Grado:</strong> {{ $condonacion->detalleCondonaciones->first()->deuda->detalleEstudianteGs->grado->descripcionGrado ?? 'N/A' }}</p>
            <p><strong>Sección:</strong> {{ $condonacion->detalleCondonaciones->first()->deuda->detalleEstudianteGs->seccion->descripcionSeccion ?? 'N/A' }}</p>
        @else
            <p class="no-datos">No se encontraron datos del estudiante asociados.</p>
        @endif

        <!-- Detalles de la Condonación -->
        <h2>Detalles de la Condonación</h2>
        <p><strong>Motivo de la Condonación:</strong> {{ $condonacion->motivoCondonacion }}</p>
        <p><strong>Fecha de Condonación:</strong> {{ $condonacion->fecha }}</p>

        <!-- Deudas Asociadas -->
        <h2>Deudas Asociadas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Deuda</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($condonacion->detalleCondonaciones as $detalle)
                    <tr>
                        <td>{{ $detalle->idDeuda }}</td>
                        <td>{{ $detalle->monto }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Información adicional o instrucciones -->
        <div class="info-box">
            <p>Esta solicitud está siendo procesada y el estudiante puede recibir una notificación una vez aprobada. Para más información, por favor contacte con la administración.</p>
        </div>

    </div>

</body>
</html>
