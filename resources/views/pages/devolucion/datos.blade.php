@extends('layouts.layoutA')
@section('titulo', '')
@php
    $buttonClass = 'bg-blue-500 hover:bg-blue-700 text-white font-medium uppercase py-2 px-4 rounded-lg';
    $buttonElimiar =
        'rounded bg-danger px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-danger-3 transition duration-150 ease-in-out hover:bg-danger-accent-300 hover:shadow-danger-2 focus:bg-danger-accent-300 focus:shadow-danger-2 focus:outline-none focus:ring-0 active:bg-danger-600 active:shadow-danger-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
    $buttonCancelar =
        'rounded bg-gray-700 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-gray-3 transition duration-150 ease-in-out hover:bg-gray-accent-300 hover:shadow-gray-2 focus:bg-gray-accent-300 focus:shadow-gray-2 focus:outline-none focus:ring-0 active:bg-gray-600 active:shadow-gray-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
@endphp
@section('contenido')
    @if (Auth::user()->hasRole("tesorero"))
        <section class="w-full flex flex-col justify-center">
            <article>
                <br>
                <div class="bg-gray-100">
                    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg pb-">
                        <form method="POST" action="{{ route('devolucion.actualizarSolicitud', $idDevolucion) }}">
                            <h1 class="text-center font-bold text-2xl mb-6">
                                EVALUAR DEVOLUCION
                            </h1>
                            @method('put')
                            @csrf
                            <div class="mb-4">
                                <x-textField label="Fecha Devolucion" name='fechaActual' valor="{{ $fechaActual }}"
                                    readonly="true" />
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-8">
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Datos del Estudiante</h3>
                                <div class="flex space-y-4 space-x-6 items-center ">
                                    <img src="/img/6073873.png" class="w-20" alt="">
                                    <div class="flex space-x-10 mb-4 px-6">
                                        <div class="">
                                            <x-textField label="Codigo" name='idEstudiante'
                                                valor="{{ $estudiante->idEstudiante }}" readonly="true" />
                                        </div>
                                        <div class="">
                                            <x-textField label="Nombre" name='nombre'
                                                valor="{{ $estudiante->nombre }}, {{ $estudiante->apellidoP }} {{ $estudiante->apellidoM }}"
                                                readonly="true" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <h3 class="text-xl font-semibold mb-4 text-gray-800">Devolucion</h3>
                            <div class="mb-4 items-center">
                                <x-textField label="Numero de Operacion" name='nroOperacion' valor="{{ $operacion }} "
                                    readonly="true" />
                            </div>
                            <div class="pt-2">
                                <x-textField label="Motivo" name='motivoDevolucion' valor="{{ $motivoDevolucion }} "
                                    readonly="true" />
                            </div>
                            <div class="pt-4">
                                <x-textField label="Observacion" name='observacion' 
                                    readonly="false" />
                            </div>
                            <article>
                                <br>
                                <div class="w-90">
                                    <table class="table-auto w-full text-left">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 border-b">Codigo Deuda</th>
                                                <th class="px-4 py-2 border-b">Concepto</th>
                                                <th class="px-4 py-2 border-b">Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600">
                                            @if (count($deudas) <= 0)
                                                <tr>
                                                    <td class="border px-4 py-2" colspan="7">No hay registros</td>
                                                </tr>
                                            @else
                                                @foreach ($deudas as $d)
                                                    <tr>
                                                        <td class="border px-4 py-2">{{ $d->idDeuda }}</td>
                                                        <td class="border px-4 py-2">{{ $d->descripcion }}</td>
                                                        <td class="border px-4 py-2">{{ $d->monto }}</td>
                                                        <input type="hidden" name="deudas[]" value="{{ $d->idDeuda }}">
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>

                                    </table>
                                    <div class="flex justify-center space-x-6 pt-6">
                                        <button type="submit" name="action" value="rechazar"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Rechazar
                                        </button>
                                        <button type="submit" name="action" value="aprobar"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Aprobar
                                        </button>
                                    </div>
                                </div>
                                <br>
                            </article>
                        </form>
                    </div>
                </div>
            </article>
        </section>
    @else
        <section class="w-full flex flex-col justify-center">
            <article>
                <br>
                <div class="bg-gray-100">
                    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg pb-">
                        <form method="GET" action="">
                            <h1 class="text-center font-bold text-2xl mb-6">
                                CONFIRMAR DEVOLUCION
                            </h1>
                            @method('put')
                            @csrf
                            <div class="mb-4">
                                <x-textField label="Fecha Devolucion" name='fechaActual' valor="{{ $fechaActual }}"
                                    readonly="true" />
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-8">
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Datos del Estudiante</h3>
                                <div class="flex space-y-4 space-x-6 items-center ">
                                    <img src="/img/6073873.png" class="w-20" alt="">
                                    <div class="flex space-x-10 mb-4 px-6">
                                        <div class="">
                                            <x-textField label="Codigo" name='idEstudiante'
                                                valor="{{ $estudiante->idEstudiante }}" readonly="true" />
                                        </div>
                                        <div class="">
                                            <x-textField label="Nombre" name='nombre'
                                                valor="{{ $estudiante->nombre }}, {{ $estudiante->apellidoP }} {{ $estudiante->apellidoM }}"
                                                readonly="true" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <h3 class="text-xl font-semibold mb-4 text-gray-800">Devolucion</h3>
                            <div class="mb-4 items-center">
                                <x-textField label="Numero de Operacion" name='nroOperacion' valor="{{ $operacion }} "
                                    readonly="true" />
                            </div>
                            <div class="pt-2">
                                <x-textField label="Observacion" name='observacion' valor="{{ $observacion }} "
                                    readonly="true" />
                            </div>

                            <article>
                                <br>
                                <div class="w-90">
                                    <table class="table-auto w-full text-left">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 border-b">Codigo Deuda</th>
                                                <th class="px-4 py-2 border-b">Concepto</th>
                                                <th class="px-4 py-2 border-b">Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600">
                                            @if (count($deudas) <= 0)
                                                <tr>
                                                    <td class="border px-4 py-2" colspan="7">No hay registros</td>
                                                </tr>
                                            @else
                                                @foreach ($deudas as $d)
                                                    <tr>
                                                        <td class="border px-4 py-2">{{ $d->idDeuda }}</td>
                                                        <td class="border px-4 py-2">{{ $d->descripcion }}</td>
                                                        <td class="border px-4 py-2">{{ $d->monto }}</td>
                                                        <input type="hidden" name="deudas[]" value="{{ $d->idDeuda }}">
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>

                                    </table>
                                    <div class="flex justify-center space-x-6 pt-6">
                                        <a href="{{ route('cancelarDevolucion') }}"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Regresar
                                        </a>
                                    </div>
                                </div>
                                <br>
                            </article>
                        </form>
                    </div>
                </div>
            </article>
        </section>
    @endif
@endsection
