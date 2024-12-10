<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Seccion;
use App\Models\Pago;
use App\Models\Deuda;
use App\Models\Grado;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        // Obtener listas para los filtros
        $periodos = DB::table('escala_estudiante')
            ->distinct()
            ->pluck('periodo')
            ->map(function ($periodo) {
                return (object) [
                    'id' => $periodo,
                    'nombre' => "Periodo {$periodo}", // Personaliza según sea necesario
                ];
            });

        $grados = Grado::pluck('descripcionGrado');
        $secciones = Seccion::pluck('descripcionSeccion');

        // Obtener parámetros de los filtros desde la solicitud
        $filtroPeriodo = $request->input('periodo');
        $filtroGrado = $request->input('grado');
        $filtroSeccion = $request->input('seccion');

        // Consulta para Total de Ingresos con filtros
        $totalIngresos = DB::table('pago')
            ->join('detalle_pago', 'pago.nroOperacion', '=', 'detalle_pago.nroOperacion')
            ->join('estudiante', 'pago.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('pago.estadoPago', 1) // Solo pagos activos
            ->when($filtroPeriodo, function ($query, $filtroPeriodo) {
                return $query->where('escala_estudiante.periodo', $filtroPeriodo);
            })
            ->when($filtroGrado, function ($query, $filtroGrado) {
                return $query->where('grado.descripcionGrado', $filtroGrado);
            })
            ->when($filtroSeccion, function ($query, $filtroSeccion) {
                return $query->where('seccion.descripcionSeccion', $filtroSeccion);
            })
            ->sum('detalle_pago.monto');

        // Consulta para Deudas Totales con filtros
        $deudasTotales = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->when($filtroPeriodo, function ($query, $filtroPeriodo) {
                return $query->whereYear('deuda.fechaLimite', $filtroPeriodo);
            })
            ->when($filtroGrado, function ($query, $filtroGrado) {
                return $query->where('grado.descripcionGrado', $filtroGrado);
            })
            ->when($filtroSeccion, function ($query, $filtroSeccion) {
                return $query->where('seccion.descripcionSeccion', $filtroSeccion);
            })
            ->where('deuda.estado', 1) // Solo deudas activas
            ->sum(DB::raw('escala.monto + deuda.montoMora - deuda.adelanto'));

        // Total de Estudiantes filtrados
        $totalEstudiantes = DB::table('estudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->when($filtroPeriodo, function ($query, $filtroPeriodo) {
                return $query->where('escala_estudiante.periodo', $filtroPeriodo);
            })
            ->when($filtroGrado, function ($query, $filtroGrado) {
                return $query->where('grado.descripcionGrado', $filtroGrado);
            })
            ->when($filtroSeccion, function ($query, $filtroSeccion) {
                return $query->where('seccion.descripcionSeccion', $filtroSeccion);
            })
            ->count();

        // Número de Morosos filtrados
        $morosos = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->when($filtroPeriodo, function ($query, $filtroPeriodo) {
                return $query->where('escala_estudiante.periodo', $filtroPeriodo);
            })
            ->when($filtroGrado, function ($query, $filtroGrado) {
                return $query->where('grado.descripcionGrado', $filtroGrado);
            })
            ->when($filtroSeccion, function ($query, $filtroSeccion) {
                return $query->where('seccion.descripcionSeccion', $filtroSeccion);
            })
            ->where('deuda.estado', 1) // Solo deudas activas
            ->distinct()
            ->count('deuda.idEstudiante');

        // Porcentaje de Morosidad filtrado
        $porcentajeMorosidad = $totalEstudiantes > 0 ? ($morosos / $totalEstudiantes) * 100 : 0;

        // Pasar datos a la vista
        return view('pages.director.reportes.index', compact(
            'totalIngresos',
            'deudasTotales',
            'porcentajeMorosidad',
            'periodos',
            'grados',
            'secciones'
        ));
    }


    public function ingresos(Request $request)
    {
        // Obtener listas para los filtros
        $periodos = DB::table('escala_estudiante')
            ->distinct()
            ->pluck('periodo')
            ->map(function ($periodo) {
                return (object) [
                    'id' => $periodo,
                    'nombre' => "Periodo {$periodo}", // Ajusta según tus necesidades
                ];
            });

        $grados = Grado::pluck('descripcionGrado');
        $secciones = Seccion::pluck('descripcionSeccion');

        // Obtener parámetros de los filtros desde la solicitud
        $filtroPeriodo = $request->input('periodo');
        $filtroGrado = $request->input('grado');
        $filtroSeccion = $request->input('seccion');

        // Asignar a variables con nombres esperados en la vista (opcional)
        $periodo = $filtroPeriodo;
        $grado = $filtroGrado;
        $seccion = $filtroSeccion;

        // Construir la consulta para obtener los totales de ingresos por grado
        $ingresos = DB::table('pago')
            ->join('detalle_pago', 'pago.nroOperacion', '=', 'detalle_pago.nroOperacion') // Asegúrate de que esta columna existe y es la correcta
            ->join('estudiante', 'pago.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('pago.estadoPago', 1) // Solo pagos activos, ajusta según tu lógica
            ->when($filtroPeriodo, function ($query) use ($filtroPeriodo) {
                return $query->where('escala_estudiante.periodo', $filtroPeriodo);
            })
            ->when($filtroGrado, function ($query) use ($filtroGrado) {
                return $query->where('grado.descripcionGrado', $filtroGrado);
            })
            ->when($filtroSeccion, function ($query) use ($filtroSeccion) {
                return $query->where('seccion.descripcionSeccion', $filtroSeccion);
            })
            ->groupBy('grado.gradoEstudiante', 'grado.descripcionGrado', 'seccion.seccionEstudiante', 'seccion.descripcionSeccion')
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('SUM(detalle_pago.monto) AS total_ingresos'), // Cambiado de pago.monto a detalle_pago.monto
                DB::raw('COUNT(DISTINCT estudiante.idEstudiante) AS total_estudiantes')
            )
            ->get();

        // Calcular el total general de ingresos y estudiantes
        $totalGeneralIngresos = $ingresos->sum('total_ingresos');
        $totalGeneralEstudiantes = $ingresos->sum('total_estudiantes');

        // Preparar datos para Chart.js
        $gradosLabels = $ingresos->pluck('descripcionGrado')->unique()->values()->all();
        $ingresosPorGrado = $ingresos->groupBy('descripcionGrado')->map(function ($group) {
            return $group->sum('total_ingresos');
        })->values()->all();



        // Pasar datos a la vista
        return view('pages.director.reportes.ingresos', compact(
            'ingresos',
            'periodos',
            'grados',
            'secciones',
            'periodo',
            'grado',
            'seccion',
            'totalGeneralIngresos',
            'totalGeneralEstudiantes',
            'gradosLabels',
            'ingresosPorGrado'
        ));
    }

    public function deudas(Request $request)
    {
        // Obtener listas para los filtros
        $periodos = DB::table('escala_estudiante')
            ->distinct()
            ->pluck('periodo')
            ->map(function ($periodo) {
                return (object) [
                    'id' => $periodo,
                    'nombre' => "Periodo {$periodo}", // Ajusta según tus necesidades
                ];
            });

        $grados = Grado::pluck('descripcionGrado');
        $secciones = Seccion::pluck('descripcionSeccion');

        // Obtener parámetros de los filtros desde la solicitud
        $filtroPeriodo = $request->input('periodo');
        $filtroGrado = $request->input('grado');
        $filtroSeccion = $request->input('seccion');

        // Asignar a variables con nombres esperados en la vista (opcional)
        $periodo = $filtroPeriodo;
        $grado = $filtroGrado;
        $seccion = $filtroSeccion;

        // Construir la consulta para obtener los totales de deudas y deudores
        $deudas = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('deuda.estado', 1) // Solo deudas activas
            ->when($filtroPeriodo, function ($query) use ($filtroPeriodo) {
                return $query->where('escala_estudiante.periodo', $filtroPeriodo);
            })
            ->when($filtroGrado, function ($query) use ($filtroGrado) {
                return $query->where('grado.descripcionGrado', $filtroGrado);
            })
            ->when($filtroSeccion, function ($query) use ($filtroSeccion) {
                return $query->where('seccion.descripcionSeccion', $filtroSeccion);
            })
            ->groupBy('grado.gradoEstudiante', 'grado.descripcionGrado', 'seccion.seccionEstudiante', 'seccion.descripcionSeccion')
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('SUM(escala.monto + deuda.montoMora - deuda.adelanto) AS total_deudas'),
                DB::raw('COUNT(DISTINCT estudiante.idEstudiante) AS total_deudores')
            )
            ->get();

        // Calcular el total general de deudas y deudores
        $totalGeneralDeudas = $deudas->sum('total_deudas');
        $totalGeneralDeudores = $deudas->sum('total_deudores');

        // Preparar datos para Chart.js
        $gradosLabels = $deudas->pluck('descripcionGrado')->unique()->values()->all();
        $deudasPorGrado = $deudas->groupBy('descripcionGrado')->map(function ($group) {
            return $group->sum('total_deudas');
        })->values()->all();



        // Depuración: Verificar el contenido de las variables

        // Pasar datos a la vista
        return view('pages.director.reportes.deudas', compact(
            'deudas',
            'periodos',
            'grados',
            'secciones',
            'periodo',
            'grado',
            'seccion',
            'totalGeneralDeudas',
            'totalGeneralDeudores',
            'gradosLabels',
            'deudasPorGrado'
        ));
    }

    public function reportes(Request $request)
    {
        return view('pages.director.reportes.indexReportes');
    }
}
