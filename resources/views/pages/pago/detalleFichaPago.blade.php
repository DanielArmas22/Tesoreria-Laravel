@extends('layouts.layoutA')
@section('titulo', 'Pagos')
@section('contenido')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="max-w-3xl mx-auto py-10 px-6 bg-gray-50 border border-gray-200 rounded-lg shadow-lg">

    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Ficha de Pago</h2>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <p class="text-gray-700"><strong>Número de Operación:</strong> {{ $pago->nroOperacion }}</p>
        <p class="text-gray-700"><strong>ID Estudiante:</strong> {{ $estudiante->idEstudiante }}</p>
        <p class="text-gray-700"><strong>Nombre Completo:</strong>
            {{ $estudiante->nombre }} {{ $estudiante->apellidoP }} {{ $estudiante->apellidoM }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Detalle del Pago</h3>
        <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2 text-left">Código</th>
                    <th class="px-4 py-2 text-left">Concepto Escala</th>
                    <th class="px-4 py-2 text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                <tr class="border-t border-gray-300">
                    <td class="px-4 py-2 text-gray-600 text-left">{{ $detalle->nroOperacion }}-{{ $detalle->idDeuda }}</td>
                    <td class="px-4 py-2 text-gray-600 text-left">{{$detalle->deuda->ConceptoEscala->descripcion}}</td>
                    <td class="px-4 py-2 text-gray-600 text-right">S/ {{ number_format($detalle->monto, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="mt-4 text-lg font-semibold text-gray-800 text-right">
            <strong>Monto Total Pagado:</strong> S/ {{ number_format($montoTotal, 2) }}
        </p>
    </div>

    <div class="my-4 flex justify-center">
        <select id="underline_select" class="block py-2.5 px-6 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
            <option class='' selected>Metodo de Pago</option>
            <option class='' value="1">Banco de Credito del Perú</option>
            <option class='' value="2">Interbank</option>
            <option class='' value="3">BBVA</option>
        </select>
    </div>

    <div class="flex justify-between mt-4 px-10">
        
        <a href="{{route('pago.fichapagos')}}">
            <div
                class="rounded hover:scale-105 py-2 relative z-[2] -ms-0.5 flex items-center rounded-e bg-black px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-secondary-900 hover:shadow-primary-2 focus:bg-secondary-900 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                id="search-button" data-twe-ripple-init data-twe-ripple-color="light">
                <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                    <i class="pt-1 fa-solid fa-arrow-left"></i>
                    <p class="flex justify-center items-center pl-2">Volver</p>
                </span>
            </div>
        </a>
        <a href="{{route('pago.actualizaFichaPago', $pago->nroOperacion)}}"
            class="rounded hover:scale-105 py-2 relative z-[2] -ms-0.5 flex items-center rounded-e bg-primary px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
            id="search-button" data-twe-ripple-init data-twe-ripple-color="light">
            <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                <i class="pt-1 pr-2 fa-solid fa-credit-card"></i>
                <p>Pagar</p>
            </span>
        </a>
    </div>

    

</div>

@endsection