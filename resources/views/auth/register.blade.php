<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
        @isset($role)
            <p>rol: {{ $role }}</p>
        @endisset
        <form method="POST"
            action="
        @if (isset($role) && $role == 'padre') {{ route('register.padre') }}
        @elseif (isset($role) && $role == 'tesorero') {{ route('register.tesorero') }}
        @else
            {{ route('register') }} @endif
            ">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nombre') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Correo Electronico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
            </div>
            @if (isset($role) && $role == 'padre')
                <div class="mt-4">
                    <x-label for="idEstudiante" value="{{ __('Codigo del Estudiante') }}" />
                    <x-input id="idEstudiante" class="block mt-1 w-full" type="text" name="idEstudiante"
                        :value="old('idEstudiante')" required autocomplete="username" />
                </div>
            @endif

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    href="{{ route('login') }}">
                    {{ __('Ya tienes una cuenta?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Registrarme') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
