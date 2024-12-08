@props([
    'cabeceras',
    'datos',
    'nombreTabla',
    'atributos',
    'ruta',
    'id',
    'color' => '',
    'clickfila' => '',
    'isonclick' => 'true',
])

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
{{-- <h3 class="border-x-2 border-t-2">{{ $nombreTabla ??'' }}</h3> --}}
<table class="table-auto w-full text-left border-x-2 p-4 rounded-3xl {{ $color }}" id="">
    <thead class="bg-gray-100">
        <tr>
            @foreach ($cabeceras as $cabecera)
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ $cabecera }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody class="text-gray-600">
        @if (count($datos) <= 0)
            <tr>
                <td class="border px-4 py-2" colspan="9">No se encontraron {{ $nombreTabla ?? 'Registros' }}</td>
            </tr>
        @else
            @foreach ($datos as $dato)
                <tr class="cursor-pointer hover:bg-blue-100"
                    {{ !empty($ruta) ? 'data-url=' . route($ruta, $dato->$id) : '' }}
                    onclick="{{ $isonclick == 'true' ? 'clickfila(' . $dato->$id . ')' : '' }}">
                    @foreach ($atributos as $atributo)
                        <td class="border-b px-4 py-2 text-center">
                            @switch($atributo)
                                @case('estudiante')
                                    {{ $dato->estudiante->nombre }}
                                @break

                                @case('conceptoEscala')
                                    {{ $dato->conceptoEscala->descripcion }}
                                @break

                                @case('montoTotal')
                                    {{ (float) $dato->conceptoEscala->escala->monto + (float) $dato->montoMora }}
                                @break

                                @case('totalPagar')
                                    {{ (float) $dato->conceptoEscala->escala->monto + (float) $dato->montoMora - (float) $dato->adelanto - (float) $dato->totalCondonacion }}
                                @break

                                @case('descripcion')
                                    {{ $dato->descripcion ?? 'sin Escala' }}
                                @break

                                @case('periodo')
                                    {{ $dato->periodo ?? 'sin periodo' }}
                                @break

                                @case('totalCondonacion')
                                    {{ $dato->totalCondonacion ?? '0.0000' }}
                                @break

                                {{-- @case('gradoEstudiante')
                                    {{ $dato->detalle_estudiante_gs->Seccion->descripcionSeccion }}
                                    {{ $dato->detalle_estudiante_gs->Grado->descripcionGrado }}
                                @break --}}

                                {{-- @case('descripcionEstudiante')
                                    {{ $dato->detalle_estudiante_gs->seccion->descripcionSeccion }}
                                @break --}}

                                {{-- agregar si existen más claves foraneas --}}
                                @case('deuda')
                                    {{ $dato->deuda->idDeuda }}

                                    @default
                                        {{ $dato->$atributo }}
                                @endswitch
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    {{ $datos->links() }}
    @if (!empty($ruta))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rows = document.querySelectorAll('tr[data-url]');

                rows.forEach(row => {
                    row.addEventListener('click', function() {
                        window.location.href = this.dataset.url;
                    });
                });
            });
        </script>
    @endif
