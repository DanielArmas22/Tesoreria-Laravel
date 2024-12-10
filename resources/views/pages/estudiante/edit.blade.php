@extends('layouts.layoutA')
{{-- @section('titulo', 'Editar Estudiante') --}}
@section('contenido')
    <section class="border border-gray-300 py-6 shadow-md rounded-xl mx-auto max-w-7xl">
        <div class="px-6 pb-4 w-full border-b-2">
            <div class="flex gap-2 items-center">
                <p class="text-2xl font-semibold text-center text-gray-800">
                    {{ ucfirst($estudiante->nombre) }} {{ ucfirst($estudiante->apellidoP) }}
                    {{ ucfirst($estudiante->apellidoM) }}
                </p>
                <p class="text-2xl text-gray-500 text-center" id="idEstudiante">
                    ID: {{ $estudiante->idEstudiante }}
                </p>
            </div>
        </div>
        <div class="flex gap-6">
            <article class="border-r border-blue-200 pr-6 px-6 pt-4">

                <div class="w-full max-w-md mx-auto">
                    <form class="space-y-6" action="{{ route('estudiante.update', $estudiante->idEstudiante) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        {{-- Campos de Texto --}}
                        <x-textField label="DNI" name="DNI" placeholder="DNI" :message={{ $message }}
                            valor="{{ old('DNI', $estudiante->DNI) }}" />
                        <x-textField label="Nombre" name="nombre" placeholder="Nombre" :message={{ $message }}
                            valor="{{ old('nombre', $estudiante->nombre) }}" />
                        <x-textField label="Apellido Paterno" name="apellidoP" placeholder="Apellido Paterno"
                            :message={{ $message }} valor="{{ old('apellidoP', $estudiante->apellidoP) }}" />
                        <x-textField label="Apellido Materno" name="apellidoM" placeholder="Apellido Materno"
                            :message={{ $message }} valor="{{ old('apellidoM', $estudiante->apellidoM) }}" />

                        {{-- Selección de Aula --}}
                        <div class="flex gap-2 items-center">
                            <label for="aula" class="block text-lg font-medium text-gray-700">Aula</label>
                            <select id="aula" name="aula"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm p-2 focus:ring-primary focus:border-primary">
                                @foreach ($aulas as $grado)
                                    <option
                                        value="{{ $grado->grado->gradoEstudiante }}-{{ $grado->seccion->seccionEstudiante }}"
                                        {{ $grado->gradoEstudiante == $estudiante->gradoEstudiante && $grado->seccion->seccionEstudiante == $estudiante->seccionEstudiante ? 'selected' : '' }}>
                                        {{ $grado->grado->descripcionGrado }} - {{ $grado->seccion->descripcionSeccion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Asignación de Escala --}}
                        @if ($escalas->isEmpty())
                            <div class="border-t border-b border-gray-200 py-4">
                                <h4 class="text-center text-lg font-medium text-gray-700">Escala sin Asignar</h4>
                                @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('secretario'))
                                    <a href="{{ route('escalaEstudiante.create', ['buscarEstudiante' => $estudiante->idEstudiante]) }}"
                                        class="mt-4 w-full bg-primary text-white font-medium py-2 rounded-md shadow hover:bg-primary-dark transition">
                                        Asignar Escala
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="flex gap-4 items-center">
                                <h4 class="text-center text-lg font-medium text-gray-700">Escala</h4>
                                <select id="periodo" name="periodo"
                                    class="flex-1 rounded-md border border-gray-300 shadow-sm p-2 focus:ring-primary focus:border-primary"
                                    onchange="mostrarEscala()">
                                    @foreach ($periodos as $peri)
                                        <option value="{{ $peri }}">{{ $peri }}</option>
                                    @endforeach
                                </select>
                                <x-textField label="Escala" name="escala" placeholder="Escala"
                                    :message={{ $message }} valor="a" readonly />
                            </div>
                        @endif

                        {{-- Botones de Acción --}}
                        @if (!Auth::user()->hasRole('director'))
                            <div class="space-y-4">
                                <div class="flex gap-4">
                                    <button type="submit"
                                        class="text-xs flex-1 bg-success text-white font-medium p-2 rounded-md shadow hover:bg-success-dark transition">
                                        Actualizar
                                    </button>
                                    <x-boton ruta="cancelarEstudiante" color="secondary" label="Cancelar" />
                                </div>
                                @if (!Auth::user()->hasRole('padre'))
                                    <div class="flex justify-center">
                                        <x-boton ruta="estudiante.confirmar" color="danger" label="Eliminar"
                                            datos="{{ $estudiante->idEstudiante }}" />
                                    </div>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>
            </article>
            <article class="w-full lg:w-2/3 px-6 pt-4">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Deudas Actuales</h3>
                <x-table :cabeceras="['Código', 'Escala', 'Monto', 'Fecha', 'Adelanto', 'Condonado', 'Total a Pagar']" :datos="$deudas" :atributos="[
                    'idDeuda',
                    'conceptoEscala',
                    'montoTotal',
                    'fechaLimite',
                    'adelanto',
                    'totalCondonacion',
                    'totalPagar',
                ]" ruta="deuda.edit" id="idDeuda" />

                @if (!Auth::user()->hasRole('padre') && !Auth::user()->hasRole('director'))
                    <div class="mt-6">
                        <x-boton label="Nueva Deuda" ruta="estudiante.create" color="primary" />
                    </div>
                @endif
            </article>
            @if (!Auth::user()->hasRole('director'))
                <article class="px-4 w-1/6 border-l border-blue-200 pt-4">
                    <h3 class="text-xl font-semibold text-center text-gray-800">Opciones</h3>
                    <div class="mt-6 space-y-6">
                        {{-- Pagos --}}
                        <div>
                            <h4 class="text-lg font-medium text-gray-700 text-center">Pagos</h4>
                            <ul class="mt-2 space-y-2">
                                <li>
                                    <a class="block w-full bg-success text-white font-medium py-2 px-4 rounded-md shadow hover:bg-success-dark transition text-center"
                                        href="{{ route('pago.show', ['idEstudiante' => $estudiante->idEstudiante]) }}">
                                        @if (Auth::user()->hasRole('padre'))
                                            Solicitar
                                        @else
                                            Nuevo
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="block w-full bg-success text-white font-medium py-2 px-4 rounded-md shadow hover:bg-success-dark transition text-center"
                                        href="{{ route('pago.index', ['buscarCodigo' => $estudiante->idEstudiante]) }}">
                                        Listar
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {{-- Devolución --}}
                        <div>
                            <h4 class="text-lg font-medium text-gray-700 text-center">Devolución</h4>
                            <ul class="mt-2 space-y-2">
                                <li>
                                    <a class="block w-full bg-success text-white font-medium py-2 px-4 rounded-md shadow hover:bg-success-dark transition text-center"
                                        href="{{ route('devolucion.create', ['idEstudiante' => $estudiante->idEstudiante]) }}">
                                        @if (Auth::user()->hasRole('padre'))
                                            Solicitar
                                        @else
                                            Nuevo
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="block w-full bg-success text-white font-medium py-2 px-4 rounded-md shadow hover:bg-success-dark transition text-center"
                                        href="{{ route('devolucion.index', ['buscarxEstudiante' => $estudiante->idEstudiante]) }}">
                                        Listar
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {{-- Condonación --}}
                        <div>
                            <h4 class="text-lg font-medium text-gray-700 text-center">Condonación</h4>
                            <ul class="mt-2 space-y-2">
                                <li>
                                    <a class="block w-full bg-success text-white font-medium py-2 px-4 rounded-md shadow hover:bg-success-dark transition text-center"
                                        href="{{ route('condonacion.create', ['codEstudiante' => $estudiante->idEstudiante]) }}">
                                        @if (Auth::user()->hasRole('padre'))
                                            Solicitar
                                        @else
                                            Nuevo
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="block w-full bg-success text-white font-medium py-2 px-4 rounded-md shadow hover:bg-success-dark transition text-center"
                                        href="{{ route('condonacion.index', ['codigoEstudiante' => $estudiante->idEstudiante, 'dniEstudiante' => $estudiante->DNI]) }}">
                                        Listar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </article>
            @endif

        </div>
    </section>

@endsection
@section('js')
    <script>
        function mostrarEscala() {
            var periodo = document.getElementById('periodo').value;
            var idestudiante = @json($estudiante->idEstudiante);
            fetch('/descripcionEscala/' + idestudiante + '/' + periodo)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('input[name="escala"]').value = data.descripcion;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        document.addEventListener('DOMContentLoaded', mostrarEscala);
    </script>
@endsection
