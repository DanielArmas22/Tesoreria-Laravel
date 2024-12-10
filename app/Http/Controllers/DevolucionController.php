<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\conceptoEscala;
use App\Models\detalle_devolucion;
use App\Models\detalle_pago;
use App\Models\deuda;
use App\Models\devolucion;
use App\Models\estudiante;
use App\Models\pago;
use App\Models\escala;
use App\Models\Grado;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use PDF;

//$fechaActual = date('Y-m-d');->obtener fecha actual

class DevolucionController extends Controller  implements HasMiddleware
{
    public static function middleware(): array 
    {
        return [
            'auth',
        ];
    }
    const PAGINATION = 5;

    public function index(Request $request)
    {
        if (Auth::user()->hasRole("tesorero")){
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

            $DevolucionesHoy = $request->get('DevolucionesHoy');
            $fechaActual = date('Y-m-d');
        
            // Base query
            $baseQuery = DB::table('detalle_devolucion as DD')
                ->join('pago as P', 'P.nroOperacion', '=', 'DD.nroOperacion')
                ->join('devolucion as D', 'D.idDevolucion', '=', 'DD.idDevolucion')
                ->join('estudiante as E', 'E.idEstudiante', '=', 'P.idEstudiante')
                ->join('detalle_estudiante_gs as DEGS', 'DEGS.idEstudiante', '=', 'E.idEstudiante')
                ->join('grado as G', 'G.gradoEstudiante', '=', 'DEGS.gradoEstudiante')
                ->join('seccion as SEC', 'SEC.seccionEstudiante', '=', 'DEGS.seccionEstudiante')
                ->leftJoin('detalle_pago as DP', 'DP.nroOperacion', '=', 'P.nroOperacion')
                ->select(
                    'DD.idDevolucion',
                    'DD.nroOperacion',
                    'DD.observacion',
                    'E.idEstudiante',
                    'E.nombre',
                    'E.apellidoP',
                    'E.apellidoM',
                    'D.fechaDevolucion',
                    DB::raw('SUM(DP.monto) as totalPago'),
                    'G.descripcionGrado',
                    'SEC.descripcionSeccion',
                    'DD.motivoDevolucion'
                )
                ->groupBy(
                    'DD.idDevolucion',
                    'DD.nroOperacion',
                    'DD.observacion',
                    'E.idEstudiante',
                    'E.nombre',
                    'E.apellidoP',
                    'E.apellidoM',
                    'D.fechaDevolucion',
                    'G.descripcionGrado',
                    'SEC.descripcionSeccion'
                );

            // Apply filters
            if ($DevolucionesHoy) {
                $baseQuery->whereDate('D.fechaDevolucion', '=', $fechaActual);
            }
            if ($buscarCodigo != null) {
                $baseQuery->where('E.idEstudiante', '=', $buscarCodigo);
            }
            if ($dniEstudiante != null) {
                $baseQuery->where('E.DNI', $dniEstudiante);
            }
            if ($busquedaNombreEstudiante != null) {
                $baseQuery->where('E.nombre', 'like', '%' . $busquedaNombreEstudiante . '%');
            }
            if ($busquedaApellidoEstudiante != null) {
                $baseQuery->where(DB::raw("CONCAT(E.apellidoP, ' ', E.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
            }
            if ($nroOperacion != null) {
                $baseQuery->where('P.nroOperacion', '=', $nroOperacion);
            }
            if ($fechaInicio) {
                $baseQuery->whereDate('D.fechaDevolucion', '>=', $fechaInicio);
            }
            if ($fechaFin) {
                $baseQuery->whereDate('D.fechaDevolucion', '<=', $fechaFin);
            }
            if ($busquedaGrado != null) {
                $baseQuery->where('DEGS.gradoEstudiante', $busquedaGrado);
            }
            if ($busquedaSeccion != null) {
                $baseQuery->where('DEGS.seccionEstudiante', $busquedaSeccion);
            }
            
            if ($request->has('generarPDF') && $request->generarPDF) {
                $estado = $request->get('estado');
                $filteredQuery = clone $baseQuery;
            
                if ($estado === 'aceptadas') {
                    $filteredQuery->where('D.estadoDevolucion', '=', 2);
                } elseif ($estado === 'rechazadas') {
                    $filteredQuery->where('D.estadoDevolucion', '=', 0);
                } elseif ($estado === 'pendientes') {
                    $filteredQuery->where('D.estadoDevolucion', '=', 1);
                }
        
                $filteredDatos = $filteredQuery->get();

                $pdf = PDF::loadView('pages.devolucion.reportepdf', compact(
                    'filteredDatos', 'estado', 'buscarCodigo', 'nroOperacion', 'fechaInicio', 'fechaFin',
                    'conceptoEscalas', 'escalaF', 'grados', 'secciones', 'busquedaConcepto', 'busquedaEscala', 'busquedaGrado',
                    'busquedaSeccion', 'dniEstudiante', 'busquedaNombreEstudiante', 'busquedaApellidoEstudiante'
                ));
            
                return $pdf->stream('reporte-' . $estado . '.pdf');
            }        

            $datosAceptados = clone $baseQuery;
            $datosRechazados = clone $baseQuery;

            $datos = $baseQuery->where('D.estadoDevolucion', '=', 1);
            $datosAceptados->where('D.estadoDevolucion', '=', 2);
            $datosRechazados->where('D.estadoDevolucion', '=', 0);

            $datos = $baseQuery->paginate($this::PAGINATION);
            $datosAceptados = $datosAceptados->paginate($this::PAGINATION);
            $datosRechazados = $datosRechazados->paginate($this::PAGINATION);

            return view('pages.devolucion.index', compact(
                'datos', 'datosAceptados', 'datosRechazados', 'buscarCodigo', 'nroOperacion', 'fechaInicio', 'fechaFin',
                'conceptoEscalas', 'escalaF', 'grados', 'secciones', 'busquedaConcepto', 'busquedaEscala', 'busquedaGrado',
                'busquedaSeccion', 'dniEstudiante', 'busquedaNombreEstudiante', 'busquedaApellidoEstudiante','DevolucionesHoy'
            ));
        }
        else{
            $buscarxEstudiante = $request->get('buscarxEstudiante');
            $busquedaxnroOperacion = $request->get('busquedaxnroOperacion');
            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');
            $estadoDevolucion = $request->get('estadoDevolucion');
            $nombreEstudiante = $request->get('nombreEstudiante');
            $mayorPago = $request->get('mayorPago');
            $menorPago = $request->get('menorPago');
            $apellidoPaterno = $request->get('apellidoPaterno');

            $estados = [
                '0' => 'Rechazado',
                '1' => 'Solicitado',
                '2' => 'En Proceso',
                '5' => 'Registrado',
            ];
                if(Auth::user()->rol != 'cajero'){
                    $query = DB::table('detalle_devolucion as DD')
                        ->join('pago as P', 'P.nroOperacion', '=', 'DD.nroOperacion')
                        ->join('devolucion as D', 'D.idDevolucion', '=', 'DD.idDevolucion')
                        ->join('estudiante as E', 'E.idEstudiante', '=', 'P.idEstudiante')
                        ->leftJoin('detalle_pago as DP', 'DP.nroOperacion', '=', 'P.nroOperacion');
                        if(isset($estadoDevolucion))
                            $query = $query->where('D.estadoDevolucion', '=' ,$estadoDevolucion)->where('DD.estadoDevolucion', '=' ,$estadoDevolucion);
                        else{
                            $query = $query->where('DD.estadoDevolucion', '=', '1');
                        }
                        $query = $query->select(
                            'DD.idDevolucion',
                            'DD.nroOperacion',
                            'DD.observacion',
                            'D.estadoDevolucion',
                            'E.idEstudiante',
                            'E.nombre',
                            'E.apellidoP',
                            'D.fechaDevolucion',
                            DB::raw('SUM(DP.monto) as totalPago')
                        )
                        ->groupBy(
                            'DD.idDevolucion',
                            'DD.nroOperacion',
                            'DD.observacion',
                            'D.estadoDevolucion',
                            'E.idEstudiante',
                            'E.nombre',
                            'E.apellidoP',
                            'D.fechaDevolucion'
                        );
                }else{  
                    $query = DB::table('detalle_devolucion as DD')
                        ->join('pago as P', 'P.nroOperacion', '=', 'DD.nroOperacion')
                        ->join('devolucion as D', 'D.idDevolucion', '=', 'DD.idDevolucion')
                        ->join('estudiante as E', 'E.idEstudiante', '=', 'P.idEstudiante')
                        ->leftJoin('detalle_pago as DP', 'DP.nroOperacion', '=', 'P.nroOperacion')
                        ->where('DD.estadoDevolucion', '=', '2')
                        ->where('D.estadoDevolucion', '=', '2')
                        ->select(
                            'DD.idDevolucion',
                            'DD.nroOperacion',
                            'DD.observacion',
                            'E.idEstudiante',
                            'D.estadoDevolucion',
                            'E.nombre',
                            'E.apellidoP',
                            'D.fechaDevolucion',
                            DB::raw('SUM(DP.monto) as totalPago')
                        )
                        ->groupBy(
                            'DD.idDevolucion',
                            'DD.nroOperacion',
                            'DD.observacion',
                            'E.idEstudiante',
                            'D.estadoDevolucion',
                            'E.nombre',
                            'E.apellidoP',
                            'D.fechaDevolucion'
                        );
                }
                $estudiantes = null;
                if (Auth::user()->hasRole('padre')) {
                    // Obtener la colección de pagos del padre (asumiendo que retorna colecciones con nroOperacion)
                    $idDevolucion = Auth::user()->getTotalDevoluciones()->pluck('idDevolucion')->toArray();
                    // Ajustar la consulta para que solo traiga esas deudas
                    $query->whereIn('D.idDevolucion', $idDevolucion);
                    $estudiantes = Auth::user()->estudiantes()->get();
                    }
            $total = DB::table('DETALLE_CONDONACION as DC')
                    ->select('DC.IDDEUDA', DB::raw('SUM(DC.MONTO) as total'))
                    ->groupBy('DC.IDDEUDA');

            if($buscarxEstudiante!=null){
                $query->where('E.idEstudiante',"=",$buscarxEstudiante);
            }
            if($busquedaxnroOperacion!=null){
                $query->where('P.nroOperacion','=',$busquedaxnroOperacion);
            }
            if ($fechaInicio) {
                $query->whereDate('D.fechaDevolucion', '>=', $fechaInicio);
            }
            if ($fechaFin) {
                $query->whereDate('D.fechaDevolucion', '<=', $fechaFin);
            }
            if($nombreEstudiante!=null){
                $query->where('E.nombre','=',$nombreEstudiante);
            }

            if($apellidoPaterno!=null){
                $query->where('E.apellidoP','=',$apellidoPaterno);
            }
            
            if ($menorPago != null) {
                $query->havingRaw('SUM(DP.monto) >= ?', [$menorPago]);
            }
            if ($mayorPago != null) {
                $query->havingRaw('SUM(DP.monto) <= ?', [$mayorPago]);
            }
            $datos = $query
                    ->paginate($this::PAGINATION)
                    ->appends(['buscarxEstudiante' => $buscarxEstudiante, 'busquedaxnroOperacion' => $busquedaxnroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin, 'estadoDevolucion' => $estadoDevolucion, 'nombreEstudiante' => $nombreEstudiante, 'apellidoPaterno' => $apellidoPaterno, 'mayorPago' => $mayorPago, 'menorPago' => $menorPago]);

            return view('pages.devolucion.index', compact('datos', 'buscarxEstudiante','busquedaxnroOperacion','fechaInicio','fechaFin','estudiantes','estados','estadoDevolucion','nombreEstudiante','apellidoPaterno','mayorPago','menorPago'));
        }
    }
    
    public function create(Request $request)
    {
        $estudiante = null;
        $detalle_pago = null;
        $estudiantes = null;
        $idEstudiante = $request->get('idEstudiante');
        if (Auth::user()->hasRole('padre')) {
            $estudiantes = Auth::user()->estudiantes()->get();
        }
        $pago=pago::where('estadoPago','=','2')->get();
        if($idEstudiante!=null){
            $estudiante = DB::table('estudiante as E')
            ->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')
            ->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')
            ->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')
            ->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion')->where('E.estado', '=', '1')->where('E.idEstudiante', $idEstudiante)->first();
            
            $detalle_pago= DB::table('detalle_pago as DP')
            ->join('pago as P','P.nroOperacion','=','DP.nroOperacion')
            ->join('deuda as D','D.idDeuda','=','DP.idDeuda')
            ->join('concepto_escala as CE','CE.idConceptoEscala','=','D.idConceptoEscala')
            ->where('P.idEstudiante','=',$idEstudiante)->where('DP.estadoPago','=','2')
            ->select('DP.monto','P.fechaPago','P.nroOperacion','D.idDeuda','CE.descripcion')->get();
            if (!isset($estudiante)) {
                return redirect()->route('devolucion.create')->with(['mensaje'=>'Estudiante no encontrado']);
            }
            return view('pages.devolucion.create', compact('pago', 'estudiante','detalle_pago','estudiantes'));
        }

        return view('pages.devolucion.create',compact('pago','estudiante','detalle_pago','estudiantes'));
    }

    public function realizarDevolucion($operacion, Request $request)
    {
        $deudaIds = $request->deudas;
        $estudiante = estudiante::findOrFail($request->idEstudiante);
        $fechaActual = date('Y-m-d');
        
        $deudas= DB::table('detalle_pago as DP')
            ->join('pago as P','P.nroOperacion','=','DP.nroOperacion')
            ->join('deuda as D','D.idDeuda','=','DP.idDeuda')
            ->join('concepto_escala as CE','CE.idConceptoEscala','=','D.idConceptoEscala')
            ->where('P.idEstudiante','=',$request->idEstudiante)->where('DP.estadoPago','=','2')
            ->where('DP.nroOperacion','=',$operacion)
            ->select('D.idDeuda','DP.monto','CE.descripcion')->get();
            
        return view('pages.devolucion.realizarDevolucion', compact('operacion', 'deudas', 'estudiante','fechaActual'));
    }

    public function update(Request $request)
    {
        $validatedData=request()->validate([
            'observacion'=>'required|max:20',
            'deudas' => 'required|array'
        ],
        [
            'observacion.required' => 'Ingrese observacion de la devolucion',
            'observacion.max' => 'Maximo 20 caracteres para la observacion',
            'deudas.required' => 'Debe seleccionar al menos una deuda',
            'deudas.array' => 'Las deudas deben ser un array',
        ]);
            $devolucion = new devolucion();
            $devolucion->fechaDevolucion = $request->input('fechaActual');
            $devolucion->estadoDevolucion = 1;
            $devolucion->save();

            $devolucion = devolucion::where('estadoDevolucion', '=', '1')->orderBy('idDevolucion', 'desc')->first();
            $detalle_devolucion = new detalle_devolucion();
            $detalle_devolucion->nroOperacion = $request->input('nroOperacion');
            $detalle_devolucion->idDevolucion = $devolucion->idDevolucion;
            $detalle_devolucion->motivoObservacion = $request->input('motivo');
            $detalle_devolucion->estadoDevolucion = 1;
            $detalle_devolucion->save();
            
            // $deudas = $validatedData['deudas'];

            // Esto es realizado por el Tesorero
            // for ($i = 0; $i < count($deudas); $i++) {
            //     $idDeuda = $deudas[$i];
                
            //     // Obtener la deuda
            //     $deuda = deuda::findOrFail($idDeuda);
                
            //     // Obtener el detalle_pago usando ambas claves
            //     $detalle_pago = detalle_pago::where('nroOperacion', $request->input('nroOperacion'))
            //                                 ->where('idDeuda', $idDeuda)
            //                                 ->firstOrFail();
                
            //     // Actualizar los estados y valores
            //     $deuda->estado = '1';
            //     $deuda->adelanto -= $detalle_pago->monto;
            //     $detalle_pago->estadoPago = '0';

            //     // Guardar los cambios
            //     $deuda->save();
            //     $detalle_pago->save();  // Corrige aquí, accede al objeto directamente, no como un array
            // }

            // $pago = pago::findOrFail($request->input('nroOperacion'));
            // $pago->estadoPago='0'; //eliminamos el pago
            // $pago->save();
            //DB::commit();

            return redirect()->route('devolucion.index')->with('mensaje', 'Devolucion Solicitada con Éxito.');
    }

    public function datos(Request $request){
        if (Auth::user()->hasRole("tesorero")){
            $fechaActual = $request->fechaDevolucion;
            $estudiante = estudiante::findOrFail($request->idEstudiante);
            $motivoDevolucion = $request->motivoDevolucion;
            $operacion = $request->nroOperacion;
            $idDevolucion = $request->idDevolucion;
            $deudas = DB::table('detalle_pago as DP')
                ->join('pago as P', 'P.nroOperacion', '=', 'DP.nroOperacion')
                ->join('deuda as D', 'D.idDeuda', '=', 'DP.idDeuda')
                ->join('concepto_escala as CE', 'CE.idConceptoEscala', '=', 'D.idConceptoEscala')
                ->where('P.idEstudiante', '=', $request->idEstudiante)
                ->where('DP.estadoPago', '=', '2')
                ->where('DP.nroOperacion', '=', $request->nroOperacion)
                ->select('D.idDeuda', 'DP.monto', 'CE.descripcion')->get();

            return view('pages.devolucion.datos', compact('operacion', 'deudas', 'estudiante', 'fechaActual', 'motivoDevolucion','idDevolucion'));
        
        }else{
            $fechaActual = $request->fechaDevolucion;
            $estudiante = estudiante::findOrFail($request->idEstudiante);
            $observacion = $request->observacion;
            $operacion = $request->nroOperacion;
            $deudas = DB::table('detalle_pago as DP')
                ->join('pago as P', 'P.nroOperacion', '=', 'DP.nroOperacion')
                ->join('deuda as D', 'D.idDeuda', '=', 'DP.idDeuda')
                ->join('concepto_escala as CE', 'CE.idConceptoEscala', '=', 'D.idConceptoEscala')
                ->where('P.idEstudiante', '=', $request->idEstudiante)
                ->where('DP.estadoPago', '=', '2')
                ->where('DP.nroOperacion', '=', $request->nroOperacion)
                ->select('D.idDeuda', 'DP.monto', 'CE.descripcion')->get();
            return view('pages.devolucion.datos', compact('operacion', 'deudas', 'estudiante', 'fechaActual', 'observacion'));
        }
    }


    public function showpdf(Request $request){
        $datos = json_decode($request->input('datos'), true);
        //dd($datos); // Verifica los datos aquí
        $datos = collect($datos)->map(function ($item) {
            return (object) $item;
        });
        $pdf = PDF::loadView('pages.devolucion.reportepdf', compact('datos'));
        return $pdf->stream('invoice.pdf');
    } 
//cajero----------------------------------------------------------------------------------------------------------------------------
    public function actualizarDevolucion($idDevolucion,$Operacion){
        if (Auth::user()->hasRole('tesorero')){
            $devolucion = Devolucion::findOrFail($idDevolucion);
            if ($request->action === 'aprobar') {
                $devolucion->estadoDevolucion = 4; 
            }
            $devolucion->save();
    
            detalle_devolucion::where('idDevolucion', $idDevolucion)
            ->update(['estadoDevolucion' => $devolucion->estadoDevolucion]);
    
            $deudas = DB::table('detalle_devolucion as DD')
                ->join('detalle_pago as DP', 'DP.nroOperacion', '=', 'DD.nroOperacion')
                ->join('deuda as D', 'D.idDeuda', '=', 'DP.idDeuda')
                ->where('DD.idDevolucion', '=', $idDevolucion) // Filtrar por idDevolucion
                ->where('DP.estadoPago', '=', '0') // Filtrar por estadoPago si es necesario
                ->select('D.idDeuda', 'DP.monto', 'D.adelanto')
                ->get();
    
            foreach ($deudas as $deuda) {
                DB::table('deuda')
                    ->where('idDeuda', $deuda->idDeuda)
                    ->update(['estado' => 1]); // Cambiar el estado de la deuda
    
                $nuevoAdelanto = $deuda->adelanto - $deuda->monto;
                DB::table('deuda')
                    ->where('idDeuda', $deuda->idDeuda)
                    ->update(['adelanto' => $nuevoAdelanto]);
            }
    
            return redirect()->route('devolucion.indexDevolucionR')->with('datos', 'Solicitud evaluada correctamente');
        }else{
            $devolucion = devolucion::findorFail($idDevolucion);
            $devolucion->estadoDevolucion = '3';
            $devolucion->save();
            $pago = pago::findorFail($Operacion);
            $pago->estadoPago='0';
            $pago->save();
            $detalle = detalle_devolucion::where('nroOperacion','=',$Operacion)->get();
            foreach($detalle as $detalle){
                $detalle->estado='3';//nuevo
                $detalle->save();
            }
            return redirect()->route('devolucion.index')->with('mensaje', 'Devolucion actualizada con éxito.');
        }
    }
    
    public function devolucionesRealizadas(Request $request){
        
        $buscarxEstudiante = $request->get('buscarxEstudiante');
        $busquedaxnroOperacion = $request->get('busquedaxnroOperacion');
        $nombreEstudiante = $request->get('nombreEstudiante');
        $mayorPago = $request->get('mayorPago');
        $menorPago = $request->get('menorPago');
        $apellidoPaterno = $request->get('apellidoPaterno');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        
        $query = DB::table('detalle_devolucion as DD')
                ->join('pago as P', 'P.nroOperacion', '=', 'DD.nroOperacion')
                ->join('devolucion as D', 'D.idDevolucion', '=', 'DD.idDevolucion')
                ->join('estudiante as E', 'E.idEstudiante', '=', 'P.idEstudiante')
                ->leftJoin('detalle_pago as DP', 'DP.nroOperacion', '=', 'P.nroOperacion')
                ->where('DD.estadoDevolucion', '=', '3')
                ->where('D.estadoDevolucion', '=', '3')
                ->select(
                    'DD.idDevolucion',
                    'DD.nroOperacion',
                    'DD.observacion',
                    'E.idEstudiante',
                    'E.nombre',
                    'E.apellidoP',
                    'D.fechaDevolucion',
                    DB::raw('SUM(DP.monto) as totalPago')
                )
                ->groupBy(
                    'DD.idDevolucion',
                    'DD.nroOperacion',
                    'DD.observacion',
                    'E.idEstudiante',
                    'E.nombre',
                    'E.apellidoP',
                    'D.fechaDevolucion'
                );
        
                $total = DB::table('DETALLE_CONDONACION as DC')
                ->select('DC.IDDEUDA', DB::raw('SUM(DC.MONTO) as total'))
                ->groupBy('DC.IDDEUDA');

        if($buscarxEstudiante!=null){
            $query->where('E.idEstudiante',"=",$buscarxEstudiante);
        }

        if($busquedaxnroOperacion!=null){
            $query->where('P.nroOperacion','=',$busquedaxnroOperacion);
        }

        if($nombreEstudiante!=null){
            $query->where('E.nombre','=',$nombreEstudiante);
        }

        if($apellidoPaterno!=null){
            $query->where('E.apellidoP','=',$apellidoPaterno);
        }
        
        if ($menorPago != null) {
            $query->havingRaw('SUM(DP.monto) >= ?', [$menorPago]);
        }
        if ($mayorPago != null) {
            $query->havingRaw('SUM(DP.monto) <= ?', [$mayorPago]);
        }

        if ($fechaInicio) {
            $query->whereDate('D.fechaDevolucion', '>=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->whereDate('D.fechaDevolucion', '<=', $fechaFin);
        }
        $datos = $query
                ->paginate($this::PAGINATION)
                ->appends(['buscarxEstudiante' => $buscarxEstudiante, 'nombreEstudiante' => $nombreEstudiante, 'apellidoPaterno' =>$apellidoPaterno ,'mayorPago'=>$mayorPago,'menorPago'=>$menorPago, 'busquedaxnroOperacion' => $busquedaxnroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]);

        return view('pages.devolucion.devolucionesRealizadas', compact('datos', 'buscarxEstudiante','menorPago','mayorPago', 'nombreEstudiante', 'apellidoPaterno' ,'busquedaxnroOperacion','fechaInicio','fechaFin'));        
    }

    public function datosRealizados(Request $request){
        $idDevolucion = $request->idDevolucion;
        $fechaActual = $request->fechaDevolucion;
        $estudiante = estudiante::findOrFail($request->idEstudiante);
        $observacion = $request->observacion;
        $operacion = $request->nroOperacion;
        $deudas = DB::table('detalle_pago as DP')
            ->join('pago as P', 'P.nroOperacion', '=', 'DP.nroOperacion')
            ->join('deuda as D', 'D.idDeuda', '=', 'DP.idDeuda')
            ->join('concepto_escala as CE', 'CE.idConceptoEscala', '=', 'D.idConceptoEscala')
            ->where('P.idEstudiante', '=', $request->idEstudiante)
            ->where('DP.estado', '=', '0')
            ->where('DP.nroOperacion', '=', $request->nroOperacion)
            ->select('D.idDeuda', 'DP.monto', 'CE.descripcion')->get();
        return view('pages.devolucion.datosRealizados', compact('operacion', 'deudas', 'estudiante', 'fechaActual', 'observacion','idDevolucion'));
    }

    //TESORERO------------------------
    public function indexDevolucionR(Request $request, $generarPDF = null)
    {
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

        $DevolucionesHoy = $request->get('DevolucionesHoy');
        $fechaActual = date('Y-m-d');
        
        // Base query
        $baseQuery = DB::table('detalle_devolucion as DD')
            ->join('pago as P', 'P.nroOperacion', '=', 'DD.nroOperacion')
            ->join('devolucion as D', 'D.idDevolucion', '=', 'DD.idDevolucion')
            ->join('estudiante as E', 'E.idEstudiante', '=', 'P.idEstudiante')
            ->join('detalle_estudiante_gs as DEGS', 'DEGS.idEstudiante', '=', 'E.idEstudiante')
            ->join('grado as G', 'G.gradoEstudiante', '=', 'DEGS.gradoEstudiante')
            ->join('seccion as SEC', 'SEC.seccionEstudiante', '=', 'DEGS.seccionEstudiante')
            ->leftJoin('detalle_pago as DP', 'DP.nroOperacion', '=', 'P.nroOperacion')
            ->select(
                'DD.idDevolucion',
                'DD.nroOperacion',
                'DD.observacion',
                'E.idEstudiante',
                'E.nombre',
                'E.apellidoP',
                'E.apellidoM',
                'D.fechaDevolucion',
                DB::raw('SUM(DP.monto) as totalPago'),
                'G.descripcionGrado',
                'SEC.descripcionSeccion',
                'DD.motivoDevolucion'
            )
            ->groupBy(
                'DD.idDevolucion',
                'DD.nroOperacion',
                'DD.observacion',
                'E.idEstudiante',
                'E.nombre',
                'E.apellidoP',
                'E.apellidoM',
                'D.fechaDevolucion',
                'G.descripcionGrado',
                'SEC.descripcionSeccion'
            );

        // Apply filters
        if ($DevolucionesHoy) {
            $baseQuery->whereDate('D.fechaDevolucion', '=', $fechaActual);
        }
        if ($buscarCodigo != null) {
            $baseQuery->where('E.idEstudiante', '=', $buscarCodigo);
        }
        if ($dniEstudiante != null) {
            $baseQuery->where('E.DNI', $dniEstudiante);
        }
        if ($busquedaNombreEstudiante != null) {
            $baseQuery->where('E.nombre', 'like', '%' . $busquedaNombreEstudiante . '%');
        }
        if ($busquedaApellidoEstudiante != null) {
            $baseQuery->where(DB::raw("CONCAT(E.apellidoP, ' ', E.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
        }
        if ($nroOperacion != null) {
            $baseQuery->where('P.nroOperacion', '=', $nroOperacion);
        }
        if ($fechaInicio) {
            $baseQuery->whereDate('D.fechaDevolucion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $baseQuery->whereDate('D.fechaDevolucion', '<=', $fechaFin);
        }
        if ($busquedaGrado != null) {
            $baseQuery->where('DEGS.gradoEstudiante', $busquedaGrado);
        }
        if ($busquedaSeccion != null) {
            $baseQuery->where('DEGS.seccionEstudiante', $busquedaSeccion);
        }
        
        if ($request->has('generarPDF') && $request->generarPDF) {
            $estado = $request->get('estado');
            $filteredQuery = clone $baseQuery;
        
            if ($estado === 'aceptadas') {
                $filteredQuery->where('D.estadoDevolucion', '=', 4);
            } elseif ($estado === 'pendientes') {
                $filteredQuery->where('D.estadoDevolucion', '=', 3);
            }
        
            $filteredDatos = $filteredQuery->get();

            $pdf = PDF::loadView('pages.devolucion.reportepdf', compact(
                'filteredDatos', 'estado', 'buscarCodigo', 'nroOperacion', 'fechaInicio', 'fechaFin',
                'conceptoEscalas', 'escalaF', 'grados', 'secciones', 'busquedaConcepto', 'busquedaEscala', 'busquedaGrado',
                'busquedaSeccion', 'dniEstudiante', 'busquedaNombreEstudiante', 'busquedaApellidoEstudiante'
            ));
        
            return $pdf->stream('reporte-' . $estado . '.pdf');
        }        

        $datosAceptados = clone $baseQuery;

        $datos = $baseQuery->where('D.estadoDevolucion', '=', 3);
        $datosAceptados->where('D.estadoDevolucion', '=', 4);

        $datos = $baseQuery->paginate($this::PAGINATION);
        $datosAceptados = $datosAceptados->paginate($this::PAGINATION);

        return view('pages.devolucion.indexDevolucionR', compact(
            'datos', 'datosAceptados', 'buscarCodigo', 'nroOperacion', 'fechaInicio', 'fechaFin',
            'conceptoEscalas', 'escalaF', 'grados', 'secciones', 'busquedaConcepto', 'busquedaEscala', 'busquedaGrado',
            'busquedaSeccion', 'dniEstudiante', 'busquedaNombreEstudiante', 'busquedaApellidoEstudiante','DevolucionesHoy'
        ));
    }

    public function actualizarSolicitud(Request $request, $idDevolucion)
    {
        $observacion = $request->observacion;
        $devolucion = Devolucion::findOrFail($idDevolucion);

        if ($request->action === 'aprobar') {
            $devolucion->estadoDevolucion = 2; 
        } elseif ($request->action === 'rechazar') {
            $devolucion->estadoDevolucion = 0; 
        }

        $devolucion->save();

        detalle_devolucion::where('idDevolucion', $idDevolucion)
        ->update(['estadoDevolucion' => $devolucion->estadoDevolucion, 'observacion'=>$observacion]);

        return redirect()->route('devolucion.index')->with('datos', 'Solicitud evaluada correctamente');
    }

    public function datosDevolucion(Request $request){

        $fechaActual = $request->fechaDevolucion;
        $estudiante = estudiante::findOrFail($request->idEstudiante);
        $motivoDevolucion = $request->motivoDevolucion;
        $operacion = $request->nroOperacion;
        $idDevolucion = $request->idDevolucion;
        $deudas = DB::table('detalle_pago as DP')
            ->join('pago as P', 'P.nroOperacion', '=', 'DP.nroOperacion')
            ->join('deuda as D', 'D.idDeuda', '=', 'DP.idDeuda')
            ->join('concepto_escala as CE', 'CE.idConceptoEscala', '=', 'D.idConceptoEscala')
            ->where('P.idEstudiante', '=', $request->idEstudiante)
            ->where('DP.estadoPago', '=', '0')
            ->where('DP.nroOperacion', '=', $request->nroOperacion)
            ->select('D.idDeuda', 'DP.monto', 'CE.descripcion')->get();
        return view('pages.devolucion.datosDevolucion', compact('operacion', 'deudas', 'estudiante', 'fechaActual', 'motivoDevolucion','idDevolucion'));
    }

}