@extends('layouts.layoutA')
@section('titulo', 'Eliminar Condonacion')

@section('contenido')
    <section class="flex justify-center flex-col">
        <article class="flex flex-col gap-3">
            <x-textField label="Codigo" name='id' placeholder='id' :message={{ $message }}
                valor="{{ old('idCondonacion', $condonacion->idCondonacion) }}" readonly="true" />
            <x-textField label="idDeuda" name='idDeuda' placeholder='idDeuda' :message={{ $message }}
                valor="{{ old('idDeuda', $condonacion->idDeuda) }}" readonly="true" />
            <x-textField label="monto" name='monto' placeholder='monto' :message={{ $message }}
                valor="{{ old('monto', $condonacion->monto) }}" readonly="true" />
            <x-textField label="Fecha" name='fecha' placeholder='fecha' :message={{ $message }}
                valor="{{ old('fecha', $condonacion->fecha) }}" readonly="true" />
        </article>
        <br>
        {{-- <x-button label="Eliminar" ruta="condonacion.destroy" color="danger" datos="{{ $condonacion->idCondonacion }}" /> --}}
        <form action="{{ route('condonacion.destroy', $condonacion->idCondonacion) }}" method="POST">
            @method('delete')
            @csrf
            <button type="submit"
                class="rounded  px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center  bg-danger shadow-danger-3 hover:shadow-danger-2 hover:bg-danger-accent-300 focus:bg-danger-accent-300 active:bg-danger-600 focus:shadow-danger-2 active:shadow-danger-2">Eliminar</button>
        </form>

    </section>
@endsection
