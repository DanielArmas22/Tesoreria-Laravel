@extends('layouts.layoutA')
@section('titulo', 'Dashboard deudas')
@section('contenido')
<div class="container py-5">
    <!-- Título con tamaño grande, centrado y en negrita -->
    <h1 class="text-center mb-5 text-4xl font-bold text-gray-800">Panel de Reportes de Deudas</h1>

    <!-- Formulario de filtros con Tailwind -->
    <form method="GET" action="{{ route('dashboard.deudas') }}" class="mb-5">
        <div class="flex justify-center gap-4 mb-4">
            <div class="w-full md:w-1/3">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="periodo" id="periodo">
                    <option value="">Seleccione un período</option>
                    @foreach ($periodos as $periodoItem)
                    <option value="{{ $periodoItem->id }}" {{ request('periodo') == $periodoItem->id ? 'selected' : '' }}>
                        {{ $periodoItem->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="grado" id="grado">
                    <option value="">Seleccione un grado</option>
                    @foreach ($grados as $gradoItem)
                    <option value="{{ $gradoItem }}" {{ request('grado') == $gradoItem ? 'selected' : '' }}>{{ $gradoItem }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="seccion" id="seccion">
                    <option value="">Seleccione una sección</option>
                    @foreach ($secciones as $seccionItem)
                    <option value="{{ $seccionItem }}" {{ request('seccion') == $seccionItem ? 'selected' : '' }}>{{ $seccionItem }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="w-full md:w-auto px-6 py-3 text-lg font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Filtrar</button>
    </form>

    <!-- Gráfico de Deudas por Grado -->
    <div class="mb-12 w-128 h-80 flex justify-center items-center flex-col"> 

        <h2 class="text-2xl font-semibold mb-4 text-gray-700">Total de Deudas</h2>
        <canvas id="chartDeudasPorGrado" width="400" height="200"></canvas>
    </div>

    <!-- Tabla de Deudas -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-2 px-4 border-b">Grado</th>
                    <th class="py-2 px-4 border-b">Sección</th>
                    <th class="py-2 px-4 border-b">Total de Deudas</th>
                    <th class="py-2 px-4 border-b">Total de Deudores</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($deudas as $deuda)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $deuda->descripcionGrado }}</td>
                    <td class="py-2 px-4 border-b">{{ $deuda->descripcionSeccion }}</td>
                    <td class="py-2 px-4 border-b">${{ number_format($deuda->total_deudas, 2) }}</td>
                    <td class="py-2 px-4 border-b">{{ $deuda->total_deudores }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-2 px-4 text-center">No se encontraron resultados.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="text-center">
                <tr class="bg-gray-100 text-gray-700 font-bold">
                    <td class="py-2 px-4 border-t" colspan="2">Total General</td>
                    <td class="py-2 px-4 border-t">${{ number_format($totalGeneralDeudas, 2) }}</td>
                    <td class="py-2 px-4 border-t">{{ $totalGeneralDeudores }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection


@section('js')
<!-- Incluir Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtener los datos pasados desde el controlador
    const gradosLabels = @json($gradosLabels);
    const deudasPorGrado = @json($deudasPorGrado);


    // Configurar el gráfico
    const ctx = document.getElementById('chartDeudasPorGrado').getContext('2d');
    const chartDeudasPorGrado = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: gradosLabels,
            datasets: [{
                label: 'Total de Deudas ($)',
                data: deudasPorGrado,
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
                        // Incluye el símbolo de dólar en las etiquetas del eje Y
                        callback: function(value, index, values) {
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
