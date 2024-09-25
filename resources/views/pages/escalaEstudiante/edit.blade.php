@extends('layouts.layoutA')
@section('titulo', '')
@section('contenido')
    <section class="shadow-3 border-neutral-400 px-16 pt-14 pb-10">
        <div class="text-center">
            <h3 class="text-xl">EDITAR ESCALA ESTUDIANTE</h3>
        </div>
        <form method="POST"
            action="{{ route('escalaEstudiante.update', ['idEstudiante' => $escalaEstudiante->idEstudiante, 'periodo' => $escalaEstudiante->periodo]) }}"
            class="flex justify-center space-y-8 flex-row">
            @csrf
            @method('PUT')
            <div class="p-4">
                <h4 class="text-xl">DATOS DE ESTUDIANTE</h4>
                <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="idEstudiante" name="idEstudiante" placeholder="ID Estudiante"
                        value="{{ $escalaEstudiante->idEstudiante ?? '' }}" readonly data-twe-input-showcounter="true"
                        maxlength="50" />
                    <label for="exampleFormControlInputCounter"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">ID
                        Estudiante</label>
                    <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                </div>
                <div class="flex flex-col w-full md:w-1/3 md:pr-4">
                    @error('idEstudiante')
                        <span class="text-red-700 text-xs" role="">
                            <i class="fa-solid fa-exclamation"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="observacion" name="observacion" placeholder="ID Estudiante"
                        value="{{ $escalaEstudiante->Estudiante->nombre ?? '' }}" readonly data-twe-input-showcounter="true"
                        maxlength="50" />
                    <label for="exampleFormControlInputCounter"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Nombre</label>
                    <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                </div>
                <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="observacion" name="observacion" placeholder="ID Estudiante"
                        value="{{ $escalaEstudiante->Estudiante->apellidoP ?? '' }}" readonly
                        data-twe-input-showcounter="true" maxlength="50" />
                    <label for="exampleFormControlInputCounter"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Apellido
                        Materno</label>
                    <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                </div>
                <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="observacion" name="observacion" placeholder="ID Estudiante"
                        value="{{ $escalaEstudiante->Estudiante->apellidoM ?? '' }}" readonly
                        data-twe-input-showcounter="true" maxlength="50" />
                    <label for="exampleFormControlInputCounter"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Apellido
                        Paterno</label>
                    <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                </div>
            </div>
            @error('idEstudiante')
                <span class="text-red-700 text-xs">
                    <i class="fa-solid fa-exclamation"></i>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="flex flex-col md:flex-row justify-between space-y-8 md:space-y-0">
                <div class="flex flex-col w-full md:w-3/3 md:pl-4">
                    <input type="text" name="idEscala" value="{{ $escalaEstudiante->escala->idEscala }}" class="hidden">
                    <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                        <input type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                            value="{{ $escalaEstudiante->escala->descripcion }}" placeholder="Id Escala"
                            data-twe-input-showcounter="true" maxlength="4" readonly />
                        <label for="exampleFormControlInputCounter"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Escala</label>
                        <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                    </div>
                    @error('idEscala')
                        <span class="text-red-700 text-xs" role="">
                            <i class="fa-solid fa-exclamation"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="relative max-w-sm mt-4">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="datepicker-format" name="fechaEE" value="{{ $escalaEstudiante->fechaEE }}" readonly
                            datepicker datepicker-format="yyyy-mm-dd" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Selecciona el Dia">
                    </div>
                    @error('fechaEE')
                        <span class="text-red-700 text-xs">
                            <i class="fa-solid fa-exclamation"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                        <input type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                            id="periodo" name="periodo" value="{{ $escalaEstudiante->periodo }}" readonly
                            placeholder="periodo" data-twe-input-showcounter="true" maxlength="4" />
                        <label for="exampleFormControlInputCounter"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Periodo</label>
                        <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                    </div>
                    @error('periodo')
                        <span class="text-red-700 text-xs" role="">
                            <i class="fa-solid fa-exclamation"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                        <input type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                            id="observacion" name="observacion" value="{{ $escalaEstudiante->observacion }}"
                            placeholder="Observación" data-twe-input-showcounter="true" maxlength="50" />
                        <label for="exampleFormControlInputCounter"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Observación</label>
                        <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                    </div>
                    @error('observacion')
                        <span class="text-red-700 text-xs" role="">
                            <i class="fa-solid fa-exclamation"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="flex space-x-4 pt-4">
                        <button type="submit"
                            class="inline-block rounded bg-success px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-success-1 transition duration-150 ease-in-out hover:bg-success-accent-300 hover:shadow-success-2 focus:bg-success-accent-300 focus:shadow-success-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-success-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">Guardar</button>
                        <a href="{{ route('escalaEstudiante.index') }}"
                            class="inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-dark-1 transition duration-150 ease-in-out hover:bg-neutral-700 hover:shadow-dark-2 focus:bg-neutral-700 focus:shadow-dark-2 focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-dark-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
