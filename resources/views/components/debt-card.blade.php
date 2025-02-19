@props(['deuda'])

<li
    class="bg-{{ $deuda->isVencida() ? 'red' : 'yellow' }}-100 border border-{{ $deuda->isVencida() ? 'red' : 'yellow' }}-300 p-4 rounded-lg shadow-sm">
    <div class="flex justify-between items-start">
        <div>
            <span class="text-sm font-semibold text-{{ $deuda->isVencida() ? 'red' : 'yellow' }}-700">
                Monto:
                S/{{ number_format((float) $deuda->conceptoEscala->escala->monto + (float) $deuda->montoMora, 2) }}
            </span>
            <span class="block text-sm font-semibold text-{{ $deuda->isVencida() ? 'red' : 'yellow' }}-500 opacity-80">
                Adelanto: S/{{ number_format((float) $deuda->adelanto, 2) }}
            </span>
        </div>
        <div class="text-right">
            <span class="text-xs font-medium text-{{ $deuda->isVencida() ? 'red' : 'yellow' }}-600">
                {{ $deuda->isVencida() ? 'Vencido el:' : 'Vencerá el:' }}
                {{ \Carbon\Carbon::parse($deuda->fechaLimite)->format('d/m/Y') }}
            </span>
        </div>
    </div>
    <p class="text-xs text-gray-700 mt-2 font-bold">
        Registrado: {{ \Carbon\Carbon::parse($deuda->fechaRegistro)->format('d/m/Y') }}
    </p>
    @if ($deuda->totalCondonacion)
        <p class="text-xs text-green-600 font-medium mt-1">
            Condonación: S/{{ number_format($deuda->totalCondonacion, 2) }}
        </p>
    @else
        <p class="text-xs text-gray-500 mt-1">Sin condonación</p>
    @endif
</li>
