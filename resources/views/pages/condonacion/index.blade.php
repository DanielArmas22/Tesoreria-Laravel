@extends('layouts.layoutA')

@section('titulo', 'Registrar Condonaciones')

@php
    $buttonClass =
        'rounded-md bg-primary px-6 py-2 text-sm font-medium uppercase text-white shadow-md transition-transform duration-200 ease-in-out hover:bg-primary-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-accent-300 active:bg-primary-600 active:shadow-lg';
@endphp

@section('contenido')
    @if (Auth::user()->hasRole('tesorero'))
        <div class="w-full flex flex-col justify-center items-center px-10">
            @if (session('datos'))
                <x-alert :mensaje="session('datos')" tipo="success" />
            @endif
            @if (session('mensaje'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('mensaje') }}</p>
                </div>
            @endif
            {{-- <div class="my-16"></div> --}}
            <section class="flex gap-4 items-center justify-between w-full">
                <article class="rounded-xl shadow-lg p-8 w-1/3">
                    <h3 class="text-center font-semibold">Filtros de Busqueda</h3>
                    <form class="form-inline my-2 my-lg-0 flex flex-col gap-2" method="GET">
                        <x-textField label="id de condonacion" placeholder="id de condonacion" name="idCondonacion"
                            valor="{{ $idCondonacion }}" />
                        <x-textField label="codigo de Estudiante" placeholder="codigo de Estudiante" name="codigoEstudiante"
                            valor="{{ $idEstudiante }}" />
                        <x-textField label="DNI de Estudiante" placeholder="DNI de Estudiante" name="dniEstudiante"
                            valor="{{ $dniEstudiante }}" />
                        <x-textField label="Nombre Estudiante" placeholder="Nombre Estudiante"
                            name="busquedaNombreEstudiante" valor="{{ $busquedaNombreEstudiante }}" />
                        <x-textField label="Apellido Estudiante" placeholder="Apellido Estudiante"
                            name="busquedaApellidoEstudiante" valor="{{ $busquedaApellidoEstudiante }}" />
                        <div class="space-y-2">
                            <h3>Monto</h3>
                            <div class="flex justify-center space-x-6">
                                <div class="border-b border-blue-500">
                                    <input name="montoMenor"
                                        class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none border-b border-blue-500"
                                        type="search" placeholder="0.0000" aria-label="Search" value="{{ $montoMenor }}">
                                </div>
                                <div class="border-b border-blue-500">
                                    <input name="montoMayor"
                                        class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none border-b border-blue-500"
                                        type="search" placeholder="999.0000" aria-label="Search"
                                        value="{{ $montoMayor }}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="flex gap-4 justify-center">
                            <button
                                class="rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                type="submit">Buscar</button>
                            <a href="{{ route('condonacion.index') }}"
                                class="rounded bg-success px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-success-accent-300 hover:shadow-success-2 focus:bg-success-accent-300 focus:shadow-success-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-success-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </article>
                <article class="w-2/3">

                    <h3 class="text-2xl border-b-[1px]">Solicitudes Condonaciones Pendientes</h3>
                    <br>
                    <div class="">
                        <x-table :cabeceras="['Codigo', 'DNI', 'Estudiante', 'Monto', 'Fecha']" :datos="$datos" :atributos="['idCondonacion', 'dni', 'nombre_completo', 'total_monto', 'fecha']" ruta="condonacion.edit"
                            id="idCondonacion" />
                        <br>
                        <article class="flex justify-center">
                            {{-- <x-button label="Nueva Condonacion" ruta="condonacion.create" color="primary" /> --}}
                        </article>
                    </div>
                    <div class="mt-6 flex justify-center">
                        <a class="px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded shadow-md"
                            href="{{ route('generarCondonacionGeneral', [
                                'estado' => 'pendientes',
                                'idCondonacion' => $idCondonacion,
                                'idEstudiante' => $idEstudiante,
                                'dniEstudiante' => $dniEstudiante,
                                'montoMenor' => $montoMenor,
                                'montoMayor' => $montoMayor,
                                'busquedaNombreEstudiante' => $busquedaNombreEstudiante,
                                'busquedaApellidoEstudiante' => $busquedaApellidoEstudiante,
                                'generarPDF' => true,
                            ]) }}">
                            Reporte Solicitudes Pendientes
                        </a>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-4 w-full flex justify-end">
                        <a href="{{ route('condonacion.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            <i class="fas fa-plus"></i> Nueva Condonacion
                        </a>
                    </div>
                </article>
            </section>
            <br>
            <div class="flex flex-cols-2 gap-6 mt-6">
                <!-- Tabla de Solicitudes Aceptadas -->
                <div class="w-full lg:w-1/2 bg-green-100 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 text-green-800">Listado de Solicitudes Aceptadas</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse border border-gray-200">
                            <thead class="bg-green-200">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Código
                                        Condonacion</th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">DNI</th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">
                                        Estudiante</th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Monto
                                    </th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Fecha
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($datosAceptados) <= 0)
                                    <tr>
                                        <td colspan="5" class="text-center text-gray-500 py-4">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($datosAceptados as $dato)
                                        <tr class="hover:bg-green-50">
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->idCondonacion }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->dni }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->nombre_completo }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->total_monto }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->fecha }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $datosAceptados->links() }}
                        </div>
                        <div class="mt-6 flex justify-center">
                            <a class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded shadow-md"
                                href="{{ route('generarCondonacionGeneral', [
                                    'estado' => 'aceptadas',
                                    'idCondonacion' => $idCondonacion,
                                    'idEstudiante' => $idEstudiante,
                                    'dniEstudiante' => $dniEstudiante,
                                    'montoMenor' => $montoMenor,
                                    'montoMayor' => $montoMayor,
                                    'busquedaNombreEstudiante' => $busquedaNombreEstudiante,
                                    'busquedaApellidoEstudiante' => $busquedaApellidoEstudiante,
                                    'generarPDF' => true,
                                ]) }}">
                                Reporte Solicitudes Aceptadas
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Solicitudes Rechazadas -->
                <div class="w-full lg:w-1/2 bg-red-100 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 text-red-800">Listado de Solicitudes Rechazadas</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse border border-gray-200">
                            <thead class="bg-red-200">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Código
                                        Condonacion</th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">DNI</th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">
                                        Estudiante</th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Monto
                                    </th>
                                    <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Fecha
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($datosRechazados) <= 0)
                                    <tr>
                                        <td colspan="5" class="text-center text-gray-500 py-4">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($datosRechazados as $dato)
                                        <tr class="hover:bg-red-50">
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->idCondonacion }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->dni }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->nombre_completo }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->total_monto }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">
                                                {{ $dato->fecha }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $datosRechazados->links() }}
                        </div>
                        <div class="mt-6 flex justify-center">
                            <a class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded shadow-md"
                                href="{{ route('generarCondonacionGeneral', [
                                    'estado' => 'rechazadas',
                                    'idCondonacion' => $idCondonacion,
                                    'idEstudiante' => $idEstudiante,
                                    'dniEstudiante' => $dniEstudiante,
                                    'montoMenor' => $montoMenor,
                                    'montoMayor' => $montoMayor,
                                    'busquedaNombreEstudiante' => $busquedaNombreEstudiante,
                                    'busquedaApellidoEstudiante' => $busquedaApellidoEstudiante,
                                    'generarPDF' => true,
                                ]) }}">
                                Reporte Solicitudes Rechazadas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="w-full flex flex-col justify-center items-center px-6 py-8">
            @if (session('datos'))
                <x-alert :mensaje="session('datos')" tipo="success" />
            @endif
            @if (session('mensaje'))
                <div class="w-full mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md"
                    role="alert">
                    <p>{{ session('mensaje') }}</p>
                </div>
            @endif
            <h2 class="text-3xl font-bold mb-4 text-gray-800 w-full text-start">Condonaciones</h2>
            <section class="flex flex-col lg:flex-row gap-6 w-max px-7">
                <!-- Filtros de Búsqueda -->
                <article class="rounded-lg shadow-md p-6 bg-white flex-1">
                    <h3 class="text-lg font-semibold text-center mb-4">Filtros de Búsqueda</h3>
                    <form class="flex flex-col gap-4" method="GET">
                        <!-- ID de Condonación -->
                        <x-textField label="ID de Condonación" placeholder="ID de Condonación" name="idCondonacion"
                            valor="{{ $idCondonacion }}" class="w-full" />

                        <!-- Estudiantes -->
                        <div>
                            <h4 class="font-medium mb-2">Estudiantes</h4>
                            @if (isset($estudiantes))
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    id="busquedaEscala" name="codigoEstudiante" aria-label="Seleccionar Estudiante">
                                    <option value="" {{ $idEstudiante == 'ninguno' ? 'selected' : '' }}>Seleccionar
                                        Estudiante</option>
                                    @foreach ($estudiantes as $estudiante)
                                        <option value="{{ $estudiante->estudiante->idEstudiante }}"
                                            {{ $idEstudiante == $estudiante->estudiante->idEstudiante ? 'selected' : '' }}>
                                            {{ $estudiante->estudiante->DNI }} - {{ $estudiante->estudiante->nombre }}
                                            {{ $estudiante->estudiante->apellidoP }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="space-y-2">
                                    <x-textField label="Código de Estudiante" placeholder="Código de Estudiante"
                                        name="codigoEstudiante" valor="{{ $idEstudiante }}" class="w-full" />
                                    <x-textField label="DNI de Estudiante" placeholder="DNI de Estudiante"
                                        name="dniEstudiante" valor="{{ $dniEstudiante }}" class="w-full" />
                                </div>
                            @endif
                        </div>

                        <!-- Monto -->
                        <div>
                            <h4 class="font-medium mb-2">Monto</h4>
                            <div class="flex space-x-4">
                                <input name="montoMenor"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    type="number" step="0.01" placeholder="Desde" aria-label="Monto Desde"
                                    value="{{ $montoMenor }}">
                                <input name="montoMayor"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    type="number" step="0.01" placeholder="Hasta" aria-label="Monto Hasta"
                                    value="{{ $montoMayor }}">
                            </div>
                        </div>

                        <!-- Estados -->
                        <select
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                            id="busquedaEscala" name="estadoCondonacion" aria-label="Seleccionar Estudiante">
                            <option value="" {{ $estadoCondonacion == 'ninguno' ? 'selected' : '' }}>Seleccionar
                                Estado</option>
                            @foreach ($estados as $key => $estado)
                                <option value="{{ $key }}"
                                    {{ isset($estadoCondonacion) && $estadoCondonacion == $key ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                        <!-- Botones de Acción -->
                        <div class="flex justify-center space-x-4 mt-4">
                            <button class="{{ $buttonClass }}" type="submit">
                                Buscar
                            </button>
                            <a href="{{ route('condonacion.index') }}"
                                class="rounded-md bg-success px-6 py-2 text-sm font-medium uppercase text-white shadow-md transition-transform duration-200 ease-in-out hover:bg-success-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-success-accent-300 active:bg-success-600 active:shadow-lg">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </article>

                <!-- Tabla de Condonaciones -->
                <article class="rounded-lg shadow-md p-6 bg-white flex-2">
                    <h3 class="text-xl font-semibold border-b pb-2 mb-4">Condonaciones Activas</h3>
                    <div class="overflow-x-auto">
                        <x-table :cabeceras="['Código', 'DNI', 'Estudiante', 'Monto', 'Fecha', 'Estado']" :datos="$datos" :atributos="[
                            'idCondonacion',
                            'dni',
                            'nombre_completo',
                            'total_monto',
                            'fecha',
                            'estadoCondonacion',
                        ]" ruta="condonacion.edit"
                            id="idCondonacion" />
                    </div>
                    @if (!Auth::user()->hasRole('director'))
                        <div class="flex justify-center mt-4">
                            {{-- Botón adicional si es necesario --}}
                            {{-- <x-button label="Nueva Condonación" ruta="condonacion.create" color="primary" /> --}}
                        </div>
                    @endif
                </article>
            </section>

            @if (!Auth::user()->hasRole('director'))
                <div class="w-full max-w-4xl mt-6 bg-white rounded-lg shadow-md p-4 flex justify-center">
                    <a href="{{ route('condonacion.create') }}"
                        class="inline-flex items-center px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        @if (Auth::user()->hasRole('padre'))
                            Solicitar
                        @endif
                        Condonación
                    </a>
                </div>
            @endif
        </div>
        {{-- kelita, mira wsp --}}
    @endif
@endsection
