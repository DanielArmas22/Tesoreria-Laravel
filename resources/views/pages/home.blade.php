@extends('layouts.layoutA')
@section('contenido')
    <section class="w-full">
        {{-- @livewire('navigation-menu') --}}

        <h1 class="text-7xl text-center">Bienvenido a la Tesorería</h1>
        <br>
        <br>
        {{-- ejemplo de validacion según rol --}}
        <p>rol:
            @if (Auth::user()->hasRole('admin'))
                Admin
            @endif
            @if (Auth::user()->hasRole('director'))
                director jeje
            @endif
            @if (Auth::user()->hasRole('padre'))
                Padre
                <br>
                Estudiantes:
                <ul>
                    @foreach (Auth::user()->estudiantes as $estudiante)
                        <li class="font-bold">Hijo: {{ $estudiante->estudiante->nombre }}</li>
                        hola
                    @endforeach
                    hola queda
                    <br>
                    nombre: {{ Auth::user()->name }}
                    <br>
                    {{-- nombre: {{ Auth::user()->countEstudiantes }} --}}
            @endif

        </p>

    </section>
@endsection
