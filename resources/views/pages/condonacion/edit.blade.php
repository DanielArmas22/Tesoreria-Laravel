@extends('layouts.layoutA')
@section('titulo', 'Editar condonacion')
@section('contenido')
    <section class="px-8 py-4 w-full flex justify-center">
        <div class="flex flex-col gap-4 rounded-2xl shadow-xl w-max p-4 py-8">
            {{-- condonacion --}}
            <div class="">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Detalles de la Condonación</h2>
            </div>
            <div class="flex flex-col gap-3 w-1/3 mx-auto">
                <div class="flex flex-col gap-5 items-center">
                    <x-textField :message={{ $message }} label="Codigo" name='Codigo' placeholder='Codigo'
                        valor="{{ $condonacion->idCondonacion }}" readonly="true" />
                    <x-textField label="Monto Condonado" name='monto' placeholder='nombre' :message={{ $message }}
                        valor="{{ old('nombre', $condonacion->totalMonto) }}" readonly="true" />
                    <x-textField label="Estado de la Condonación" name='monto' placeholder='nombre'
                        :message={{ $message }} valor="{{ old('estado', $estado) }}" readonly="true" />
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="datepicker" datepicker datepicker-format="yyyy-mm-dd" datepicker-orientation="top right"
                            type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Fecha" name="fecha" value="{{ $condonacion->fecha }}" disabled>
                    </div>
                    <div class="flex flex-row gap-2">
                        <div class="lg:flex-row flex flex-col justify-center gap-4">
                            <x-boton ruta="cancelarCondonacion" color="dark" label="Atras"></x-boton>
                        </div>
                        <div class="lg:flex-row flex flex-col justify-center gap-4">
                            <a class="rounded flex items-center justify-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center  bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2"
                                href="{{ route('generarCondonacion', ['id' => $condonacion->idCondonacion, 'generarPDF' => $condonacion->fecha]) }}">
                                Generar Ficha</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-5 items-center"">
                <h3 class="text-xl font-semibold">Deudas Condonadas</h3>
                <table class="w-full border border-gray-300 rounded-lg overflow-hidden" id="tabla_deudas">
                    <thead class="bg-gray-100">
                        <tr class="bg-gray-200 text-gray-700">
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Código
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Concepto
                                Escala</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto
                                Escala</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto
                                Mora</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Fecha
                                Límite</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Adelanto
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto
                                Condonado
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if (isset($detalle))
                            @foreach ($detalle as $deuda)
                                <tr class="border-t border-gray-300 text-center">
                                    <td class="px-6 py-4  text-sm font-medium text-gray-900">
                                        {{ $deuda->deuda->idDeuda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->deuda->conceptoEscala->descripcion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->deuda->conceptoEscala->escala->monto }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->deuda->montoMora }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->deuda->fechaLimite }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->deuda->adelanto }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deuda->monto ?? '0.0000' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="8">No
                                    se
                                    encontraron deudas</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if (isset($detalle))
                    <div class="mt-4">
                        {{ $detalle->links() }}
                    </div>
                @endif
            </div>
            {{-- condonacion --}}


    </section>

@endsection
