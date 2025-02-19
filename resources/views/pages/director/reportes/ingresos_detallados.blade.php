@extends('layouts.layoutA')

@section('titulo', 'Ingresos Detallados')

@section('contenido')
<div class="container py-5">
    <h1 class="text-center mb-5 text-3xl font-bold text-gray-800">Reporte de Ingresos Detallados</h1>

    <!-- Tabla de Ingresos Detallados -->
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-md">
            <thead class="text-center">
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-2 px-4 border-b">Fecha de Pago</th>
                    <th class="py-2 px-4 border-b">Estudiante</th>
                    <th class="py-2 px-4 border-b">Monto ($)</th>
                    <th class="py-2 px-4 border-b">Método de Pago</th>
                    <th class="py-2 px-4 border-b">Grado</th>
                    <th class="py-2 px-4 border-b">Sección</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($ingresosDetallados as $ingreso)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $ingreso->fechaPago}}</td>
                    <td class="py-2 px-4 border-b">{{ $ingreso->nombre }}{{ $ingreso->apellidoP }}{{ $ingreso->apellidoM }}</td>
                    <td class="py-2 px-4 border-b">${{ number_format($ingreso->monto, 2) }}</td>
                    <td class="py-2 px-4 border-b">{{ $ingreso->metodoPago }}</td>
                    <td class="py-2 px-4 border-b">{{ $ingreso->descripcionGrado }}</td>
                    <td class="py-2 px-4 border-b">{{ $ingreso->descripcionSeccion }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-2 px-4 text-center">No se encontraron resultados.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="text-center">
                <tr class="bg-gray-100 text-gray-700 font-bold text-center">
                    <td class="py-2 px-4 border-t" colspan="2">Total Ingresos</td>
                    <td class="py-2 px-4 border-t">${{ number_format($totalIngresos, 2) }}</td>
                    <td class="py-2 px-4 border-t" colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
