@extends('layouts.layoutA')
@section('titulo', 'Listar Escala Estudiantes')
@php
    $buttonClass =
        'rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
@endphp
@section('contenido')
    <form class="form-inline my-2 my-lg-0" method="GET">
        <div class="flex flex-col space-y-4 px-6 border-r-2">
            <h2 class="text-lg font-semibold">Filtros de Búsqueda</h2>

            <div class="flex flex-col items-start">
                <input type="search"
                    class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="ID Estudiante" aria-label="Código" id="buscarCodigo" name="buscarCodigo"
                    value="{{ $buscarCodigo }}" aria-describedby="search-button" />
            </div>

            <div class="flex flex-col items-start">
                <input type="search"
                    class="peer block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Periodo" aria-label="Periodo" id="busquedaPeriodo" name="busquedaPeriodo"
                    value="{{ $busquedaPeriodo }}" aria-describedby="search-button" />
            </div>

            <div class="flex flex-col items-start">
                <select
                    class="block w-full min-w-[200px] rounded border border-gray-300 bg-white px-3 py-2 leading-normal text-gray-700 outline-none transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    aria-label="Seleccionar Opción" id="busquedaOpcion" name="busquedaOpcion">
                    <option value="" selected>Escala</option>
                    @foreach ($escalaF as $des)
                        <option value="{{ $des->idEscala }}" {{ $busquedaOpcion == $des->idEscala ? 'Selected' : '' }}>
                            {{ $des->descripcion }}</option>
                    @endforeach
                </select>
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
                <x-button label="Limpiar" color="success" ruta="escalaEstudiante.index">
                    <i class="fa-solid fa-broom mr-2"></i>
                </x-button>
            </div>
        </div>
    </form>

    <hr class="my-6 border-gray-300">

    <section class="w-full flex flex-col justify-center p-8 px-8 shadow-lg">
        <article>
            <br>
            <div class="w-full">
                <table class="table-auto w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">ID Estudiante</th>
                            <th class="px-4 py-2 border-b">Nombres</th>
                            <th class="px-4 py-2 border-b">Escala</th>
                            <th class="px-4 py-2 border-b">Periodo</th>
                            <th class="px-4 py-2 border-b">Observación</th>
                            <th class="px-4 py-2 border-b">Fecha Asignación</th>
                            <th class="px-4 py-2 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @if ($escalaEstudiante->count() <= 0)
                            <tr>
                                <td class="border px-4 py-2" colspan="7">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($escalaEstudiante as $dato)
                                <tr class="cursor-pointer hover:bg-gray-200">
                                    <td class="border px-4 py-2">{{ $dato->Estudiante->idEstudiante }}</td>
                                    <td class="border px-4 py-2">{{ $dato->Estudiante->nombre }} ,
                                        {{ $dato->Estudiante->apellidoP }} </td>
                                    <td class="border px-4 py-2">{{ $dato->escala->descripcion }}</td>
                                    <td class="border px-4 py-2">{{ $dato->periodo }}</td>
                                    <td class="border px-4 py-2">{{ $dato->observacion }}</td>
                                    <td class="border px-4 py-2">{{ $dato->fechaEE }}</td>
                                    <td class="border px-4 py-2 flex space-x-2">
                                        <a href="{{ route('escalaEstudiante.edit', ['idEstudiante' => $dato->idEstudiante, 'periodo' => $dato->periodo]) }}"
                                            class="inline-block rounded bg-green-500 px-4 py-2 text-xs font-medium uppercase leading-normal text-white shadow transition duration-150 ease-in-out hover:bg-green-600 focus:bg-green-600 focus:outline-none focus:ring-0 active:bg-green-700">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{ $escalaEstudiante->links() }}
            </div>
            <br>
        </article>
        <article class="flex justify-center">
            <a href="{{ route('escalaEstudiante.create') }}"
                class="rounded bg-blue-500 px-6 py-2 text-xs font-medium uppercase leading-normal text-white shadow transition duration-150 ease-in-out hover:bg-blue-600 focus:bg-blue-600 focus:outline-none focus:ring-0 active:bg-blue-700">Nuevo
                Escala Estudiante</a>
        </article>
    </section>

@endsection
