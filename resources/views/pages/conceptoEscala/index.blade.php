@extends('layouts.layoutA')
@section('titulo', 'Conceptos de Escala')
@php
    $buttonClass =
        'rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong';
@endphp
@section('contenido')
    <section class="flex items-center justify-center w-10/12 mx-auto">
        <div class="w-full flex flex-col justify-center shadow-light-2 p-6">
            <article>
                <br>
                <div class="w-90">
                    <table class="table-auto w-full" id="">
                        <thead class="border bg-slate-100">
                            <tr class="text-center">
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Descripcion
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Mora</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">ID escala</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Mes</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @if (count($conceptoEscala) <= 0)
                                <tr>
                                    <td class="border px-4 py-2" colspan="7">No hay registros</td>
                                </tr>
                            @else
                                @foreach ($conceptoEscala as $dato)
                                    <tr class="cursor-pointer hover:bg-slate-300 ">
                                        <td class="text-center border px-4 py-2">{{ $dato->idConceptoEscala }}</td>
                                        <td class="border px-4 py-2">{{ $dato->descripcion }}</td>
                                        <td class="text-center border px-4 py-2">{{ $dato->conMora }}</td>
                                        <td class="text-center border px-4 py-2">{{ $dato->escala->descripcion }}</td>
                                        <td class="text-center border px-4 py-2">{{ $dato->nMes }}</td>
                                        <td class="px-6 border flex justify-center space-x-5 py-1">
                                            <button type="button"
                                                class="hover:scale-105 inline-block rounded bg-success px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-success-3 hover:transition hover:duration-300 hover:ease-in-out hover:bg-success-accent-300 hover:shadow-success-2 focus:bg-success-accent-300 focus:shadow-success-2 focus:outline-none focus:ring-0 active:bg-success-600 active:shadow-success-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                                                <a
                                                    href="{{ route('conceptoEscala.edit', $dato->idConceptoEscala) }}">Editar</a>
                                            </button>
                                            <button type="button"
                                                class="hover:scale-105 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-danger-3 hover:transition hover:duration-300 hover:ease-in-out hover:bg-danger-accent-300 hover:shadow-danger-2 focus:bg-danger-accent-300 focus:shadow-danger-2 focus:outline-none focus:ring-0 active:bg-danger-600 active:shadow-danger-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong">
                                                <a
                                                    href="{{ route('conceptoEscala.confirmar', $dato->idConceptoEscala) }}">Eliminar</a>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $conceptoEscala->links() }}


                    <div class="flex w-full">
                    </div>
                </div>
                <br>
            </article>
            <article class="hover:scale-105 hover:transition hover:duration-500 hover:ease-in-out flex justify-center"><a
                    class="{{ $buttonClass }}" href="{{ route('conceptoEscala.create') }}">Agregar Concepto</a></article>

        </div>
        <div class="ml-20 shadow-dark-mild  h-max py-8 px-10">
            <h3 class="text-center font-bold">FILTROS</h3>
            <form class="form-inline my-2 my-lg-0  space-y-4 " method="GET">
                <div class="relative flex" data-twe-input-wrapper-init data-twe-input-group-ref>
                    <input type="search"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                        placeholder="Descripcion" aria-label="busqueda por descripcion" id="busqueda" name="busqueda"
                        aria-describedby="search-button" />

                </div>
                <div>
                    <p>Mora</p>
                    <div class="relative mb-3 border rounded-md">
                        <select
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0 form-control"
                            id="busquedaxMora" name="busquedaxMora">
                            <option value="{{ $buscarxMora }}">{{ $buscarxMora }}</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div>
                    <p>Escala</p>
                    <div class="relative mb-3 border rounded-md">
                        <select type="button" id="busquedaxEscala" name="busquedaxEscala"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0 form-control">
                            <option value="{{ $buscarxEscala }}">
                                @if ($buscarxEscala == 2)
                                    A
                                @elseif ($buscarxEscala == 3)
                                    B
                                @elseif ($buscarxEscala == 4)
                                    C
                                @elseif ($buscarxEscala == 5)
                                    D
                                @elseif ($buscarxEscala == 6)
                                    E
                                @else
                                    {{ $buscarxEscala }}
                                @endif
                            </option>
                            <option value="1">N</option>
                            <option value="2">A</option>
                            <option value="3">B</option>
                            <option value="4">C</option>
                            <option value="5">D</option>
                            <option value="6">E</option>
                        </select>
                    </div>
                </div>

                <div class="">
                    <p>Mes</p>
                    <div class="flex space-x-8 justify-center">
                        <div class="relative mb-3 border rounded-md">
                            <select id="busquedaxNmes1" name="busquedaxNmes1"
                                class="peer block min-h-[auto] w-20 rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0 form-control">
                                <option value="{{ $buscarxNmes1 }}">{{ $buscarxNmes1 }}</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option class="px-3" value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="relative mb-3 border rounded-md">
                            <select id="busquedaxNmes2" name="busquedaxNmes2"
                                class="peer block min-h-[auto] rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0 form-control w-20">
                                <option value="{{ $buscarxNmes2 }}">{{ $buscarxNmes2 }}</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                </div>

                <button
                    class="rounded hover:scale-105 py-2 w-full relative z-[2] -ms-0.5 flex items-center rounded-e bg-primary px-5  text-xs font-medium uppercase leading-normal text-white shadow-primary-3 hover:transition hover:duration-500 hover:ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                    type="submit" id="search-button" data-twe-ripple-init data-twe-ripple-color="light">
                    <span class="mx-auto [&>svg]:h-5 [&>svg]:w-5 flex space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M11 20q-.425 0-.712-.288T10 19v-6L4.2 5.6q-.375-.5-.112-1.05T5 4h14q.65 0 .913.55T19.8 5.6L14 13v6q0 .425-.288.713T13 20z" />
                        </svg>
                        <p>ver</p>
                    </span>
                </button>

            </form>
        </div>
    </section>
@endsection
<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        list-style: none;
        gap: 0.5rem;
        padding: 1rem 0rem;
        font-size: 1.25rem;
    }

    .active {
        background-color: #2563EB;
        color: white;
    }

    .page-item {
        padding: 0.5rem;

    }

    .page-item:hover {
        scale: 1.1;
        transition: 0.1s
    }
</style>
