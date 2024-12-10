@extends('layouts.layoutA')

@section('titulo', 'Reporte de Deudores')

@section('contenido')
<div class="container py-5">
    <h1 class="text-center mb-5 text-3xl font-bold text-gray-800">Reporte de Deudores</h1>

    <!-- Tabla de Deudores -->
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-md">
            <thead class="text-center">
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-2 px-4 border-b">Nombre del Estudiante</th>
                    <th class="py-2 px-4 border-b">Grado</th>
                    <th class="py-2 px-4 border-b">Sección</th>
                    <th class="py-2 px-4 border-b">Monto Adeudado</th>
                    <th class="py-2 px-4 border-b">Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($deudores as $deudor)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $deudor->nombre }}{{ $deudor->apellidoP }}{{ $deudor->apellidoM }}</td>
                    <td class="py-2 px-4 border-b">{{ $deudor->descripcionGrado }}</td>
                    <td class="py-2 px-4 border-b">{{ $deudor->descripcionSeccion }}</td>
                    <td class="py-2 px-4 border-b">${{ number_format($deudor->monto, 2) }}</td>
                    <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($deudor->fechaLimite)->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr class="text-center">
                    <td colspan="6" class="py-2 px-4 text-center">No se encontraron resultados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
