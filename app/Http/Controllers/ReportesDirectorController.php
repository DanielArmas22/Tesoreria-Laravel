<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grado;
use App\Models\Seccion;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReportesDirectorController extends Controller implements hasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'role:admin,director',
        ];
    }
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
                    'nombre' => "Periodo {$periodo}",
                ];
            });

        $grados = Grado::pluck('descripcionGrado');
        $secciones = Seccion::pluck('descripcionSeccion');

        // Retornar la vista principal con los filtros
        return view('pages.director.reportes.indexReportes', compact('periodos', 'grados', 'secciones'));
    }

    public function resumenIngresos(Request $request)
    { // Obtener filtros desde la solicitud
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta utilizando Query Builder de Laravel
        $ingresosQuery = DB::table('pago')
            ->join('detalle_pago', 'pago.nroOperacion', '=', 'detalle_pago.nroOperacion')
            ->join('estudiante', 'pago.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('pago.estadoPago', 1) // Solo pagos activos
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('SUM(detalle_pago.monto) AS total_ingresos')
            )
            ->groupBy('grado.descripcionGrado', 'seccion.descripcionSeccion')
            // Aplicar filtros de manera condicional
            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('pago.fechaPago', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });

        // Ejecutar la consulta y obtener los resultados
        $ingresos = $ingresosQuery->get();

        // Calcular el total general de ingresos
        $totalGeneralIngresos = $ingresos->sum('total_ingresos');

        // Preparar datos para Chart.js
        $gradosLabels = $ingresos->pluck('descripcionGrado')->unique()->values()->all();
        $ingresosPorGrado = $ingresos->groupBy('descripcionGrado')->map(function ($group) {
            return $group->sum('total_ingresos');
        })->values()->all();

        // Retornar la vista con los datos
        return view('pages.director.reportes.resumen_ingresos', compact(
            'ingresos',
            'totalGeneralIngresos',
            'gradosLabels',
            'ingresosPorGrado',
            'periodo',
            'mes',
            'grado',
            'seccion',
            'ingresosQuery'
        ));
    }

    public function resumenIngresosPDF(Request $request)
    {
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta utilizando Query Builder de Laravel
        $ingresosQuery = DB::table('pago')
            ->join('detalle_pago', 'pago.nroOperacion', '=', 'detalle_pago.nroOperacion')
            ->join('estudiante', 'pago.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('pago.estadoPago', 1) // Solo pagos activos
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('SUM(detalle_pago.monto) AS total_ingresos')
            )
            ->groupBy('grado.descripcionGrado', 'seccion.descripcionSeccion')
            // Aplicar filtros de manera condicional
            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('pago.fechaPago', $mes);
            })
            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });

        // Ejecutar la consulta y obtener los resultados
        $ingresos = $ingresosQuery->get();

        // Calcular el total general de ingresos
        $totalGeneralIngresos = $ingresos->sum('total_ingresos');

        // Preparar datos para Chart.js
        $gradosLabels = $ingresos->pluck('descripcionGrado')->unique()->values()->all();
        $ingresosPorGrado = $ingresos->groupBy('descripcionGrado')->map(function ($group) {
            return $group->sum('total_ingresos');
        })->values()->all();

        // Preparar el array asociativo correctamente
        $data = [
            'ingresos' => $ingresos,
            'totalGeneralIngresos' => $totalGeneralIngresos,
            'gradosLabels' => $gradosLabels,
            'ingresosPorGrado' => $ingresosPorGrado,
            'periodo' => $periodo,
            'mes' => $mes,
            'grado' => $grado,
            'seccion' => $seccion,

        ];


        // Generar el PDF
        $pdf = PDF::loadView('pages.director.reportes.resumen_ingresos_pdf', $data);

        // Descargar el PDF
        return $pdf->stream('resumen_ingresos.pdf');
    }

    public function resumenDeudas(Request $request)
    {
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta
        $query = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('deuda.estado', 1) // Solo deudas activas
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('SUM(escala.monto + deuda.montoMora - deuda.adelanto) AS total_deudas')
            )
            ->groupBy('grado.descripcionGrado', 'seccion.descripcionSeccion')

            // Aplicar filtros de manera condicional
            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('deuda.fechaLimite', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });

        $deudas = $query->get();

        // Calcular el total general
        $totalGeneralDeudas = $deudas->sum('total_deudas');

        // Preparar datos para Chart.js
        $gradosLabels = $deudas->pluck('descripcionGrado')->unique()->values()->all();
        $deudasPorGrado = $deudas->groupBy('descripcionGrado')->map(function ($group) {
            return $group->sum('total_deudas');
        })->values()->all();

        // Retornar la vista con los datos
        return view('pages.director.reportes.resumen_deudas', compact(
            'deudas',
            'totalGeneralDeudas',
            'gradosLabels',
            'deudasPorGrado',
            'periodo',
            'mes',
            'grado',
            'seccion'
        ));
    }

    public function resumenDeudasPDF(Request $request)
    {
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta
        $query = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('deuda.estado', 1) // Solo deudas activas
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('SUM(escala.monto + deuda.montoMora - deuda.adelanto) AS total_deudas')
            )
            ->groupBy('grado.descripcionGrado', 'seccion.descripcionSeccion')

            // Aplicar filtros de manera condicional
            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('deuda.fechaLimite', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });


        $deudas = $query->get();
        $totalGeneralDeudas = $deudas->sum('total_deudas');

        // Preparar datos para el PDF
        $data = [
            'deudas' => $deudas,
            'totalGeneralDeudas' => $totalGeneralDeudas,
            'periodo' => $periodo,
            'mes' => $mes,
            'grado' => $grado,
            'seccion' => $seccion
        ];

        // Generar el PDF
        $pdf = PDF::loadView('pages.director.reportes.resumen_deudas_pdf', $data);

        // Descargar el PDF
        return $pdf->stream('resumen_deudas.pdf');
    }


    public function ingresosVsDeudas(Request $request)
    {
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Obtener ingresos por período
        $ingresosQuery = DB::table('pago')
            ->join('detalle_pago', 'pago.nroOperacion', '=', 'detalle_pago.nroOperacion')
            ->join('estudiante', 'pago.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('pago.estadoPago', 1)
            ->select(
                DB::raw('DATE_FORMAT(pago.fechaPago, "%Y-%m") AS periodo'),
                DB::raw('SUM(detalle_pago.monto) AS total_ingresos')
            )
            ->groupBy('periodo')

            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('pago.fechaPago', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });



        $ingresos = $ingresosQuery->get();

        // Obtener deudas por período
        $deudasQuery = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->join('detalle_pago', 'deuda.idDeuda', '=', 'detalle_pago.idDeuda')
            ->where('deuda.estado', 1)
            ->select(
                DB::raw('DATE_FORMAT(deuda.fechaRegistro, "%Y-%m") AS periodo'),
                DB::raw('SUM(escala.monto + deuda.montoMora - deuda.adelanto) AS total_deudas')
            )
            ->groupBy('deuda.fechaRegistro')
            // Aplicar filtros de manera condicional
            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('deuda.fechaLimite', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });

        $deudas = $deudasQuery->get();

        // Unificar ingresos y deudas por período
        $periodosIngresos = $ingresos->pluck('periodo')->all();
        $periodosDeudas = $deudas->pluck('periodo')->all();
        $periodosUnicos = array_unique(array_merge($periodosIngresos, $periodosDeudas));
        sort($periodosUnicos);

        $datosIngresos = [];
        $datosDeudas = [];

        foreach ($periodosUnicos as $p) {
            $ingreso = $ingresos->firstWhere('periodo', $p);
            $deuda = $deudas->firstWhere('periodo', $p);
            $datosIngresos[] = $ingreso ? $ingreso->total_ingresos : 0;
            $datosDeudas[] = $deuda ? $deuda->total_deudas : 0;
        }

        // Retornar la vista con los datos
        return view('pages.director.reportes.ingresos_vs_deudas', compact(
            'periodosUnicos',
            'datosIngresos',
            'datosDeudas',
            'grado',
            'seccion'
        ));
    }

    public function ingresosDetallados(Request $request)
    {
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta
        $query = DB::table('pago')
            ->join('detalle_pago', 'pago.nroOperacion', '=', 'detalle_pago.nroOperacion')
            ->join('estudiante', 'pago.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('pago.estadoPago', 1)
            ->select(
                'pago.fechaPago',
                'estudiante.nombre',
                'estudiante.apellidoP',
                'estudiante.apellidoM', // Asumiendo que existe una columna 'nombre'
                'detalle_pago.monto',
                'pago.metodoPago', // Asumiendo que existe una columna 'metodoPago'
                'grado.descripcionGrado',
                'seccion.descripcionSeccion'
            )

            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('pago.fechaPago', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });

        $ingresosDetallados = $query->get();

        // Calcular el total general
        $totalIngresos = $ingresosDetallados->sum('monto');

        // Retornar la vista con los datos
        return view('pages.director.reportes.ingresos_detallados', compact(
            'ingresosDetallados',
            'totalIngresos'
        ));
    }

    public function ingresosDetalladosPDF(Request $request)
    {
        // Repetir la lógica del método ingresosDetallados
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $trimestre = $request->input('trimestre');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta
        $query = DB::table('pago')
            ->join('detalle_pago', 'pago.nroOperacion', '=', 'detalle_pago.nroOperacion')
            ->join('estudiante', 'pago.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('pago.estadoPago', 1)
            ->select(
                'pago.fechaPago',
                'estudiante.nombre',
                'estudiante.apellidoP',
                'estudiante.apellidoM',
                'detalle_pago.monto',
                'pago.metodoPago',
                'grado.descripcionGrado',
                'seccion.descripcionSeccion'
            )

            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('pago.fechaPago', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });
        $ingresosDetallados = $query->get();
        $totalIngresos = $ingresosDetallados->sum('monto');

        // Preparar datos para el PDF
        $data = [
            'ingresosDetallados' => $ingresosDetallados,
            'totalIngresos' => $totalIngresos,
            'periodo' => $periodo,
            'mes' => $mes,
            'grado' => $grado,
            'seccion' => $seccion,
        ];

        // Generar el PDF
        $pdf = PDF::loadView('pages.director.reportes.ingresos_detallados_pdf', $data);

        // Descargar el PDF
        return $pdf->stream('ingresos_detallados.pdf');
    }


    public function deudores(Request $request)
    {
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta
        $query = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('deuda.estado', 1) // Solo deudas activas
            ->select(
                'estudiante.nombre',
                'estudiante.apellidoP',
                'estudiante.apellidoM',
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('escala.monto + deuda.montoMora - deuda.adelanto AS monto'),
                'deuda.fechaLimite',
                'deuda.idDeuda' // Asumiendo que existe una columna 'id' para identificar la deuda
            )

            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('deuda.fechaLimite', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });

        $deudores = $query->get();

        // Retornar la vista con los datos
        return view('pages.director.reportes.deudores', compact(
            'deudores'
        ));
    }

    public function deudoresPDF(Request $request)
    {
        // Repetir la lógica del método deudores
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');

        // Construir la consulta
        $query = DB::table('deuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->where('deuda.estado', 1)
            ->select(
                'estudiante.nombre',
                'estudiante.apellidoP',
                'estudiante.apellidoM',
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('escala.monto + deuda.montoMora - deuda.adelanto AS monto'),
                'deuda.fechaLimite',
                'deuda.idDeuda' // Asumiendo que existe una columna 'id' para identificar la deuda
            )

            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('deuda.fechaLimite', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });
        $deudores = $query->get();

        // Preparar datos para el PDF
        $data = [
            'deudores' => $deudores,
            'periodo' => $periodo,
            'mes' => $mes,
            'grado' => $grado,
            'seccion' => $seccion,
        ];

        // Generar el PDF
        $pdf = PDF::loadView('pages.director.reportes.deudores_pdf', $data);

        // Descargar el PDF
        return $pdf->stream('reporte_deudores.pdf');
    }

    public function inscripcionGradoSeccion(Request $request)
    {
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');


        // Construir la consulta
        $query = DB::table('estudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('COUNT(estudiante.idEstudiante) AS total_inscritos')
            )
            ->groupBy('grado.descripcionGrado', 'seccion.descripcionSeccion')


            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('escala_estudiante.fechaEE', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });



        $inscripciones = $query->get();

        // Calcular el total general
        $totalInscritos = $inscripciones->sum('total_inscritos');

        // Preparar datos para Chart.js
        $gradosLabels = $inscripciones->pluck('descripcionGrado')->unique()->values()->all();
        $inscripcionesPorGrado = $inscripciones->groupBy('descripcionGrado')->map(function ($group) {
            return $group->sum('total_inscritos');
        })->values()->all();

        // Retornar la vista con los datos
        return view('pages.director.reportes.inscripcion_grado_seccion', compact(
            'inscripciones',
            'totalInscritos',
            'gradosLabels',
            'inscripcionesPorGrado'
        ));
    }

    public function inscripcionGradoSeccionPDF(Request $request)
    {
        // Obtener filtros
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $grado = $request->input('grado');
        $seccion = $request->input('seccion');


        // Construir la consulta
        $query = DB::table('estudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->join('escala_estudiante', 'estudiante.idEstudiante', '=', 'escala_estudiante.idEstudiante')
            ->join('escala', 'escala_estudiante.idEscala', '=', 'escala.idEscala')
            ->select(
                'grado.descripcionGrado',
                'seccion.descripcionSeccion',
                DB::raw('COUNT(estudiante.idEstudiante) AS total_inscritos')
            )
            ->groupBy('grado.descripcionGrado', 'seccion.descripcionSeccion')


            ->when($periodo, function ($query, $periodo) {
                return $query->where('escala_estudiante.periodo', $periodo);
            })
            ->when($mes, function ($query, $mes) {
                return $query->whereMonth('escala_estudiante.fechaEE', $mes);
            })

            ->when($grado, function ($query, $grado) {
                return $query->where('grado.descripcionGrado', $grado);
            })
            ->when($seccion, function ($query, $seccion) {
                return $query->where('seccion.descripcionSeccion', $seccion);
            });



        $inscripciones = $query->get();

        // Calcular el total general
        $totalInscritos = $inscripciones->sum('total_inscritos');


        $inscripciones = $query->get();
        $totalInscritos = $inscripciones->sum('total_inscritos');

        // Preparar datos para el PDF
        $data = [
            'inscripciones' => $inscripciones,
            'totalInscritos' => $totalInscritos,
            'periodo' => $periodo,
            'mes' => $mes,
            'grado' => $grado,
            'seccion' => $seccion,
        ];

        // Generar el PDF
        $pdf = PDF::loadView('pages.director.reportes.inscripcion_grado_seccion_pdf', $data);

        // Descargar el PDF
        return $pdf->stream('inscripcion_grado_seccion.pdf');
    }
}
