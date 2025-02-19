<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\condonacion;
use App\Models\Estudiante;
use App\Models\Deuda;
use App\Models\Pago;
use App\Models\Detalle_grado_seccion;
use Illuminate\Support\Facades\DB;
use App\Models\detalle_condonacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class CondonacionController extends Controller implements HasMiddleware
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
        if (Auth::user()->hasRole('tesorero'))
        {
            $idCondonacion = $request->get('idCondonacion');
            $idEstudiante = $request->get('codigoEstudiante');
            $dniEstudiante = $request->get('dniEstudiante');
            $montoMenor = $request->get('montoMenor');
            $montoMayor = $request->get('montoMayor');
            $busquedaNombreEstudiante = $request->get('busquedaNombreEstudiante');
            $busquedaApellidoEstudiante = $request->get('busquedaApellidoEstudiante');
            $baseQuery = DB::table('condonacion as C')
                ->join('detalle_condonacion as DC', 'DC.idCondonacion', '=', 'C.idCondonacion')
                ->join('deuda as D', 'D.idDeuda', '=', 'DC.idDeuda')
                ->join('estudiante as E', 'D.idEstudiante', '=', 'E.idEstudiante')
                ->select('C.idCondonacion', 'C.fecha','C.estadoCondonacion', 'E.idEstudiante', 'E.dni', DB::raw("CONCAT(E.nombre, ' ', E.apellidoP) as nombre_completo"),DB::raw('SUM(DC.monto) as total_monto'))
                ->groupBy('C.idCondonacion', 'C.fecha', 'E.idEstudiante', 'E.dni', 'E.nombre', 'E.apellidoP')
                ->havingBetween ('total_monto',[$montoMenor?: 0,$montoMayor?: 10000]);
            if ($idCondonacion)
                $baseQuery->where('C.idCondonacion',  'like', '%' .$idCondonacion. '%');
            if($idEstudiante)
                $baseQuery->where('E.idEstudiante','like', '%' . $idEstudiante. '%');
            if($dniEstudiante)
                $baseQuery->where('E.dni', 'like', '%' . $dniEstudiante. '%');
            if ($busquedaNombreEstudiante!=null) {
                $baseQuery->where('E.nombre', 'like', '%'.$busquedaNombreEstudiante.'%');
            }
            if ($busquedaApellidoEstudiante != null) {
                $baseQuery->where(DB::raw("CONCAT(E.apellidoP, ' ', E.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
            }
    
            if ($request->has('generarPDF') && $request->generarPDF) {
                $estado = $request->get('estado');
                $filteredQuery = clone $baseQuery;
            
                if ($estado === 'aceptadas') {
                    $filteredQuery->where('C.estadoCondonacion', '=', 2);
                } elseif ($estado === 'rechazadas') {
                    $filteredQuery->where('C.estadoCondonacion', '=', 0);
                } elseif ($estado === 'pendientes') {
                    $filteredQuery->where('C.estadoCondonacion', '=', 1);
                }
            
                $filteredDatos = $filteredQuery->get();
                $pdf = PDF::loadView('pages.condonacion.reporteGeneralpdf', 
                            compact('estado', 'filteredDatos','idCondonacion', 'idEstudiante', 'dniEstudiante', 'montoMenor', 'montoMayor','busquedaNombreEstudiante','busquedaApellidoEstudiante'));
                return $pdf->stream('reporte-' . $estado . '.pdf');
            }      
    
            $datosAceptados = clone $baseQuery;
            $datosRechazados = clone $baseQuery;
    
            $datos = $baseQuery->where('C.estadoCondonacion', '=', 1);
            $datosAceptados->where('C.estadoCondonacion', '=', 2);
            $datosRechazados->where('C.estadoCondonacion', '=', 0);
    
            $datos = $baseQuery->paginate($this::PAGINATION);
            $datosAceptados = $datosAceptados->paginate($this::PAGINATION);
            $datosRechazados = $datosRechazados->paginate($this::PAGINATION);
    
            return view('pages.condonacion.index', compact('datos', 'datosAceptados','datosRechazados','idCondonacion', 'idEstudiante', 'dniEstudiante', 'montoMenor', 'montoMayor','busquedaNombreEstudiante','busquedaApellidoEstudiante'));
        }
        else{
            $estados = [
                '0' => 'Rechazado',
                '1' => 'Solicitado',
                // '2' => 'Devuelto',
                '5' => 'Registrado',
            ];
            // dd($estados);
            $idCondonacion = $request->get('idCondonacion');
            $estadoCondonacion = $request->get('estadoCondonacion');
            $idEstudiante = $request->get('codigoEstudiante');
            $dniEstudiante = $request->get('dniEstudiante');
            $montoMenor = $request->get('montoMenor');
            $montoMayor = $request->get('montoMayor');
            $datos = DB::table('condonacion as C')
                ->join('detalle_condonacion as DC', 'DC.idCondonacion', '=', 'C.idCondonacion')
                ->join('deuda as D', 'D.idDeuda', '=', 'DC.idDeuda')
                ->join('estudiante as E', 'D.idEstudiante', '=', 'E.idEstudiante')
                ->select('C.idCondonacion', 'C.fecha','C.estadoCondonacion', 'E.idEstudiante','E.dni', DB::raw("CONCAT(E.nombre, ' ', E.apellidoP) as nombre_completo"),DB::raw('SUM(DC.monto) as total_monto'))
                ->groupBy('C.idCondonacion', 'C.fecha', 'E.idEstudiante', 'E.dni', 'E.nombre', 'E.apellidoP');
                if(isset($estadoCondonacion))
                    $datos = $datos->where('C.estadoCondonacion', '=' ,$estadoCondonacion);
                else $datos = $datos->whereIn('C.estadoCondonacion',['0','1','5']);

            $datos = $datos->havingBetween ('total_monto',[$montoMenor?: 0,$montoMayor?: 10000]);
            $estudiantes = null;
            if (Auth::user()->hasRole('padre')) {
                // Obtener la colección de condonaciones del padre (asumiendo que retorna colecciones con idCondonacion)
                $idCondonaciones = Auth::user()->getTotalCondonaciones()->pluck('idCondonacion')->toArray();
                // Ajustar la consulta para que solo traiga esas deudas
                $datos->whereIn('C.idCondonacion', $idCondonaciones);
                $estudiantes = Auth::user()->estudiantes()->get();
            }   
            if ($idCondonacion)
                $datos = $datos->where('C.idCondonacion',  'like', '%' .$idCondonacion. '%');
            if($idEstudiante)
                $datos = $datos->where('E.idEstudiante','like', '%' . $idEstudiante. '%');
            if($dniEstudiante)
                $datos = $datos->where('E.dni', 'like', '%' . $dniEstudiante. '%');
            
            $datos = $datos->paginate($this::PAGINATION)->appends(['idCondonacion' => $idCondonacion, 'codigoEstudiante' => $idEstudiante, 'dniEstudiante' => $dniEstudiante, 'montoMenor' => $montoMenor, 'montoMayor' => $montoMayor, 'estadoCondonacion' => $estadoCondonacion]);
            return view('pages.condonacion.index', compact('datos', 'idCondonacion', 'idEstudiante', 'dniEstudiante', 'montoMenor', 'montoMayor', 'estudiantes', 'estadoCondonacion', 'estados'));
        }
    }


    public function create(Request $request)
    {
        $codEstudiante = $request->get('codEstudiante');
        $dniEstudiante = $request->get('dniEstudiante');
        $busquedaNombreEstudiante = $request->get('busquedaNombreEstudiante');
        $busquedaApellidoEstudiante = $request->get('busquedaApellidoEstudiante');
        
        $students = null;
        if (Auth::user()->hasRole('padre')) {
            $students = Auth::user()->estudiantes()->get();
        }

        if($codEstudiante || $dniEstudiante || $busquedaNombreEstudiante || $busquedaApellidoEstudiante){
            $filtro =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.estado','1');

            if ($codEstudiante!=null) {
                $filtro->where('E.idEstudiante', '=', $codEstudiante);
            }

            if($dniEstudiante!=null){
                $filtro->where('E.DNI', $dniEstudiante);
            }

            if ($busquedaNombreEstudiante!=null) {
                $filtro->where('E.nombre', 'like', '%'.$busquedaNombreEstudiante.'%');
            }

            if ($busquedaApellidoEstudiante != null) {
                $filtro->where(DB::raw("CONCAT(E.apellidoP, ' ', E.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
            }
            $filtro = $filtro->paginate($this::PAGINATION);
            if($filtro->isEmpty()){
                return redirect()->route('condonacion.create')->with('mensaje', 'Estudiante no encontrado');
            }
            if(count($filtro) >1){
                $estudiantes = $filtro;
                return view('pages.condonacion.create', compact('estudiantes', 'codEstudiante', 'dniEstudiante', 'busquedaNombreEstudiante', 'busquedaApellidoEstudiante'));
            }
            $filtro = $filtro->first();

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
                'D.adelanto',
                'D.estado',
                'Esc.monto',
                'sub.total as totalCondonacion'

            )->where('D.idEstudiante', '=',$filtro->idEstudiante)->where('D.estado','=','1')->get();

        }
        
        else {
            $deudas = null;
            $filtro = null;

        }
        return view('pages.condonacion.create', compact('filtro', 'deudas', 'codEstudiante', 'dniEstudiante', 'busquedaNombreEstudiante', 'busquedaApellidoEstudiante', 'students'));
    }


    public function edit($id, $generarPDF = null)
    {
        // $condonacion = condonacion::findOrFail($id);
        if (Auth::user()->hasRole('tesorero')){
            // $condonacion = condonacion::findOrFail($id);
            $condonacion = DB::table('condonacion as C')
            ->join('detalle_condonacion as DC', 'C.idCondonacion', '=', 'DC.idCondonacion')
            ->select(
                'C.idCondonacion','C.fecha','C.estadoCondonacion',
                DB::raw('sum(DC.monto) as totalMonto')
            )
            ->where('C.idCondonacion', '=', $id)
            ->groupBy('C.idCondonacion')
            ->first();
            // dd($condonacion);
            // dd($condonacion)->toSql();
            $detalle = detalle_condonacion::where('idCondonacion', '=', $id)->first();
            //  dd($detalle)->toSql();
            $idEstudiante = Deuda::where('idDeuda', '=', $detalle->idDeuda)->pluck('idEstudiante');
            $estudiante =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.idEstudiante', '=', $idEstudiante)->first();
            $detalle =detalle_condonacion::where('idCondonacion', '=', $id)->paginate($this::PAGINATION);
    
            $aulas = Detalle_grado_seccion::get();
            
            if(isset($generarPDF)){
                $pdf = PDF::loadView('pages.condonacion.reportepdf', compact('condonacion', 'estudiante', 'aulas', 'detalle'));
                return $pdf->stream('invoice.pdf');
            }
            return view('pages.condonacion.edit', compact('condonacion', 'estudiante', 'aulas', 'detalle'));
    
        }else
        {
            $condonacion = DB::table('condonacion as C')
            ->join('detalle_condonacion as DC', 'C.idCondonacion', '=', 'DC.idCondonacion')
            ->select(
                'C.idCondonacion','C.fecha','C.estadoCondonacion',
                DB::raw('sum(DC.monto) as totalMonto')
            )
            ->where('C.idCondonacion', '=', $id)
            ->groupBy('C.idCondonacion')
            ->first();
            // dd($condonacion);
            // dd($condonacion)->toSql();
            $detalle = detalle_condonacion::where('idCondonacion', '=', $id)->first();
            //  dd($detalle)->toSql();
            $idEstudiante = Deuda::where('idDeuda', '=', $detalle->idDeuda)->pluck('idEstudiante');
            $estudiante =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.idEstudiante', '=', $idEstudiante)->first();
            $detalle =detalle_condonacion::where('idCondonacion', '=', $id)->paginate($this::PAGINATION);

            $aulas = Detalle_grado_seccion::get();
            switch ($condonacion->estadoCondonacion) {
                case 1:
                    $estado = 'Solicitado';
                    break;
                case 2:
                    $estado = 'En proceso';
                    break;
                case 3:
                    $estado = 'Devuelto';
                    break;
                case 4:
                    $estado = 'Registrado';
                    break;
                default:
                    $estado = 'Desconocido';
                    break;
            }
            if(isset($generarPDF)){
                $pdf = PDF::loadView('pages.condonacion.reportepdf', compact('condonacion', 'estudiante', 'aulas', 'detalle'));
                return $pdf->download('invoice.pdf');
            }
            return view('pages.condonacion.edit', compact('condonacion', 'estudiante', 'aulas', 'detalle','estado'));
            }
    }


    public function store(Request $request)
    {
        if (Auth::user()->hasRole('tesorero'))
        {
            try{
                DB::beginTransaction();
                $idEstudiante = $request->get('idestudiante');
                if (!isset($idEstudiante) || empty($idEstudiante)) {
                    throw new \Exception('Busque un estudiante o seleccione una deuda para condonar primero');
                }
                $condonacion = new Condonacion();
                $condonacion->fecha =  now()->format('Y-m-d');
                //$condonacion->estado = 1;
                $condonacion->estadoCondonacion = 1;
                $condonacion->save();
                
                
                foreach ($request->condonaciones as $idDeuda =>$pagoData) {
    
                    // dd($condonacion->idCondonacion);
                    // $cond = DB::table('DETALLE_CONDONACION as DC')->select(DB::raw('SUM(DC.MONTO) as total'))->where('DC.IDDEUDA', '=', $idDeuda)->groupBy('DC.IDDEUDA')->first();
                    // dd($cond->total);
                    $monto = $pagoData['monto'];
                    //   dd($monto);
                    //   dd($idDeuda);
                    //   dd($condonacion->idCondonacion);
                    $detalleCondonacion = new detalle_condonacion();
                    $detalleCondonacion->idCondonacion = $condonacion->idCondonacion;
                    $detalleCondonacion->idDeuda = $idDeuda;
                    $detalleCondonacion->monto = $monto;
                    $detalleCondonacion->save();
                    // dd($detalleCondonacion);
    
                    $deuda= Deuda::findOrFail($idDeuda);
                    if ($deuda) {
                        $montotmp = $monto + $deuda->adelanto;
                        $montototal = $deuda->conceptoEscala->escala->monto + $deuda->montoMora;
                        // $deuda->adelanto += $monto;
                        if($montotmp>=$montototal){
                            // $deuda->adelanto += $monto;
                            $deuda->estado = '0';
                        }
                        // $deuda->adelanto = $montotmp;
                        $deuda->save();
                    }
                }
                DB::commit();
                return redirect()->route('condonacion.index')->with('datos', 'Registro Nuevo Guardado...!');
            }
            catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('condonacion.create',['idEstudiante' => $idEstudiante])->with(['datos' => 'Error al guardar el registro', 'mensaje' => $e->getMessage()]);
            }
        }else{
            try{
                DB::beginTransaction();
                $idEstudiante = $request->get('idestudiante');
                if (!isset($idEstudiante) || empty($idEstudiante)) {
                    throw new \Exception('Busque un estudiante o seleccione una deuda para condonar primero');
                }
                $condonacion = new Condonacion();
                $condonacion->fecha =  now()->format('Y-m-d');
                $condonacion->estadoCondonacion = 1;
                $condonacion->save();
                // dd($request->condonaciones);
                foreach ($request->condonaciones as $idDeuda =>$pagoData) {

                    // dd($condonacion->idCondonacion);
                    // $cond = DB::table('DETALLE_CONDONACION as DC')->select(DB::raw('SUM(DC.MONTO) as total'))->where('DC.IDDEUDA', '=', $idDeuda)->groupBy('DC.IDDEUDA')->first();
                    // dd($cond->total);
                    $monto = $pagoData['monto'];
                    //   dd($monto);
                    //   dd($idDeuda);
                    //   dd($condonacion->idCondonacion);
                    $detalleCondonacion = new detalle_condonacion();
                    $detalleCondonacion->idCondonacion = $condonacion->idCondonacion;
                    $detalleCondonacion->idDeuda = $idDeuda;
                    $detalleCondonacion->monto = $monto;
                    $detalleCondonacion->save();
                    // dd($detalleCondonacion);

                    // -- esto lo hacen otros Roles

                    // $deuda= Deuda::findOrFail($idDeuda);
                    // if ($deuda) {
                    //     $montotmp = $monto + $deuda->adelanto;
                    //     $montototal = $deuda->conceptoEscala->escala->monto + $deuda->montoMora;
                    //     // $deuda->adelanto += $monto;
                    //     if($montotmp>=$montototal){
                    //         // $deuda->adelanto += $monto;
                    //         $deuda->estadoCondonacion = '0';
                    //     }
                    //     // $deuda->adelanto = $montotmp;
                    //     $deuda->save();
                    // }
                }
                DB::commit();
                return redirect()->route('condonacion.index')->with('datos', 'Registro Nuevo Guardado...!');
            }
            catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('condonacion.create',['idEstudiante' => $idEstudiante])->with(['datos' => 'Error al guardar el registro', 'mensaje' => $e->getMessage()]);
            }
        }
    }



    // public function update(Request $request, $id)
    // {
    //     $data = request()->validate(
    //         [
    //             'monto' => 'required|numeric',
    //             'fecha' => 'required|date',
    //         ],
    //         [
    //             'monto.required' => 'Ingrese monto de la condonacion',
    //             'monto.numeric' => 'El monto debe ser un número',
    //             'fecha.required' => 'Ingrese fecha de la condonacion',
    //             'fecha.date' => 'La fecha debe ser una fecha válida',
    //         ]
    //     );
    //     $condonacion = condonacion::findOrFail($id);
    //     $condonacion->monto = $request->monto;
    //     $condonacion->fecha = $request->fecha;
    //     $condonacion->save();
    //     return redirect()->route('condonacion.index')->with('datos', 'Registro Actualizado...!');
    // }
    public function confirmar($id)
    {
        $condonacion = condonacion::findOrFail($id);
        return view('pages.condonacion.confirm', compact('condonacion'));
    }
    public function destroy($id)
    {
        $condonacion = condonacion::findOrFail($id);
        // dd($condonacion);
        $detalles = detalle_condonacion::where('idCondonacion', '=', $id)->get();
        // dd($detalles);
        foreach ($detalles as $detalle) {
            // dd($detalle);
            $deuda = Deuda::findOrFail($detalle->idDeuda);
            // dd($deuda);
            //si la deuda está cancelada, quitarle el monto de la condonación, calcular el adelanto y cambiar el estadoCondonacion
            if($deuda->estadoCondonacion = '0'){
                $deuda->estadoCondonacion = '1';
                $deuda->save();
            }
            $detalle->monto = (float)0;
            $detalle->save();
        }
        $condonacion->estadoCondonacion = '0';
        $condonacion->save();
        return redirect()->route('condonacion.index')->with('datos', 'Registro Eliminado...!');
    }

    //TESORERO---------------------------
    public function indexCondonacionRR(Request $request, $generarPDF = null)
    {
        $idCondonacion = $request->get('idCondonacion');
        $idEstudiante = $request->get('codigoEstudiante');
        $dniEstudiante = $request->get('dniEstudiante');
        $montoMenor = $request->get('montoMenor');
        $montoMayor = $request->get('montoMayor');
        $busquedaNombreEstudiante = $request->get('busquedaNombreEstudiante');
        $busquedaApellidoEstudiante = $request->get('busquedaApellidoEstudiante');
        $baseQuery = DB::table('condonacion as C')
            ->join('detalle_condonacion as DC', 'DC.idCondonacion', '=', 'C.idCondonacion')
            ->join('deuda as D', 'D.idDeuda', '=', 'DC.idDeuda')
            ->join('estudiante as E', 'D.idEstudiante', '=', 'E.idEstudiante')
            ->select('C.idCondonacion', 'C.fecha','C.estadoCondonacion', 'E.idEstudiante', 'E.dni', DB::raw("CONCAT(E.nombre, ' ', E.apellidoP) as nombre_completo"),DB::raw('SUM(DC.monto) as total_monto'))
            ->groupBy('C.idCondonacion', 'C.fecha', 'E.idEstudiante', 'E.dni', 'E.nombre', 'E.apellidoP')
            ->havingBetween ('total_monto',[$montoMenor?: 0,$montoMayor?: 10000]);
        if ($idCondonacion)
            $baseQuery->where('C.idCondonacion',  'like', '%' .$idCondonacion. '%');
        if($idEstudiante)
            $baseQuery->where('E.idEstudiante','like', '%' . $idEstudiante. '%');
        if($dniEstudiante)
            $baseQuery->where('E.dni', 'like', '%' . $dniEstudiante. '%');
        if ($busquedaNombreEstudiante!=null) {
            $baseQuery->where('E.nombre', 'like', '%'.$busquedaNombreEstudiante.'%');
        }
        if ($busquedaApellidoEstudiante != null) {
            $baseQuery->where(DB::raw("CONCAT(E.apellidoP, ' ', E.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
        }

        if ($request->has('generarPDF') && $request->generarPDF) {
            $estado = $request->get('estado');
            $filteredQuery = clone $baseQuery;
        
            if ($estado === 'aceptadas') {
                $filteredQuery->where('C.estadoCondonacion', '=', 4);
            } elseif ($estado === 'rechazadas') {
                $filteredQuery->where('C.estadoCondonacion', '=', 3);
            } elseif ($estado === 'pendientes') {
                $filteredQuery->where('C.estadoCondonacion', '=', 5);
            }
        
            $filteredDatos = $filteredQuery->get();
            $pdf = PDF::loadView('pages.condonacion.reporteGeneralpdf', 
                        compact('estado', 'filteredDatos','idCondonacion', 'idEstudiante', 'dniEstudiante', 'montoMenor', 'montoMayor','busquedaNombreEstudiante','busquedaApellidoEstudiante'));
            return $pdf->stream('reporte-' . $estado . '.pdf');
        }      

        $datosAceptados = clone $baseQuery;
        $datosRechazados = clone $baseQuery;

        $datos = $baseQuery->where('C.estadoCondonacion', '=', 5);
        $datosAceptados->where('C.estadoCondonacion', '=', 4);
        $datosRechazados->where('C.estadoCondonacion', '=', 3);

        $datos = $baseQuery->paginate($this::PAGINATION);
        $datosAceptados = $datosAceptados->paginate($this::PAGINATION);
        $datosRechazados = $datosRechazados->paginate($this::PAGINATION);

        return view('pages.condonacion.indexCondonacionRR', compact('datos', 'datosAceptados','datosRechazados','idCondonacion', 'idEstudiante', 'dniEstudiante', 'montoMenor', 'montoMayor','busquedaNombreEstudiante','busquedaApellidoEstudiante'));
    }

    public function editCondonacion($id, $generarPDF = null)
    {
        // $condonacion = condonacion::findOrFail($id);
        $condonacion = DB::table('condonacion as C')
        ->join('detalle_condonacion as DC', 'C.idCondonacion', '=', 'DC.idCondonacion')
        ->select(
            'C.idCondonacion','C.fecha','C.estadoCondonacion',
            DB::raw('sum(DC.monto) as totalMonto')
        )
        ->where('C.idCondonacion', '=', $id)
        ->groupBy('C.idCondonacion')
        ->first();
        // dd($condonacion);
        // dd($condonacion)->toSql();
        $detalle = detalle_condonacion::where('idCondonacion', '=', $id)->first();
        //  dd($detalle)->toSql();
        $idEstudiante = Deuda::where('idDeuda', '=', $detalle->idDeuda)->pluck('idEstudiante');
        $estudiante =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.idEstudiante', '=', $idEstudiante)->first();
        $detalle =detalle_condonacion::where('idCondonacion', '=', $id)->paginate($this::PAGINATION);

        $aulas = Detalle_grado_seccion::get();
        
        if(isset($generarPDF)){
            $pdf = PDF::loadView('pages.condonacion.reportepdf', compact('condonacion', 'estudiante', 'aulas', 'detalle'));
            return $pdf->stream('invoice.pdf');
        }
        return view('pages.condonacion.editCondonacion', compact('condonacion', 'estudiante', 'aulas', 'detalle'));

    }

    public function realizarCondonacionA($id, $generarPDF = null)
    {
        // $condonacion = condonacion::findOrFail($id);
        $condonacion = DB::table('condonacion as C')
        ->join('detalle_condonacion as DC', 'C.idCondonacion', '=', 'DC.idCondonacion')
        ->select(
            'C.idCondonacion','C.fecha','C.estadoCondonacion',
            DB::raw('sum(DC.monto) as totalMonto')
        )
        ->where('C.idCondonacion', '=', $id)
        ->groupBy('C.idCondonacion')
        ->first();
        // dd($condonacion);
        // dd($condonacion)->toSql();
        $detalle = detalle_condonacion::where('idCondonacion', '=', $id)->first();
        //  dd($detalle)->toSql();
        $idEstudiante = Deuda::where('idDeuda', '=', $detalle->idDeuda)->pluck('idEstudiante');
        $estudiante =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.idEstudiante', '=', $idEstudiante)->first();
        $detalle =detalle_condonacion::where('idCondonacion', '=', $id)->paginate($this::PAGINATION);

        $aulas = Detalle_grado_seccion::get();
        
        if(isset($generarPDF)){
            $pdf = PDF::loadView('pages.condonacion.reportepdf', compact('condonacion', 'estudiante', 'aulas', 'detalle'));
            return $pdf->stream('invoice.pdf');
        }
        return view('pages.condonacion.realizarCondonacionA', compact('condonacion', 'estudiante', 'aulas', 'detalle'));

    }

    public function realizarCondonacionO($id, $generarPDF = null)
    {
        // $condonacion = condonacion::findOrFail($id);
        $condonacion = DB::table('condonacion as C')
        ->join('detalle_condonacion as DC', 'C.idCondonacion', '=', 'DC.idCondonacion')
        ->select(
            'C.idCondonacion','C.fecha','C.estadoCondonacion',
            DB::raw('sum(DC.monto) as totalMonto')
        )
        ->where('C.idCondonacion', '=', $id)
        ->groupBy('C.idCondonacion')
        ->first();
        // dd($condonacion);
        // dd($condonacion)->toSql();
        $detalle = detalle_condonacion::where('idCondonacion', '=', $id)->first();
        //  dd($detalle)->toSql();
        $idEstudiante = Deuda::where('idDeuda', '=', $detalle->idDeuda)->pluck('idEstudiante');
        $estudiante =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.idEstudiante', '=', $idEstudiante)->first();
        $detalle =detalle_condonacion::where('idCondonacion', '=', $id)->paginate($this::PAGINATION);

        $aulas = Detalle_grado_seccion::get();
        
        if(isset($generarPDF)){
            $pdf = PDF::loadView('pages.condonacion.reportepdf', compact('condonacion', 'estudiante', 'aulas', 'detalle'));
            return $pdf->stream('invoice.pdf');
        }
        return view('pages.condonacion.realizarCondonacionO', compact('condonacion', 'estudiante', 'aulas', 'detalle'));

    }

    public function actualizarSolicitud(Request $request, $idCondonacion)
    {
        $observacion = $request->observacion;
        $condonacion = condonacion::findOrFail($idCondonacion);

        if ($request->action === 'aprobar') {
            $condonacion->estadoCondonacion = 2; 
        } elseif ($request->action === 'rechazar') {
            $condonacion->estadoCondonacion = 0; 
        }

        $condonacion->save();

        return redirect()->route('condonacion.index')->with('datos', 'Solicitud evaluada correctamente');
    }

    public function actualizarCondonacionO(Request $request, $idCondonacion)
    {
        $observacion = $request->observacion;
        $condonacion = condonacion::findOrFail($idCondonacion);

        if ($request->action === 'aprobar') {
            $condonacion->estadoCondonacion = 2; 
        }

        $condonacion->save();

        return redirect()->route('condonacion.indexCondonacionRR')->with('datos', 'Solicitud evaluada correctamente');
    }

    public function actualizarCondonacionA(Request $request, $idCondonacion)
    {
        $observacion = $request->observacion;
        $condonacion = condonacion::findOrFail($idCondonacion);

        if ($request->action === 'aprobar') {
            $condonacion->estadoCondonacion = 5; 
        }

        $condonacion->save();
        //dd($condonacion);
        $condonaciones = detalle_condonacion::where('idCondonacion', '=', $idCondonacion);
        //dd($condonaciones);
        foreach ($condonaciones as $detalle) {

            $deuda= Deuda::findOrFail($detalle->idDeuda);
            if ($deuda) {
                $montotmp = $detalle->monto + $deuda->adelanto;
                $montototal = $deuda->conceptoEscala->escala->monto + $deuda->montoMora;
                // $deuda->adelanto += $monto;
                if($montotmp>=$montototal){
                    // $deuda->adelanto += $monto;
                    $deuda->estado = '0';
                }
                // $deuda->adelanto = $montotmp;
                $deuda->save();
            }
        }

        return redirect()->route('condonacion.indexCondonacionRR')->with('datos', 'Solicitud evaluada correctamente');
    }
}

