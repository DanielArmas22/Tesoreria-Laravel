@extends('layouts.layoutA')

@section('titulo', 'Resumen de Ingresos')

@section('contenido')
<div class="container py-5">
    <h1 class="text-center mb-5 text-3xl font-bold text-gray-800">Resumen de Ingresos segun Grado y Seccion</h1>

    <!-- Tabla de Ingresos -->
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-2 px-4 border-b">Grado</th>
                    <th class="py-2 px-4 border-b">Sección</th>
                    <th class="py-2 px-4 border-b">Total de Ingresos</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($ingresos as $ingreso)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $ingreso->descripcionGrado }}</td>
                    <td class="py-2 px-4 border-b">{{ $ingreso->descripcionSeccion }}</td>
                    <td class="py-2 px-4 border-b">${{ number_format($ingreso->total_ingresos, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-2 px-4 text-center">No se encontraron resultados.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="text-center">
                <tr class="bg-gray-100 text-gray-700 font-bold">
                    <td class="py-2 px-4 border-t" colspan="2">Total General</td>
                    <td class="py-2 px-4 border-t">${{ number_format($totalGeneralIngresos, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Gráfico de Ingresos por Grado -->
    <div class="mb-12 w-128 h-80 flex justify-center items-center flex-col"> 
        <h2 class="text-2xl font-semibold mb-4 text-gray-700 text-center">Total de Ingresos por Grado</h2>
        <canvas id="chartResumenIngresos"></canvas>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtener los datos pasados desde el controlador
    const gradosLabels = @json($gradosLabels);
    const ingresosPorGrado = @json($ingresosPorGrado);

    // Configurar el gráfico
    const ctx = document.getElementById('chartResumenIngresos').getContext('2d');
    const chartResumenIngresos = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: gradosLabels,
            datasets: [{
                label: 'Total de Ingresos ($)',
                data: ingresosPorGrado,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += '$' + context.parsed.y.toLocaleString();
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
