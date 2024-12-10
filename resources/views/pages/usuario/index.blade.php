@extends('layouts.layoutA')
{{-- @section('titulo', 'Estudiantes Registrados') --}}
@section('contenido')
    <section class="w-full flex flex-col justify-center items-center">
        @if (session('datos'))
            @if (session()->has('color'))
                <x-alert :mensaje="session('datos')" :tipo="session('color')" />
            @else
                <!-- Si session('color') no está definido, se usa un valor predeterminado -->
                <x-alert :mensaje="session('datos')" tipo="success" />
            @endif
        @endif
        <section class="lg:flex gap-4 px-4 py-8 shadow-xl  rounded-3xl border-[1px]">
            <div class="">
                <article class="flex justify-start">
                    {{-- <button label="Nuevo Estudiante" ruta="estudiante.create" color="success" /> --}}
                    {{-- <form action="" method="GET">
                        <button type=""
                            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            
                    </form> --}}
                    <a href="{{ route('registarRol') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <i class="fas fa-plus pr-2"></i> Nuevo Usuario
                    </a>

                </article>
                <br>
                <x-table nombreTabla="Usuarios" :cabeceras="['Codigo', 'DNI', 'Nombre', 'Email', 'Rol']" :datos="$datos" :atributos="['id', 'DNI', 'name', 'email', 'rol']" isonclick='false' />
                <br>
            </div>
        </section>
    @endsection
