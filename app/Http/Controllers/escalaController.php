<?php

namespace App\Http\Controllers;

use App\Models\escala;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class escalaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('log', only: ['index']),
            new Middleware('subscribed', except: ['store']),
        ];
    }
    //ConceptoEscala,nueva escala
    const PAGINATION = 5;
    public function index(Request $request)
    {
        $monto1 = $request->monto1;
        $monto2 = $request->monto2;
        $busqueda = $request->get('busqueda');
        $datos = escala::where('estado', "=", "1")
            ->where('idEscala', 'like', '%' . $busqueda . "%")
            ->whereBetween('monto',[$monto1?: 0,$monto2?: 10000])   
            ->paginate($this::PAGINATION);
        return view('pages.escala.index', compact('datos', 'busqueda'));
    }

    public function create()
    {
        return view('pages.escala.create');
    }

    public function store(Request $request)
    {
        // Validación de datos del formulario
        $request->validate([
            'monto' => 'required|numeric',
            'descripcion' => 'required|max:1',
        ]);

        // Crear una nueva instancia de Escala con los datos del formulario
        $escala = new Escala();
        $escala->monto = $request->monto;
        $escala->descripcion = $request->descripcion;
        $escala->estado = '1';
        $escala->save();

        // Redireccionar a alguna vista (por ejemplo, la lista de escalas)
        return redirect()->route('escala.index')->with('success', 'Escala creada exitosamente.');
    }

    public function edit($id)
    {
        $escala = Escala::findOrFail($id);
        return view('pages.escala.edit', compact('escala'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'monto' => 'required|numeric',
            'descripcion' => 'required|string|max:1',
        ]);

        $escala = Escala::findOrFail($id);
        $escala->monto = $request->monto;
        $escala->descripcion = $request->descripcion;
        $escala->save();

        return redirect()->route('escala.index')->with('success', 'Escala actualizada exitosamente');
    }

}
