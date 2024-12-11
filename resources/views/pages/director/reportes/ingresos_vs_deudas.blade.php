@extends('layouts.layoutA')

@section('titulo', 'Ingresos vs Deudas')

@section('contenido')
    <!-- Contenedor del título y gráfico -->
    <div class="w-full flex flex-col items-center bg-white p-6 rounded-lg shadow mx-auto">
        <!-- Título general -->
        <h2 class="text-2xl font-bold mb-4">Ingresos Vs Deudas según meses</h2>

        <!-- Gráfico de Ingresos vs Deudas -->
        <div class="w-2/3 h-96">
            <canvas id="chartIngresosVsDeudas"></canvas>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Obtener los datos pasados desde el controlador
        const periodos = @json($periodosUnicos);
        const ingresos = @json($datosIngresos);
        const deudas = @json($datosDeudas);

        // Configurar el gráfico
        const ctx = document.getElementById('chartIngresosVsDeudas').getContext('2d');
        const chartIngresosVsDeudas = new Chart(ctx, {
            type: 'line',
            data: {
                labels: periodos,
                datasets: [{
                        label: 'Ingresos ($)',
                        data: ingresos,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.1
                    },
                    {
                        label: 'Deudas ($)',
                        data: deudas,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                        tension: 0.1
                    }
                ]
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
