@extends('layouts.layoutA')
@section('contenido')
    <section class="w-full">
        {{-- @livewire('navigation-menu') --}}
        <p class="opacity-50">Usted a inciado sesión como <strong>{{ Auth::user()->rol }}</strong></p>
        @if (Auth::user()->hasRole('admin'))
            <h1 class="text-7xl text-center font-thin">Sistema de Tesorería</h1>
        @endif
        @if (Auth::user()->hasRole('director'))
            <h1 class="text-7xl text-center font-thin">Sistema de Reportes - Tesorería</h1>
        @endif
        @if (Auth::user()->hasRole('padre'))
            <h1 class="text-7xl text-center font-thin">Tramites - Sideral Carrion</h1>
        @endif
        @if (Auth::user()->hasRole('Secretaria'))
            <h1 class="text-7xl text-center font-thin">Sistema de Registros - Sideral Carrion</h1>
        @endif
        <br>
        <br>
        {{-- ejemplo de validacion según rol --}}
        @if (Auth::user()->hasRole('padre'))
            <x-padre.homeP />
        @endif


    </section>
@endsection
