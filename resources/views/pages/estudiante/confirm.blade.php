@extends('layouts.layoutA')
{{-- @section('titulo', 'Eliminar Estudiante') --}}

@section('contenido')
    <section class="flex place-content-center shadow-2xl rounded-3xl border[1px] py-8 px-6 gap-8">
        <div class="flex justify-center flex-col ">
            <article class="flex flex-col gap-3">
                <h3 class="font-semibold text-xl">Eliminar Estudiante</h3>
                <x-textField label="DNI" valor="{{ $estudiante->DNI }}" readonly="true" />
                <x-textField label="Nombre" valor="{{ $estudiante->nombre }}" readonly="true" />
                <x-textField label="Apellido Paterno" valor="{{ $estudiante->apellidoP }}" readonly="true" />
                <x-textField label="Apellido Materno" valor="{{ $estudiante->apellidoM }}" readonly="true" />
                <x-textField label="Grado/Seccion"
                    valor="{{ $estudiante->descripcionGrado }} {{ $estudiante->descripcionSeccion }}" readonly="true" />
            </article>
            <br>
            {{-- <x-boton label="Eliminar" ruta="estudiante.destroy" color="danger" datos="{{ $estudiante->idEstudiante }}" /> --}}
            <form action="{{ route('estudiante.destroy', $estudiante->idEstudiante) }}" method="POST"
                class="flex justify-center gap-4">
                @method('delete')
                @csrf
                <button type="submit"
                    class="rounded  px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center  bg-danger shadow-danger-3 hover:shadow-danger-2 hover:bg-danger-accent-300 focus:bg-danger-accent-300 active:bg-danger-600 focus:shadow-danger-2 active:shadow-danger-2">Eliminar</button>
                {{-- <x-boton ruta="estudiante.edit" datos="{{ $estudiante->idEstudiante }}" /> --}}
                <button type="submit"
                    class="rounded  px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center  bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2">Cancelar</button>
            </form>

        </div>
        <article class="">
            <h3 class="text-lg">Se Eliminarán:</h3>
            <br>
            <div class="">
                <x-table nombreTabla="Deudas" :cabeceras="['Codigo', 'Escala', 'Monto', 'Fecha', 'Adelanto', 'Total a Pagar']" :datos="$deudas" :atributos="['idDeuda', 'conceptoEscala', 'montoTotal', 'fechaLimite', 'adelanto', 'totalPagar']" ruta="deuda.edit"
                    id="idDeuda" />
            </div>
            <p class="px-4 font-bold text-sm">Pagos, Devoluciones y Condonaciones relacionadas a
                {{ $estudiante->nombre }}
                {{ $estudiante->apellidoP }}
                tambien serán eliminadas.
            </p>
            {{-- <div class="">
                <h4 class="text-xl font-semibold">Pagos</h4>
                <x-table :cabeceras="['Codigo', 'Escala', 'Monto', 'Fecha', 'Adelanto', 'Total a Pagar']" :datos="$deudas" :atributos="['idDeuda', 'conceptoEscala', 'montoTotal', 'fechaLimite', 'adelanto', 'totalPagar']" ruta="deuda.edit" id="idDeuda" />
            </div>
            <div class="">
                <h4 class="text-xl font-semibold">Devoluciones</h4>
                <x-table :cabeceras="['Codigo', 'Escala', 'Monto', 'Fecha', 'Adelanto', 'Total a Pagar']" :datos="$deudas" :atributos="['idDeuda', 'conceptoEscala', 'montoTotal', 'fechaLimite', 'adelanto', 'totalPagar']" ruta="deuda.edit" id="idDeuda" />
            </div>
            <div class="">
                <h4 class="text-xl font-semibold">Condonaciones</h4>
                <x-table :cabeceras="['Codigo', 'Escala', 'Monto', 'Fecha', 'Adelanto', 'Total a Pagar']" :datos="$deudas" :atributos="['idDeuda', 'conceptoEscala', 'montoTotal', 'fechaLimite', 'adelanto', 'totalPagar']" ruta="deuda.edit" id="idDeuda" />
            </div> --}}

        </article>
    </section>
@endsection
