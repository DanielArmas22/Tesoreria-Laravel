@extends('layouts.layoutA')
{{-- @section('titulo', 'Nuevo Estudiante') --}}
@section('contenido')
    <section class="w-3/4 flex flex-col justify-center mx-auto">
        <article class=" w-full mx-auto shadow-xl border-[1px] p-4 pb-8 px-6 rounded-3xl">
            <div class="rounded-full flex flex-col items-center py-8 px-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div class=" flex flex-col">
                {{-- encabezado --}}
                <div class="grid grid-cols-3 gap-5 px-2">
                    <div class="border-r-2 p-3 ">
                        <div class="h-full flex items-center">
                            <p class="text-2xl font-semibold">
                                Estudiante
                            </p>
                        </div>
                    </div>
                    {{-- form busqueda apoderado --}}
                    <div class="col-span-2 col-start-2 p-3">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-2xl font-semibold">
                                    Apoderado
                                </p>
                            </div>
                            <form class="flex gap-2 w-max" action="{{ route('buscarApoderado') }}" method="GET">
                                <input type="text" hidden name="nombre" value="xddd">
                                <x-textField label="DNI del Apoderado" placeholder="Busqueda por DNI" name="DNIApoderado"
                                    valor="{{ old('DNIApoderado') }}" />
                                <button
                                    class="flex justify-center bg-blue-500 hover:bg-blue-600 border-blue-500 hover:border-blue-600 text-sm border-4 text-white py-1 px-2 rounded"
                                    type="submit">
                                    <p>
                                        Buscar
                                    </p>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- form general --}}
                <form class="grid grid-cols-3 gap-5  px-2" action="{{ route('estudiante.store') }}" method="POST">
                    {{-- form estudiante --}}
                    <section class="col-span-1 p-3 border-r-2">
                        <div class="flex flex-col gap-3">
                            @csrf
                            <x-textField label="DNI" name='DNI' placeholder='DNI' :message={{ $message }}
                                valor="{{ old('DNI') }}" />
                            <x-textField label="nombre Completo" name='nombre' placeholder='nombre'
                                :message={{ $message }} valor="{{ old('nombre') }}" />
                            <x-textField label="apellido Paterno" name='apellidoP' placeholder='apellidoP'
                                :message={{ $message }} valor="{{ old('apellidoP') }}" />
                            <x-textField label="apellido Materno" name='apellidoM' placeholder='apellidoM'
                                :message={{ $message }} valor="{{ old('apellidoM') }}" />
                            <div class="relative h-10 w-full min-w-[200px] mx-auto">
                                <select
                                    class="peer h-full w-full rounded-[7px] border border-blue-gray-200 border-t-transparent bg-transparent px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 empty:!bg-gray-900 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
                                    name="aula">
                                    @foreach ($aulas as $grado)
                                        <option
                                            value="{{ $grado->gradoEstudiante }}-{{ $grado->seccion->seccionEstudiante }}">
                                            {{ $grado->grado->descripcionGrado }}
                                            {{ $grado->seccion->descripcionSeccion }}
                                        </option>
                                    @endforeach
                                </select>
                                <label
                                    class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-gray-900 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-gray-900 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-gray-900 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">Seleccione
                                    un Aula</label>
                            </div>
                            @error('aula')
                                <p class="text-red-700 text-xs font-bold"><i
                                        class="fa-solid fa-exclamation"></i>{{ $message }}
                                </p>
                            @enderror
                            <br>
                            <br>
                        </div>
                    </section>
                    {{-- form apoderado --}}
                    <section class="col-span-2 flex flex-col gap-3 p-3">

                        <div class="flex flex-col gap-3">
                            @csrf
                            <x-textField label="DNI" name='DNIApoderado' placeholder='DNI' :message={{ $message }}
                                valor="{{ session('apoderado')->DNI ?? old('DNIApoderado') }}" />
                            <x-textField label="nombre Completo" name='nombreApoderado' placeholder='nombreAp'
                                :message={{ $message }}
                                valor="{{ session('apoderado')->name ?? old('nombreApoderado') }}" />
                            <x-textField label="Correo Electrónico" name='emailApoderado' placeholder='correoAp'
                                :message={{ $message }}
                                valor="{{ session('apoderado')->email ?? old('emailApoderado') }}" type='email' />
                            <br>
                            <div class="flex gap-4 place-content-center">
                                <button
                                    class="bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2 rounded  flex items-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center"
                                    type="submit">Registrar</button>

                                <x-boton ruta="cancelarEstudiante" color="dark" label="Cancelar"></x-boton>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </article>
    </section>
@endsection
