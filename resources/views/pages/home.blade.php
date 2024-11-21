@extends('layouts.layoutA')
@section('contenido')
    <section class="w-full">
        {{-- @livewire('navigation-menu') --}}
        <p class="opacity-50">Usted a inciado sesión como <strong>{{ Auth::user()->rol }}</strong></p>
        @if (Auth::user()->hasRole('admin'))
            <h1 class="text-7xl text-center font-thin">Sistema de Tesorería</h1>
        @endif
        @if (Auth::user()->hasRole('director'))
            <h1 class="text-7xl text-center font-thin">Sistema de Reportes - Tesorería</h1>
        @endif
        @if (Auth::user()->hasRole('padre'))
            <h1 class="text-7xl text-center font-thin">Tramites - Sideral Carrion</h1>
        @endif
        <br>
        <br>
        {{-- ejemplo de validacion según rol --}}
        @if (Auth::user()->hasRole('padre'))
            <article class="">
                <ul class="flex flex-wrap gap-6 w-full justify-center">
                    @foreach (Auth::user()->estudiantes as $estudiante)
                        {{ $estudiante->estudiante->getDeudasProximas() }}
                        <li
                            class="flex flex-col items-center gap-4 w-full md:w-1/2 lg:w-1/3 bg-white p-4 rounded-lg shadow-md border border-gray-200">
                            <!-- Encabezado del Estudiante -->
                            <div class="font-bold w-full border-b-2 border-gray-700 px-4 text-center text-gray-800 text-lg">
                                {{ $estudiante->estudiante->nombre }} {{ $estudiante->estudiante->apellidoP }}
                                <span class="text-gray-500 text-sm">({{ $estudiante->estudiante->DNI }})</span>
                            </div>

                            <!-- Sección de Deudas Vencidas -->
                            <p class="text-red-800 font-semibold mt-2">Deudas Vencidas</p>

                            @if ($estudiante->estudiante->getDeudasVencidas()->isEmpty())
                                <p class="text-sm text-gray-600">No hay deudas vencidas</p>
                            @else
                                <ul class="w-full space-y-2 mt-2">
                                    @foreach ($estudiante->estudiante->getDeudasVencidas() as $deuda)
                                        <li class="bg-red-100 border border-red-300 p-3 rounded-lg">
                                            <div class="flex items-start justify-between ">
                                                <div class="flex flex-col gap-1">
                                                    <span class="text-sm font-semibold text-red-700">Monto: S/
                                                        {{ number_format((float) $deuda->conceptoEscala->escala->monto + (float) $deuda->montoMora, 2) }}</span>
                                                    <span
                                                        class="text-sm font-semibold text-red-500 pl-2 opacity-80">Adelanto:
                                                        S/
                                                        {{ number_format((float) $deuda->adelanto, 2) }}</span>
                                                </div>
                                                <div class="">
                                                    <span class="text-xs text-red-600 font-medium">Vencido el:
                                                        {{ \Carbon\Carbon::parse($deuda->fechaLimite)->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-700 mt-1 font-bold">Registrado:
                                                {{ \Carbon\Carbon::parse($deuda->fechaRegistro)->format('d/m/Y') }}</p>

                                            @if ($deuda->totalCondonacion)
                                                <p class="text-xs text-green-600 font-medium mt-1">Condonación: S/
                                                    {{ number_format($deuda->totalCondonacion, 2) }}</p>
                                            @else
                                                <p class="text-xs text-gray-500 mt-1">Sin condonación</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

            </article>
        @endif


    </section>
@endsection
