<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\escala;
use Carbon\Carbon;
use App\Models\escala_estudiante;
use App\Models\deuda;
use App\Models\conceptoEscala;
use Illuminate\Support\Facades\DB;

class escalaEstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PAGINATION = 10;

    public function index(Request $request)
    {
        $escalaF = escala::get();
        $buscarCodigo = $request->get('buscarCodigo');
        $busquedaPeriodo = $request->get('busquedaPeriodo');
        $busquedaOpcion = $request->get('busquedaOpcion');

        // Construir la consulta
        $query = escala_estudiante::where('estado', '=', '1');

        if ($buscarCodigo != null) {
            $query->where('idEstudiante', 'like', '%' . $buscarCodigo . '%');
        }
        if ($busquedaPeriodo != null) {
            $query->where('periodo', 'like', '%' . $busquedaPeriodo . '%');
        }
        if ($busquedaOpcion != null) {
            $query->where('idEscala', 'like', '%' . $busquedaOpcion . '%');
        }
        $escalaEstudiante = $query->paginate($this::PAGINATION)
            ->appends(['buscarCodigo' => $buscarCodigo, 'busquedaPeriodo' => $busquedaPeriodo, 'busquedaOpcion' => $busquedaOpcion]);

        return view('pages.escalaEstudiante.index', compact('escalaEstudiante', 'buscarCodigo', 'busquedaPeriodo', 'escalaF', 'busquedaOpcion'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $escala = escala::get();
        $estudiante = null;
        $buscarEstudiante = $request->get('buscarEstudiante');
        if($buscarEstudiante!=null){
            $estudiante = Estudiante::find($buscarEstudiante);
            if (!isset($estudiante)) {
                return redirect()->route('escalaEstudiante.create')->with(['mensaje'=>'Estudiante no encontrado']);
            }
            return view('pages.escalaEstudiante.create', compact('escala', 'estudiante'));
        }
        return view('pages.escalaEstudiante.create', compact('escala', 'estudiante'));
    }

    public function edit($idEstudiante, $periodo)
    {
        $escalaEstudiante = escala_estudiante::where('idEstudiante',$idEstudiante )
                            ->where('periodo', $periodo)
                            ->firstOrFail();
        $escala = escala::get();
        $estudiante = Estudiante::get();
        //dd($escalaEstudiante, $escala, $estudiante);
        return view('pages.escalaEstudiante.edit', compact('escalaEstudiante', 'estudiante', 'escala'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = request()->validate(
            [
                'idEscala' => 'required',
                'idEstudiante' => 'required',
                'periodo' => 'required|min:4',
                'observacion' => 'required|max:50',
                'fechaEE' => 'required|date_format:Y-m-d',
            ],
            [
                'idEscala.required' => 'Seleccione la escala',
                'idEstudiante.required' => 'Debe buscar a un Estudiante primero',
                'periodo.required' => 'Ingrese el periodo',
                'periodo.min' => 'Mínimo 4 caracteres',
                'observacion.required' => 'Ingrese la observación',
                'observacion.max' => 'Máximo 50 caracteres',
                'fechaEE.required' => 'Ingrese la fecha límite',
                'fechaEE.date_format' => 'La fecha límite debe estar en el formato Año-Mes-Día (YYYY-MM-DD)',
            ]
        );
        $escalaEstudiante = new escala_estudiante();
        $escalaEstudiante->idEstudiante = $request->idEstudiante;
        $escalaEstudiante->idEscala = $request->idEscala;
        $escalaEstudiante->periodo = $request->periodo;

        $esduplicado = escala_estudiante::where('idEstudiante', $request->idEstudiante)
                        ->where('idEscala', $request->idEscala)
                        ->where('periodo', $request->periodo)
                        ->first();
        if ($esduplicado) {
            return redirect()->route('escalaEstudiante.create')->with(['mensaje'=>'Entrada duplicada']);
        }

        $escalaEstudiante->observacion = $request->observacion;
        $escalaEstudiante->fechaEE=$request->fechaEE;
        $escalaEstudiante->estado = 1;
        $escalaEstudiante->save();
        //PARA AUTOGENERAR LA DEUDA
        $fechaEE = Carbon::createFromFormat('Y-m-d', $request->fechaEE);
        $mesFechaEE = $fechaEE->month;
        $añoFechaEE = $fechaEE->year;

        for($mes = $mesFechaEE; $mes <= 12; $mes++){
            $fechaLimite = Carbon::createFromDate($añoFechaEE, $mes, 1)->endOfMonth();
            
            $conceptoEscala = conceptoEscala::where('idEscala', $request->idEscala)
                                        ->where('nMes', $mes)
                                        ->first();
            //verifica que se encontro un conceptoEscala
            if ($conceptoEscala) {
                $deuda = new deuda();
                $deuda->idEstudiante = $request->idEstudiante;
                $deuda->montoMora = 0;
                $deuda->fechaLimite = $fechaLimite->format('Y-m-d');
                $deuda->fechaRegistro = $request->fechaEE;
                $deuda->adelanto = 0;
                $deuda->estado = 1;
                $deuda->idConceptoEscala = $conceptoEscala->idConceptoEscala;
                $deuda->save();
            }
        }
        return redirect()->route('escalaEstudiante.index')->with('datos', 'Registro Actualizado...!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $idEstudiante, $periodo)
    {
        $data = $request->validate([
            'idEstudiante' => 'required',
            'idEscala' => 'required',
            'periodo' => 'required|min:4',
            'observacion' => 'required|max:50',
            'fechaEE' => 'required|date_format:Y-m-d',
        ], [
            'idEstudiante.required' => 'Seleccione el estudiante',
            'idEscala.required' => 'Seleccione la escala',
            'periodo.required' => 'Ingrese el periodo',
            'periodo.min' => 'Mínimo 4 caracteres',
            'observacion.required' => 'Ingrese la observación',
            'observacion.max' => 'Máximo 50 caracteres',
            'fechaEE.required' => 'Ingrese la fecha límite',
            'fechaEE.date_format' => 'La fecha límite debe estar en el formato Año-Mes-Día (YYYY-MM-DD)',
        ]);

        $escalaEstudiante = escala_estudiante::where('idEstudiante', $idEstudiante)
                            ->where('periodo', $periodo)
                            ->firstOrFail();

        $escalaEstudiante->update($data);

        return redirect()->route('escalaEstudiante.index')->with('datos', 'Registro Actualizado...!');
    }

    /**
     * Remove the specified resource from storage.
     */

}
