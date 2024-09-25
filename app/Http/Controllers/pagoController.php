<?php

namespace App\Http\Controllers;

use App\Models\detalle_pago;
use App\Models\estudiante;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\deuda;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

//use Carbon\Carbon

class pagoController extends Controller
{


    const PAGINATION = 3;

    public function index(Request $request, $generarPDF = null)
    {
        $buscarpor = $request->get('buscarpor');
        $buscarCodigo = $request->get('buscarCodigo');
        $nroOperacion = $request->get('nroOperacion');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');

        $pago = DB::table('pago as P')
        ->join('detalle_pago as DP', 'P.nroOperacion', '=', 'DP.nroOperacion')                                 
        ->join('estudiante as E', 'P.idEstudiante', '=', 'E.idEstudiante')
        ->select('P.nroOperacion','P.idEstudiante', 'P.fechaPago','P.periodo','E.nombre','E.apellidoP','E.apellidoM',DB::raw('ROUND(sum(DP.monto),2) as totalMonto'))
        ->where('P.estado','=','1')
        ->groupBy('p.nroOperacion');
        
        
        if($buscarCodigo !=null){
            $pago->where('P.idEstudiante', 'like','%' .$buscarCodigo . '%');
        }
        if($nroOperacion !=null){
            $pago->where('P.nroOperacion', 'like','%' .$nroOperacion . '%');
        }

        if ($fechaInicio) {
            $pago->whereDate('P.fechaPago', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $pago->whereDate('P.fechaPago', '<=', $fechaFin);
        }

        $pagos = $pago->paginate($this::PAGINATION);
        $totalPago = 0;

        // Iterar sobre los registros y sumar los valores de totalMonto
        foreach ($pagos as $minipago) {
            $totalPago += $minipago->totalMonto;
        }
        $pagos = $pagos->appends(['buscarCodigo' => $buscarCodigo, 'nroOperacion' => $nroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin,'totalPago' => $totalPago]);

        if($request->has('generarPDF') && $request->generarPDF){
            $pdf = PDF::loadView('pages.pago.reportepdf', compact('pagos', 'nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago'));
            return $pdf->stream('invoice.pdf');
        }

        return view('pages.pago.index', compact('pagos', 'nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago'));

    }



    public function create()
    {
        return view('pages.pago.create');

    }
    public function show(Request $request)
    {
        // Obtener el id del estudiante desde la solicitud
        $idEstudiante = $request->input('idEstudiante');
        // Consultar la base de datos para obtener los datos del estudiante
        $estudiante = null;
        $deudas = null;

        $estudiante = DB::table('estudiante as E')
            ->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')
            ->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')
            ->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')
            ->select('E.idEstudiante', 'E.dni', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion')->where('E.estado', '=', '1')->where('E.idEstudiante', $idEstudiante)->first();
        
            $condonacion = DB::table('DETALLE_CONDONACION as DC')
            ->select('DC.IDDEUDA', DB::raw('SUM(DC.MONTO) as total'))
        ->groupBy('DC.IDDEUDA');

        $deudas = DB::table('deuda as D')
            ->join('estudiante as E', 'D.idEstudiante', '=', 'E.idEstudiante')
            ->join('concepto_escala as CE', 'D.idConceptoEscala', '=', 'CE.idConceptoEscala')
            ->join('escala as Esc', 'CE.idEscala', '=', 'Esc.idEscala')
            ->leftJoinSub($condonacion, 'sub', function($join) {
                $join->on('D.idDeuda', '=', 'sub.IDDEUDA');
            })
            ->select(
                'D.idDeuda',
                'CE.descripcion as conceptoDescripcion',
                'D.montoMora',
                'D.fechaLimite',
                DB::raw('ROUND(D.adelanto,2) as adelanto'),
                'D.estado',
                'Esc.monto',
                'sub.total as totalCondonacion'

            )->where('D.idEstudiante', '=',$idEstudiante)->where('D.estado','=','1')
            ->get(['estudiante' => $estudiante, 'deudas' => $deudas, 'idEstudiante'=> $idEstudiante ]);
            // dd($deudas)->toSql();
        return view('pages.pago.create', ['estudiante' => $estudiante, 'deudas' => $deudas]);
        
    }

    public function store(Request $request)
    {
       
        try {
            DB::beginTransaction();
            // Crear el pago

            // $idEstudiante = $request->get('idEstudiante');
            // if (!isset($idEstudiante) || empty($idEstudiante)) {
            //     throw new \Exception('Busque un estudiante o seleccione un pago para continuar');
            // }
            $idEstudiante = $request->idestudiante;
            if (!isset($idEstudiante) || empty($idEstudiante)) {
                throw new \Exception('Busque un estudiante o seleccione un pago para continuar');
            }
            $pago = new Pago();
            $pago->idEstudiante = $request->idestudiante;
            $pago->fechaPago = now()->format('Y-m-d');
            $pago->periodo = '2024';   //$pago->periodo = Carbon::now()->year; SE PUEDE PONER ESTO ARA QUE LO HAGA CON LA FECHA DEL SISTEMA
            $pago->estado = '1';
            $pago->save();
            
            // Crear los detalles del pago
            foreach ($request->pagos as $idDeuda => $pagoData) {
                $monto = $pagoData['monto'];
                
                // Crear el detalle del pago
                $detallePago = new detalle_pago();
                // podria tenerse que buscar el pago guardado para recuperar el id
                $detallePago->nroOperacion = $pago->nroOperacion;
                $detallePago->idDeuda = $idDeuda;
                $detallePago->monto = $monto;
                $detallePago->estado = '1';
                $detallePago->save();
                
                // Actualizar la deuda
                $deuda = Deuda::findOrFail($idDeuda);
                if ($deuda) {
                                $cond = DB::table('DETALLE_CONDONACION as DC')
                ->select('DC.IDDEUDA', DB::raw('SUM(DC.MONTO) as total'))->where('DC.IDDEUDA', '=', $idDeuda)
                ->groupBy('DC.IDDEUDA')->first();
                // dd($cond);
                    if($cond == null){
                        $montoPagar = $deuda->conceptoEscala->escala->monto + $deuda->montoMora;
                    }
                    else{
                        $montoPagar = $deuda->conceptoEscala->escala->monto + $deuda->montoMora - $cond->total;
                    }
                    $montotmp = $monto + $deuda->adelanto;
                    // $deuda->adelanto += $monto;
                    if($montotmp>= $montoPagar){
                        // $deuda->adelanto += $monto;
                        $deuda->estado = '0';
                    }
                    $deuda->adelanto = $montotmp;
                    $deuda->save();
                }
            }

            // Confirmar la transacción
            DB::commit();

            return redirect()->route('pago.index', ['idEstudiante' => $request->idEstudiante])->with('success', 'Pago realizado con éxito.');

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollback();
            return redirect()->route('pago.create', ['idEstudiante' => $request->idEstudiante])->with(['mensaje'=>$e->getMessage()]);
        }
    }

    public function showBoleta($nroOperacion)
    {
        // Obtener los datos del pago, estudiante y detalles del pago
        $pago = Pago::where('nroOperacion', $nroOperacion)->first();
        $estudiante = Estudiante::where('idEstudiante', $pago->idEstudiante)->first();
        $detalles = detalle_pago::where('nroOperacion', $nroOperacion)->get();
        foreach ($detalles as $detalle) {
            $detalle->monto = number_format($detalle->monto, 2, '.', '');
        }
        $montoTotal = number_format($detalles->sum('monto'), 2, '.', '');


        return view('pages.pago.boleta', compact('pago', 'estudiante', 'detalles', 'montoTotal'));

        // Generar la vista para el PDF
        // $pdf = PDF::loadView('pago.boleta', compact('pago', 'estudiante', 'detalles', 'montoTotal'));

        // Descargar el PDF
        // return $pdf->download('boleta_pago_' . $nroOperacion . '.pdf');
    }
}
