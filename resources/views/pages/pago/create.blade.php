@extends('layouts.layoutA')
@section('titulo', 'Pagos')
@section('contenido')

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



    <button id="botonAlerta" data-modal-target="popup-modal" data-modal-toggle="popup-modal"
        class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Toggle modal
    </button>
    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center flex items-center flex-col mt-4 ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#f00" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="#fff" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-red-400" id="errorMensaje"></h3>
                    {{-- <button data-modal-hide="popup-modal" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>
                    <button data-modal-hide="popup-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                        cancel</button> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="max-w-7xl bg-white rounded-lg shadow-md mx-auto">
        @if (session('mensaje'))
            <x-alert :mensaje="session('mensaje')" tipo="error" />
        @endif
        <div class="grid gap-8 grid-cols-1 md:grid-cols-2">
            <!-- <form method="POST" action="{{ route('pago.store') }}"> -->
            <!-- Búsqueda del Estudiante -->
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Buscar Estudiante</h2>
                <form action="{{ route('pago.show') }}" method="GET" class="flex space-x-4 items-center">
                    <div class="flex-grow">
                        <label for="codigo" class="block text-sm font-medium text-gray-700">Código del Estudiante</label>
                        <input type="text" name="idEstudiante" id="idEstudiante"
                            placeholder="Ingrese el código del estudiante" value="{{ request('idEstudiante') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Buscar
                    </button>
                </form>
            </div>

            <!-- Datos del Estudiante -->
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Datos del Estudiante</h3>
                <div class="flex flex-col space-y-4">
                    <div class="flex space-x-4 mb-4">
                        <div class="flex-1">
                            <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                            <input type="text" id="dni" value="{{ $estudiante->dni ?? '' }}"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="flex-1">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="nombre" value="{{ $estudiante->nombre ?? '' }}"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>
                    </div>
                    <div class="flex space-x-4 mb-4">
                        <div class="flex-1">
                            <label for="apellidoP" class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
                            <input type="text" id="apellidoP" value="{{ $estudiante->apellidoP ?? '' }}"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="flex-1">
                            <label for="apellidoM" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                            <input type="text" id="apellidoM" value="{{ $estudiante->apellidoM ?? '' }}"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label for="grado" class="block text-sm font-medium text-gray-700">Grado</label>
                            <input type="text" id="grado" value="{{ $estudiante->descripcionGrado ?? '' }}"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="flex-1">
                            <label for="seccion" class="block text-sm font-medium text-gray-700">Sección</label>
                            <input type="text" id="seccion" value="{{ $estudiante->descripcionSeccion ?? '' }}"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg shadow-sm">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Deudas del Estudiante</h2>
            <table class="min-w-full divide-y divide-gray-200" id="tabla_deudas">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concepto
                            Escala</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto
                            Escala</th>
                        {{-- <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto
                            Mora</th> --}}
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha
                            Límite</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adelanto
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto
                            Total</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto a
                            Pagar</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if (isset($deudas))
                        @foreach ($deudas as $deuda)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $deuda->idDeuda }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $deuda->conceptoDescripcion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deuda->monto }}</td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deuda->montoMora }}</td> --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deuda->fechaLimite }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deuda->adelanto }}</td>
                                <td class="deuda px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $deuda->monto + $deuda->montoMora - $deuda->adelanto }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <input class="monto-a-pagar form-input rounded-md shadow-sm" type="number"
                                        name="monto_a_pagar[]" min="0" step="0.01">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button"
                                        class="agregar inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        Agregar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="8">No se
                                encontraron deudas</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if (isset($deudas))
                <div class="mt-4">

                </div>
            @endif

        </div>


        <!-- Lista de deudas seleccionadas para el pago -->
        <div class="bg-gray-50 rounded-lg shadow-sm mt-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Pagos Temporales</h2>
            <form id="pago-form" action="{{ route('pago.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idestudiante" value="{{ $estudiante->idEstudiante ?? '' }}">
                <table id="detalles" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Código</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Concepto</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto a Pagar</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-right font-bold">Total a Pagar</td>
                            <td id="total-a-pagar" class="px-6 py-4 font-bold">0.00</td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="agregar inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        PAGAR TOTAL
                    </button>
                </div>
                <br><br>
            </form>
        </div>

        <!-- </form> -->
    </div>

    <script src="{{ asset('js/script.js') }}"></script>

@endsection
