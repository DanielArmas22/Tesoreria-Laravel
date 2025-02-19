@extends('layouts.layoutA')
@section('titulo', 'Pagos')

@section('contenido')

    <style>
        /* Ajustes para la impresión en PDF */
        .body {
            font-family: 'Helvetica', sans-serif;
        }
    </style>


    <div class="max-w-2xl mx-auto p-4 border border-rounded">
        <h2 class="text-2xl font-bold mb-4">Boleta de Pago</h2>
        <div class="bg-white shadow-md rounded p-4 mb-4">
            <p><strong>Número de Operación:</strong> {{ $pago->nroOperacion }}</p>
            <p><strong>ID Estudiante:</strong> {{ $estudiante->idEstudiante }}</p>
            <p><strong>Nombre Completo:</strong> {{ $estudiante->nombre }} {{ $estudiante->apellidoP }}
                {{ $estudiante->apellidoM }}</p>
        </div>
        <div class="bg-white shadow-md rounded p-4">
            <h3 class="text-xl font-bold mb-2">Detalle del Pago</h3>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Codigo</th>
                        <th class="py-2">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
                        <tr>
                            <td class="py-2 text-center">{{ $detalle->nroOperacion }}-{{ $detalle->idDeuda }}</td>
                            <td class="py-2 text-center">{{ $detalle->monto }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="mt-4"><strong>Monto Total Pagado:</strong> {{ $montoTotal }}</p>
        </div>
    </div>
@endsection
