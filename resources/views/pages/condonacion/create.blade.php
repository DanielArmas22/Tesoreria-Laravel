@extends('layouts.layoutA')
@section('titulo', 'Nueva condonacion')
@section('contenido')
    <section class="w-11/12 mx-auto">
        @if (session('mensaje'))
            <x-alert :mensaje="session('mensaje')" tipo="error" />
        @endif

        {{-- Alerta de errores --}}
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
                    </div>
                </div>
            </div>
        </div>

        {{--  --}}

        <article class="grid grid-cols-2 gap-4 justify-center">
            <div class="flex">
                <form class="w-max" method="GET">
                    {{-- estudiantes --}}
                    <section class="pr-6 w-max">
                        <h3 class="">Estudiante</h3>
                        @if (isset($students))
                            <div class="w-max">
                                <select
                                    class="block w-max rounded border border-gray-300 bg-white p-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm"
                                    aria-label="Seleccionar Escala" id="idEstudiante" name="codEstudiante">
                                    <option value="" {{ $codEstudiante == 'ninguno' ? 'Selected' : '' }}>
                                        Estudiante
                                    </option>
                                    @foreach ($students as $es)
                                        <option value="{{ $es->estudiante->idEstudiante }}"
                                            {{ $codEstudiante == $es->estudiante->idEstudiante ? 'Selected' : '' }}>
                                            {{ $es->estudiante->DNI }} -
                                            {{ $es->estudiante->nombre }}
                                            {{ $es->estudiante->apellidoP }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <article class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="ID Estudiante" aria-label="Código" id="codEstudiante"
                                        name="codEstudiante" value="{{ $codEstudiante }}"
                                        aria-describedby="search-button" />
                                </div>
                                <div class="flex flex-col items-start">
                                    <input type="number"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="DNI" aria-label="Código" id="dniEstudiante" name="dniEstudiante"
                                        value="{{ $dniEstudiante }}" aria-describedby="search-button" />
                                </div>
                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Nombres" aria-label="Código" id="busquedaNombreEstudiante"
                                        name="busquedaNombreEstudiante" value="{{ $busquedaNombreEstudiante }}"
                                        aria-describedby="search-button" />
                                </div>
                                <div class="flex flex-col items-start">
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Apellidos" aria-label="Código" id="busquedaApellidoEstudiante"
                                        name="busquedaApellidoEstudiante" value="{{ $busquedaApellidoEstudiante }}"
                                        aria-describedby="search-button" />
                                </div>
                            </article>
                        @endif
                    </section>
                    <br>
                    <section class="flex justify-center gap-4">
                        <button
                            class="rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                            type="submit">Buscar</button>
                        <x-boton label="Limpiar" color="success" ruta="condonacion.create">
                            <i class="fa-solid fa-broom mr-2"></i>
                        </x-boton>

                    </section>
                </form>
            </div>
            @isset($filtro)
                <div class="grid gap-4 justify-end">
                    <div class="flex flex-col gap-5 col-span-1">
                        <h3 class="text-xl font-bold">Estudiante</h3>
                        <x-textField label="Nombre" name='nombre' placeholder='nombre' :message={{ $message }}
                            valor="{{ old('nombre', $filtro->nombre ?? '') }}" readonly='true' />
                        <div class="flex gap-2 items-center">
                            <x-textField label="Apellido Paterno" name='apellidoP' placeholder='apellidoP'
                                :message={{ $message }} valor="{{ old('apellidoP', $filtro->apellidoP ?? '') }}"
                                readonly='true' />
                            <x-textField label="Apellido Materno" name='apellidoM' placeholder='apellidoM'
                                :message={{ $message }} valor="{{ old('apellidoM', $filtro->apellidoM ?? '') }}"
                                readonly='true' />
                        </div>
                        <x-textField label="Grado" name='grado' placeholder='grado' :message={{ $message }}
                            valor="{{ old('grado', isset($filtro->descripcionGrado) ? $filtro->descripcionGrado . ' ' . $filtro->descripcionSeccion : '') }}"
                            readonly='true' />
                    </div>
                </div>
            @endisset
        </article>

        <div class="my-8"></div>


        <div class="my-8"></div>
        @isset($deudas)
            <article>
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Deudas del Estudiante</h2>
                <table class="min-w-full divide-y divide-gray-200" id="tabla_deudas">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Código
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Concepto
                                Escala</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Monto
                                Escala</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Monto
                                Mora</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Fecha
                                Límite</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Adelanto
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Monto
                                Condonado
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Monto
                                Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Monto
                                a
                                Condonar</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Opciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if (isset($deudas))
                            @foreach ($deudas as $deuda)
                                <tr class="">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $deuda->idDeuda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->conceptoDescripcion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deuda->monto }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deuda->montoMora }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->fechaLimite }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deuda->adelanto }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->totalCondonacion ?? '0.0000' }}</td>
                                    <td class="deudaMonto px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->monto + $deuda->montoMora - $deuda->adelanto - $deuda->totalCondonacion }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <input class="monto-a-condonar form-input rounded-md shadow-sm" type="number"
                                            name="monto_a_condonar[]" min="0" step="0.01">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button"
                                            class="paraCondonar inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                            Seleccionar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="8">
                                    No se
                                    encontraron deudas</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if (isset($deudas))
                    <div class="mt-4">
                        {{-- {{ $deudas->links() }} --}}
                    </div>
                @endif
            </article>
            <div class="my-8"></div>

            <div class="rounded-lg shadow-sm mt-6">
                <form id="pago-form" action="{{ route('condonacion.store') }}" method="POST">
                    @csrf
                    <div>

                    </div>
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold mb-4 text-gray-800">Deudas a Condonar</h2>
                        <button type="submit"
                            class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                            Solicitar Condonación
                        </button>
                    </div>
                    <br>
                    <input type="hidden" name="idestudiante" value={{ $codEstudiante }}>
                    <table id="detalleCon" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Concepto</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Monto a Condonar</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-center"></tbody>
                        <tfoot class="flex justify-start">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-right font-bold">Total a Condonar</td>
                                <td id="total-a-condonar" class="px-6 py-4 font-bold">0.00</td>
                                <td class="px-6 py-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>

                    <div class="flex justify-start mt-4">

                    </div>
                </form>
            </div>
        @endisset

        @if (isset($estudiantes))
            <div class="py-8"></div>
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Estudiantes Encontrados</h2>
            <table class="min-w-full divide-y divide-gray-200" id="tabla_deudas">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Código
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            DNI</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Nombre</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Apellidos</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Grado</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                            Seccion
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($estudiantes as $estudiante)
                        <tr class="text-center">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $estudiante->idEstudiante }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $estudiante->DNI }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $estudiante->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $estudiante->apellidoP }}
                                {{ $estudiante->apellidoM }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $estudiante->descripcionGrado }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $estudiante->descripcionSeccion }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('condonacion.create', ['codEstudiante' => $estudiante->idEstudiante, 'dniEstudiante' => $estudiante->DNI, 'busquedaNombreEstudiante' => $estudiante->nombre, 'busquedaApellidoEstudiante' => $estudiante->apellidoP . ' ' . $estudiante->apellidoM]) }}"
                                    class="inline-block rounded bg-green-500 px-4 py-2 text-xs font-medium uppercase leading-normal text-white shadow transition duration-150 ease-in-out hover:bg-green-600 focus:bg-green-600 focus:outline-none focus:ring-0 active:bg-green-700">Seleccionar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    @endsection
    @section('js')
        <script>
            function clickfila(id) {
                var campoDeuda = document.getElementById('campoDeuda');
                campoDeuda.value = id;
            }
        </script>
        <script src="{{ asset('js/scriptCondon.js') }}"></script>
    @endsection
