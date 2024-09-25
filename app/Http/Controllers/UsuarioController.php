<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }
    public function showRegistro()
    {
        return view('register');
    }

    public function verificalogin(Request $request) // funcion para verificar el login(recibe un request con los datos)
    {
        //return dd($request->all());
        // validacion de campos
        $data = request()->validate(
            [
                'name' => 'required',
                'password' => 'required'
            ],
            [
                'name.required' => 'Ingrese Usuario existente',
                'password.required' => 'Ingrese contraseña correcta',
            ]
        );
        $user = User::where('name', $request->get('name'))->first();
        if (!$user) {
            return back()->withErrors(['name' => 'Usuario no válido'])->withInput(request(['name']));
        }
        if (Auth::attempt($data)) {
            $request->session()->put('name', $user->name); //guardamos el nombre del usuario en la sesion
            //guadar el correo
            $request->session()->put('email', $user->email);
            return redirect('home');
        }
        return back()->withErrors(['password' => 'Contraseña no válida'])->withInput(request(['name', 'password']));
    }

    
    public function salir()
    {
        Auth::logout();
        return redirect('/identificacion');
    }
}
