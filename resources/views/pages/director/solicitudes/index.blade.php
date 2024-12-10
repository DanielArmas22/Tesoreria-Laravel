@extends('layouts.layoutA')
@section('titulo', 'Listar Solicitudes')
@section('contenido')

<div class="flex flex-col space-y-6">
    <!-- Div para el filtro -->
    <div class="bg-gray-50 rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Filtros de busqueda</h2>
        <form method="GET" action="" class="flex flex-wrap items-end gap-4">
            <!-- Filtro por Grado -->
            <div>
                <label for="grado" class="block text-sm font-medium text-gray-700 mb-1">Grado</label>
                <select name="grado" id="grado" class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none">
                    <option value="">Todos</option>
                    @foreach($listadoGrados as $grado)
                        <option value="{{ $grado }}" {{ (request('grado') == $grado) ? 'selected' : '' }}>{{ $grado }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Sección -->
            <div>
                <label for="seccion" class="block text-sm font-medium text-gray-700 mb-1">Sección</label>
                <select name="seccion" id="seccion" class="form-select block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none">
                    <option value="">Todas</option>
                    @foreach($listadoSecciones as $seccion)
                        <option value="{{ $seccion }}" {{ (request('seccion') == $seccion) ? 'selected' : '' }}>{{ $seccion }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}"
                    class="form-input block w-full px-4 py-2 text-lg rounded-lg border-2 border-gray-300 focus:outline-none"/>
            </div>

            <!-- Botón Filtrar -->
            <div>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Div para la tabla -->
    <div class="bg-gray-50 rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Listado de Solicitudes de Condonación</h2>
        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ver</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Opciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if ($datos->count() <= 0)
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                No hay registros
                            </td>
                        </tr>
                    @else
                        @foreach ($datos as $itemdatos)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ $itemdatos->idCondonacion }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $itemdatos->fecha }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                <a href="{{ route('verSolicitud.pdf', $itemdatos->idCondonacion) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Ver
                                </a>
                            </td>
                            <form action="{{ route('solicitudes.observar', $itemdatos->idCondonacion) }}" method="POST" style="display:inline;">
                                @csrf
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <input type="text" name="observaciones" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        Observar
                                    </button>
                            </form>
                            <form action="{{ route('solicitudes.aceptar', $itemdatos->idCondonacion) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    Aceptar
                                </button>
                            </form>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="mt-4">
                {{ $datos->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</div>

@endsection
