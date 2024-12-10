@extends('layouts.layoutA')
@section('titulo', 'Dashboard')
@section('contenido')
<div class="container py-5">
    <h1 class="text-center mb-5 text-4xl font-bold text-gray-800">Panel de Reportes</h1>

    <!-- Formulario de Filtros -->
    <form method="GET" action="" class="mb-5">
        <div class="flex flex-col md:flex-row justify-center gap-4 mb-4">
            <!-- Filtro Período -->
            <div class="w-full md:w-1/4">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="periodo" id="periodo">
                    <option value="">Seleccione un período</option>
                    @foreach ($periodos as $periodoItem)
                    <option value="{{ $periodoItem->id }}" {{ request('periodo') == $periodoItem->id ? 'selected' : '' }}>
                        {{ $periodoItem->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- Filtro Mes -->
            <div class="w-full md:w-1/4">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="mes" id="mes">
                    <option value="">Seleccione un mes</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
    
            <div class="w-full md:w-1/4">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="grado" id="grado">
                    <option value="">Seleccione un grado</option>
                    @foreach ($grados as $gradoItem)
                    <option value="{{ $gradoItem }}" {{ request('grado') == $gradoItem ? 'selected' : '' }}>{{ $gradoItem }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Filtro Sección -->
            <div class="w-full md:w-1/4">
                <select class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="seccion" id="seccion">
                    <option value="">Seleccione una sección</option>
                    @foreach ($secciones as $seccionItem)
                    <option value="{{ $seccionItem }}" {{ request('seccion') == $seccionItem ? 'selected' : '' }}>{{ $seccionItem }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Botón de Filtrar -->
        <div class="flex justify-center">
            <button type="submit" class="px-6 py-3 text-lg font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Aplicar Filtros</button>
        </div>
    </form>

    <!-- Lista de Botones para Cada Reporte -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Resumen de Ingresos -->
        <div>
            <a href="{{ route('director.reportes.resumen-ingresos', request()->query()) }}" class="block w-full px-6 py-4 text-center text-white bg-green-500 rounded-lg hover:bg-green-600">Resumen de Ingresos</a>
            <a href="{{ route('director.reportes.resumen-ingresos.pdf', request()->query()) }}" class="block mt-2 text-center text-sm text-gray-700 hover:underline">Descargar PDF</a>
        </div>
        <!-- Resumen de Deudas -->
        <div>
            <a href="{{ route('director.reportes.resumen-deudas', request()->query()) }}" class="block w-full px-6 py-4 text-center text-white bg-red-500 rounded-lg hover:bg-red-600">Resumen de Deudas</a>
            <a href="{{ route('director.reportes.resumen-deudas.pdf', request()->query()) }}" class="block mt-2 text-center text-sm text-gray-700 hover:underline">Descargar PDF</a>
        </div>
        <!-- Ingresos vs Deudas -->
        <div>
            <a href="{{ route('director.reportes.ingresos-vs-deudas', request()->query()) }}" class="block w-full px-6 py-4 text-center text-white bg-purple-500 rounded-lg hover:bg-purple-600">Ingresos vs Deudas</a>
        </div>
        <!-- Reporte de Ingresos Detallados -->
        <div>
            <a href="{{ route('director.reportes.ingresos-detallados', request()->query()) }}" class="block w-full px-6 py-4 text-center text-white bg-indigo-500 rounded-lg hover:bg-indigo-600">Reporte de Ingresos Detallados</a>
            <a href="{{ route('director.reportes.ingresos-detallados.pdf', request()->query()) }}" class="block mt-2 text-center text-sm text-gray-700 hover:underline">Descargar PDF</a>
        </div>
        <!-- Reporte de Deudores -->
        <div>
            <a href="{{ route('director.reportes.deudores', request()->query()) }}" class="block w-full px-6 py-4 text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">Reporte de Deudores</a>
            <a href="{{ route('director.reportes.deudores.pdf', request()->query()) }}" class="block mt-2 text-center text-sm text-gray-700 hover:underline">Descargar PDF</a>
        </div>
        <!-- Inscripción por Grado y Sección -->
        <div>
            <a href="{{ route('director.reportes.inscripcion-grado-seccion', request()->query()) }}" class="block w-full px-6 py-4 text-center text-white bg-pink-500 rounded-lg hover:bg-pink-600">Inscripción por Grado y Sección</a>
            <a href="{{ route('director.reportes.inscripcion-grado-seccion.pdf', request()->query()) }}" class="block mt-2 text-center text-sm text-gray-700 hover:underline">Descargar PDF</a>
        </div>
    </div>
</div>
@endsection
