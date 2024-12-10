@extends('layouts.layoutA')
@section('titulo', 'Dashboard')
@section('contenido')
<div class="container py-5">
    <!-- Título con tamaño grande, centrado y en negrita -->
    <h1 class="text-center mb-5 text-4xl font-bold text-gray-800">Panel de Reportes Financieros</h1>

    <!-- Formulario de filtros con Tailwind -->
    <form method="GET" action="" class="mb-5">
        <div class="flex justify-center gap-4 mb-4">
            <div class="w-full md:w-1/3">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="periodo" id="periodo">
                    <option value="">Seleccione un período</option>
                    @foreach ($periodos as $periodo)
                    <option value="{{ $periodo->id }}" {{ request('periodo') == $periodo->id ? 'selected' : '' }}>
                        {{ $periodo->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="grado" id="grado">
                    <option value="">Seleccione un grado</option>
                    @foreach ($grados as $grado)
                    <option value="{{ $grado }}" {{ request('grado') == $grado ? 'selected' : '' }}>{{ $grado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="seccion" id="seccion">
                    <option value="">Seleccione una sección</option>
                    @foreach ($secciones as $seccion)
                    <option value="{{ $seccion }}" {{ request('seccion') == $seccion ? 'selected' : '' }}>{{ $seccion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="w-full md:w-auto px-6 py-3 text-lg font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Filtrar</button>
    </form>

    <!-- Cards de información con Tailwind -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
        <div class="card p-4 bg-white shadow-lg rounded-lg flex flex-col items-center">
            <h5 class="text-xl font-bold text-gray-800">Total de Ingresos</h5>
            <p class="text-2xl font-semibold text-green-600">S/. {{ number_format($totalIngresos, 2) }}</p>
        </div>
        <div class="card p-4 bg-white shadow-lg rounded-lg flex flex-col items-center">
            <h5 class="text-xl font-bold text-gray-800">Deudas Totales</h5>
            <p class="text-2xl font-semibold text-red-600">S/. {{ number_format($deudasTotales, 2) }}</p>
        </div>
        <div class="card p-4 bg-white shadow-lg rounded-lg flex flex-col items-center">
            <h5 class="text-xl font-bold text-gray-800">Porcentaje de Morosidad</h5>
            <p class="text-2xl font-semibold text-yellow-600">{{ number_format($porcentajeMorosidad, 2) }}%</p>
        </div>
    </div>

    <!-- Botones alineados horizontalmente -->
    <div class="flex justify-center gap-4 mt-4">
        <a href="{{route('dashboard.ingresos')}}" class="px-6 py-3 text-lg font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Ver Ingresos</a>
        <a href="{{route('dashboard.deudas')}}" class="px-6 py-3 text-lg font-bold text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">Ver Deudas</a>
        <a href="{{ route('director.reportes.index')}}" class="px-6 py-3 text-lg font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Ver Reportes</a>
    </div>
</div>
@endsection
