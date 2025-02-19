@extends('layouts.layoutA')

@section('titulo', 'Editar Escala')

@section('contenido')
    <section class="w-full flex flex-col justify-center items-center">
        <form action="{{ route('escala.update', $escala->idEscala) }}" method="POST"
            class="w-full max-w-md bg-white p-6 my-10 rounded-lg shadow-md">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="monto" class="block text-gray-700 text-sm font-bold mb-2">Monto:</label>
                <input type="number" step="0.01" id="monto" name="monto" value="{{ $escala->monto }}"
                    placeholder="Ingrese el monto"
                    class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="mb-6">
                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" value="{{ $escala->descripcion }}"
                    placeholder="Ingrese la descripción"
                    class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex justify-between px-10">
                <button
                    class="hover:scale-105 hover:transition hover:duration-500 hover:ease-in-out inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-dark-1 transition duration-150 ease-in-out hover:bg-neutral-700 hover:shadow-dark-2 focus:bg-neutral-700 focus:shadow-dark-2 focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-dark-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                    <a href="{{ route('escala.index') }}">Cancelar</a>
                </button>
                <button type="submit"
                    class="hover:scale-105 hover:transition hover:duration-500 hover:ease-in-out bg-blue-500 shadow-xl hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Actualizar Escala
                </button>

            </div>
        </form>
    </section>
@endsection
