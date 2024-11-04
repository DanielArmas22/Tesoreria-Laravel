@extends('layouts.layoutA')
@section('titulo', 'Listar Deudas')
@php
    $buttonClass =
        'rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
@endphp
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
@section('contenido')
    @if (Auth::user()->hasRole('admin') or Auth::user()->hasRole('tesorero'))
        @if (session('deuda'))
            <x-alert :mensaje="session('deuda')" tipo="{{ !empty($color) ? $color : 'success' }}" />
        @endif
        <div class="container mx-auto">
            <div class="bg-gray-50 rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Listado de Pagos</h2>
        
                <!-- Filtros de Búsqueda -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Filtros de Búsqueda</h2>
                    <form class="form-inline" method="GET">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                            <!-- Selección del tipo de filtro -->
                            <div>
                                <select id="filterType" onchange="toggleFilters()" class="w-full rounded border-gray-300 p-3">
                                    <option value="all">Mostrar Todos</option>
                                    <option value="student">Filtro por Estudiante</option>
                                    <option value="scale">Filtro por Escala</option>
                                    <option value="grade">Filtro por Grado y Sección</option>
                                </select>
                            </div>
        
                            <!-- Filtros por Estudiante -->
                            <div id="studentFilters" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="codEstudiante" placeholder="ID Estudiante" class="w-full rounded border-gray-300 p-3" value="{{ $codEstudiante }}">
                                <input type="number" name="dniEstudiante" placeholder="DNI" id="dniEstudiante" value="{{ $dniEstudiante }}" class="w-full rounded border-gray-300 p-3">
                                <input type="text" name="busquedaNombreEstudiante" placeholder="Nombres" id="busquedaNombreEstudiante" value="{{ $busquedaNombreEstudiante }}" class="w-full rounded border-gray-300 p-3">
                                <input type="text" name="busquedaApellidoEstudiante" id="busquedaApellidoEstudiante" value="{{ $busquedaApellidoEstudiante }}" placeholder="Apellidos" class="w-full rounded border-gray-300 p-3">
                            </div>
        
                            <!-- Filtros por Escala -->
                            <div id="scaleFilters" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <select class="w-full rounded border-gray-300 p-3" aria-label="Seleccionar Escala" id="busquedaEscala" name="busquedaEscala">
                                    <option value="" {{ $busquedaEscala == 'ninguno' ? 'Selected' : '' }}>Escala</option>
                                    @foreach ($escalaF as $des)
                                        <option value="{{ $des->idEscala }}" {{ $busquedaEscala == $des->idEscala ? 'Selected' : '' }}>
                                            {{ $des->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                <select class="w-full rounded border-gray-300 p-3" aria-label="Seleccionar Concepto" id="busquedaConcepto" name="busquedaConcepto">
                                    <option value="" {{ $conceptoEscalas == 'ninguno' ? 'Selected' : '' }}>Concepto</option>
                                    @foreach ($conceptoEscalas as $concepto)
                                        <option value="{{ $concepto->descripcion }}" {{ $busquedaConcepto == $concepto->descripcion ? 'Selected' : '' }}>
                                            {{ $concepto->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" name="codMinimo" value="{{ $codMinimo }}" placeholder="Monto Mínimo" class="w-full rounded border-gray-300 p-3">
                                <input type="number" name="codMaximo" value="{{ $codMaximo }}" placeholder="Monto Máximo" class="w-full rounded border-gray-300 p-3">
                            </div>
        
                            <!-- Filtros por Grado y Sección -->
                            <div id="gradeFilters" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <select class="w-full rounded border-gray-300 p-3" aria-label="Seleccionar Grado" id="busquedaGrado" name="busquedaGrado">
                                    <option value="" {{ $busquedaGrado == 'ninguno' ? 'Selected' : '' }}>Grado</option>
                                    @foreach ($grados as $g)
                                        <option value="{{ $g->gradoEstudiante }}" {{ $busquedaGrado == $g->gradoEstudiante ? 'Selected' : '' }}>
                                            {{ $g->descripcionGrado }}
                                        </option>
                                    @endforeach
                                </select>
                                <select class="w-full rounded border-gray-300 p-3" aria-label="Seleccionar Sección" id="busquedaSeccion" name="busquedaSeccion">
                                    <option value="" {{ $busquedaSeccion == 'ninguno' ? 'Selected' : '' }}>Sección</option>
                                    @foreach ($secciones as $des)
                                        <option value="{{ $des->seccionEstudiante }}" {{ $busquedaSeccion == $des->seccionEstudiante ? 'Selected' : '' }}>
                                            {{ $des->descripcionSeccion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
        
                            <!-- Filtros de Fecha y Deuda -->
                            <div id="commonFilters" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Campo de Fecha Inicio con Etiqueta -->
                                <div class="flex flex-col">
                                    <label for="fechaInicio" class="text-gray-700 mb-2">Fecha Inicio</label>
                                    <input type="date" id="fechaInicio" name="fechaInicio" value="{{ $fechaInicio }}" class="w-full rounded border-gray-300 p-3">
                                </div>
                                
                                <!-- Campo de Fecha Fin con Etiqueta -->
                                <div class="flex flex-col">
                                    <label for="fechaFin" class="text-gray-700 mb-2">Fecha Fin</label>
                                    <input type="date" id="fechaFin" name="fechaFin" value="{{ $fechaFin }}" class="w-full rounded border-gray-300 p-3">
                                </div>
                                
                                <!-- Checkbox para Deuda del Día con Espacio Mejorado -->
                                <div class="flex items-center space-x-4">
                                    <input
                                        class="relative h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-secondary-500 outline-none checked:border-primary checked:bg-primary checked:after:content-['✓'] hover:cursor-pointer"
                                        type="checkbox" name="deudaHoy" value="si" id="deudaHoy"
                                        {{ isset($deudaHoy) ? 'Checked' : '' }} 
                                    />
                                    <label for="deudaHoy" class="text-gray-700">Deuda del Día</label>
                                </div>
                            </div>                        
                        </div>
        
                        <div class="mt-6 flex justify-center gap-x-1">
                            <button
                                class="flex justify-center bg-blue-500 hover:bg-blue-600 border-blue-500 hover:border-blue-600 text-sm border-4 text-white py-1 px-2 rounded"
                                type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                                </svg>
                                <p>Buscar</p>
                            </button>
                            <x-button label="Limpiar" color="success" ruta="pago.index" />
                        </div>
                    </form>
                </div>

            <hr class="my-6 border-gray-300">
            <section class="w-full flex flex-col justify-center">
                <article>
                    <br>
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Codigo</th>
                                <th class="px-4 py-2 border-b">Nombres</th>
                                <th class="px-4 py-2 border-b">Concepto Escala</th>
                                <th class="px-4 py-2 border-b">Escala</th>
                                <th class="px-4 py-2 border-b">Fecha Registro</th>
                                <th class="px-4 py-2 border-b">Fecha Limite</th>
                                <th class="px-4 py-2 border-b">Adelanto</th>
                                <th class="px-4 py-2 border-b">Monto Total</th>
                                <th class="px-4 py-2 border-b text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @if (count($datos) <= 0)
                                <tr>
                                    <td class="border px-4 py-2" colspan="9">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($datos as $dato)
                                    <tr class="cursor-pointer hover:bg-gray-200">
                                        <td class="border px-4 py-2">{{ $dato->idEstudiante }}</td>
                                        <td class="border px-4 py-2">{{ $dato->nombre }} {{ $dato->apellidoP }} </td>
                                        <td class="border px-4 py-2">{{ $dato->descripcion }}</td>
                                        <td class="border px-4 py-2">{{ $dato->desEscala }}</td>
                                        <td class="border px-4 py-2">{{ $dato->fechaRegistro }}</td>
                                        <td class="border px-4 py-2">{{ $dato->fechaLimite }}</td>
                                        <td class="border px-4 py-2">{{ $dato->adelanto }}</td>
                                        <td class="border px-4 py-2">
                                            {{ $dato->montoMora + $dato->monto - $dato->totalCondonacion }} </td>
                                        <td class="border px-4 py-2 flex space-x-2">
                                            <a href="{{ route('deuda.edit', $dato->idDeuda) }}"
                                                class="inline-block rounded bg-green-500 px-4 py-2 text-xs font-medium uppercase leading-normal text-white shadow transition duration-150 ease-in-out hover:bg-green-600 focus:bg-green-600 focus:outline-none focus:ring-0 active:bg-green-700">Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $datos->links() }}
                    <br>
                </article>
                <article class="flex justify-center"><a class="{{ $buttonClass }}"
                        href="{{ route('deuda.create') }}">Nueva
                        Deuda</a></article>
            </section>
        </div>
    @else
        @if (session('deuda'))
            <x-alert :mensaje="session('deuda')" tipo="{{ !empty($color) ? $color : 'success' }}" />
        @endif
        <div class="flex flex-col w-full">
            <form class="w-full" method="GET">
                <h2 class="text-lg font-semibold">Datos del Estudiante</h2>
                <br>
                <section class="flex flex-col w-11/12 mx-auto gap-8">
                    {{-- flitros --}}
                    <div class="flex gap-8 items-center">
                        {{-- estudiantes --}}
                        <section class="border-r-2 pr-6 h-full w-max">
                            <h3 class="">ESTUDIANTE</h3>
                            <article class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col items-start">
                                    <h4 class="">Codigo</h4>
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="ID Estudiante" aria-label="Código" id="codEstudiante"
                                        name="codEstudiante" value="{{ $codEstudiante }}"
                                        aria-describedby="search-button" readonly>
                                </div>
                                <div class="flex flex-col items-start">
                                    <h4 class="">Dni</h4>
                                    <input type="number"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="DNI" aria-label="Código" id="dniEstudiante" name="dniEstudiante"
                                        value="{{ $dniEstudiante }}" aria-describedby="search-button" readonly />
                                </div>
                                <div class="flex flex-col items-start">
                                    <h4 class="">Nombre</h4>
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Nombres" aria-label="Código" id="busquedaNombreEstudiante"
                                        name="busquedaNombreEstudiante" value="{{ $busquedaNombreEstudiante }}" readonly
                                        aria-describedby="search-button" />
                                </div>
                                <div class="flex flex-col items-start">
                                    <h4 class="">Apellido</h4>
                                    <input type="search"
                                        class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        placeholder="Apellidos" aria-label="Código" id="busquedaApellidoEstudiante"
                                        name="busquedaApellidoEstudiante" value="{{ $busquedaApellidoEstudiante }}"
                                        readonly aria-describedby="search-button" />
                                </div>
                            </article>
                        </section>
                        {{-- deudas --}}
                        <section class="border-r-2 pr-6 w-max">
                            <article class="flex flex-col gap-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <h4 class="">Deuda</h4>
                                    <h4 class="">Concepto</h4>
                                    <select
                                        class="block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        aria-label="Seleccionar Escala" id="busquedaEscala" name="busquedaEscala">
                                        <option value="" {{ $busquedaEscala == 'ninguno' ? 'Selected' : '' }}>Escala
                                        </option>
                                        @foreach ($escalaF as $des)
                                            <option value="{{ $des->idEscala }}"
                                                {{ $busquedaEscala == $des->idEscala ? 'Selected' : '' }}>
                                                {{ $des->descripcion }}</option>
                                        @endforeach
                                    </select>

                                    <select
                                        class="block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        aria-label="Seleccionar Escala" id="busquedaConcepto" name="busquedaConcepto">
                                        <option value="" {{ $conceptoEscalas == 'ninguno' ? 'Selected' : '' }}>
                                            Concepto
                                        </option>
                                        @foreach ($conceptoEscalas as $concepto)
                                            <option value="{{ $concepto->descripcion }}"
                                                {{ $busquedaConcepto == $concepto->descripcion ? 'Selected' : '' }}>
                                                {{ $concepto->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="">
                                    <h4>Monto</h4>
                                    <div class="flex gap-2">
                                        <div class="flex flex-col items-start">
                                            <input type="number"
                                                class="peer block w-full rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                                placeholder="Minimo" aria-label="Código" id="codMinimo" name="codMinimo"
                                                value="{{ $codMinimo }}" aria-describedby="search-button" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <input type="number"
                                                class="peer block w-full rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                                placeholder="Maximo" aria-label="Código" id="codMaximo" name="codMaximo"
                                                value="{{ $codMaximo }}" aria-describedby="search-button" />
                                        </div>
                                    </div>
                                </div>

                            </article>
                        </section>
                        {{-- diversos --}}
                        <section class="w-max">
                            <h4>Grado y Seccion</h4>
                            <div class="flex flex-row items-start gap-2">
                                <select
                                    class="block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                    aria-label="Seleccionar Grado" id="busquedaGrado" name="busquedaGrado">
                                    <option value="" {{ $busquedaGrado == 'ninguno' ? 'Selected' : '' }}>Grado
                                    </option>
                                    @foreach ($grados as $g)
                                        <option value="{{ $g->gradoEstudiante }}"
                                            {{ $busquedaGrado == $g->gradoEstudiante ? 'Selected' : '' }}>
                                            {{ $g->descripcionGrado }}</option>
                                    @endforeach
                                </select>
                                <select
                                    class="block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                    aria-label="Seleccionar Seccion" id="busquedaSeccion" name="busquedaSeccion">
                                    <option value="" {{ $busquedaSeccion == 'ninguno' ? 'Selected' : '' }}>Seccion
                                    </option>
                                    @foreach ($secciones as $des)
                                        <option value="{{ $des->seccionEstudiante }}"
                                            {{ $busquedaSeccion == $des->seccionEstudiante ? 'Selected' : '' }}>
                                            {{ $des->descripcionSeccion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div class="flex flex-col">
                                <h4 class="">Fecha</h4>
                                <div class="flex gap-4 items-center">
                                    <div class="flex flex-col items-start relative max-w-sm">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="fechaInicioPicker" name="fechaInicio" value="{{ $fechaInicio }}"
                                            datepicker datepicker-format="yyyy-mm-dd" type="text"
                                            class="fechaPicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Fecha Inicio">
                                    </div>
                                    <div class="flex flex-col items-start relative max-w-sm">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="fechaFinPicker" name="fechaFin" value="{{ $fechaFin }}"
                                            datepicker datepicker-format="yyyy-mm-dd" type="text"
                                            class="fechaPicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Fecha Fin">
                                    </div>
                                    <div>
                                        <div class="mb-[0.125rem] block min-h-[1.5rem] ps-[1.5rem]">
                                            <input
                                                class="relative float-left -ms-[1.5rem] me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-secondary-500 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-checkbox before:shadow-transparent before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ms-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-black/60 focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-black/60 focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-checkbox checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ms-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent rtl:float-right dark:border-neutral-400 dark:checked:border-primary dark:checked:bg-primary"
                                                type="checkbox" name="deudasHoy" value="si" id="deudasHoy"
                                                {{ isset($deudaHoy) ? 'Checked' : '' }} />
                                            <label class="inline-block ps-[0.15rem] hover:cursor-pointer"
                                                for="checkboxChecked">
                                                Deuda del Dia
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    {{-- botones --}}
                    <div class="flex gap-3 place-content-center">
                        <button
                            class="flex justify-center bg-blue-500 hover:bg-blue-600 border-blue-500 hover:border-blue-600 text-sm border-4 text-white py-1 px-2 rounded"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                            </svg>
                            <p>
                                Buscar
                            </p>
                        </button>
                    </div>
                </section>
            </form>

            <hr class="my-6 border-gray-300">
            <section class="w-full flex flex-col justify-center">
                <article>
                    <br>
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Codigo</th>
                                <th class="px-4 py-2 border-b">Nombres</th>
                                <th class="px-4 py-2 border-b">Concepto Escala</th>
                                <th class="px-4 py-2 border-b">Escala</th>
                                <th class="px-4 py-2 border-b">Fecha Registro</th>
                                <th class="px-4 py-2 border-b">Fecha Limite</th>
                                <th class="px-4 py-2 border-b">Adelanto</th>
                                <th class="px-4 py-2 border-b">Monto Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @if (count($datos) <= 0)
                                <tr>
                                    <td class="border px-4 py-2" colspan="9">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($datos as $dato)
                                    <tr class="cursor-pointer hover:bg-gray-200">
                                        <td class="border px-4 py-2">{{ $dato->idEstudiante }}</td>
                                        <td class="border px-4 py-2">{{ $dato->nombre }} {{ $dato->apellidoP }} </td>
                                        <td class="border px-4 py-2">{{ $dato->descripcion }}</td>
                                        <td class="border px-4 py-2">{{ $dato->desEscala }}</td>
                                        <td class="border px-4 py-2">{{ $dato->fechaRegistro }}</td>
                                        <td class="border px-4 py-2">{{ $dato->fechaLimite }}</td>
                                        <td class="border px-4 py-2">{{ $dato->adelanto }}</td>
                                        <td class="border px-4 py-2">
                                            {{ $dato->montoMora + $dato->monto - $dato->totalCondonacion }} </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $datos->links() }}
                    <br>
                </article>
            </section>
        </div>
    @endif
@endsection
@section('js')
    <script>
        const fecha1 = document.getElementsByClassName('fechaPicker')[0];
        const fecha2 = document.getElementsByClassName('fechaPicker')[1];
        const checkDeuda = document.getElementById('deudasHoy');
        const cambioFecha = () => {
            if (checkDeuda.checked) {
                fecha1.disabled = true;
                fecha2.disabled = true;
            } else {
                fecha1.disabled = false;
                fecha2.disabled = false;
            }
        }
        checkDeuda.addEventListener('change', cambioFecha);
        cambioFecha();
    </script>
    <script>
        function toggleFilters() {
            const filterType = document.getElementById('filterType').value;
            document.getElementById('studentFilters').classList.add('hidden');
            document.getElementById('scaleFilters').classList.add('hidden');
            document.getElementById('gradeFilters').classList.add('hidden');
    
            if (filterType === 'student') {
                document.getElementById('studentFilters').classList.remove('hidden');
            } else if (filterType === 'scale') {
                document.getElementById('scaleFilters').classList.remove('hidden');
            } else if (filterType === 'grade') {
                document.getElementById('gradeFilters').classList.remove('hidden');
            } else {
                document.getElementById('studentFilters').classList.remove('hidden');
                document.getElementById('scaleFilters').classList.remove('hidden');
                document.getElementById('gradeFilters').classList.remove('hidden');
            }
        }
    </script>
@endsection
