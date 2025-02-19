<?php

namespace App\Http\Controllers;

use App\Models\conceptoEscala;
use App\Models\deuda;
use App\Models\estudiante;
use App\Models\escala;
use App\Models\Grado;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use PDF;

class DeudaController extends Controller implements HasMiddleware
{
    public static function middleware(): array 
    {
        return [
            'auth',
        ];
    }

    
    const PAGINATION = 10;
    public function index(Request $request,$generarPDF = null)
    {
        
        $escalaF = escala::get();
        $grados = Grado::get();
        $secciones = Seccion::get();
        $conceptoEscalas = conceptoEscala::select('descripcion')->distinct()->get(); // Obtener valores únicos de 'descripcion'



        $codEstudiante = $request->get('codEstudiante');
        $dniEstudiante = $request->get('dniEstudiante');
        $busquedaNombreEstudiante = $request->get('busquedaNombreEstudiante');
        $busquedaApellidoEstudiante = $request->get('busquedaApellidoEstudiante');

        
        $codMinimo = $request->get('codMinimo');
        $codMaximo = $request->get('codMaximo');
        $busquedaConcepto = $request->get('busquedaConcepto');

        $deudaHoy = $request->get('deudasHoy');
        $fechaActual = date('Y-m-d');

        $busquedaEscala = $request->get('busquedaEscala');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin') ;
        $busquedaGrado = $request->get('busquedaGrado');
        $busquedaSeccion = $request->get('busquedaSeccion');

        // if(Auth::user()->hasRole('padre')){
        //     $deudas = Auth::user()->getTotalDeudas();
        //     dd($deudas);
        // }
        $query = DB::table('deuda as D')
                    ->join('estudiante as ES','ES.idEstudiante','=','D.idEstudiante')
                    ->join('concepto_escala as C','C.idConceptoEscala','=','D.idConceptoEscala')
                    ->join('escala as E','C.idEscala','=','E.idEscala')
                    ->join('detalle_estudiante_gs as DEGS','DEGS.idEstudiante','=','ES.idEstudiante')
                    ->select('D.*', 'C.descripcion', 'E.descripcion as desEscala','ES.idEstudiante','ES.nombre','ES.apellidoP','E.monto',DB::raw('(SELECT SUM(DC.MONTO) FROM DETALLE_CONDONACION as DC WHERE DC.IDDEUDA = D.idDeuda GROUP BY DC.IDDEUDA) as totalCondonacion'))->where('D.estado','1');
        $estudiantes = null;
        if (Auth::user()->hasRole('padre')) {
            // Obtener la colección de deudas del padre (asumiendo que retorna colecciones con idDeuda)
            $idsDeudasPadre = Auth::user()->getTotalDeudas()->pluck('idDeuda')->toArray();
            // Ajustar la consulta para que solo traiga esas deudas
            $query->whereIn('D.idDeuda', $idsDeudasPadre);
            $estudiantes = Auth::user()->estudiantes()->get();
        }

        if($busquedaConcepto!=null){
            $query->where('C.descripcion','like','%'.$busquedaConcepto.'%');
        }

        if ($deudaHoy != null) {
            $query->whereDate('D.fechaRegistro', '=', $fechaActual);
        }
        if ($fechaInicio != null) {
            $query->whereDate('D.fechaLimite', '>=', $fechaInicio);
        }

        if ($fechaInicio == $fechaActual) {
            $query->whereDate('D.fechaLimite', '>=', $fechaActual);
        }
        
        if ($busquedaEscala!=null){
            $query->where('E.idEscala','=', $busquedaEscala);
        }
        // Filtros de estudiantes
        if ($codEstudiante!=null) {
            $query->where('ES.idEstudiante', '=', $codEstudiante);
        }

        if($dniEstudiante!=null){
            $query->where('ES.DNI', $dniEstudiante);
        }

        if ($busquedaNombreEstudiante!=null) {
            $query->where('ES.nombre', 'like', '%'.$busquedaNombreEstudiante.'%');
        }

        if ($busquedaApellidoEstudiante != null) {
            $query->where(DB::raw("CONCAT(ES.apellidoP, ' ', ES.apellidoM)"), 'like', '%' . $busquedaApellidoEstudiante . '%');
        }
        
        if ($codMinimo != null) {
            $query->where('E.monto', '>=', $codMinimo);
        }

        if ($codMaximo != null) {
            $query->where('E.monto', '<=', $codMaximo);
        }    

        if ($fechaFin!=null) {
            $query->whereDate('D.fechaLimite', '<=', $fechaFin);
        }

        if ($busquedaGrado!=null) {
            $query->where('DEGS.gradoEstudiante', $busquedaGrado);
        }

        if($busquedaSeccion!=null){
            $query->where('DEGS.seccionEstudiante', $busquedaSeccion);
        }
        
        $datos = $query
                ->paginate($this::PAGINATION)
                ->appends(['codEstudiante' => $codEstudiante, 'dniEstudiante'=> $dniEstudiante, 'busquedaNombreEstudiante' => $busquedaNombreEstudiante, 'busquedaApellidoEstudiante' => $busquedaApellidoEstudiante, 'codMinimo' => $codMinimo, 'codMaximo'=>$codMaximo, 'busquedaSeccion' => $busquedaSeccion, 'busquedaGrado' => $busquedaGrado,
                'busquedaEscala' => $busquedaEscala, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin,'deudaHoy' => $deudaHoy,'busquedaConcepto' => $busquedaConcepto]);
        if($request->has('generarPDF') && $request->generarPDF){
            $totalDeuda = 0;
            foreach ($datos as $deuda) {
                $totalDeuda += $deuda->montoMora + $deuda->monto - $deuda->totalCondonacion;
            }
            $pdf = PDF::loadView('pages.deuda.reporteDeuda', compact('datos', 'totalDeuda'));
            return $pdf->stream('invoice.pdf');
        }
        return view('pages.deuda.index', compact('datos', 'codEstudiante', 'dniEstudiante', 'busquedaNombreEstudiante','busquedaApellidoEstudiante', 'codMinimo', 'codMaximo', 'busquedaGrado', 'busquedaSeccion' ,'escalaF', 'grados', 'secciones','conceptoEscalas', 'fechaInicio', 'fechaFin','busquedaEscala','deudaHoy','busquedaConcepto', 'estudiantes'));
    }
    public function create(Request $request)
    {
        $estudiante = null;
        $buscarEstudiante = $request->get('buscarEstudiante');
        $conceptoEscala = conceptoEscala::findOrFail(1);
        $escala = escala::findOrFail(1);

        if ($buscarEstudiante != null) {
            $estudiante = Estudiante::find($buscarEstudiante);
            if (!isset($estudiante)) {
                return redirect()->route('deuda.create')->with(['mensaje'=>'Estudiante no encontrado']);
            }
            return view('pages.deuda.create', compact('estudiante', 'conceptoEscala', 'escala'));
        }
        return view('pages.deuda.create', compact('estudiante', 'conceptoEscala', 'escala'));
    }

    public function edit($id)
    {
        $deuda = deuda::findOrFail($id);
        return view('pages.deuda.edit', compact('deuda'));
    }
    public function store(Request $request){
        $data=request()->validate([
            'fechaLimite' => 'required|date_format:Y-m-d',
            ],
            [
                'fechaLimite.required' => 'Ingrese la fecha límite',
                'fechaLimite.date_format' => 'La fecha límite debe estar en el formato Año-Mes-Día (YYYY-MM-DD)',
            ]);
            $deuda=new deuda();
            $deuda->idEstudiante=$request->idEstudiante;
            $deuda->idConceptoEscala=$request->idConceptoEscala;
            $deuda->estado='1';
            $deuda->montoMora=0;
            $deuda->fechaLimite=$request->fechaLimite;
            $deuda->adelanto=0;
            $deuda->save();
            
            return redirect()->route('deuda.index')->with('deuda','Registro Nuevo Guardado...!');
    }
    public function update(Request $request, $id)
    {
        $data=request()->validate([
            'fechaLimite' => 'required|date_format:Y-m-d'
        ],
        [
            'fechaLimite.required' => 'Ingrese la fecha límite',
            'fechaLimite.date_format' => 'La fecha límite debe estar en el formato Año-Mes-Día (YYYY-MM-DD)',
        ]);
        $deuda=deuda::findOrFail($id);
        $deuda->idEstudiante=$request->idEstudiante;
        $deuda->idConceptoEscala=$request->idConceptoEscala;
        $deuda->montoMora=0;
        $deuda->fechaLimite=$request->fechaLimite;
        $deuda->adelanto=0;
        $deuda->estado='1';
        $deuda->save();
        return redirect()->route('deuda.index')->with('deuda','Registro Actualizado...!');
    }
    public function confirmar($id)
    {
        $deuda = deuda::findOrFail($id);
        return view('pages.deuda.confirm', compact('deuda'));
    }
    public function destroy($id)
    {
        $deuda = deuda::findOrFail($id);
        $deuda->estado = '0';
        $deuda->save();
        return redirect()->route('deuda.index')->with('deuda', 'Registro Eliminado...!');
    }
}