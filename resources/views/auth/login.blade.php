<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        {{-- <x-validation-errors class="mb-4" /> --}}

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession
        @isset($role)
            @if($role == 'admin')
                <p class="text-center my-2">Panel de Administrador</p>
            @else
                @if ($role == 'padre')
                    <p class="text-center my-2">Panel de Padres</p>
                @else
                    <p class="text-center my-2">{{$rol}}</p>
                @endif
            @endif
        @endisset
        @php
            $loginRoute = route('login');
            if (isset($role)) {
                switch ($role) {
                    case 'admin':
                        $loginRoute = route('login.admin');
                        break;
                    case 'padre':
                        $loginRoute = route('login.padre');
                        break;
                }
            }
        @endphp
        <form method="POST" action="{{ $loginRoute }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Correo Electronico') }}"></x-label>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" />
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recuerdame') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                {{-- @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}">
                        {{ __('Olvidaste tu Constraseña?') }}
                    </a>
                @endif --}}

                <x-button class="ms-4">
                    {{ __('Iniciar') }}
                </x-button>
            </div>
        </form>
        {{-- <div class="pt-4">
            <p class="text-sm text-gray-600 rounded-md">
                ¿No tienes una cuenta?</p>
            <a href="{{ route('register') }}"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Registrate
                aquí</a>
        </div> --}}
    </x-authentication-card>
</x-guest-layout>
