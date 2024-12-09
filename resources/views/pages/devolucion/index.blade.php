@extends('layouts.layoutA')

@section('titulo', 'Listar Devolución')

@php
    $buttonClass =
        'rounded-md bg-primary px-6 py-2 text-sm font-medium uppercase text-white shadow-md transition-transform duration-200 ease-in-out hover:bg-primary-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-accent-300 active:bg-primary-600 active:shadow-lg';
@endphp

@section('contenido')


    @if (Auth::user()->rol == 'cajero')
        <section class="flex flex-col items-center space-y-6 p-6 mx-auto">
            @if (session('mensaje'))
                <x-alert :mensaje="session('mensaje')" tipo="success" />
            @endif

            <form class="w-full max-w-4xl p-6 bg-white rounded-md shadow-md" method="GET">
                <div class="space-y-6">
                    <!-- Campos de Búsqueda -->
                    <div class="space-y-4">
                        <div class="flex space-x-4">
                            <input name="buscarxEstudiante" id="buscarxEstudiante" value="{{ $buscarxEstudiante }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="search" placeholder="ID Estudiante" aria-label="Buscar por ID Estudiante">
                            <input name="busquedaxnroOperacion" id="busquedaxnroOperacion"
                                value="{{ $busquedaxnroOperacion }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="search" placeholder="NRO Operación" aria-label="Buscar por NRO Operación">
                        </div>

                        <div class="flex space-x-4">
                            <input name="nombreEstudiante" id="nombreEstudiante" value="{{ $nombreEstudiante }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="search" placeholder="Nombre Estudiante" aria-label="Buscar por Nombre">
                            <input name="apellidoPaterno" id="apellidoPaterno" value="{{ $apellidoPaterno }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="search" placeholder="Apellido Paterno" aria-label="Buscar por Apellido Paterno">
                        </div>

                        <div class="flex space-x-4">
                            <input name="menorPago" id="menorPago" value="{{ $menorPago }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="number" placeholder="Pago desde" aria-label="Pago desde">
                            <input name="mayorPago" id="mayorPago" value="{{ $mayorPago }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="number" placeholder="Pago hasta" aria-label="Pago hasta">
                        </div>

                        <div class="flex space-x-4">
                            <div class="relative flex-1">
                                <input id="datepicker" name="fechaInicio" value="{{ $fechaInicio }}" datepicker
                                    datepicker-format="yyyy-mm-dd" type="text"
                                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    placeholder="Fecha Inicio">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative flex-1">
                                <input id="datepicker-format" name="fechaFin" value="{{ $fechaFin }}" datepicker
                                    datepicker-format="yyyy-mm-dd" type="text"
                                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    placeholder="Fecha Fin">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex justify-end space-x-4">
                        <button
                            class="flex items-center justify-center px-6 py-2 bg-primary text-white rounded-md shadow-md hover:bg-primary-accent-300 hover:shadow-lg focus:ring-2 focus:ring-primary-accent-300 active:bg-primary-600"
                            type="submit" id="search-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                            </svg>
                            Filtrar
                        </button>
                        <a href="{{ route('devolucion.index') }}"
                            class="flex items-center justify-center px-6 py-2 bg-black text-white rounded-md shadow-md hover:bg-secondary-900 hover:shadow-lg focus:ring-2 focus:ring-secondary-900 active:bg-primary-600">
                            <i class="text-base fa-solid fa-filter-circle-xmark mr-2"></i>
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <article class="w-full max-w-4xl bg-white rounded-md shadow-md p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID Devolución</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nro Operación</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pago Total</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID Estudiante</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estudiante</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Observación</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if (count($datos) <= 0)
                                <tr>
                                    <td class="px-6 py-4 text-center" colspan="8">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($datos as $dato)
                                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->idDevolucion }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->nroOperacion }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->totalPago }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->idEstudiante }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->nombre }}
                                            {{ $dato->apellidoP }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->fechaDevolucion }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->observacion }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('devolucion.datos') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="idDevolucion"
                                                    value="{{ $dato->idDevolucion }}">
                                                <input type="hidden" name="nroOperacion"
                                                    value="{{ $dato->nroOperacion }}">
                                                <input type="hidden" name="idEstudiante"
                                                    value="{{ $dato->idEstudiante }}">
                                                <input type="hidden" name="fechaDevolucion"
                                                    value="{{ $dato->fechaDevolucion }}">
                                                <input type="hidden" name="observacion"
                                                    value="{{ $dato->observacion }}">
                                                <button type="submit"
                                                    class="px-4 py-2 bg-primary text-white rounded-md shadow-md hover:bg-primary-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-accent-300 active:bg-primary-600">
                                                    Detalle
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- Paginación -->
                <div class="mt-4">
                    {{ $datos->links() }}
                </div>
                <!-- Generar Reporte -->
                <div class="flex justify-center mt-6">
                    <form action="{{ route('generarDevolucion') }}" method="POST">
                        @csrf
                        <input type="hidden" name="datos" value="{{ json_encode($datos->items()) }}">
                        <button type="submit" class="{{ $buttonClass }}">
                            <i class="fa-solid fa-file-pdf pr-2"></i>
                            Generar Reporte
                        </button>
                    </form>
                </div>
            </article>
        </section>
    @else
        <section class="flex flex-col items-center space-y-6 p-6 mx-auto">
            @if (session('mensaje'))
                <x-alert :mensaje="session('mensaje')" tipo="success" />
            @endif

            <form class="w-full max-w-4xl p-6 bg-white rounded-md shadow-md" method="GET">
                <div class="space-y-6">
                    <!-- Campos de Búsqueda -->
                    <div class="space-y-4">
                        <div class="flex space-x-4">
                            @if (isset($estudiantes))
                                <div class="flex-1">
                                    <select
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                        id="busquedaEscala" name="buscarxEstudiante" aria-label="Seleccionar Estudiante">
                                        <option value="" {{ $buscarxEstudiante == 'ninguno' ? 'selected' : '' }}>
                                            Estudiante</option>
                                        @foreach ($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->estudiante->idEstudiante }}"
                                                {{ $buscarxEstudiante == $estudiante->estudiante->idEstudiante ? 'selected' : '' }}>
                                                {{ $estudiante->estudiante->DNI }} - {{ $estudiante->estudiante->nombre }}
                                                {{ $estudiante->estudiante->apellidoP }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input name="buscarxEstudiante" id="buscarxEstudiante" value="{{ $buscarxEstudiante }}"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    type="search" placeholder="ID Estudiante" aria-label="Buscar por ID Estudiante">
                            @endif
                            <input name="busquedaxnroOperacion" id="busquedaxnroOperacion"
                                value="{{ $busquedaxnroOperacion }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="search" placeholder="NRO Operación" aria-label="Buscar por NRO Operación">
                        </div>

                        <div class="flex space-x-4">
                            <div class="relative flex-1">
                                <input id="datepicker" name="fechaInicio" value="{{ $fechaInicio }}" datepicker
                                    datepicker-format="yyyy-mm-dd" type="text"
                                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    placeholder="Fecha Inicio">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative flex-1">
                                <input id="datepicker-format" name="fechaFin" value="{{ $fechaFin }}" datepicker
                                    datepicker-format="yyyy-mm-dd" type="text"
                                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                    placeholder="Fecha Fin">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                        id="busquedaEscala" name="estadoDevolucion" aria-label="Seleccionar Estudiante">
                        <option value="" {{ $estadoDevolucion == 'ninguno' ? 'selected' : '' }}>Seleccionar
                            Estado</option>
                        @foreach ($estados as $key => $estado)
                            <option value="{{ $key }}"
                                {{ isset($estadoDevolucion) && $estadoDevolucion == $key ? 'selected' : '' }}>
                                {{ $estado }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Botones de Acción -->
                    <div class="flex justify-end space-x-4">
                        <button
                            class="flex items-center justify-center px-6 py-2 bg-primary text-white rounded-md shadow-md hover:bg-primary-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-accent-300 active:bg-primary-600"
                            type="submit" id="search-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                            </svg>
                            Filtrar
                        </button>
                        <x-boton label="Limpiar" color="success" ruta="devolucion.index" class="px-6 py-2" />
                    </div>
                </div>
            </form>

            <article class="w-full max-w-4xl bg-white rounded-md shadow-md p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID Devolución</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nro Operación</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pago Total</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID Estudiante</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estudiante</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Observación</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if (count($datos) <= 0)
                                <tr>
                                    <td class="px-6 py-4 text-center" colspan="9">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($datos as $dato)
                                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->idDevolucion }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->nroOperacion }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->totalPago }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->idEstudiante }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->nombre }}
                                            {{ $dato->apellidoP }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->fechaDevolucion }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dato->observacion }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            @switch($dato->estadoDevolucion)
                                                @case(1)
                                                    <span class="text-yellow-600">Solicitado</span>
                                                @break

                                                @case(2)
                                                    <span class="text-blue-600">En proceso</span>
                                                @break

                                                @case(3)
                                                    <span class="text-green-600">Devuelto</span>
                                                @break

                                                @case(4)
                                                    <span class="text-gray-600">Registrado</span>
                                                @break

                                                @default
                                                    <span class="text-gray-400">Desconocido</span>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('devolucion.datos') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="idDevolucion"
                                                    value="{{ $dato->idDevolucion }}">
                                                <input type="hidden" name="nroOperacion"
                                                    value="{{ $dato->nroOperacion }}">
                                                <input type="hidden" name="idEstudiante"
                                                    value="{{ $dato->idEstudiante }}">
                                                <input type="hidden" name="fechaDevolucion"
                                                    value="{{ $dato->fechaDevolucion }}">
                                                <input type="hidden" name="observacion"
                                                    value="{{ $dato->observacion }}">
                                                <button type="submit"
                                                    class="px-4 py-2 bg-primary text-white rounded-md shadow-md hover:bg-primary-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-accent-300 active:bg-primary-600">
                                                    Ver
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- Paginación -->
                <div class="mt-4">
                    {{ $datos->links() }}
                </div>
                <!-- Generar Reporte -->
                <div class="flex justify-center mt-6">
                    <form action="{{ route('generarDevolucion') }}" method="POST">
                        @csrf
                        <input type="hidden" name="datos" value="{{ json_encode($datos->items()) }}">
                        <button type="submit" class="{{ $buttonClass }}">
                            <i class="fa-solid fa-file-pdf pr-2"></i>
                            Generar Reporte
                        </button>
                    </form>
                </div>
                <!-- Botón para Padres -->
                @if (Auth::user()->hasRole('padre'))
                    <div class="flex justify-center mt-6">
                        <a href="{{ route('devolucion.create') }}" class="{{ $buttonClass }}">
                            Solicitar Devolución
                        </a>
                    </div>
                @endif
            </article>
        </section>
    @endif

@endsection
