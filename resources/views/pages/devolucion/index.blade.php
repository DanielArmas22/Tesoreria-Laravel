@extends('layouts.layoutA')
@section('titulo', 'Listar Devolucion')
@php
    $buttonClass =
        'rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
@endphp
@section('contenido')

    @if (Auth::user()->hasRole('admin') or Auth::user()->hasRole('tesorero'))
    <div class="container mx-auto">

        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Listado de Devoluciones</h2>
    
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
                                <option value="grade">Filtro por Grado y Sección</option>
                            </select>
                        </div>
    
                        <!-- Filtros por Estudiante -->
                        <div id="studentFilters" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="buscarCodigo" placeholder="ID Estudiante" class="w-full rounded border-gray-300 p-3"/>
                            <input type="text" name="dniEstudiante" placeholder="DNI" id="dniEstudiante" value="{{ $dniEstudiante }}" class="w-full rounded border-gray-300 p-3"/>
                            <input type="text" name="busquedaNombreEstudiante" placeholder="Nombres" id="busquedaNombreEstudiante" value="{{ $busquedaNombreEstudiante }}" class="w-full rounded border-gray-300 p-3"/>
                            <input type="text" name="busquedaApellidoEstudiante" id="busquedaApellidoEstudiante" value="{{ $busquedaApellidoEstudiante }}" placeholder="Apellidos" class="w-full rounded border-gray-300 p-3"/>
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
                                <input type="date" id="fechaInicio" name="fechaInicio" class="w-full rounded border-gray-300 p-3" placeholder="Fecha Inicio"/>
                            </div>
                            
                            <!-- Campo de Fecha Fin con Etiqueta -->
                            <div class="flex flex-col">
                                <label for="fechaFin" class="text-gray-700 mb-2">Fecha Fin</label>
                                <input type="date" id="fechaFin" name="fechaFin" class="w-full rounded border-gray-300 p-3" placeholder="Fecha Fin"/>
                            </div>
                            
                            <!-- Checkbox para Deuda del Día con Espacio Mejorado -->
                            <div class="flex items-center space-x-4">
                                <input
                                    class="relative h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-secondary-500 outline-none checked:border-primary checked:bg-primary checked:after:content-['✓'] hover:cursor-pointer"
                                    type="checkbox" name="DevolucionesHoy" value="si" id="DevolucionesHoy"
                                    {{ isset($DevolucionesHoy) ? 'Checked' : '' }} 
                                />
                                <label for="DevolucionesHoy" class="text-gray-700">Devoluciones del Día</label>
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
                            <p>
                                Buscar
                            </p>
                        </button>
                        <i class="fa-solid fa-broom"></i>
                        <x-button label="Limpiar" color="success" ruta="pago.index" />
                        <a class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                href="{{ route('devolucion.create') }}">Nueva Devolucion
                        </a>
                    </div>
                </form>
            </div>
    
            <!-- Tabla de Devoluciones -->
            <div class="overflow-x-auto">
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Código Devolucion</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nro Operacion</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pago Total</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ID Estudiante</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Seccion</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Observacion</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Opción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if (count($datos) <= 0)
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    No hay registros
                                </td>
                            </tr>
                        @else
                            @foreach ($datos as $dato)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->idDevolucion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->nroOperacion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->totalPago }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->idEstudiante }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->nombre }} {{ $dato->apellidoP }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->descripcionGrado }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->descripcionSeccion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->fechaDevolucion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $dato->observacion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        <form action="{{ route('devolucion.datos') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="idDevolucion"
                                                value="{{ $dato->idDevolucion }}">
                                            <input type="hidden" name="nroOperacion"
                                                value="{{ $dato->nroOperacion }}">
                                            <input type="hidden" name="idEstudiante"
                                                value="{{ $dato->idEstudiante }}">
                                            <input type="hidden" name="fechaDevolucion"
                                                value="{{ $dato->fechaDevolucion }}">
                                            <input type="hidden" name="observacion"
                                                value="{{ $dato->observacion }}">
                                            <button type="submit" class="{{ $buttonClass }}">Ver</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $datos->links() }}
                </div>
                <div class="lg:flex-row flex flex-col justify-center gap-4">
                    {{-- <a class="rounded flex items-center justify-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out focus:outline-none focus:ring-0 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2"
                        href="{{ route('generarPago', ['buscarCodigo' => $buscarCodigo, 'nroOperacion' => $nroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin, 'totalPago' => $totalPago, 'codMinimo'=>$codMinimo,
        'codMaximo'=>$codMaximo,'conceptoEscalas'=>$conceptoEscalas,'busquedaConcepto'=>$busquedaConcepto,'escalaF'=>$escalaF, 
        'grados'=>$grados, 'secciones'=>$secciones,'busquedaEscala'=>$busquedaEscala,'busquedaGrado'=>$busquedaGrado,
        'busquedaSeccion'=>$busquedaSeccion,'dniEstudiante'=>$dniEstudiante,'busquedaNombreEstudiante'=>$busquedaNombreEstudiante,
        'busquedaApellidoEstudiante'=>$busquedaApellidoEstudiante,'pagosHoy'=>$pagosHoy,'generarPDF' => true]) }}">
                        Reporte de Devoluciones</a> --}}
                </div>
            </div>
        </div>
    </div>
    @else
        <section class=" space-x-3 flex flex-col items-center">
            <form class="flex flex-col w-full mb-4 p-6 shadow-light-2" method="GET">
                <div class="flex flex-col items-center justify-center border-b space-y-6 border-blue-500 py-2 w-full">
                    <div class="flex space-x-4 w-full">
                        <input name="buscarxEstudiante" id="buscarxEstudiante" value="{{ $buscarxEstudiante }}"
                            class="appearance-none bg-transparent border-none w-full text-gray-700 py-1 px-2 leading-tight focus:outline-none border-b border-blue-500"
                            type="search" placeholder="ID Estudiante" aria-label="Search" hidden>
                    </div>
                </div>
            </form>
            <article class="flex flex-col items-center shadow-light-2 px-14 py-6 gap-4">
                <div class="w-90">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">ID Devolucion</th>
                                <th class="px-4 py-2 border-b">Nro Operacion</th>
                                <th class="px-4 py-2 border-b">Pago Total</th>
                                <th class="px-4 py-2 border-b">ID Estudiante</th>
                                <th class="px-4 py-2 border-b">Estudiante</th>
                                <th class="px-4 py-2 border-b">Fecha</th>
                                <th class="px-4 py-2 border-b">Observacion</th>
                                <th class="px-4 py-2 border-b text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @if (count($datos) <= 0)
                                <tr>
                                    <td class="border px-4 py-2" colspan="8">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($datos as $dato)
                                    <tr class="cursor-pointer hover:bg-gray-200">
                                        <td class="border px-4 py-2">{{ $dato->idDevolucion }}</td>
                                        <td class="border px-4 py-2">{{ $dato->nroOperacion }}</td>
                                        <td class="border px-4 py-2">{{ $dato->totalPago }}</td>
                                        <td class="border px-4 py-2">{{ $dato->idEstudiante }}</td>
                                        <td class="border px-4 py-2">{{ $dato->nombre }} {{ $dato->apellidoP }}</td>
                                        <td class="border px-4 py-2">{{ $dato->fechaDevolucion }} </td>
                                        <td class="border px-4 py-2">{{ $dato->observacion }}</td>
                                        <td class="border px-4 py-2 text-center">
                                            <form action="{{ route('devolucion.datos') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="idDevolucion"
                                                    value="{{ $dato->idDevolucion }}">
                                                <input type="hidden" name="nroOperacion"
                                                    value="{{ $dato->nroOperacion }}">
                                                <input type="hidden" name="idEstudiante"
                                                    value="{{ $dato->idEstudiante }}">
                                                <input type="hidden" name="fechaDevolucion"
                                                    value="{{ $dato->fechaDevolucion }}">
                                                <input type="hidden" name="observacion"
                                                    value="{{ $dato->observacion }}">
                                                <button type="submit" class="{{ $buttonClass }}">Ver</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $datos->links() }}
                </div>
            </article>

        </section>
    @endif
    <script>
        function toggleFilters() {
            const filterType = document.getElementById('filterType').value;
            document.getElementById('studentFilters').classList.add('hidden');
            document.getElementById('gradeFilters').classList.add('hidden');

            if (filterType === 'student') {
                document.getElementById('studentFilters').classList.remove('hidden');
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
