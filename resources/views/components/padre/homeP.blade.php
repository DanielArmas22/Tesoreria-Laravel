<article class="">
    <div class="flex flex-col gap-4">
        <div>
            <h3 class="text-xl font-semibold text-gray-700">Deudas Vencidas</h3>
        </div>
        <ul class="flex flex-wrap gap-6 w-full justify-center">
            @if (Auth::user()->countTotalDeudas('vencidas') == 0)
                <p>No tiene deudas Vencidas</p>
            @else
                @foreach (Auth::user()->estudiantes as $estudiante)
                    @if ($estudiante->estudiante->getDeudasVencidas()->isEmpty())
                        {{-- <p class="text-sm text-gray-600">No hay deudas vencidas</p> --}}
                    @else
                        <li
                            class="flex flex-col items-center gap-4 w-full md:w-1/2 lg:w-1/3 bg-white p-4 rounded-lg shadow-md border border-gray-200">
                            <!-- Encabezado del Estudiante -->
                            <div
                                class="font-bold w-full border-b-2 border-gray-700 px-4 text-center text-gray-800 text-lg">
                                {{ ucfirst($estudiante->estudiante->nombre) }}
                                {{ ucfirst($estudiante->estudiante->apellidoP) }}
                                <span class="text-gray-500 text-sm">({{ $estudiante->estudiante->DNI }})</span>
                            </div>
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
            @endif
        </ul>

    </div>
    <div class="flex flex-col gap-4">
        <div>
            <h3 class="text-xl font-semibold text-gray-700">Deudas Proximas a Vencer</h3>
        </div>
        <ul class="flex flex-wrap gap-6 w-full justify-center">
            @if (Auth::user()->countTotalDeudas('proximas') == 0)
                <p>No tiene deudas Proximas a vencer</p>
            @else
                @foreach (Auth::user()->estudiantes as $estudiante)
                    @if (!$estudiante->estudiante->getDeudasProximas()->isEmpty())
                        {{-- <p class="text-sm text-gray-600">No hay deudas vencidas</p> --}}
                        <li
                            class="flex flex-col items-center gap-4 w-full md:w-1/2 lg:w-1/3 bg-white p-4 rounded-lg shadow-md border border-gray-200">
                            <!-- Encabezado del Estudiante -->
                            <div
                                class="font-bold w-full border-b-2 border-gray-700 px-4 text-center text-gray-800 text-lg">
                                {{ ucfirst($estudiante->estudiante->nombre) }}
                                {{ ucfirst($estudiante->estudiante->apellidoP) }}
                                <span class="text-gray-500 text-sm">({{ $estudiante->estudiante->DNI }})</span>
                            </div>
                            <ul class="w-full space-y-2 ">
                                @foreach ($estudiante->estudiante->getDeudasProximas() as $deuda)
                                    <li class="bg-yellow-100 border border-yellow-300 p-3 rounded-lg">
                                        <div class="flex items-start justify-between ">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-sm font-semibold text-yellow-700">Monto: S/
                                                    {{ number_format((float) $deuda->conceptoEscala->escala->monto + (float) $deuda->montoMora, 2) }}</span>
                                                <span
                                                    class="text-sm font-semibold text-yellow-500 pl-2 opacity-80">Adelanto:
                                                    S/
                                                    {{ number_format((float) $deuda->adelanto, 2) }}</span>
                                            </div>
                                            <div class="">
                                                <span class="text-xs text-yellow-600 font-medium">Vencerá el:
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
            @endif
        </ul>
    </div>
</article>
