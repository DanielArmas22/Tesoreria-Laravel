@extends('layouts.layoutA')

@section('titulo', 'Inscripción por Grado y Sección')

@section('contenido')
<div class="container py-5">
    <h1 class="text-center mb-5 text-3xl font-bold text-gray-800">Inscripción por Grado y Sección</h1>

    <!-- Tabla de Inscripciones -->
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-md">
            <thead class="text-center">
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-2 px-4 border-b">Grado</th>
                    <th class="py-2 px-4 border-b">Sección</th>
                    <th class="py-2 px-4 border-b">Total Inscritos</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($inscripciones as $inscripcion)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $inscripcion->descripcionGrado }}</td>
                    <td class="py-2 px-4 border-b">{{ $inscripcion->descripcionSeccion }}</td>
                    <td class="py-2 px-4 border-b">{{ $inscripcion->total_inscritos }}</td>
                </tr>
                @empty
                <tr class="text-center">
                    <td colspan="3" class="py-2 px-4 text-center">No se encontraron resultados.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="text-center">
                <tr class="bg-gray-100 text-gray-700 font-bold">
                    <td class="py-2 px-4 border-t" colspan="2">Total General</td>
                    <td class="py-2 px-4 border-t">{{ $totalInscritos }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Gráfico de Inscripciones por Grado -->
    <div class="w-full h-64">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700 text-center">Total de Inscripciones por Grado</h2>
        <canvas id="chartInscripcionesGrado"></canvas>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtener los datos pasados desde el controlador
    const gradosLabels = @json($gradosLabels);
    const inscripcionesPorGrado = @json($inscripcionesPorGrado);

    // Configurar el gráfico
    const ctx = document.getElementById('chartInscripcionesGrado').getContext('2d');
    const chartInscripcionesGrado = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: gradosLabels,
            datasets: [{
                label: 'Total de Inscripciones',
                data: inscripcionesPorGrado,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, 
        }
    });
</script>
@endsection
