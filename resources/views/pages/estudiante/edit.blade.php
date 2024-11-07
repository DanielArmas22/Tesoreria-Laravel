@extends('layouts.layoutA')
{{-- @section('titulo', 'Editar Estudiante') --}}
@section('contenido')
    @if (Auth::user()->hasRole('admin') or Auth::user()->hasRole('tesorero'))
        <section class="border-[1px] flex gap-4  px-4 py-8 shadow-xl  rounded-3xl mx-auto justify-between">
            <article class="border-r-2 border-r-blue-100 px-4">
                <div class="flex gap-4 flex-col items-center">
                    <div>
                        <p class="px-4 py-2 text-xl font-bold text-center">{{ ucfirst($estudiante->nombre) }}
                            <br>{{ ucfirst($estudiante->apellidoP) }} {{ ucfirst($estudiante->apellidoM) }}
                        </p>
                        <p class="px-4 py-2 text-xl font-light text-center" id="idEstudiante">{{ $estudiante->idEstudiante }}
                        </p>
                    </div>
                </div>
                <br>
                <div class="lg:w-1/2 w-3/4 flex justify-center mx-auto">
                    <form class="flex flex-col gap-3" action="{{ route('estudiante.update', $estudiante->idEstudiante) }}"
                        method="POST">
                        @csrf
                        @method('put')
                        {{-- csrf: importante  --}}
                        <x-textField label="DNI" name='DNI' placeholder='DNI' :message={{ $message }}
                            valor="{{ old('DNI', $estudiante->DNI) }}" />
                        <x-textField label="Nombre" name='nombre' placeholder='nombre' :message={{ $message }}
                            valor="{{ old('nombre', $estudiante->nombre) }}" />
                        <x-textField label="Apellido Paterno" name='apellidoP' placeholder='apellidoP'
                            :message={{ $message }} valor="{{ old('apellidoP', $estudiante->apellidoP) }}" />
                        <x-textField label="Apellido Materno" name='apellidoM' placeholder='apellidoM'
                            :message={{ $message }} valor="{{ old('apellidoM', $estudiante->apellidoM) }}" />
                        {{-- <div class="flex gap-4">
                        <div>
                            <x-textField label="Grado" name='grado' placeholder='grado' :message={{ $message }}
                                valor="{{ old('grado', $estudiante->grado) }}" />
                        </div>
                        <div>
                            <x-textField label="Seccion" name='seccion' placeholder='seccion' :message={{ $message }}
                                valor="{{ old('seccion', $estudiante->seccion) }}" />
                        </div>
                    </div> --}}
                        <select class="rounded-lg" name="aula">
                            {{-- <option value="">Seleccione un Aula</option> --}}
                            @foreach ($aulas as $grado)
                                <option
                                    value="{{ $grado->grado->gradoEstudiante }}-{{ $grado->seccion->seccionEstudiante }}"
                                    {{ $grado->gradoEstudiante == $estudiante->gradoEstudiante && $grado->seccion->seccionEstudiante == $estudiante->seccionEstudiante ? 'selected' : '' }}>
                                    {{ $grado->grado->descripcionGrado }} {{ $grado->seccion->descripcionSeccion }}
                                </option>
                            @endforeach
                        </select>
                        @if ($escalas->isEmpty())
                            <div class="border-y-2">
                                <h4 class="text-center my-1">Escala sin Asignar</h4>
                                <a class="rounded flex items-center justify-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center  bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2"
                                    href="{{ route('escalaEstudiante.create', ['buscarEstudiante' => $estudiante->idEstudiante]) }}">Asignar
                                    Escala</a>
                            </div>
                            <br>
                        @else
                            <h4 class="text-center my-1">Escala</h4>
                            <div class ="flex gap-4">
                                <select class="w-full rounded-lg" name="periodo" id="periodo" onchange="mostrarEscala()">
                                    @foreach ($periodos as $peri)
                                        <option value="{{ $peri }}">{{ $peri }}</option>
                                    @endforeach
                                </select>
                                {{-- <h3>{{ $escalas->periodo }}</h3> --}}
                                <x-textField label="Escala" name='escala' placeholder='Escala'
                                    :message={{ $message }} valor="a" readonly="true" />
                            </div>
                        @endif
                        <div class="flex flex-col justify-center gap-4">
                            <div class="lg:flex-row flex flex-col justify-center gap-4">
                                <button
                                    class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                    type="submit">Actualizar</button>
                                <x-button ruta="cancelarEstudiante" color="dark" label="Cancelar"></x-button>
                            </div>
                            <div class="flex justify-center">
                                <x-button ruta="estudiante.confirmar" color="danger" label="Eliminar"
                                    datos="{{ $estudiante->idEstudiante }}" />

                            </div>
                        </div>
                    </form>

                </div>
            </article>
            <article class="py-4">
                <h3 class="text-xl font-semibold">Deudas Actuales</h3>
                <br>
                <x-table :cabeceras="['Codigo', 'Escala', 'Monto', 'Fecha', 'Adelanto', 'Condonado', 'Total a Pagar']" :datos="$deudas" :atributos="[
                    'idDeuda',
                    'conceptoEscala',
                    'montoTotal',
                    'fechaLimite',
                    'adelanto',
                    'totalCondonacion',
                    'totalPagar',
                ]" ruta="deuda.edit" id="idDeuda" />
                <br>

                <article class="flex justify-start">
                    <x-button label="Nueva Deuda" ruta="estudiante.create" color="primary" />
                </article>
            </article>
            <article class="px-2 w-1/6 border-l border-blue-100 ">
                <h3 class="text-xl font-semibold text-center">Opciones</h3>
                <div class="text-center flex flex-col items-center justify-center h-4/5">
                    <h4 class="text-lg">Pagos</h4>
                    <br>
                    <ul class="flex gap-2 p-3">
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('pago.show', ['idEstudiante' => $estudiante->idEstudiante]) }}">Nuevo
                            </a></li>
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('pago.index', ['buscarCodigo' => $estudiante->idEstudiante]) }}">Listar
                            </a></li>
                    </ul>
                    <br>
                    <h4 class="text-lg">Devolucion</h4>
                    <br>
                    <ul class="flex gap-2 p-3">
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('devolucion.create', ['idEstudiante' => $estudiante->idEstudiante]) }}">Nuevo
                            </a></li>
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('devolucion.index', ['buscarxEstudiante' => $estudiante->idEstudiante]) }}">Listar
                            </a></li>
                    </ul>
                    <br>
                    <h4 class="text-lg">Condonacion</h4>
                    <br>
                    <ul class="flex gap-2 p-3">
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('condonacion.create', ['idEstudiante' => $estudiante->idEstudiante]) }}">Nueva
                            </a></li>
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('condonacion.index', ['codigoEstudiante' => $estudiante->idEstudiante, 'dniEstudiante' => $estudiante->DNI]) }}">Listar
                            </a></li>
                    </ul>
                </div>
            </article>
        </section>
    @else
        <section class="border-[1px] flex gap-4  px-4 py-8 shadow-xl  rounded-3xl mx-auto justify-between">
            <article class="border-r-2 border-r-blue-100 px-4">
                <div class="flex gap-4 flex-col items-center">
                    <div>
                        <p class="px-4 py-2 text-xl font-bold text-center">{{ ucfirst($estudiante->nombre) }}
                            <br>{{ ucfirst($estudiante->apellidoP) }} {{ ucfirst($estudiante->apellidoM) }}
                        </p>
                        <p class="px-4 py-2 text-xl font-light text-center" id="idEstudiante">
                            {{ $estudiante->idEstudiante }}</p>
                    </div>
                </div>
                <br>
                <div class="lg:w-1/2 w-3/4 flex justify-center mx-auto">
                    <form class="flex flex-col gap-3" action="{{ route('estudiante.update', $estudiante->idEstudiante) }}"
                        method="POST">
                        @csrf
                        @method('put')
                        {{-- csrf: importante  --}}
                        <x-textField label="DNI" name='DNI' placeholder='DNI' :message={{ $message }}
                            valor="{{ old('DNI', $estudiante->DNI) }}" />
                        <x-textField label="Nombre" name='nombre' placeholder='nombre' :message={{ $message }}
                            valor="{{ old('nombre', $estudiante->nombre) }}" />
                        <x-textField label="Apellido Paterno" name='apellidoP' placeholder='apellidoP'
                            :message={{ $message }} valor="{{ old('apellidoP', $estudiante->apellidoP) }}" />
                        <x-textField label="Apellido Materno" name='apellidoM' placeholder='apellidoM'
                            :message={{ $message }} valor="{{ old('apellidoM', $estudiante->apellidoM) }}" />
                        <select class="rounded-lg" name="aula">
                            {{-- <option value="">Seleccione un Aula</option> --}}
                            @foreach ($aulas as $grado)
                                <option
                                    value="{{ $grado->grado->gradoEstudiante }}-{{ $grado->seccion->seccionEstudiante }}"
                                    {{ $grado->gradoEstudiante == $estudiante->gradoEstudiante && $grado->seccion->seccionEstudiante == $estudiante->seccionEstudiante ? 'selected' : '' }}>
                                    {{ $grado->grado->descripcionGrado }} {{ $grado->seccion->descripcionSeccion }}
                                </option>
                            @endforeach
                        </select>
                        @if ($escalas->isEmpty())
                            <div class="border-y-2">
                                <h4 class="text-center my-1">Escala sin Asignar</h4>
                                <a class="rounded flex items-center justify-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center  bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2"
                                    href="{{ route('escalaEstudiante.create', ['buscarEstudiante' => $estudiante->idEstudiante]) }}">Asignar
                                    Escala</a>
                            </div>
                            <br>
                        @else
                            <h4 class="text-center my-1">Escala</h4>
                            <div class ="flex gap-4">
                                <select class="w-full rounded-lg" name="periodo" id="periodo"
                                    onchange="mostrarEscala()">
                                    @foreach ($periodos as $peri)
                                        <option value="{{ $peri }}">{{ $peri }}</option>
                                    @endforeach
                                </select>
                                {{-- <h3>{{ $escalas->periodo }}</h3> --}}
                                <x-textField label="Escala" name='escala' placeholder='Escala'
                                    :message={{ $message }} valor="a" readonly="true" />
                            </div>
                        @endif

                    </form>

                </div>
            </article>
            <article class="py-4">
                <h3 class="text-xl font-semibold">Deudas Actuales</h3>
                <br>
                <x-table :cabeceras="['Codigo', 'Escala', 'Monto', 'Fecha', 'Adelanto', 'Condonado', 'Total a Pagar']" :datos="$deudas" :atributos="[
                    'idDeuda',
                    'conceptoEscala',
                    'montoTotal',
                    'fechaLimite',
                    'adelanto',
                    'totalCondonacion',
                    'totalPagar',
                ]" id="idDeuda" />
                <br>

            </article>
            <article class="px-2 w-1/6 border-l border-blue-100 ">
                <h3 class="text-xl font-semibold text-center">Opciones</h3>
                <div class="text-center flex flex-col items-center justify-center h-4/5">
                    <h4 class="text-lg">Pagos</h4>
                    <br>
                    <ul class="flex gap-2 p-3">
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('pago.index', ['buscarCodigo' => $estudiante->idEstudiante]) }}">Listar
                            </a></li>
                    </ul>
                    <br>
                    <h4 class="text-lg">Devolucion</h4>
                    <br>
                    <ul class="flex gap-2 p-3">
                        <li> <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('devolucion.index', ['buscarxEstudiante' => $estudiante->idEstudiante]) }}">Listar
                            </a></li>
                    </ul>
                    <br>
                </div>
            </article>
        </section>
    @endif
@endsection
@section('js')
    <script>
        function mostrarEscala() {
            var periodo = document.getElementById('periodo').value;
            var idestudiante = @json($estudiante->idEstudiante);
            fetch('/descripcionEscala/' + idestudiante + '/' + periodo).then(response => response.json()).then(data => {
                document.querySelector('input[name="escala"]').value = data.descripcion;
            }).catch(error => {
                console.error('Error:', error);
            });
        }
        mostrarEscala();
    </script>
@endsection
