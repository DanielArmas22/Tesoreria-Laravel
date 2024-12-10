@extends('layouts.layoutA')
{{-- @section('titulo', 'Estudiantes Registrados') --}}
@section('contenido')
    <section class="w-full flex flex-col justify-center items-center">
        @if (session('datos'))
            @if (session()->has('color'))
                <x-alert :mensaje="session('datos')" :tipo="session('color')" />
            @else
                <!-- Si session('color') no está definido, se usa un valor predeterminado -->
                <x-alert :mensaje="session('datos')" tipo="success" />
            @endif
        @endif
        <section class="lg:flex gap-4 px-4 py-8 shadow-xl  rounded-3xl border-[1px]">
            <div class="">
                @if (!Auth::user()->hasRole('tesorero'))
                    <article class="flex justify-start">
                        {{-- <button label="Nuevo Estudiante" ruta="estudiante.create" color="success" /> --}}
                        
                        <form action="{{ route('estudiante.create') }}" method="GET">
                            <button type=""
                                class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Nuevo estudiante
                        </form>

                    </article>
                @endif
                <br>
                <x-table nombreTabla="Estudiantes" :cabeceras="[
                    'Codigo',
                    'DNI',
                    'Nombre',
                    'Apellido Paterno',
                    'Apellido Materno',
                    'Grado',
                    'Seccion',
                    'Escala',
                    'Periodo',
                ]" :datos="$datos" :atributos="[
                    'idEstudiante',
                    'DNI',
                    'nombre',
                    'apellidoP',
                    'apellidoM',
                    'descripcionGrado',
                    'descripcionSeccion',
                    'descripcion',
                    'periodo',
                ]"
                    ruta="estudiante.edit" id="idEstudiante" />
                <br>

            </div>

            <article class="flex flex-col items-center gap-10 p-5  shadow-lg rounded-3xl">
                <h2 class="text-center font-semibold text-xl">Filtros de Busqueda</h2>
                <form class="w-full max-w-sm mb-4" method="GET">
                    <div class="flex justify-between flex-col  py-2 gap-4">
                        <x-textField label="Busqueda por Codigo" placeholder="Busqueda por Codigo" name="busquedaCodigo"
                            valor="{{ $busquedaCodigo }}" />
                        <x-textField label="Busqueda por Nombre" placeholder="Busqueda por Nombre" name="busquedaNombre"
                            valor="{{ $busquedaNombre }}" />
                        <x-textField label="Busqueda por DNI" placeholder="Busqueda por DNI" name="busquedaDNI"
                            valor="{{ $busquedaDNI }}" />


                        <div class="flex gap-2 items-center justify-between">
                            <div>
                                <h3 class="text-lg">Grado</h3>
                                <select
                                    class="block w-full mt-1 border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:focus:border-blue-500 dark:focus:ring-blue-500 dark:text-gray-300"
                                    name="grado">
                                    <option value=""></option>
                                    @foreach ($grados as $gra)
                                        <option value="{{ $gra->gradoEstudiante }}"
                                            {{ $gra->gradoEstudiante == $grado ? 'selected' : '' }}>
                                            {{ $gra->grado->descripcionGrado }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <h3 class="text-lg">Seccion</h3>
                                <select
                                    class="block w-full mt-1 border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:focus:border-blue-500 dark:focus:ring-blue-500 dark:text-gray-300"
                                    name="seccion">
                                    <option value=""></option>
                                    @foreach ($secciones as $secc)
                                        <option value="{{ $secc->seccionEstudiante }}"
                                            {{ intval($secc->seccionEstudiante) == $seccion ? 'selected' : '' }}>
                                            {{ $secc->seccion->descripcionSeccion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="flex items-center border-b border-blue-200 py-2 gap-4 max-w-sm">
                            <h3 class="text-lg">¿Tiene Deudas?</h3>
                            <div class="flex items-center">
                                <input {{ $opDeuda == 'si' ? 'checked' : '' }} id="default-radio-1" type="radio"
                                    value="si" name="deuda"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-radio-1"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sí</label>
                            </div>
                            <div class="flex items-center">
                                <input {{ $opDeuda == 'no' ? 'checked' : '' }} id="default-radio-2" type="radio"
                                    value="no" name="deuda"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-radio-2"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">No</label>
                            </div>
                            <div class="flex items-center">
                                <input {{ $opDeuda == 'todos' ? 'checked' : '' }} id="default-radio-3" type="radio"
                                    value="todos" name="deuda"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-radio-3"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Todos</label>
                            </div>
                        </div>
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
                            <i class="fa-solid fa-broom"></i>
                            <x-button label="Limpiar" color="success" ruta="estudiante.index" />
                        </div>
                    </div>
                </form>


            </article>
            <br>
        </section>

    </section>
    <p>
    </p>
@endsection
