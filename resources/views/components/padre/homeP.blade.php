<article class="w-full space-y-12 px-4 py-8 bg-gray-100 min-h-screen">
    <!-- Sección de Deudas Vencidas -->
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold text-gray-800">Deudas Vencidas</h2>
            <span class="text-sm text-red-600">
                {{ Auth::user()->countTotalDeudas('vencidas') }} deuda(s) vencida(s)
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if (Auth::user()->countTotalDeudas('vencidas') == 0)
                <p class="col-span-full text-center text-gray-600">No tienes deudas vencidas.</p>
            @else
                @foreach (Auth::user()->estudiantes as $estudiante)
                    @if (!$estudiante->estudiante->getDeudasVencidas()->isEmpty())
                        @foreach ($estudiante->estudiante->getDeudasVencidas() as $deuda)
                            <a
                                href="{{ route('pago.show', ['idEstudiante' => $estudiante->estudiante->idEstudiante]) }}">
                                <div
                                    class="bg-white p-6 rounded-lg shadow-md border border-red-200 flex flex-col justify-between">
                                    <!-- Encabezado del Estudiante -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ ucfirst($estudiante->estudiante->nombre) }}
                                            {{ ucfirst($estudiante->estudiante->apellidoP) }}
                                        </h3>
                                        <span class="text-sm text-gray-500">DNI:
                                            {{ $estudiante->estudiante->DNI }}</span>
                                    </div>
                                    <!-- Detalles de la Deuda -->
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-700">
                                            Monto:
                                            S/{{ number_format((float) $deuda->conceptoEscala->escala->monto + (float) $deuda->montoMora, 2) }}
                                        </p>
                                        <p class="text-sm font-medium text-red-500 opacity-80">
                                            Adelanto: S/{{ number_format((float) $deuda->adelanto, 2) }}
                                        </p>
                                        <p class="text-xs text-red-600 font-medium mt-2">
                                            Vencido el:
                                            {{ \Carbon\Carbon::parse($deuda->fechaLimite)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-700 mt-1 font-bold">
                                            Registrado:
                                            {{ \Carbon\Carbon::parse($deuda->fechaRegistro)->format('d/m/Y') }}
                                        </p>
                                        @if ($deuda->totalCondonacion)
                                            <p class="text-xs text-green-600 font-medium mt-1">
                                                Condonación: S/{{ number_format($deuda->totalCondonacion, 2) }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">Sin condonación</p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>
    </section>

    <!-- Sección de Deudas Próximas a Vencer -->
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold text-gray-800">Deudas Próximas a Vencer</h2>
            <span class="text-sm text-yellow-600">
                {{ Auth::user()->countTotalDeudas('proximas') }} deuda(s) próxima(s) a vencer
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if (Auth::user()->countTotalDeudas('proximas') == 0)
                <p class="col-span-full text-center text-gray-600">No tienes deudas próximas a vencer.</p>
            @else
                @foreach (Auth::user()->estudiantes as $estudiante)
                    @if (!$estudiante->estudiante->getDeudasProximas()->isEmpty())
                        @foreach ($estudiante->estudiante->getDeudasProximas() as $deuda)
                            <a
                                href="{{ route('pago.show', ['idEstudiante' => $estudiante->estudiante->idEstudiante]) }}
                            ">
                                <div
                                    class="bg-white p-6 rounded-lg shadow-md border border-yellow-200 flex flex-col justify-between">
                                    <!-- Encabezado del Estudiante -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ ucfirst($estudiante->estudiante->nombre) }}
                                            {{ ucfirst($estudiante->estudiante->apellidoP) }}
                                        </h3>
                                        <span class="text-sm text-gray-500">DNI:
                                            {{ $estudiante->estudiante->DNI }}</span>
                                    </div>
                                    <!-- Detalles de la Deuda -->
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-yellow-700">
                                            Monto:
                                            S/{{ number_format((float) $deuda->conceptoEscala->escala->monto + (float) $deuda->montoMora, 2) }}
                                        </p>
                                        <p class="text-sm font-medium text-yellow-500 opacity-80">
                                            Adelanto: S/{{ number_format((float) $deuda->adelanto, 2) }}
                                        </p>
                                        <p class="text-xs text-yellow-600 font-medium mt-2">
                                            Vencerá el:
                                            {{ \Carbon\Carbon::parse($deuda->fechaLimite)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-700 mt-1 font-bold">
                                            Registrado:
                                            {{ \Carbon\Carbon::parse($deuda->fechaRegistro)->format('d/m/Y') }}
                                        </p>
                                        @if ($deuda->totalCondonacion)
                                            <p class="text-xs text-green-600 font-medium mt-1">
                                                Condonación: S/{{ number_format($deuda->totalCondonacion, 2) }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">Sin condonación</p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>
    </section>

    <!-- Botón de Acción Principal -->
    {{-- <div class="w-full flex justify-end">
        <a href="{{ route('devolucion.create') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>
            @if (Auth::user()->hasRole('padre'))
                Solicitar
            @endif
            Condonación
        </a>
    </div> --}}
</article>
