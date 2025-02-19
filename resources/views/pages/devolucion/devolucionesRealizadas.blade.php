@extends('layouts.layoutA')
@section('titulo', 'Listar Devolucion')
@php
    $buttonClass =
        'rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
@endphp
@section('contenido')

    <section class=" space-x-3 flex flex-col items-center mx-auto">
        <h3 class="text-center pb-4 font-semibold text-xl text-cyan-900">Devoluciones Realizadas</h3>
        @if (session('mensaje'))
            <x-alert :mensaje="session('mensaje')" tipo="success" />
        @endif
        <form class="flex flex-col w-full mb-4 p-6 shadow-light-2" method="GET">
            <div class="flex flex-col items-center justify-center border-b space-y-6 border-blue-500 py-2 w-full">
                <div class="flex space-x-4 w-full">
                    <input name="buscarxEstudiante" id="buscarxEstudiante" value="{{ $buscarxEstudiante }}"
                        class="appearance-none bg-transparent w-full text-gray-700 py-1 px-2 border-collapse leading-tight focus:outline-none focus:border-b-0 border-b border-l-0 border-r-0 border-t-0 border-blue-500"
                        type="search" placeholder="ID Estudiante" aria-label="Search">
                    <input name="busquedaxnroOperacion" id="busquedaxnroOperacion" value="{{ $busquedaxnroOperacion }}"
                        class="appearance-none bg-transparent w-full text-gray-700 py-1 px-2 leading-tight focus:outline-none focus:border-b-0 border-b border-l-0 border-r-0 border-t-0 border-blue-500"
                        type="search" placeholder="NRO Operacion" aria-label="Search">
                </div>

                <div class="flex space-x-4 w-full">
                    <input name="nombreEstudiante" id="nombreEstudiante" value="{{ $nombreEstudiante }}"
                        class="appearance-none bg-transparent w-full text-gray-700 py-1 px-2 leading-tight focus:outline-none focus:border-b-0 border-b border-l-0 border-r-0 border-t-0 border-blue-500"
                        type="search" placeholder="Nombre Estudiante" aria-label="Search">
                    <input name="apellidoPaterno" id="apellidoPaterno" value="{{ $apellidoPaterno }}"
                        class="appearance-none bg-transparent w-full text-gray-700 py-1 px-2 leading-tight focus:outline-none focus:border-b-0 border-b border-l-0 border-r-0 border-t-0 border-blue-500"
                        type="search" placeholder="Apellido Paterno" aria-label="Search">
                </div>

                <div class="flex space-x-4 w-full">
                    <input name="menorPago" id="menorPago" value="{{ $menorPago }}"
                        class="appearance-none bg-transparent w-full text-gray-700 py-1 px-2 leading-tight focus:outline-none focus:border-b-0 border-b border-l-0 border-r-0 border-t-0 border-blue-500"
                        type="search" placeholder="Pago desde" aria-label="Search">
                    <input name="mayorPago" id="mayorPago" value="{{ $mayorPago }}"
                        class="appearance-none bg-transparent w-full text-gray-700 py-1 px-2 leading-tight focus:outline-none focus:border-b-0 border-b border-l-0 border-r-0 border-t-0 border-blue-500"
                        type="search" placeholder="Pago hasta" aria-label="Search">
                </div>

                <div class="flex space-x-10 w-full">
                    <div class="flex flex-col items-start relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="datepicker" name="fechaInicio" value="{{ $fechaInicio }}" datepicker
                            datepicker-format="yyyy-mm-dd" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Fecha Inicio">
                    </div>
                    <div class="flex flex-col items-start relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="datepicker-format" name="fechaFin" value="{{ $fechaFin }}" datepicker
                            datepicker-format="yyyy-mm-dd" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Fecha Fin">
                    </div>
                </div>
                <div class="flex space-x-20">
                    <button
                        class="rounded hover:scale-105 py-2 w-full relative z-[2] -ms-0.5 flex items-center rounded-e bg-primary px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                        type="submit" id="search-button" data-twe-ripple-init data-twe-ripple-color="light">
                        <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                            </svg>
                            <p>Filtrar</p>
                        </span>
                    </button>
                    <a href="{{ route('devolucion.index') }}">
                        <div class="rounded hover:scale-105 py-2 w-full relative z-[2] -ms-0.5 flex items-center rounded-e bg-black px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-secondary-900 hover:shadow-primary-2 focus:bg-secondary-900 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                            id="search-button" data-twe-ripple-init data-twe-ripple-color="light">
                            <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                                <i class="text-base -px-2 fa-solid fa-filter-circle-xmark"></i>
                                <p class="flex justify-center items-center pl-2">LIMPIAR</p>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </form>
        <article class="flex flex-col items-center shadow-light-2 px-14 py-6 gap-4">
            <div class="w-90">
                <table class="table-auto w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">ID Devolucion</th>
                            <th class="px-4 py-2 border-b">Nro Operacion</th>
                            <th class="px-4 py-2 border-b">Pago Total</th>
                            <th class="px-4 py-2 border-b">ID Estudiante</th>
                            <th class="px-4 py-2 border-b">Estudiante</th>
                            <th class="px-4 py-2 border-b">Fecha</th>
                            <th class="px-4 py-2 border-b">Observacion</th>
                            <th class="px-4 py-2 border-b text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @if (count($datos) <= 0)
                            <tr>
                                <td class="border px-4 py-2" colspan="8">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($datos as $dato)
                                <tr class="cursor-pointer hover:bg-gray-200">
                                    <td class="border px-4 py-2">{{ $dato->idDevolucion }}</td>
                                    <td class="border px-4 py-2">{{ $dato->nroOperacion }}</td>
                                    <td class="border px-4 py-2">{{ $dato->totalPago }}</td>
                                    <td class="border px-4 py-2">{{ $dato->idEstudiante }}</td>
                                    <td class="border px-4 py-2">{{ $dato->nombre }} {{ $dato->apellidoP }}</td>
                                    <td class="border px-4 py-2">{{ $dato->fechaDevolucion }} </td>
                                    <td class="border px-4 py-2">{{ $dato->observacion }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <form action="{{ route('devolucion.datosRealizados') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="idDevolucion" value="{{ $dato->idDevolucion }}">
                                            <input type="hidden" name="nroOperacion" value="{{ $dato->nroOperacion }}">
                                            <input type="hidden" name="idEstudiante" value="{{ $dato->idEstudiante }}">
                                            <input type="hidden" name="fechaDevolucion"
                                                value="{{ $dato->fechaDevolucion }}">
                                            <input type="hidden" name="observacion" value="{{ $dato->observacion }}">
                                            <button type="submit" class="{{ $buttonClass }}">ver mas</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{ $datos->links() }}
                <div class="flex justify-center space-x-3 mt-6">
                    <form action="{{ route('generarDevolucion') }}" method="POST">
                        @csrf
                        <input type="hidden" name="datos" value="{{ json_encode($datos->items()) }}">
                        <button type="submit" class="{{ $buttonClass }}">
                            <i class="fa-solid fa-file-pdf pr-1"></i>
                            Generar Reporte
                        </button>
                    </form>
                </div>
            </div>
        </article>

    </section>

@endsection
