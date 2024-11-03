<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Estudiante_padre;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{
    const ROLE_PADRE = 'padre';
    const ROLE_ADMIN = 'admin';
    const ROLE_CAJERO = 'cajero';
    // const ROLE_PADRE = 'padre';
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


    public function showRegPadre()
    {
        return view('auth.register', ['role' => $this::ROLE_PADRE]);
    }

    public function showRegCajero()
    {
        return view('auth.register', ['role' => $this::ROLE_CAJERO]);
    }

    public function regPadre(Request $request)
    {
        $data=request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user =  User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'rol' => $this::ROLE_PADRE
        ]);
        //crear los estudiante_padre
        $idEstudiante = $request->get('idEstudiante');
        Estudiante_padre::create([
            'idEstudiante' => $idEstudiante,
            'idUsuario' => $user->id
        ]);
        
        Auth::login($user); //iniciar sesion
        return redirect("/home");
    }







    public function showLoginPadre()
    {
        return view('auth.login',['role' => $this::ROLE_PADRE]);
    }
    public function loginPadre(Request $request)
    {
        return $this->loginByRole($request, $this::ROLE_PADRE);
    }

    protected function loginByRole(Request $request, $role)
    {
        $credentials = request()->validate(
            [
                'name' => 'required',
                'password' => 'required'
            ],
            [
                'name.required' => 'Ingrese Usuario existente',
                'password.required' => 'Ingrese contraseña correcta',
            ]
        );
        // $credentials = $request->only('email', 'password');
        $credentials['role'] = $role;

        if (Auth::attempt($credentials)) {
            return redirect()->intended("/home");
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas para el rol seleccionado']);
    }
    public function salir()
    {
        Auth::logout();
        return redirect('/identificacion');
    }
}
