<?php

namespace App\Http\Controllers;

use App\Models\detalle_condonacion;
use App\Models\Detalle_estudiante_GS;
use App\Models\Detalle_grado_seccion;
use App\Models\Estudiante;
use App\Models\Deuda;
use App\Models\pago;
use App\Models\escala_estudiante;
use App\Models\detalle_devolucion;
use App\Models\escala;
use App\Models\User;
use App\Models\Estudiante_padre;

// use App\Models\escala_estudiante;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EstudianteController extends Controller
{
    const PAGINATION = 5;
    // const Busqueda = 
    public function index(Request $request)
    {
        $busquedaNombre = $request->get('busquedaNombre');
        $busquedaCodigo = $request->get('busquedaCodigo');
        $busquedaDNI = $request->get('busquedaDNI');
        $grado = $request->get('grado');
        $seccion = $request->get('seccion');
        $opDeuda = $request->get('deuda');

        $aulas = Detalle_grado_seccion::get();
        $grados = $aulas->unique('gradoEstudiante');
        $secciones = $aulas->unique('seccionEstudiante');

        $subquery = DB::table('escala_estudiante as EE1')
            ->select('EE1.idEstudiante', 'EE1.idEscala', 'EE1.periodo')
            ->whereIn('periodo', function ($query) {
                $query->select(DB::raw('MAX(periodo)'))
                    ->from('escala_estudiante as EE2')
                    ->whereColumn('EE1.idEstudiante', 'EE2.idEstudiante');
            });
        switch ($opDeuda) {
            case 'si':
                    $datos = DB::table('estudiante as E')
                        ->join('deuda as D', 'D.idEstudiante', '=', 'E.idEstudiante')
                        ->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')
                        ->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')
                        ->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')
                        ->leftJoinSub($subquery, 'EE', function ($join) {
                            $join->on('EE.idEstudiante', '=', 'E.idEstudiante');
                        })
                        ->leftJoin('escala as Es', 'Es.idEscala', '=', 'EE.idEscala')
                        ->select(
                            'E.idEstudiante', 
                            'E.DNI', 
                            'E.nombre', 
                            'E.apellidoP', 
                            'E.apellidoM', 
                            'G.descripcionGrado', 
                            'S.descripcionSeccion', 
                            'G.gradoEstudiante', 
                            'S.seccionEstudiante', 
                            'Es.descripcion', 
                            'Es.idEscala', 
                            'EE.periodo',
                        )
                        ->distinct()
                        ->where('E.estado', '=', '1')
                        ->where('D.estado', '=', '1');
                break;
            case 'no':
                $datos = DB::table('estudiante as E')
                ->leftJoin('deuda as D', 'D.idEstudiante', '=', 'E.idEstudiante')
                ->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')
                ->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')
                ->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')
                ->leftJoinSub($subquery, 'EE', function ($join) {
                    $join->on('EE.idEstudiante', '=', 'E.idEstudiante');
                })
                ->leftJoin('escala as Es', 'Es.idEscala', '=', 'EE.idEscala')
                ->select(
                    'E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM',
                    'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante',
                    'S.seccionEstudiante', 'Es.descripcion', 'Es.idEscala', 'EE.periodo'
                )
                ->where('E.estado', '=', '1')
                ->where(function ($query) {
                    $query->whereNull('D.idEstudiante')->orWhere('D.estado', '=', '0');}) 
                ->whereNull('EE.idEstudiante');// Estudiantes que no existen en la tabla escala_estudiante
                break;
            default:
                $datos = DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->leftJoinSub($subquery, 'EE', function ($join) {
                    $join->on('EE.idEstudiante', '=', 'E.idEstudiante');
                })->leftJoin('escala as Es', 'Es.idEscala', '=', 'EE.idEscala')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante', 'Es.descripcion', 'Es.idEscala', 'EE.periodo')->where('E.estado', '=', '1');
                $opDeuda = "todos";
                break;
        }

        // $datos = Estudiante::where('estado', '=', '1');

        if ($busquedaCodigo != null) {
            $datos = $datos->where('E.idEstudiante', 'like', '%' . $busquedaCodigo . '%');
        }

        if ($busquedaNombre != null) {
            $datos = $datos->where('E.nombre', 'like', '%' . $busquedaNombre . '%');
        }
        if ($busquedaDNI != null) {
            $datos = $datos->where('E.dni', 'like', '%' . $busquedaDNI . '%');
        }
        if ($grado != null) {
            $datos = $datos->where('G.gradoEstudiante', '=', $grado);
        }
        if ($seccion != null) {
            $datos = $datos->where('S.seccionEstudiante', '=', $seccion);
        }
        // $datos = Estudiante::with('detalle_estudiante_gs')->where('estado', '=', '1');
        $datos = $datos->paginate($this::PAGINATION)->appends(['busquedaCodigo' => $busquedaCodigo, 'busquedaNombre' => $busquedaNombre, 'busquedaDNI' => $busquedaDNI, 'grado' => $grado, 'seccion' => $seccion, 'deuda' => $opDeuda]);
        // dd($datos);
        // return view('pagina.estudiante.index', compact('datos', 'busquedaCodigo', 'busquedaNombre'));
        return view('pages.estudiante.index', compact('datos', 'busquedaCodigo', 'busquedaNombre', 'busquedaDNI', 'grados', 'secciones', 'grado', 'seccion', 'opDeuda'));
    }
    public function create()
    {
        $aulas = Detalle_grado_seccion::get();
        return view('pages.estudiante.create', compact('aulas'));
    }

    public function store(Request $request)
    {
        $data = request()->validate(
            [
                'DNI' => 'required|max:8|unique:estudiante,DNI',
                'nombre' => 'required|max:20',
                'apellidoP' => 'required|max:20',
                'apellidoM' => 'required|max:20',
                'aula' => 'required',
            ],
            [
                'DNI.required' => 'Ingrese DNI del estudiante',
                'DNI.max' => 'Maximo 8 caracteres para el DNI',
                'DNI.unique' => 'El DNI ya esta registrado',
                'nombre.required' => 'Ingrese nombre del estudiante',
                'nombre.max' => 'Maximo 20 caracteres para el nombre',
                'apellidoP.required' => 'Ingrese apellido paterno del estudiante',
                'apellidoP.max' => 'Maximo 20 caracteres para el apellido paterno',
                'apellidoM.required' => 'Ingrese apellido materno del estudiante',
                'apellidoM.max' => 'Maximo 20 caracteres para el apellido materno',
                'aula.required' => 'Seleccione un aula para el estudiante',

            ]
        );
        $estudiante = new Estudiante();
        $estudiante->DNI = $request->DNI;
        $estudiante->nombre = $request->nombre;
        $estudiante->apellidoP = $request->apellidoP;
        $estudiante->apellidoM = $request->apellidoM;
        $estudiante->estado = 1;
        $estudiante->save();
        // $estudiante->grado = $request->grado;
        $aula = $request->aula;
        $valores = explode("-", $aula);
        $grado = $valores[0];
        $seccion = $valores[1];
        //guardar en la tabla detalle_estudiante_gs
        $detalleEstudianteGS = new Detalle_estudiante_GS();
        $detalleEstudianteGS->idEstudiante = Estudiante::max('idEstudiante');
        $detalleEstudianteGS->gradoEstudiante = $grado;
        $detalleEstudianteGS->seccionEstudiante = $seccion;
        $detalleEstudianteGS->fechaAsignacion = now();
        $detalleEstudianteGS->estado = 1;
        $detalleEstudianteGS->save();

         // Logica de creacion del padre
        $apoderado = User::where('DNI', '=', $request->DNIApoderado)->where("rol","=","padre")->first();
        if(!isset($apoderado)){
            $apoderado = new User();
            $apoderado->DNI = $request->DNIApoderado;
            $apoderado->name = $request->nombreApoderado;
            $apoderado->email = $request->emailApoderado;
            $apoderado->password = bcrypt($request->DNIApoderado);
            $apoderado->rol = "padre";
            $apoderado->save();
        }
        $estudiantePadre = new Estudiante_padre();
        $estudiantePadre->idEstudiante = $estudiante->idEstudiante;
        $estudiantePadre->idUsuario = $apoderado->id;  
        $estudiantePadre->save();
        return redirect()->route('estudiante.index')->with('datos', 'Registro Nuevo Guardado...!');
    }
    public function buscarApoderado(Request $request)
    {
        $apoderado = User::where('DNI', '=', $request->DNIApoderado)->where("rol","=","padre")->first();
        return redirect()->back()->with('apoderado', $apoderado);
    }
    
    public function edit($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        // dd($estudiante);
        if (auth()->user()->cannot('edit', $estudiante)) {
            abort(403, 'Estudiante no encontrado');
        }
        $aulas = Detalle_grado_seccion::get();
        $deudas = Deuda::select(
            'deuda.*',
            DB::raw('(SELECT SUM(DC.MONTO) FROM DETALLE_CONDONACION as DC WHERE DC.IDDEUDA = deuda.idDeuda GROUP BY DC.IDDEUDA) as totalCondonacion')
        )
        ->where('idEstudiante', '=', $id)
        ->where('estado', '1')
        ->paginate($this::PAGINATION);
        // $periodo = $request->get('periodo');

        // if ($periodo != null) {
        //     $deudas = Deuda::where('idEstudiante', '=', $id)->where('estado','1')->whereYear('fechaLimite','=',$periodo)->paginate($this::PAGINATION);
        // }

        $estudiante =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.idEstudiante', '=', $id)->first();
        $escalas = escala_estudiante::where('idEstudiante', '=', $id)->get();
        $periodos = escala_estudiante::where('idEstudiante', '=', $id)->orderBy('periodo', 'desc')->pluck('periodo');
        // dd($escalas);
        return view('pages.estudiante.edit', compact('estudiante','aulas', 'deudas', 'escalas', 'periodos'));
    }
    public function update(Request $request, $id)
    {
        $data = request()->validate(
            [
                'DNI' => 'required|max:8',
                'nombre' => 'required|max:20',
                'apellidoP' => 'required|max:20',
                'apellidoM' => 'required|max:20',
            ],
            [
                'DNI.required' => 'Ingrese DNI del estudiante',
                'nombre.required' => 'Ingrese nombre del estudiante',
                'nombre.max' => 'Maximo 20 caracteres para el nombre',
                'apellidoP.required' => 'Ingrese apellido paterno del estudiante',
                'apellidoP.max' => 'Maximo 20 caracteres para el apellido paterno',
                'apellidoM.required' => 'Ingrese apellido materno del estudiante',
                'apellidoM.max' => 'Maximo 20 caracteres para el apellido materno',
            ]
        );
        $estudiante = Estudiante::findOrFail($id);
        $estudiante->nombre = $request->nombre;
        $estudiante->DNI = $request->DNI;
        $estudiante->apellidoP = $request->apellidoP;
        $estudiante->apellidoM = $request->apellidoM;
        $estudianteGrado = Detalle_estudiante_GS::where('idEstudiante', '=', $id)->first();
        $gradoEstudiante = $estudianteGrado->gradoEstudiante;
        $seccionEstudiante = $estudianteGrado->seccionEstudiante;
        $aula = $request->aula;
        $valores = explode("-", $aula);
        $grado = $valores[0];
        $seccion = $valores[1];
        if ($gradoEstudiante != $grado || $seccionEstudiante != $seccion) {
            $detalleEstudianteGS = new Detalle_estudiante_GS();
            $detalleEstudianteGS->idEstudiante = $id;
            $detalleEstudianteGS->gradoEstudiante = $request->grado;
            $detalleEstudianteGS->seccionEstudiante = $request->seccion;
            $detalleEstudianteGS->fechaAsignacion = now();
            $detalleEstudianteGS->estado = 1;
            $detalleEstudianteGS->save();
        }
        $estudiante->save();

       
        return redirect()->route('estudiante.index')->with('datos', 'Registro Actualizado...!');
    }
    public function confirmar($id)
    {
        $estudiante =  DB::table('estudiante as E')->join('detalle_estudiante_gs as DE', 'E.idEstudiante', '=', 'DE.idEstudiante')->join('grado as G', 'DE.gradoEstudiante', '=', 'G.gradoEstudiante')->join('seccion as S', 'DE.seccionEstudiante', '=', 'S.seccionEstudiante')->select('E.idEstudiante', 'E.DNI', 'E.nombre', 'E.apellidoP', 'E.apellidoM', 'G.descripcionGrado', 'S.descripcionSeccion', 'G.gradoEstudiante', 'S.seccionEstudiante')->where('E.idEstudiante', '=', $id)->first();
        $aulas = Detalle_grado_seccion::get();
        $deudas = Deuda::where('idEstudiante', '=', $id)->paginate($this::PAGINATION);
        // $pagos = pago::where('idEstudiante', '=', $id)->paginate($this::PAGINATION);
        return view('pages.estudiante.confirm', compact('estudiante', 'aulas', 'deudas'));
    }
    public function destroy(string $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        // eliminar escala estudiante
        $escalas = escala_estudiante::where('idEstudiante', '=', $id)->get();
        foreach ($escalas as $escala) {
            $escala->estado = 0;
            $escala->save();
        }


        //eliminar deudas del estudiante
        $deudas = Deuda::where('idEstudiante', '=', $id)->get();
        foreach ($deudas as $deuda) {
            $deuda->estado = 0;
            $deuda->save();
        }

        //eliminar pagos del estudiante
        $pagos = pago::where('idEstudiante', '=', $id)->get();
        foreach ($pagos as $pago) {
            $pago->estado = 0;
            $pago->save();
        }
        //eliminar detalle estudiante gs
        // $detalleEstudianteGS = Detalle_estudiante_GS::where('idEstudiante', '=', $id)->first();
        // foreach ($detalleEstudianteGS as $detalle) {
        //     $detalle->estado = 0;
        //     $detalle->save();
        // }
        //eliminar condonaciones del estudiante
        // $condonaciones = detalle_condonacion::where('idEstudiante', '=', $id)->get();
        // foreach ($condonaciones as $condonacion) {
        //     $condonacion->estado = 0;
        //     $condonacion->save();
        // }

        //eliminar devoluciones del estudiante
        // $devoluciones = detalle_devolucion::where('idEstudiante', '=', $id)->get();
        // foreach ($devoluciones as $devolucion) {
        //     $devolucion->estado = 0;
        //     $devolucion->save();
        // }

        $estudiante->estado = '0';
        $estudiante->save();
        return redirect()->route('estudiante.index')->with('datos', 'Registro Eliminado...!');
    }
    public function desEscala($idEstudiante, $periodo)
    {
        // Realiza la consulta a la base de datos
        // $descripcion =  escala_estudiante::where('idEstudiante', $idEstudiante)->where('periodo', $periodo)->with('escala:idEscala,descripcion')->get();
        $descripcion = DB::table('escala_estudiante as EE')->join('escala as E', 'E.idEscala', '=', 'EE.idEscala')->select('E.descripcion')->where('EE.idEstudiante', $idEstudiante)
            ->where('EE.periodo', $periodo)->first();
        return $descripcion;
    }
}
