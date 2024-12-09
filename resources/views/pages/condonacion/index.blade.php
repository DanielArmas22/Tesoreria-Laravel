@extends('layouts.layoutA')

@section('titulo', 'Registrar Condonaciones')

@php
    $buttonClass =
        'rounded-md bg-primary px-6 py-2 text-sm font-medium uppercase text-white shadow-md transition-transform duration-200 ease-in-out hover:bg-primary-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-accent-300 active:bg-primary-600 active:shadow-lg';
@endphp

@section('contenido')
    <div class="w-full flex flex-col justify-center items-center px-6 py-8">
        @if (session('datos'))
            <x-alert :mensaje="session('datos')" tipo="success" />
        @endif
        @if (session('mensaje'))
            <div class="w-full mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                <p>{{ session('mensaje') }}</p>
            </div>
        @endif

        <section class="flex flex-col lg:flex-row gap-6 w-full">
            <!-- Filtros de Búsqueda -->
            <article class="rounded-lg shadow-md p-6 bg-white flex-1">
                <h3 class="text-lg font-semibold text-center mb-4">Filtros de Búsqueda</h3>
                <form class="flex flex-col gap-4" method="GET">
                    <!-- ID de Condonación -->
                    <x-textField label="ID de Condonación" placeholder="ID de Condonación" name="idCondonacion"
                        valor="{{ $idCondonacion }}" class="w-full" />

                    <!-- Estudiantes -->
                    <div>
                        <h4 class="font-medium mb-2">Estudiantes</h4>
                        @if (isset($estudiantes))
                            <select
                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                id="busquedaEscala" name="codigoEstudiante" aria-label="Seleccionar Estudiante">
                                <option value="" {{ $idEstudiante == 'ninguno' ? 'selected' : '' }}>Seleccionar
                                    Estudiante</option>
                                @foreach ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->estudiante->idEstudiante }}"
                                        {{ $idEstudiante == $estudiante->estudiante->idEstudiante ? 'selected' : '' }}>
                                        {{ $estudiante->estudiante->DNI }} - {{ $estudiante->estudiante->nombre }}
                                        {{ $estudiante->estudiante->apellidoP }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="space-y-2">
                                <x-textField label="Código de Estudiante" placeholder="Código de Estudiante"
                                    name="codigoEstudiante" valor="{{ $idEstudiante }}" class="w-full" />
                                <x-textField label="DNI de Estudiante" placeholder="DNI de Estudiante" name="dniEstudiante"
                                    valor="{{ $dniEstudiante }}" class="w-full" />
                            </div>
                        @endif
                    </div>

                    <!-- Monto -->
                    <div>
                        <h4 class="font-medium mb-2">Monto</h4>
                        <div class="flex space-x-4">
                            <input name="montoMenor"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="number" step="0.01" placeholder="Desde" aria-label="Monto Desde"
                                value="{{ $montoMenor }}">
                            <input name="montoMayor"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                                type="number" step="0.01" placeholder="Hasta" aria-label="Monto Hasta"
                                value="{{ $montoMayor }}">
                        </div>
                    </div>

                    <!-- Estados -->
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-accent-300"
                        id="busquedaEscala" name="estadoCondonacion" aria-label="Seleccionar Estudiante">
                        <option value="" {{ $estadoCondonacion == 'ninguno' ? 'selected' : '' }}>Seleccionar
                            Estado</option>
                        @foreach ($estados as $key => $estado)
                            <option value="{{ $key }}"
                                {{ isset($estadoCondonacion) && $estadoCondonacion == $key ? 'selected' : '' }}>
                                {{ $estado }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Botones de Acción -->
                    <div class="flex justify-center space-x-4 mt-4">
                        <button class="{{ $buttonClass }}" type="submit">
                            Buscar
                        </button>
                        <a href="{{ route('condonacion.index') }}"
                            class="rounded-md bg-success px-6 py-2 text-sm font-medium uppercase text-white shadow-md transition-transform duration-200 ease-in-out hover:bg-success-accent-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-success-accent-300 active:bg-success-600 active:shadow-lg">
                            Limpiar
                        </a>
                    </div>
                </form>
            </article>

            <!-- Tabla de Condonaciones -->
            <article class="rounded-lg shadow-md p-6 bg-white flex-2">
                <h3 class="text-xl font-semibold border-b pb-2 mb-4">Condonaciones Activas</h3>
                <div class="overflow-x-auto">
                    <x-table :cabeceras="['Código', 'DNI', 'Estudiante', 'Monto', 'Fecha', 'Estado']" :datos="$datos" :atributos="['idCondonacion', 'dni', 'nombre_completo', 'total_monto', 'fecha', 'estadoCondonacion']" ruta="condonacion.edit"
                        id="idCondonacion" />
                </div>
                <div class="flex justify-center mt-4">
                    {{-- Botón adicional si es necesario --}}
                    {{-- <x-button label="Nueva Condonación" ruta="condonacion.create" color="primary" /> --}}
                </div>
            </article>
        </section>

        <div class="w-full max-w-4xl mt-6 bg-white rounded-lg shadow-md p-4 flex justify-end">
            <a href="{{ route('condonacion.create') }}"
                class="inline-flex items-center px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                @if (Auth::user()->hasRole('padre'))
                    Solicitar
                @endif
                Condonación
            </a>
        </div>
    </div>
@endsection
