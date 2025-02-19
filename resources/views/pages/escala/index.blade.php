@extends('layouts.layoutA')
@section('titulo', 'Listar Escalas')
@php
    $buttonClass =
        'rounded bg-blue-500 px-6 py-2 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-blue-600 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50';
@endphp
@section('contenido')
    <section class="w-full flex justify-center py-4 space-x-6">
        <article class="w-full max-w-4xl shadow-light-2 p-9">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            @foreach (['ID Escala', 'Monto', 'Descripcion'] as $header)
                                <th
                                    class="py-3 px-4 bg-gray-100 border-b text-center border-gray-200 text-gray-700 text-sm font-semibold uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                            <th
                                class="py-3 px-4 bg-gray-100 border-b border-gray-200 text-gray-700 text-center text-sm font-semibold uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos as $dato)
                            <tr class="hover:bg-gray-50 text-center">
                                @foreach (['idEscala', 'monto', 'descripcion'] as $attribute)
                                    <td class="py-3 px-4 border-b border-gray-200 text-gray-600 text-sm">
                                        {{ $dato->$attribute }}
                                    </td>
                                @endforeach
                                <td class="border-b border-gray-200 flex justify-center py-1">
                                    <button type="button"
                                        class="hover:scale-105 hover:transition hover:duration-300 hover:ease-in-out inline-block rounded bg-success px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-success-3 transition duration-150 ease-in-out hover:bg-success-accent-300 hover:shadow-success-2 focus:bg-success-accent-300 focus:shadow-success-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-success-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                                        <a class="h-full" href="{{ route('escala.edit', $dato->idEscala) }}">Editar</a>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br>

            <div class="flex justify-center hover:scale-105 hover:transition hover:duration-700 hover:ease-in-out">
                <a class="{{ $buttonClass }}" href="{{ route('escala.create') }}">Nueva escala</a>
                <!--  -->
            </div>
        </article>

        <form class="w-full max-w-sm mb-4 space-y-10 px-14 shadow-light-2 py-10" method="GET">
            <h3 class="text-center font-bold">FILTROS</h3>
            <div class="flex items-center border-b border-blue-500 py-2">
                <input name="busqueda"
                    class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                    type="search" placeholder="Buscar por ID" aria-label="Search" value="">
            </div>

            <div class="space-y-2">
                <h3>Monto</h3>
                <div class="flex justify-center space-x-6">
                    <div class="border-b border-blue-500">
                        <input name="monto1"
                            class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none border-b border-blue-500"
                            type="search" placeholder="0.0000" aria-label="Search" value="">
                    </div>
                    <div class="border-b border-blue-500">
                        <input name="monto2"
                            class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none border-b border-blue-500"
                            type="search" placeholder="999.0000" aria-label="Search" value="">
                    </div>
                </div>
            </div>
            <button
                class="hover:scale-105 py-2 w-full relative z-[2] -ms-0.5 flex items-center rounded-e bg-primary px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                type="submit" id="search-button" data-twe-ripple-init data-twe-ripple-color="light">
                <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                    </svg>
                    <p>Ver</p>
                </span>

            </button>
        </form>


    </section>
@endsection
