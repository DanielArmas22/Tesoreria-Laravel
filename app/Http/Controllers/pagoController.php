<?php

namespace App\Http\Controllers;

use App\Models\detalle_pago;
use App\Models\estudiante;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\deuda;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Models\conceptoEscala;
use App\Models\escala;
use App\Models\Grado;
use App\Models\Seccion;

//use Carbon\Carbon

class pagoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    const PAGINATION = 5;

    public function index(Request $request, $generarPDF = null)
    {
        if (Auth::user()->hasRole("tesorero")){
            $buscarpor = $request->get('buscarpor');
            $buscarCodigo = $request->get('buscarCodigo');
            $nroOperacion = $request->get('nroOperacion');
            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');

            $conceptoEscalas = conceptoEscala::select('descripcion')->distinct()->get();
            $escalaF = escala::get();
            $grados = Grado::get();
            $secciones = Seccion::get();

            $busquedaConcepto = $request->get('busquedaConcepto');
            $busquedaEscala = $request->get('busquedaEscala');
            $busquedaGrado = $request->get('busquedaGrado');
            $busquedaSeccion = $request->get('busquedaSeccion');

            $dniEstudiante = $request->get('dniEstudiante');
            $busquedaNombreEstudiante = $request->get('busquedaNombreEstudiante');
            $busquedaApellidoEstudiante = $request->get('busquedaApellidoEstudiante');

            $codMinimo = $request->get('codMinimo');
            $codMaximo = $request->get('codMaximo');

            $pagosHoy = $request->get('pagosHoy');
            $fechaActual = date('Y-m-d');

            $pago = DB::table('pago as P')
            ->join('detalle_pago as DP', 'P.nroOperacion', '=', 'DP.nroOperacion') 
            ->join('deuda as D', 'D.idDeuda', '=', 'DP.idDeuda')   
            ->join('concepto_escala as C','C.idConceptoEscala','=','D.idConceptoEscala')
            ->join('escala as ES','C.idEscala','=','ES.idEscala')
            ->join('estudiante as E', 'P.idEstudiante', '=', 'E.idEstudiante')
            ->join('detalle_estudiante_gs as DEGS','DEGS.idEstudiante','=','E.idEstudiante')
            ->join('grado as G','G.gradoEstudiante','=','DEGS.gradoEstudiante')
            ->join('seccion as SEC','SEC.seccionEstudiante','=','DEGS.seccionEstudiante')
            ->select('P.nroOperacion','P.idEstudiante', 'P.fechaPago','P.periodo','E.nombre','E.apellidoP','E.apellidoM',DB::raw('ROUND(sum(DP.monto),2) as totalMonto'),
                    'G.descripcionGrado','SEC.descripcionSeccion','ES.descripcion as escala','C.descripcion as concep')
            ->where('P.estadoPago','=','1')
            ->groupBy('p.nroOperacion','G.descripcionGrado', 
            'SEC.descripcionSeccion','escala','concep');
            
            if ($pagosHoy) {
                $pago->whereDate('P.fechaPago', '=', $fechaActual);
            }
            
            if($buscarCodigo !=null){
                $pago->where('P.idEstudiante', 'like','%' .$buscarCodigo . '%');
            }
            if($dniEstudiante!=null){
                $pago->where('E.DNI', $dniEstudiante);
            }
            if ($busquedaNombreEstudiante!=null) {
                $pago->where('E.nombre', 'like', '%'.$busquedaNombreEstudiante.'%');
            }

            if ($busquedaApellidoEstudiante != null) {
                $pago->where(DB::raw("CONCAT(E.apellidoP, ' ', E.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
            }

            if ($codMinimo != null) {
                $pago->having('totalMonto', '>=', $codMinimo);
            }
            if ($codMaximo != null) {
                $pago->having('totalMonto', '<=', $codMaximo);
            }

            if($nroOperacion !=null){
                $pago->where('DP.nroOperacion', 'like','%' .$nroOperacion . '%');
            }

            if ($fechaInicio) {
                $pago->whereDate('P.fechaPago', '>=', $fechaInicio);
            }

            if ($fechaFin) {
                $pago->whereDate('P.fechaPago', '<=', $fechaFin);
            }

            if($busquedaConcepto!=null){
                $pago->where('C.descripcion','like','%'.$busquedaConcepto.'%');
            }

            if ($busquedaEscala!=null){
                $pago->where('ES.idEscala','=', $busquedaEscala);
            }

            if ($busquedaGrado!=null) {
                $pago->where('DEGS.gradoEstudiante', $busquedaGrado);
            }

            if($busquedaSeccion!=null){
                $pago->where('DEGS.seccionEstudiante', $busquedaSeccion);
            }

            $pagos = $pago->paginate($this::PAGINATION);
            $totalPago = 0;

            // Iterar sobre los registros y sumar los valores de totalMonto
            foreach ($pagos as $minipago) {
                $totalPago += $minipago->totalMonto;
            }

            $pagos = $pagos->appends(['buscarCodigo' => $buscarCodigo, 'nroOperacion' => $nroOperacion, 
            'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin,'totalPago' => $totalPago, 'codMinimo'=>$codMinimo,
            'codMaximo'=>$codMaximo,'conceptoEscalas'=>$conceptoEscalas,'busquedaConcepto'=>$busquedaConcepto,'escalaF'=>$escalaF, 
            'grados'=>$grados, 'secciones'=>$secciones,'busquedaEscala'=>$busquedaEscala,'busquedaGrado'=>$busquedaGrado,
            'busquedaSeccion'=>$busquedaSeccion,'dniEstudiante'=>$dniEstudiante,'busquedaNombreEstudiante'=>$busquedaNombreEstudiante,
            'busquedaApellidoEstudiante'=>$busquedaApellidoEstudiante,'pagosHoy'=>$pagosHoy]);

            if($request->has('generarPDF') && $request->generarPDF){
                $pdf = PDF::loadView('pages.pago.reportepdf', compact(
                    'pagos', 'nroOperacion', 'buscarCodigo', 'fechaInicio', 'fechaFin', 'totalPago', 'conceptoEscalas', 'busquedaConcepto', 
                    'escalaF', 'grados', 'secciones', 'busquedaEscala', 'busquedaGrado', 'busquedaSeccion', 'dniEstudiante', 
                    'busquedaNombreEstudiante', 'busquedaApellidoEstudiante', 'codMinimo', 'codMaximo', 'pagosHoy'
                ));
                
                return $pdf->stream('invoice.pdf');
            }

            return view('pages.pago.index', compact('pagos', 'nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago','conceptoEscalas','busquedaConcepto','escalaF', 'grados', 'secciones',
                        'busquedaEscala','busquedaGrado','busquedaSeccion','dniEstudiante','busquedaNombreEstudiante','busquedaApellidoEstudiante','codMinimo','codMaximo','pagosHoy'));
        }
        else
        {
            $buscarpor = $request->get('buscarpor');
            $buscarCodigo = $request->get('buscarCodigo');
            $nroOperacion = $request->get('nroOperacion');
            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');
            $nombreEstudiante = $request->get('nombreEstudiante');
            $apellidoPaterno = $request->get('apellidoPaterno');
            $apellidoMaterno = $request->get('apellidoMaterno');
            $buscarpor = $request->get('buscarpor');

            $pago = DB::table('pago as P')
            ->join('detalle_pago as DP', 'P.nroOperacion', '=', 'DP.nroOperacion')                                 
            ->join('estudiante as E', 'P.idEstudiante', '=', 'E.idEstudiante')
            ->select('P.nroOperacion','P.idEstudiante', 'P.fechaPago','P.periodo','P.metodoPago','E.nombre','E.apellidoP','E.apellidoM',DB::raw('ROUND(sum(DP.monto),2) as totalMonto'))
            ->whereIn('P.estadoPago', ['1', '2'])
            ->groupBy('p.nroOperacion');
            $estudiantes = null;
            if (Auth::user()->hasRole('padre')) {
                // Obtener la colección de pagos del padre (asumiendo que retorna colecciones con nroOperacion)
                $nroOpPadre = Auth::user()->getTotalPagos()->pluck('nroOperacion')->toArray();
                // Ajustar la consulta para que solo traiga esas deudas
                $pago->whereIn('P.nroOperacion', $nroOpPadre);
                $estudiantes = Auth::user()->estudiantes()->get();

                }
            
            if($buscarCodigo !=null){
                $pago->where('P.idEstudiante', 'like','%' .$buscarCodigo . '%');
            }
            if($nroOperacion !=null){
                $pago->where('P.nroOperacion', 'like','%' .$nroOperacion . '%');
            }
            
            if($nombreEstudiante !=null){
                $pago->where('E.nombre', 'like','%' .$nombreEstudiante . '%');
            }
            
            if($apellidoPaterno !=null){
                $pago->where('E.apellidoP', 'like','%' .$apellidoPaterno . '%');
            }
            
            if($apellidoMaterno !=null){
                $pago->where('E.apellidoM', 'like','%' .$apellidoMaterno . '%');
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
                $pdf = PDF::loadView('pages.pago.reportepdf', compact('pagos', 'nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago','estudiantes','nombreEstudiante','apellidoPaterno','apellidoMaterno'));
                return $pdf->stream('invoice.pdf');
            }

            return view('pages.pago.index', compact('pagos', 'nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago','estudiantes','nombreEstudiante','apellidoPaterno','apellidoMaterno'));

        }
    }

    

    public function create()
    {
        $estudiante = null;
        $deudas = null;
        $estudiantes = null;
        if (Auth::user()->hasRole('padre')) {
            $estudiantes = Auth::user()->estudiantes()->get();
            // $idEstudiantes = Auth::user()->estudiantes->pluck('idEstudiante')->toArray();
            // // Ajustar la consulta para que solo traiga esas deudas
            // $estudiante->whereIn('E.idEstudiante', $idEstudiantes);
        }
        return view('pages.pago.create', ['estudiantes' => $estudiantes, 'estudiante' => $estudiante, 'deudas' => $deudas, 'idEstudiante' => null]);

    }
    public function show(Request $request)
    {
        // Obtener el id del estudiante desde la solicitud
        $idEstudiante = $request->input('idEstudiante');
        // Consultar la base de datos para obtener los datos del estudiante
        $estudiante = null;
        $deudas = null;
        $estudiantes = null;
        if (Auth::user()->hasRole('padre')) {
            $estudiantes = Auth::user()->estudiantes()->get();
        }
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
            // dd($estudiante);
        return view('pages.pago.create', ['estudiante' => $estudiante, 'deudas' => $deudas, 'estudiantes' => $estudiantes, 'idEstudiante' => $idEstudiante]);
        
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasRole("tesorero")){
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
                $pago->estadoPago = '1';
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
                    $detallePago->estadoPago = '1';
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
        else{
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
                $pago->estadoPago = '2';
                $pago->metodoPago= $request->metodoPago;
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
                    $detallePago->estadoPago = '2';
                    $detallePago->save();
                    
                    
                    // Actualizar la deuda (SE QUEDA PQ ES EL PAGO VIRTUAL DEL PADRE)
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

        if(Auth::user()->rol != 'cajero'){
            return view('pages.pago.boleta', compact('pago', 'estudiante', 'detalles', 'montoTotal'));
        }else{
            $pdf = PDF::loadView('pages.pago.boletapdf', compact('pago', 'estudiante', 'detalles', 'montoTotal'));
            return $pdf->stream('boleta_pago_' . $nroOperacion . '.pdf');
        // Generar la vista para el PDF
        // $pdf = PDF::loadView('pago.boleta', compact('pago', 'estudiante', 'detalles', 'montoTotal'));
        }
        // Descargar el PDF
        // return $pdf->download('boleta_pago_' . $nroOperacion . '.pdf');
    }


//CAJERO <----------------------------------------------------------------------------------------------------------------------------
    public function indexCajero(Request $request, $generarPDF = null)
    {
        $nombreEstudiante = $request->get('nombreEstudiante');
        $apellidoPaterno = $request->get('apellidoPaterno');
        $apellidoMaterno = $request->get('apellidoMaterno');
        $buscarpor = $request->get('buscarpor');
        $buscarCodigo = $request->get('buscarCodigo');
        $nroOperacion = $request->get('nroOperacion');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');

        $pago = DB::table('pago as P')
        ->join('detalle_pago as DP', 'P.nroOperacion', '=', 'DP.nroOperacion')                                 
        ->join('estudiante as E', 'P.idEstudiante', '=', 'E.idEstudiante')
        ->select('P.nroOperacion','P.idEstudiante','P.metodoPago','P.fechaPago','P.periodo','E.nombre','E.apellidoP','E.apellidoM',DB::raw('ROUND(sum(DP.monto),2) as totalMonto'))
        ->where('P.estadoPago','=','2')
        ->groupBy('p.nroOperacion');
        
        
        if($buscarCodigo !=null){
            $pago->where('P.idEstudiante', 'like','%' .$buscarCodigo . '%');
        }
        if($nroOperacion !=null){
            $pago->where('P.nroOperacion', 'like','%' .$nroOperacion . '%');
        }
        
        if($nombreEstudiante !=null){
            $pago->where('E.nombre', 'like','%' .$nombreEstudiante . '%');
        }
        
        if($apellidoPaterno !=null){
            $pago->where('E.apellidoP', 'like','%' .$apellidoPaterno . '%');
        }
        
        if($apellidoMaterno !=null){
            $pago->where('E.apellidoM', 'like','%' .$apellidoMaterno . '%');
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
        $pagos = $pagos->appends(['buscarCodigo' => $buscarCodigo, 'apellidoPaterno'=>$apellidoPaterno, 'apellidoMaterno'=>$apellidoMaterno , 'nombreEstudiante'=>$nombreEstudiante  ,'nroOperacion' => $nroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin,'totalPago' => $totalPago]);

        if($request->has('generarPDF') && $request->generarPDF){
            $pdf = PDF::loadView('pages.pago.reportepdf', compact('pagos','nombreEstudiante' ,'apellidoPaterno','apellidoMaterno','nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago'));
            return $pdf->stream('invoice.pdf');
        }

        return view('pages.pago.index', compact('pagos','nombreEstudiante','apellidoPaterno','apellidoMaterno','nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago'));

    }


    public function fichapagos(Request $request){
        $nombreEstudiante = $request->get('nombreEstudiante');
        $apellidoPaterno = $request->get('apellidoPaterno');
        $apellidoMaterno = $request->get('apellidoMaterno');
        $buscarpor = $request->get('buscarpor');
        $buscarCodigo = $request->get('buscarCodigo');
        $nroOperacion = $request->get('nroOperacion');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');

        $pago = DB::table('pago as P')
        ->join('detalle_pago as DP', 'P.nroOperacion', '=', 'DP.nroOperacion')                                 
        ->join('estudiante as E', 'P.idEstudiante', '=', 'E.idEstudiante')
        ->select('P.nroOperacion','P.idEstudiante', 'P.fechaPago','P.periodo','E.nombre','E.apellidoP','E.apellidoM',DB::raw('ROUND(sum(DP.monto),2) as totalMonto'))
        //->where('P.estado','=','1')
        ->where('P.estadoPago','=','1')
        #aqui pones la columna que se agregara a la tabla
        ->groupBy('p.nroOperacion');
        
        
        if($buscarCodigo !=null){
            $pago->where('P.idEstudiante', 'like','%' .$buscarCodigo . '%');
        }
        if($nroOperacion !=null){
            $pago->where('P.nroOperacion', 'like','%' .$nroOperacion . '%');
        }
        
        if($nombreEstudiante !=null){
            $pago->where('E.nombre', 'like','%' .$nombreEstudiante . '%');
        }
        
        if($apellidoPaterno !=null){
            $pago->where('E.apellidoP', 'like','%' .$apellidoPaterno . '%');
        }
        
        if($apellidoMaterno !=null){
            $pago->where('E.apellidoM', 'like','%' .$apellidoMaterno . '%');
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
        $pagos = $pagos->appends(['buscarCodigo' => $buscarCodigo, 'apellidoPaterno'=>$apellidoPaterno, 'apellidoMaterno'=>$apellidoMaterno , 'nombreEstudiante'=>$nombreEstudiante  ,'nroOperacion' => $nroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin,'totalPago' => $totalPago]);

        if($request->has('generarPDF') && $request->generarPDF){
            $pdf = PDF::loadView('pages.pago.reportepdf', compact('pagos','nombreEstudiante' ,'apellidoPaterno','apellidoMaterno','nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago'));
            return $pdf->stream('invoice.pdf');
        }

        return view('pages.pago.fichaPago', compact('pagos','nombreEstudiante','apellidoPaterno','apellidoMaterno','nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago'));

    }

    public function detalleFichaPago($nroOperacion){
        $pago = Pago::where('nroOperacion', $nroOperacion)->first();
        $estudiante = Estudiante::where('idEstudiante', $pago->idEstudiante)->first();
        $detalles = detalle_pago::where('nroOperacion', $nroOperacion)->get();
        //dd($detalles->deuda->ConceptoEscala->descripcion);

        foreach ($detalles as $detalle) {
            $detalle->monto = number_format($detalle->monto, 2, '.', '');

            $detalle->estadoPago = '1';
            $detalle->save();
            $monto = $detalle->monto;
            // Actualizar la deuda
            $deuda = Deuda::findOrFail($detalle->idDeuda);
            if ($deuda) {
                $cond = DB::table('DETALLE_CONDONACION as DC')
                ->select('DC.IDDEUDA', DB::raw('SUM(DC.MONTO) as total'))->where('DC.IDDEUDA', '=', $deuda->idDeuda)
                    ->groupBy('DC.IDDEUDA')->first();
                // dd($cond);
                if ($cond == null) {
                    $montoPagar = $deuda->conceptoEscala->escala->monto + $deuda->montoMora;
                } else {
                    $montoPagar = $deuda->conceptoEscala->escala->monto + $deuda->montoMora - $cond->total;
                }
                $montotmp = $monto + $deuda->adelanto;
                // $deuda->adelanto += $monto;
                if ($montotmp >= $montoPagar) {
                    // $deuda->adelanto += $monto;
                    $deuda->estado = '0';
                }
                //$deuda->estado = '1';
                $deuda->adelanto = $montotmp;
                $deuda->save();
            }
        }
        $montoTotal = number_format($detalles->sum('monto'), 2, '.', '');

        return view('pages.pago.detalleFichaPago', compact('pago', 'estudiante', 'detalles', 'montoTotal'));

    }

    public function actualizaFichaPago($nroOperacion){
        $pago = Pago::findOrFail($nroOperacion);
        $pago->estadoPago = '2';
        $pago->metodoPago ='Fisico';
        $pago->save();
        $detalles = detalle_pago::where('nroOperacion', $nroOperacion)->get();
        foreach ($detalles as $detalle) {
            $detalle->estadoPago = '2';
            $detalle->save();
        }
        return redirect()->route('pago.fichapagos');
    }

    //TESORERO------------------------------------------------------------
    public function index1(Request $request, $generarPDF = null)
    {
        $buscarpor = $request->get('buscarpor');
        $buscarCodigo = $request->get('buscarCodigo');
        $nroOperacion = $request->get('nroOperacion');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');

        $conceptoEscalas = conceptoEscala::select('descripcion')->distinct()->get();
        $escalaF = escala::get();
        $grados = Grado::get();
        $secciones = Seccion::get();

        $busquedaConcepto = $request->get('busquedaConcepto');
        $busquedaEscala = $request->get('busquedaEscala');
        $busquedaGrado = $request->get('busquedaGrado');
        $busquedaSeccion = $request->get('busquedaSeccion');

        $dniEstudiante = $request->get('dniEstudiante');
        $busquedaNombreEstudiante = $request->get('busquedaNombreEstudiante');
        $busquedaApellidoEstudiante = $request->get('busquedaApellidoEstudiante');

        $codMinimo = $request->get('codMinimo');
        $codMaximo = $request->get('codMaximo');

        $pagosHoy = $request->get('pagosHoy');
        $fechaActual = date('Y-m-d');

        $pago = DB::table('pago as P')
        ->join('detalle_pago as DP', 'P.nroOperacion', '=', 'DP.nroOperacion') 
        ->join('deuda as D', 'D.idDeuda', '=', 'DP.idDeuda')   
        ->join('concepto_escala as C','C.idConceptoEscala','=','D.idConceptoEscala')
        ->join('escala as ES','C.idEscala','=','ES.idEscala')
        ->join('estudiante as E', 'P.idEstudiante', '=', 'E.idEstudiante')
        ->join('detalle_estudiante_gs as DEGS','DEGS.idEstudiante','=','E.idEstudiante')
        ->join('grado as G','G.gradoEstudiante','=','DEGS.gradoEstudiante')
        ->join('seccion as SEC','SEC.seccionEstudiante','=','DEGS.seccionEstudiante')
        ->select('P.nroOperacion','P.idEstudiante', 'P.fechaPago','P.periodo','E.nombre','E.apellidoP','E.apellidoM',DB::raw('ROUND(sum(DP.monto),2) as totalMonto'),
                'G.descripcionGrado','SEC.descripcionSeccion','ES.descripcion as escala','C.descripcion as concep','P.metodoPago')
        ->where('P.estadoPago','=','2')
        ->groupBy('p.nroOperacion','G.descripcionGrado', 
        'SEC.descripcionSeccion','escala','concep');
        
        if ($pagosHoy) {
            $pago->whereDate('P.fechaPago', '=', $fechaActual);
        }
        
        if($buscarCodigo !=null){
            $pago->where('P.idEstudiante', 'like','%' .$buscarCodigo . '%');
        }
        if($dniEstudiante!=null){
            $pago->where('E.DNI', $dniEstudiante);
        }
        if ($busquedaNombreEstudiante!=null) {
            $pago->where('E.nombre', 'like', '%'.$busquedaNombreEstudiante.'%');
        }

        if ($busquedaApellidoEstudiante != null) {
            $pago->where(DB::raw("CONCAT(E.apellidoP, ' ', E.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
        }

        if ($codMinimo != null) {
            $pago->having('totalMonto', '>=', $codMinimo);
        }
        if ($codMaximo != null) {
            $pago->having('totalMonto', '<=', $codMaximo);
        }

        if($nroOperacion !=null){
            $pago->where('DP.nroOperacion', 'like','%' .$nroOperacion . '%');
        }

        if ($fechaInicio) {
            $pago->whereDate('P.fechaPago', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $pago->whereDate('P.fechaPago', '<=', $fechaFin);
        }

        if($busquedaConcepto!=null){
            $pago->where('C.descripcion','like','%'.$busquedaConcepto.'%');
        }

        if ($busquedaEscala!=null){
            $pago->where('ES.idEscala','=', $busquedaEscala);
        }

        if ($busquedaGrado!=null) {
            $pago->where('DEGS.gradoEstudiante', $busquedaGrado);
        }

        if($busquedaSeccion!=null){
            $pago->where('DEGS.seccionEstudiante', $busquedaSeccion);
        }

        $pagos = $pago->paginate($this::PAGINATION);
        $totalPago = 0;

        // Iterar sobre los registros y sumar los valores de totalMonto
        foreach ($pagos as $minipago) {
            $totalPago += $minipago->totalMonto;
        }

        $pagos = $pagos->appends(['buscarCodigo' => $buscarCodigo, 'nroOperacion' => $nroOperacion, 
        'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin,'totalPago' => $totalPago, 'codMinimo'=>$codMinimo,
        'codMaximo'=>$codMaximo,'conceptoEscalas'=>$conceptoEscalas,'busquedaConcepto'=>$busquedaConcepto,'escalaF'=>$escalaF, 
        'grados'=>$grados, 'secciones'=>$secciones,'busquedaEscala'=>$busquedaEscala,'busquedaGrado'=>$busquedaGrado,
        'busquedaSeccion'=>$busquedaSeccion,'dniEstudiante'=>$dniEstudiante,'busquedaNombreEstudiante'=>$busquedaNombreEstudiante,
        'busquedaApellidoEstudiante'=>$busquedaApellidoEstudiante,'pagosHoy'=>$pagosHoy]);

        if($request->has('generarPDF') && $request->generarPDF){
            $pdf = PDF::loadView('pages.pago.reportepdf', compact(
                'pagos', 'nroOperacion', 'buscarCodigo', 'fechaInicio', 'fechaFin', 'totalPago', 'conceptoEscalas', 'busquedaConcepto', 
                'escalaF', 'grados', 'secciones', 'busquedaEscala', 'busquedaGrado', 'busquedaSeccion', 'dniEstudiante', 
                'busquedaNombreEstudiante', 'busquedaApellidoEstudiante', 'codMinimo', 'codMaximo', 'pagosHoy'
            ));
            
            return $pdf->stream('invoice.pdf');
        }

        return view('pages.pago.index1', compact('pagos', 'nroOperacion','buscarCodigo', 'fechaInicio', 'fechaFin','totalPago','conceptoEscalas','busquedaConcepto','escalaF', 'grados', 'secciones',
                    'busquedaEscala','busquedaGrado','busquedaSeccion','dniEstudiante','busquedaNombreEstudiante','busquedaApellidoEstudiante','codMinimo','codMaximo','pagosHoy'));

    }

}
