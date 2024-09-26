<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\conceptoEscala;
use App\Models\escala;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class conceptoEscalaController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('log', only: ['index']),
            new Middleware('subscribed', except: ['store']),
        ];
    }
    const PAGINATION = 10;
    public function index(Request $request)
    {
        $buscarxMora=$request->get('busquedaxMora');
        $buscarxNmes1=$request->get('busquedaxNmes1');
        $buscarxNmes2=$request->get('busquedaxNmes2');
        $buscarxEscala=$request->get('busquedaxEscala');
        $buscarpor = $request->get('busqueda');

        $buscarxNmes1 = is_numeric($buscarxNmes1) ? (int)$buscarxNmes1 : 1;
        $buscarxNmes2 = is_numeric($buscarxNmes2) ? (int)$buscarxNmes2 : 12;
        $conceptoEscala = conceptoEscala::where('estado', '=', '1')
            ->where('descripcion', 'like', '%' . $buscarpor . '%')
            ->where('conMora', 'like', '%' . $buscarxMora . '%')
            ->where('idEscala', 'like', '%' . $buscarxEscala . '%')
            ->whereBetween('nmes', [$buscarxNmes1, $buscarxNmes2])
           // ->orderBy('idEscala', 'asc')
            ->paginate($this::PAGINATION)
            ->appends(['busqueda' => $buscarpor, 'busquedaxMora' => $buscarxMora, 'busquedaxEscala' => $buscarxEscala, 'busquedaxNmes1' => $buscarxNmes1, 'busquedaxNmes2' => $buscarxNmes2]);
        return view('pages.conceptoEscala.index', compact('conceptoEscala', 'buscarpor', 'buscarxMora', 'buscarxEscala', 'buscarxNmes1', 'buscarxNmes2'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $escala = escala::where('estado', '=', '1')->get();
        return view('pages.conceptoEscala.create', compact('escala'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'descripcion' => 'required|max:30',
                'conMora' => 'required',
                'idEscala' => 'required',
                'nmes' => 'required|min:1',
            ],
            [
                'descripcion.required' => 'Ingrese la descripción',
                'descripcion.max' => 'Máximo 30 caracteres',
                'conMora.required' => 'Seleccione si tiene mora',
                'idEscala.required' => 'Seleccione la escala',
                'nmes.required' => 'Ingrese el número de meses',
                'nmes.min' => 'Mínimo 1 mes',
            ]
        );
        $conceptoEscala = new conceptoEscala();
        $conceptoEscala->descripcion = $request->descripcion;
        $conceptoEscala->conMora = $request->conMora;
        $conceptoEscala->idEscala = $request->idEscala;
        $conceptoEscala->nmes = $request->nmes;
        $conceptoEscala->estado = '1';
        $conceptoEscala->save();
        return redirect()->route('conceptoEscala.index')->with('datos', 'Registro Actualizado...!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $conceptoEscala = conceptoEscala::findOrFail($id);
        $escala = escala::where('estado', '=', '1')->get();
        return view('pages.conceptoEscala.edit', compact('conceptoEscala', 'escala'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'descripcion' => 'required|max:30',
                'conMora' => 'required',
                'idEscala' => 'required',
                'nmes' => 'required|min:1',
            ],
            [
                'descripcion.required' => 'Ingrese la descripción',
                'descripcion.max' => 'Máximo 30 caracteres',
                'conMora.required' => 'Seleccione si tiene mora',
                'idEscala.required' => 'Seleccione la escala',
                'nmes.required' => 'Ingrese el número de meses',
                'nmes.min' => 'Mínimo 1 mes',
            ]
        );
        $conceptoEscala = conceptoEscala::findOrFail($id);
        $conceptoEscala->descripcion = $request->descripcion;
        $conceptoEscala->conMora = $request->conMora;
        $conceptoEscala->idEscala = $request->idEscala;
        $conceptoEscala->nmes = $request->nmes;
        $conceptoEscala->estado = '1';
        $conceptoEscala->save();
        return redirect()->route('conceptoEscala.index')->with('datos', 'Registro Actualizado...!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $conceptoEscala = conceptoEscala::findOrFail($id);
        $conceptoEscala->estado = '0';
        $conceptoEscala->save();
        return redirect()->route('conceptoEscala.index')->with('datos', 'Registro Eliminado...!');
    }

    public function confirmar($id)
    {
        $conceptoEscala = conceptoEscala::findOrFail($id);
        return view('pages.conceptoEscala.confirm', compact('conceptoEscala'));
    }
}

