@props(['title', 'deudas', 'icon'])

<div class="w-full space-y-4">
    <div class="flex items-center space-x-2">
        {{-- <x-dynamic-component :component="$icon" class="text-2xl text-gray-700" /> --}}
        <i class="{{ $icon }} text-2xl text-gray-700"></i>
        <h3 class="text-2xl font-semibold text-gray-700">{{ $title }}</h3>
    </div>
    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($deudas as $estudiante)
            @if (!$estudiante->estudiante->getDeudas($title)->isEmpty())
                <li class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex flex-col">
                    <!-- Encabezado del Estudiante -->
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-bold text-gray-800">
                            {{ ucfirst($estudiante->estudiante->nombre) }}
                            {{ ucfirst($estudiante->estudiante->apellidoP) }}
                        </h4>
                        <span class="text-sm text-gray-500">({{ $estudiante->estudiante->DNI }})</span>
                    </div>
                    <!-- Lista de Deudas -->
                    <ul class="space-y-4">
                        @foreach ($estudiante->estudiante->getDeudas($title) as $deuda)
                            <x-debt-card :deuda="$deuda" />
                        @endforeach
                    </ul>
                </li>
            @endif
        @empty
            <p class="text-center text-gray-600 col-span-3">No hay deudas para mostrar.</p>
        @endforelse
    </ul>
    @if ($deudas->isEmpty())
        <p class="text-center text-gray-600">No tiene {{ strtolower($title) }}.</p>
    @endif
</div>
