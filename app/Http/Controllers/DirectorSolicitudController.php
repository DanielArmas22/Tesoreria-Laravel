<?php

namespace App\Http\Controllers;

use App\Models\condonacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;

class DirectorSolicitudController extends Controller
{
    //
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }
    const PAGINATION = 5;
    public function index(Request $request)
    {
        // Obtener filtros desde la solicitud
        $filtroGrado = $request->input('grado');
        $filtroSeccion = $request->input('seccion');
        $filtroFecha = $request->input('fecha');
    
        // Consulta base: condonaciones con estadoCondonacion = 2
        $query = DB::table('condonacion')
            ->join('detalle_condonacion', 'condonacion.idCondonacion', '=', 'detalle_condonacion.idCondonacion')
            ->join('deuda', 'detalle_condonacion.idDeuda', '=', 'deuda.idDeuda')
            ->join('estudiante', 'deuda.idEstudiante', '=', 'estudiante.idEstudiante')
            ->join('detalle_estudiante_gs', 'estudiante.idEstudiante', '=', 'detalle_estudiante_gs.idEstudiante')
            ->join('grado', 'detalle_estudiante_gs.gradoEstudiante', '=', 'grado.gradoEstudiante')
            ->join('seccion', 'detalle_estudiante_gs.seccionEstudiante', '=', 'seccion.seccionEstudiante')
            ->where('condonacion.estadoCondonacion', '=', 2)
            ->select('condonacion.*');
    
        // Aplicar filtro por grado
        if (!empty($filtroGrado)) {
            $query->where('grado.descripcionGrado', $filtroGrado);
        }
    
        // Aplicar filtro por seccion
        if (!empty($filtroSeccion)) {
            $query->where('seccion.descripcionSeccion', $filtroSeccion);
        }
    
        // Aplicar filtro por fecha
        if (!empty($filtroFecha)) {
            $query->whereDate('condonacion.fecha', $filtroFecha);
        }
    
        // Ejecutar la consulta con paginación
        $datos = $query->paginate(10);
    
        // Obtener listas de grados y secciones para los selects
        $listadoGrados = DB::table('grado')->pluck('descripcionGrado');
        $listadoSecciones = DB::table('seccion')->pluck('descripcionSeccion');
    
        return view('pages.director.solicitudes.index', compact('datos', 'listadoGrados', 'listadoSecciones', 'filtroGrado', 'filtroSeccion', 'filtroFecha'));
    }

    public function observar(Request $request, $id)
    {
        // Encuentra el registro de la tabla condonacion por ID
        $condonacion = Condonacion::find($id);

        if (!$condonacion) {
            return redirect()->back()->with('error', 'Condonación no encontrada.');
        }

        // Actualiza el estado
        $condonacion->observacion = $request->input('observaciones');
        $condonacion->estadoCondonacion = $request->input('estado', 3);
        $condonacion->save();

        // Redirige de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Estado actualizado con éxito.');
    }
    public function aceptar(Request $request, $id)
    {
        // Encuentra el registro de la tabla condonacion por ID
        $condonacion = Condonacion::find($id);

        if (!$condonacion) {
            return redirect()->back()->with('error', 'Condonación no encontrada.');
        }

        // Actualiza el estado
        $condonacion->estadoCondonacion = $request->input('estado', 4);
        $condonacion->save();

        // Redirige de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Estado actualizado con éxito.');
    }

    public function verSolicitud($idCondonacion)
    {
        // Obtener la condonación con las relaciones necesarias
        $condonacion = Condonacion::with([
            'detalleCondonaciones.deuda.estudiante',
            'detalleCondonaciones.deuda.detalleEstudianteGs.grado',
            'detalleCondonaciones.deuda.detalleEstudianteGs.seccion'
        ])->find($idCondonacion);

        // Verificar si existe la condonación
        if (!$condonacion) {
            return redirect()->back()->with('error', 'Condonación no encontrada.');
        }


        // Generar el PDF
        $pdf = PDF::loadView('pages.director.solicitudes.solicitud', compact('condonacion'));

        // Abrir el PDF en una nueva ventana
        return $pdf->stream('solicitud-condonacion-' . $condonacion->idCondonacion . '.pdf');
    }
}
