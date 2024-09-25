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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


//$fechaActual = date('Y-m-d');->obtener fecha actual

class DevolucionController extends Controller
{
    const PAGINATION = 5;

    public function index(Request $request)
    {
        $buscarxEstudiante = $request->get('buscarxEstudiante');
        $busquedaxnroOperacion = $request->get('busquedaxnroOperacion');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        //if($busquedaxEstudiante=null)
        
        $query = DB::table('detalle_devolucion as DD')
            ->join('pago as P', 'P.nroOperacion', '=', 'DD.nroOperacion')
            ->join('devolucion as D', 'D.idDevolucion', '=', 'DD.idDevolucion')
            ->join('estudiante as E', 'E.idEstudiante', '=', 'P.idEstudiante')
            ->leftJoin('detalle_pago as DP', 'DP.nroOperacion', '=', 'P.nroOperacion')
            ->where('DD.estado', '=', '1')
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
        if ($fechaInicio) {
            $query->whereDate('D.fechaDevolucion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->whereDate('D.fechaDevolucion', '<=', $fechaFin);
        }
        $datos = $query
                ->paginate($this::PAGINATION)
                ->appends(['buscarxEstudiante' => $buscarxEstudiante, 'busquedaxnroOperacion' => $busquedaxnroOperacion, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]);

        return view('pages.devolucion.index', compact('datos', 'buscarxEstudiante','busquedaxnroOperacion','fechaInicio','fechaFin'));
    }
    
    public function create(Request $request)
    {
        $estudiante = null;
        $detalle_pago = null;
        $idEstudiante = $request->get('idEstudiante');
        $pago=pago::where('estado','=','1')->get();
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
            ->where('P.idEstudiante','=',$idEstudiante)->where('DP.estado','=','1')
            ->select('DP.monto','P.fechaPago','P.nroOperacion','D.idDeuda','CE.descripcion')->get();
            if (!isset($estudiante)) {
                return redirect()->route('devolucion.create')->with(['mensaje'=>'Estudiante no encontrado']);
            }
            return view('pages.devolucion.create', compact('pago', 'estudiante','detalle_pago'));
        }

        return view('pages.devolucion.create',compact('pago','estudiante','detalle_pago'));
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
            ->where('P.idEstudiante','=',$request->idEstudiante)->where('DP.estado','=','1')
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
            $devolucion->estado = 1;
            $devolucion->save();

            $devolucion = devolucion::where('estado', '=', '1')->orderBy('idDevolucion', 'desc')->first();
            $detalle_devolucion = new detalle_devolucion();
            $detalle_devolucion->nroOperacion = $request->input('nroOperacion');
            $detalle_devolucion->idDevolucion = $devolucion->idDevolucion;
            $detalle_devolucion->observacion = $request->input('observacion');
            $detalle_devolucion->estado = 1;
            $detalle_devolucion->save();
            
            $deudas = $validatedData['deudas'];

            for ($i = 0; $i < count($deudas); $i++) {
                $idDeuda = $deudas[$i];
                
                // Obtener la deuda
                $deuda = deuda::findOrFail($idDeuda);
                
                // Obtener el detalle_pago usando ambas claves
                $detalle_pago = detalle_pago::where('nroOperacion', $request->input('nroOperacion'))
                                            ->where('idDeuda', $idDeuda)
                                            ->firstOrFail();
                
                // Actualizar los estados y valores
                $deuda->estado = '1';
                $deuda->adelanto -= $detalle_pago->monto;
                $detalle_pago->estado = '0';

                // Guardar los cambios
                $deuda->save();
                $detalle_pago->save();  // Corrige aquí, accede al objeto directamente, no como un array
            }

            $pago = pago::findOrFail($request->input('nroOperacion'));
            $pago->estado='0'; //eliminamos el pago
            $pago->save();
            //DB::commit();

            return redirect()->route('devolucion.index')->with('mensaje', 'Devolucion realizada con éxito.');
    }

    public function datos(Request $request){

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
        return view('pages.devolucion.datos', compact('operacion', 'deudas', 'estudiante', 'fechaActual', 'observacion'));
    }
}