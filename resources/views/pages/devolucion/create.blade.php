@extends('layouts.layoutA')
@section('titulo', 'Pagos')
@section('contenido')
    @php
        if ($detalle_pago != null) {
            $groupedData = [];
            foreach ($detalle_pago as $d) {
                $groupedData[$d->nroOperacion][] = $d;
            }
        }
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
    @if(Auth::user()->rol != 'cajero')
        <div class="max-w-7xl bg-white rounded-lg shadow-md mx-auto p-8">
            @if (session('mensaje'))
                <div class="bg-green-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('mensaje') }}</p>
                </div>
            @endif
            <div class="grid gap-8 grid-cols-1 md:grid-cols-2">
                <!-- Búsqueda del Estudiante -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Buscar Estudiante</h2>
                    <form method="GET" class="flex space-x-4 items-center">
                        <div class="flex-grow">
                            <label for="codigo" class="block text-sm font-medium text-gray-700">Código del Estudiante</label>
                            <input type="text" name="idEstudiante" id="idEstudiante"
                                placeholder="Ingrese el código del estudiante" value="{{ request('idEstudiante') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Buscar
                        </button>
                    </form>
                </div>

                <!-- Datos del Estudiante -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Datos del Estudiante</h3>
                    <div class="flex flex-col space-y-4">
                        <div class="flex space-x-4 mb-4">
                            <div class="flex-1">
                                <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                                <input type="text" id="dni" value="{{ $estudiante->DNI ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                            <div class="flex-1">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nombre" value="{{ $estudiante->nombre ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                        <div class="flex space-x-4 mb-4">
                            <div class="flex-1">
                                <label for="apellidoP" class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
                                <input type="text" id="apellidoP" value="{{ $estudiante->apellidoP ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                            <div class="flex-1">
                                <label for="apellidoM" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                                <input type="text" id="apellidoM" value="{{ $estudiante->apellidoM ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="grado" class="block text-sm font-medium text-gray-700">Grado</label>
                                <input type="text" id="grado" value="{{ $estudiante->descripcionGrado ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                            <div class="flex-1">
                                <label for="seccion" class="block text-sm font-medium text-gray-700">Sección</label>
                                <input type="text" id="seccion" value="{{ $estudiante->descripcionSeccion ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Lista de deudas seleccionadas para el pago -->
            <div class="bg-gray-50 rounded-lg shadow-sm mt-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Pagos</h2>
                <form id="pago-form" action="" method="GET">
                    @csrf
                    <input type="hidden" name="idestudiante" value="{{ $estudiante->idEstudiante ?? '' }}">
                    <table id="detalles" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border-b">Nro Operacion</th>
                                    <th class="px-4 py-2 border-b">Fecha de Pago</th>
                                    <th class="px-4 py-2 border-b">ID Deuda</th>
                                    <th class="px-4 py-2 border-b">Descripcion</th>
                                    <th class="px-4 py-2 border-b">Monto</th>
                                    <th class="px-4 py-2 border-b text-center">Opciones</th>
                                </tr>

                            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($detalle_pago == null)
                                <tr>
                                    <td class="border px-4 py-2" colspan="7">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($groupedData as $nroOperacion => $rows)
                                    <tr class="cursor-pointer hover:bg-gray-200">
                                        <td class="border px-4 py-2" rowspan="{{ count($rows) }}">{{ $nroOperacion }}</td>
                                        <td class="border px-4 py-2" rowspan="{{ count($rows) }}">{{ $rows[0]->fechaPago }}
                                        </td>
                                        @foreach ($rows as $index => $d)
                                            @if ($index > 0)
                                    <tr class="cursor-pointer hover:bg-gray-200">
                                @endif
                                <td class="border px-4 py-2">{{ $d->idDeuda }}</td>
                                <td class="border px-4 py-2">{{ $d->descripcion }}</td>
                                <td class="border px-4 py-2">{{ $d->monto }}</td>
                                @if ($index == 0)
                                    <td class="border px-4 py-2" rowspan="{{ count($rows) }}">
                                        <button type="button"
                                            class="devolucion-btn hover:scale-105 inline-block rounded bg-success px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-danger-3 hover:transition hover:duration-300 hover:ease-in-out hover:bg-success-accent-300 hover:shadow-danger-2 focus:bg-success-accent-300 focus:shadow-danger-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-danger-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                            data-operacion="{{ $nroOperacion }}" data-deudas='@json($rows)'
                                            data-idestudiante="{{ $estudiante->idEstudiante ?? '' }}">
                                            Devolucion
                                        </button>
                                    </td>
                                @endif
                                @if ($index > 0)
                                    </tr>
                                @endif
                            @endforeach
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>

            <!-- </form> -->
        </div>
    @else
    <div class="flex space-x-6">
        <!-- Búsqueda del Estudiante -->
        <div class="p-6 rounded-lg shadow-xl ">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Buscar Estudiante</h2>
                <form method="GET" class="flex space-x-4 items-center">
                    
                    <div class="flex-grow">
                        <label for="codigo" class="block text-sm font-medium text-gray-700">Código del Estudiante</label>
                        <input type="text" name="idEstudiante" id="idEstudiante"
                                    placeholder="Ingrese el código del estudiante" value="{{ request('idEstudiante') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Buscar
                    </button>
                </form>
        </div>
        <div class="max-w-7xl bg-white rounded-lg shadow-md mx-auto p-8">
            @if (session('mensaje'))
                <div class="bg-green-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('mensaje') }}</p>
                </div>
            @endif

            <div class="grid gap-8 grid-cols-1 md:grid-cols-2">
                
                <!-- Datos del Estudiante -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Datos del Estudiante</h3>
                    <div class="flex flex-col space-y-4">
                        <div class="flex space-x-4 mb-4">
                            <div class="flex-1">
                                <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                                <input type="text" id="dni" value="{{ $estudiante->DNI ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                            <div class="flex-1">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nombre" value="{{ $estudiante->nombre ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                        <div class="flex space-x-4 mb-4">
                            <div class="flex-1">
                                <label for="apellidoP" class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
                                <input type="text" id="apellidoP" value="{{ $estudiante->apellidoP ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                            <div class="flex-1">
                                <label for="apellidoM" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                                <input type="text" id="apellidoM" value="{{ $estudiante->apellidoM ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="grado" class="block text-sm font-medium text-gray-700">Grado</label>
                                <input type="text" id="grado" value="{{ $estudiante->descripcionGrado ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                            <div class="flex-1">
                                <label for="seccion" class="block text-sm font-medium text-gray-700">Sección</label>
                                <input type="text" id="seccion" value="{{ $estudiante->descripcionSeccion ?? '' }}"
                                    class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Lista de deudas seleccionadas para el pago -->
            <div class="bg-gray-50 rounded-lg shadow-sm mt-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Pagos</h2>
                <form id="pago-form" action="" method="GET">
                    @csrf
                    <input type="hidden" name="idestudiante" value="{{ $estudiante->idEstudiante ?? '' }}">
                    <table id="detalles" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border-b">Nro Operacion</th>
                                    <th class="px-4 py-2 border-b">Fecha de Pago</th>
                                    <th class="px-4 py-2 border-b">ID Deuda</th>
                                    <th class="px-4 py-2 border-b">Descripcion</th>
                                    <th class="px-4 py-2 border-b">Monto</th>
                                    <th class="px-4 py-2 border-b text-center">Opciones</th>
                                </tr>

                            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($detalle_pago == null)
                                <tr>
                                    <td class="border px-4 py-2" colspan="7">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($groupedData as $nroOperacion => $rows)
                                    <tr class="cursor-pointer hover:bg-gray-200">
                                        <td class="border px-4 py-2" rowspan="{{ count($rows) }}">{{ $nroOperacion }}</td>
                                        <td class="border px-4 py-2" rowspan="{{ count($rows) }}">{{ $rows[0]->fechaPago }}
                                        </td>
                                        @foreach ($rows as $index => $d)
                                            @if ($index > 0)
                                    <tr class="cursor-pointer hover:bg-gray-200">
                                @endif
                                <td class="border px-4 py-2">{{ $d->idDeuda }}</td>
                                <td class="border px-4 py-2">{{ $d->descripcion }}</td>
                                <td class="border px-4 py-2">{{ $d->monto }}</td>
                                @if ($index == 0)
                                    <td class="border px-4 py-2" rowspan="{{ count($rows) }}">
                                        <button type="button"
                                            class="devolucion-btn hover:scale-105 inline-block rounded bg-success px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-danger-3 hover:transition hover:duration-300 hover:ease-in-out hover:bg-success-accent-300 hover:shadow-danger-2 focus:bg-success-accent-300 focus:shadow-danger-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-danger-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                                            data-operacion="{{ $nroOperacion }}" data-deudas='@json($rows)'
                                            data-idestudiante="{{ $estudiante->idEstudiante ?? '' }}">
                                            Devolucion
                                        </button>
                                    </td>
                                @endif
                                @if ($index > 0)
                                    </tr>
                                @endif
                            @endforeach
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>

            <!-- </form> -->
        </div>
    </div>
    @endif
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.devolucion-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var operacion = this.getAttribute('data-operacion');
                    var deudas = JSON.parse(this.getAttribute('data-deudas'));
                    var idEstudiante = this.getAttribute('data-idestudiante');
                    var deudaIds = deudas.map(deuda => deuda.idDeuda).join(',');

                    var url = '{{ route('devolucion.realizarDevolucion', ':operacion') }}'.replace(
                        ':operacion', operacion);
                    url += '?deudas=' + encodeURIComponent(deudaIds) + '&idEstudiante=' +
                        encodeURIComponent(idEstudiante);

                    window.location.href = url;
                });
            });
        });
    </script>
@endsection
