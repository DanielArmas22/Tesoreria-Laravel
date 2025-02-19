@extends('layouts.layoutA')
@section('titulo', '')
@php
    $buttonClass = 'bg-blue-500 hover:bg-blue-700 text-white font-medium uppercase py-2 px-4 rounded-lg';
    $buttonCancelar =
        'rounded bg-danger px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-danger-3 transition duration-150 ease-in-out hover:bg-danger-accent-300 hover:shadow-danger-2 focus:bg-danger-accent-300 focus:shadow-danger-2 focus:outline-none focus:ring-0 active:bg-danger-600 active:shadow-danger-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
@endphp
@section('contenido')
    <section class="shadow-3 border-neutral-400 px-16 pb-10">
        <h1 class="text-center font-bold text-2xl mb-6">
            REGISTRAR DEUDA
        </h1>
        <form method="GET" class="flex items-center flex-col gap-2">
            <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                <input type="text"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                    id="buscarEstudiante" name="buscarEstudiante" value="{{ old('buscarEstudiante') }}"
                    placeholder="Buscar Estudiante" data-twe-input-showcounter="true" maxlength="10" />

                <label for="exampleFormControlInputCounter"
                    class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Buscar
                    Estudiante</label>
                <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
            </div>
            @if (session('mensaje'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('mensaje') }}</p>
                </div>
            @endif
            <button
                class=" rounded bg-blue-500 px-4 py-2 text-xs font-medium uppercase leading-normal text-white shadow transition duration-150 ease-in-out hover:bg-blue-600 focus:bg-blue-600 focus:outline-none focus:ring-0 active:bg-blue-700"
                type="submit"> Buscar
            </button>
        </form>
        <form method="POST" action="{{ route('deuda.store') }}" class="space-y-4 flex items-center gap-5">
            @csrf
            <div class="p-4">
                <h4 class="text-xl">DATOS DE ESTUDIANTE</h4>
                <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="idEstudiante" name="idEstudiante" placeholder="ID Estudiante"
                        value="{{ $estudiante->idEstudiante ?? '' }}" readonly data-twe-input-showcounter="true"
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
                        value="{{ $estudiante->nombre ?? '' }}" readonly data-twe-input-showcounter="true" maxlength="50" />
                    <label for="exampleFormControlInputCounter"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Nombre</label>
                    <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                </div>
                <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="observacion" name="observacion" placeholder="ID Estudiante"
                        value="{{ $estudiante->apellidoP ?? '' }}" readonly data-twe-input-showcounter="true"
                        maxlength="50" />
                    <label for="exampleFormControlInputCounter"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Apellido
                        Materno</label>
                    <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                </div>
                <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                    <input type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        id="observacion" name="observacion" placeholder="ID Estudiante"
                        value="{{ $estudiante->apellidoM ?? '' }}" readonly data-twe-input-showcounter="true"
                        maxlength="50" />
                    <label for="exampleFormControlInputCounter"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Apellido
                        Paterno</label>
                    <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between space-y-8 md:space-y-0">
                <input type="text" class="hidden" value="{{ $conceptoEscala->idConceptoEscala }}"
                    name="idConceptoEscala">
                <div class="flex flex-col w-full md:w-3/3 md:pl-4">
                    <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                        <input type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                            id="g" name="g" placeholder="Pago Total"
                            value="{{ $conceptoEscala->descripcion }}" readonly data-twe-input-showcounter="true"
                            maxlength="50" />
                        <label for="exampleFormControlInputCounter"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Concepto
                            Deuda</label>
                        <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                    </div>
                    <div class="relative mb-3 mt-4" data-twe-input-wrapper-init>
                        <input type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                            id="observacion" name="observacion" placeholder="Concepto Pago" value="{{ $escala->monto }}"
                            readonly data-twe-input-showcounter="true" maxlength="50" />
                        <label for="exampleFormControlInputCounter"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">Concepto
                            Pago</label>
                        <div class="absolute w-full text-sm text-neutral-500" data-twe-input-helper-ref></div>
                    </div>
                    <div class="mb-4">
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input id="datepicker-format" name="fechaLimite" datepicker datepicker-format="yyyy-mm-dd"
                                type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date">
                        </div>
                    </div>
                    <div class="flex justify-center space-x-4">
                        <button type="submit" class="{{ $buttonClass }}"><i class="fas fa-save"></i> Grabar</button>
                        <article class="flex justify-center"><a class="{{ $buttonCancelar }}"
                                href="{{ route('cancelarDeuda') }}">Cancelar</a></article>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var datepicker = document.getElementById('datepicker-format');
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
            var year = today.getFullYear();
            var todayString = year + '-' + month + '-' + day;
            datepicker.value = todayString;
        });
    </script>
@endsection
