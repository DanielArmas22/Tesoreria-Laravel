@extends('layouts.layoutA')
@section('titulo', 'Pagos')
@section('contenido')
    @if (Auth::user()->rol == 'admin' || Auth::user()->rol == 'cajero')
        
        <div class="container mx-auto">
            <div class="bg-gray-50 rounded-lg shadow-sm p-4">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Lista de Pagos</h2>
                @if (Auth::user()->rol != 'cajero')
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                        <a href="{{ route('pago.create') }}">
                            <div class="rounded hover:scale-105 py-2 w-1/5 relative z-[2] -ms-0.5 flex items-center rounded-e bg-primary px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                id="search-button" data-twe-ripple-init data-twe-ripple-color="light">
                                <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                                    <i class="fas fa-plus pt-1 pr-3"></i>
                                    Nuevo Pago
                                </span>
                            </div>
                        </a>
                    </div>
                @endif
                @if (session('datos'))
                    <div id="mensaje" class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
                        {{ session('datos') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="grid grid-cols-4 gap-4">
                    <div class="overflow-x-auto col-span-3">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Código</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Periodo</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Id Estudiante</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Opcion</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if (count($pagos) <= 0)
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                            No
                                            hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($pagos as $itempagos)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                {{ $itempagos->nroOperacion }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->fechaPago }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->totalMonto }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->periodo }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->idEstudiante }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->apellidoP }} {{ $itempagos->apellidoM }}
                                                {{ $itempagos->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <a
                                                    href="{{ route('pago.showBoleta', ['nroOperacion' => $itempagos->nroOperacion]) }}">
                                                    <div class="rounded hover:scale-105 py-2 w-full relative z-[2] -ms-0.5 flex items-center rounded-e bg-primary px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                                        id="search-button" data-twe-ripple-init
                                                        data-twe-ripple-color="light">
                                                        <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                                                            <i class="fa-solid fa-file-lines pr-3"></i> Boleta
                                                        </span>
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="flex justify-start items-end">
                            <p class="px-6 py-4 whitespace-nowrap text-xl text-gray-900 font-semibold">
                                TOTAL: {{ $totalPago }}
                            </p>
                        </div>
                        <div class="mt-4">
                            {{ $pagos->links() }}
                        </div>
                    </div>
                    <div class="">
                        <form class="form-inline my-2 my-lg-0" method="GET">
                            <div class="flex flex-col space-y-4 px-6 py-4 shadow-lg">
                                <h2 class="text-lg font-semibold">Filtros de Búsqueda</h2>

                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Id Estudiante" aria-label="Código" id="buscarCodigo"
                                        name="buscarCodigo" value="{{ $buscarCodigo }}" aria-describedby="search-button" />
                                </div>

                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Codigo" aria-label="Periodo" id="nroOperacion" name="nroOperacion"
                                        value="{{ $nroOperacion }}" aria-describedby="search-button" />
                                </div>

                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Nombre Estudiante" aria-label="Periodo" id="nombreEstudiante"
                                        name="nombreEstudiante" value="{{ $nombreEstudiante }}"
                                        aria-describedby="search-button" />
                                </div>

                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Apellido Paterno" aria-label="Periodo" id="apellidoPaterno"
                                        name="apellidoPaterno" value="{{ $apellidoPaterno }}"
                                        aria-describedby="search-button" />
                                </div>

                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Apellido Materno" aria-label="Periodo" id="apellidoMaterno"
                                        name="apellidoMaterno" value="{{ $apellidoMaterno }}"
                                        aria-describedby="search-button" />
                                </div>

                                <div class="">
                                    <p>Fecha</p>
                                    <div class="flex flex-col items-start relative max-w-sm">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker" name="fechaInicio" value="{{ $fechaInicio }}" datepicker
                                            datepicker-format="yyyy-mm-dd" type="text"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Fecha Inicio">
                                    </div>
                                    <br>
                                    <div class="flex flex-col items-start relative max-w-sm">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker-format" name="fechaFin" value="{{ $fechaFin }}"
                                            datepicker datepicker-format="yyyy-mm-dd" type="text"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Fecha Fin">
                                    </div>

                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        class="rounded hover:scale-105 py-2 w-full relative z-[2] -ms-0.5 flex items-center rounded-e bg-primary px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                        type="submit" id="search-button" data-twe-ripple-init
                                        data-twe-ripple-color="light">
                                        <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                                            </svg>
                                            <p>Filtrar</p>
                                        </span>
                                    </button>
                                    <a href="{{ route('pago.index') }}">
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
                    </div>
                </div>
                <div class="lg:flex-row flex flex-col justify-center gap-4">
                    <a class="rounded flex items-center justify-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out focus:outline-none focus:ring-0 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2"
                        href="{{ route('generarPago', ['buscarCodigo' => $buscarCodigo, 'nroOperacion' => $nroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin, 'generarPDF' => true]) }}">
                        Reporte de Pagos</a>

                </div>
            </div>
        </div>
    @else
        <div class="container mx-auto">
            @if (Auth::user()->hasRole('padre'))
            @endif

            <div class="bg-gray-50 rounded-lg shadow-sm p-4">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Listado de Pagos</h2>
                <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                    <a href="{{ route('pago.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <i class="fas fa-plus"></i> Nuevo Pago
                    </a>
                </div>
                @if (session('datos'))
                    <div id="mensaje" class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
                        {{ session('datos') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="grid grid-cols-4 gap-4">
                    <div class="overflow-x-auto col-span-3">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Código</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Periodo</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Id Estudiante</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Opcion</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if (count($pagos) <= 0)
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                            No
                                            hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($pagos as $itempagos)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                {{ $itempagos->nroOperacion }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->fechaPago }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->totalMonto }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->periodo }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->idEstudiante }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $itempagos->apellidoP }} {{ $itempagos->apellidoM }}
                                                {{ $itempagos->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <a href="{{ route('pago.showBoleta', ['nroOperacion' => $itempagos->nroOperacion]) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                                    <i class="fas fa-plus"></i> Boleta
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="flex justify-start items-end">
                            <p class="px-6 py-4 whitespace-nowrap text-xl text-gray-900 font-semibold">
                                TOTAL: {{ $totalPago }}
                            </p>
                        </div>
                        <div class="mt-4">
                            {{ $pagos->links() }}
                        </div>
                    </div>
                    <div>
                        <form class="form-inline my-2 my-lg-0" method="GET">
                            <div class="flex flex-col space-y-4 px-6 border-r-2">
                                <h2 class="text-lg font-semibold">Filtros de Búsqueda</h2>

                                @if (isset($estudiantes))
                                    <h3 class="font-semibold">Estudiantes</h3>
                                    <div class="w-max">
                                        <select
                                            class="block w-max rounded border border-gray-300 bg-white p-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm"
                                            aria-label="Seleccionar Escala" id="busquedaEscala" name="buscarCodigo">
                                            <option value="" {{ $buscarCodigo == 'ninguno' ? 'Selected' : '' }}>
                                                Estudiante
                                            </option>
                                            @foreach ($estudiantes as $estudiante)
                                                <option value="{{ $estudiante->estudiante->idEstudiante }}"
                                                    {{ $buscarCodigo == $estudiante->estudiante->idEstudiante ? 'Selected' : '' }}>
                                                    {{ $estudiante->estudiante->DNI }} -
                                                    {{ $estudiante->estudiante->nombre }}
                                                    {{ $estudiante->estudiante->apellidoP }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="flex flex-col items-start">
                                        <input type="search"
                                            class="peer block w-full rounded border border-gray-300 bg-white p-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                            placeholder="Id Estudiante" aria-label="Código" id="buscarCodigo"
                                            name="buscarCodigo" value="{{ $buscarCodigo }}"
                                            aria-describedby="search-button" />
                                    </div>

                                    <div class="flex flex-col items-start">
                                        <input type="search"
                                            class="peer block w-full rounded border border-gray-300 bg-white p-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                            placeholder="Codigo" aria-label="Periodo" id="nroOperacion"
                                            name="nroOperacion" value="{{ $nroOperacion }}"
                                            aria-describedby="search-button" />
                                    </div>
                                @endif
                                <div class="">
                                    <p>Fecha</p>
                                    <div class="flex flex-col items-start relative max-w-sm">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker" name="fechaInicio" value="{{ $fechaInicio }}" datepicker
                                            datepicker-format="yyyy-mm-dd" datepicker-orientation="left" type="text"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Fecha Inicio">
                                    </div>
                                    <br>
                                    <div class="flex flex-col items-start relative max-w-sm">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker-format" name="fechaFin" value="{{ $fechaFin }}"
                                            datepicker datepicker-format="yyyy-mm-dd" datepicker-orientation="left"
                                            type="text"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Fecha Fin">
                                    </div>

                                </div>
                                <div class="flex gap-3 place-content-center">
                                    <button
                                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                                        type="submit">Buscar</button>

                                    <i class="fa-solid fa-broom"></i>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="lg:flex-row flex flex-col justify-center gap-4">
                    <a class="rounded flex items-center justify-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out focus:outline-none focus:ring-0 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2"
                        href="{{ route('generarPago', ['buscarCodigo' => $buscarCodigo, 'nroOperacion' => $nroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin, 'generarPDF' => true]) }}">
                        Reporte de Pagos</a>
                </div>
            </div>
        </div>
    @endif
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            list-style: none;
            gap: 0.5rem;
            padding: 2rem 0rem;
            font-size: 1.25rem;
        }

        .active {
            background-color: #2563EB;
            color: white;
        }

        .page-item {
            padding: 0.5rem;

        }

        .page-item:hover {
            scale: 1.1;
            transition: 0.1s
        }
    </style>

@endsection
