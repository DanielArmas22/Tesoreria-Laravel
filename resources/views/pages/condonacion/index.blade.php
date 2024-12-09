@extends('layouts.layoutA')
@section('titulo', 'Registrar Condonaciones')
@section('contenido')
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
                                    type="search" placeholder="999.0000" aria-label="Search" value="{{ $montoMayor }}">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="flex gap-4 justify-center">
                        <button
                            class="rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                            type="submit">Buscar</button>
                        <x-button label="Limpiar" color="success" ruta="condonacion.index" />
                    </div>
                </form>
            </article>
            <article class="w-2/3">

                <h3 class="text-2xl border-b-[1px]">Condonaciones Activas</h3>
                <br>
                <div class="">
                    <x-table :cabeceras="['Codigo', 'DNI', 'Estudiante', 'Monto', 'Fecha', 'Estado']" :datos="$datos" :atributos="['idCondonacion', 'dni', 'nombre_completo', 'total_monto', 'fecha', 'estadoCondonacion']" ruta="condonacion.edit"
                        id="idCondonacion" />
                    <br>
                    <article class="flex justify-center">
                        {{-- <x-button label="Nueva Condonacion" ruta="condonacion.create" color="primary" /> --}}
                    </article>
                </div>
            </article>
        </section>
        <br>
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4 w-full flex justify-end">
            <a href="{{ route('condonacion.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <i class="fas fa-plus"></i>
                @if (Auth::user()->hasRole('padre'))
                    Solicitar
                @endif
                Condonacion
            </a>
        </div>
    </div>
@endsection
