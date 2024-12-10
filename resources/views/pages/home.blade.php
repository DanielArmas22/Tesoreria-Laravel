@extends('layouts.layoutA')

@section('contenido')
    <section class="w-full px-4 py-8 bg-gray-100 min-h-screen flex flex-col items-center">
        <!-- Mensaje de Rol -->
        <div class="mb-6">
            <p class="text-sm text-gray-600">
                Usted ha iniciado sesión como <strong class="font-semibold">{{ ucfirst(Auth::user()->rol) }}</strong>
            </p>
        </div>

        <!-- Títulos Según Rol -->
        <div class="mb-12">
            @if (Auth::user()->hasRole('admin'))
                <h1 class="text-4xl md:text-5xl font-semibold text-gray-800 text-center">
                    Sistema de Tesorería
                </h1>
            @endif

            @if (Auth::user()->hasRole('director'))
                <h1 class="text-4xl md:text-5xl font-semibold text-gray-800 text-center">
                    Sistema de Reportes - Tesorería
                </h1>
            @endif

            @if (Auth::user()->hasRole('padre'))
                <h1 class="text-4xl md:text-5xl font-semibold text-gray-800 text-center">
                    Trámites - Sideral Carrion
                </h1>
            @endif

            @if (Auth::user()->hasRole('secretario'))
                <h1 class="text-4xl md:text-5xl font-semibold text-gray-800 text-center">
                    Sistema de Registros - Sideral Carrion
                </h1>
            @endif
        </div>

        <!-- Contenido Principal Según Rol -->
        <div class="w-full max-w-7xl">
            @if (Auth::user()->hasRole('padre'))
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <x-padre.homeP />
                </div>
            @endif
            <!-- Puedes añadir más condiciones para otros roles si es necesario -->
        </div>
    </section>
@endsection
