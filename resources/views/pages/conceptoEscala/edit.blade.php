@extends('layouts.layoutA')
@section('titulo', 'Editar Estudiante')
@section('contenido')
    <section class=" shadow-3 border-neutral-400 px-16 pt-14 pb-10 mx-auto">
        <form method="POST" action="{{ route('conceptoEscala.update', $conceptoEscala->idConceptoEscala) }}"
            class="flex justify-center space-y-6 flex-col mx-auto">
            @method('PUT')
            @csrf
            <div>
                <h3 class="text-xl text-center">EDITAR</h3>
            </div>
            <div class="relative mb-3" data-twe-input-wrapper-init>
                <label for="precio" class="absolute -top-3 left-3 bg-white px-1 text-xs">ID</label>
                <input type="text"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="precio" name="precio" value="{{ $conceptoEscala->idConceptoEscala }}" disabled>
            </div>

            <div class="relative mb-3" data-twe-input-wrapper-init>
                <label for="descripcion" class="absolute -top-3 left-3 bg-white px-1 text-xs">Descripción</label>
                <input type="text"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="descripcion" name="descripcion" value="{{ $conceptoEscala->descripcion }}">
            </div>
            @error('descripcion')
                <span class="text-red-700 text-xs" role="">
                    <i class="fa-solid fa-exclamation"></i>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="relative mb-3" data-twe-input-wrapper-init>
                <label for="conMora" class="absolute -top-3 left-3 bg-white px-1 text-xs">Con Mora</label>
                <input type="text"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="conMora" name="conMora" value="{{ $conceptoEscala->conMora }}">
            </div>
            @error('conMora')
                <span class="text-red-700 text-xs" role="">
                    <i class="fa-solid fa-exclamation"></i>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror


            <div>
                <p>Escala</p>
                <div class="relative mb-3 border rounded-md">
                    <select
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0 form-control @error('idEstudiante') isinvalid @enderror"
                        id="idEscala" name="idEscala">
                        <option value="{{ $conceptoEscala->escala->idescala }}">{{ $conceptoEscala->escala->descripcion }}
                        </option>
                        @foreach ($escala as $es)
                            @if ($es->descripcion !== $conceptoEscala->escala->descripcion)
                                <option value="{{ $es->idEscala }}">{{ $es->descripcion }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @error('idEscala')
                <span class="text-red-700 text-xs" role="">
                    <i class="fa-solid fa-exclamation"></i>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror


            <div class="relative mb-3" data-twe-input-wrapper-init>
                <label for="nmes" class="absolute -top-3 left-3 bg-white px-1 text-xs">N Mes</label>
                <input type="text"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="nmes" name="nmes" value="{{ $conceptoEscala->nmes }}">
            </div>
            @error('nmes')
                <span class="text-red-700 text-xs" role="">
                    <i class="fa-solid fa-exclamation"></i>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="space-x-4 pt-4">
                <button type="submit"
                    class="hover:scale-105 inline-block rounded bg-success px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-success-1 hover:transition hover:duration-500 hover:ease-in-out hover:bg-success-accent-300 hover:shadow-success-2 focus:bg-success-accent-300 focus:shadow-success-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-success-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                    <a>Guardar</a>
                </button>
                <button
                    class="hover:scale-105 inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-dark-1 hover:transition hover:duration-500 hover:ease-in-out hover:bg-neutral-700 hover:shadow-dark-2 focus:bg-neutral-700 focus:shadow-dark-2 focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-dark-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                    <a href="{{ route('conceptoEscala.index') }}">Cancelar</a>
                </button>
            </div>

        </form>
    </section>
@endsection
