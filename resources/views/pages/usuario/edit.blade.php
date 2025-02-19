@extends('layouts.layoutA')

@section('titulo', 'Editar Usuario')

@section('contenido')
    <section class="w-full flex flex-col justify-center items-center">
        <form action="{{ route('usuarios.update', $user->id) }}" method="POST"
            class="w-full max-w-md bg-white p-6 my-10 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    placeholder="Ingrese el nombre"
                    class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Correo Electrónico -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    placeholder="Ingrese el correo electrónico"
                    class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Ingrese la contraseña (opcional)"
                    class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmar
                    Contraseña:</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirme la contraseña"
                    class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Rol -->
            <div class="mb-6">
                <label for="rol" class="block text-gray-700 text-sm font-bold mb-2">Rol:</label>
                <select id="rol" name="rol"
                    class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Seleccione un rol</option>
                    <option value="director" {{ old('rol', $user->rol) == 'director' ? 'selected' : '' }}>Director</option>
                    <option value="secretario" {{ old('rol', $user->rol) == 'secretario' ? 'selected' : '' }}>Secretario
                    </option>
                    <option value="tesorero" {{ old('rol', $user->rol) == 'tesorero' ? 'selected' : '' }}>Tesorero</option>
                    <option value="cajero" {{ old('rol', $user->rol) == 'cajero' ? 'selected' : '' }}>Cajero</option>
                </select>
                @error('rol')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones de Editar, Cancelar y Eliminar -->
            <div class="flex justify-center px-10 mt-6 gap-5 flex-wrap ">
                <button
                    class="hover:scale-105 hover:transition hover:duration-500 hover:ease-in-out inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-dark-1 transition duration-150 ease-in-out hover:bg-neutral-700 hover:shadow-dark-2 focus:bg-neutral-700 focus:shadow-dark-2 focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-dark-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                    <a href="{{ route('usuarios.index') }}">Cancelar</a>
                </button>
                <button type="submit"
                    class="hover:scale-105 hover:transition hover:duration-500 hover:ease-in-out bg-blue-500 shadow-xl hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Actualizar Usuario
                </button>
                <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST"
                    onsubmit="return confirm('¿Está seguro de que desea eliminar al usuario {{ $user->name }}? Esta acción no se puede deshacer.');"
                    class="ml-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Eliminar Usuario
                    </button>
                </form>
            </div>

        </form>
    </section>
@endsection
