<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Estudiante_padre;
use App\Models\User;
use App\Models\Estudiante;

class UsuarioController extends Controller
{
    const ROLE_PADRE = 'padre';
    const ROLE_ADMIN = 'admin';
    const ROLE_CAJERO = 'cajero';
    const ROLE_DIRECTOR = 'director';
    const ROLE_SECRETARIO = 'secretario';

    const PAGINATION = 5;
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

    
    public function showRegRoles()
    {
        //inicializar el array de estudiantes
        // session()->put('estudiantes',[]);
        return view('auth.register', ['role' => $this::ROLE_ADMIN]);
    }

    public function showRegPadre()
    {
        //inicializar el array de estudiantes
        session()->put('estudiantes',[]);
        return view('auth.register', ['role' => $this::ROLE_PADRE]);
    }

    public function showRegCajero()
    {
        return view('auth.register', ['role' => $this::ROLE_CAJERO]);
    }
    
    public function regPadre(Request $request)
    {
        // Validación de los datos del formulario
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'idEstudiantes' => ['required', 'array', 'min:1'],
            'idEstudiantes.*' => ['integer', 'exists:estudiante,idEstudiante'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'idEstudiantes.required' => 'Debe seleccionar al menos un estudiante.',
            'idEstudiantes.array' => 'El formato de los estudiantes es incorrecto.',
            'idEstudiantes.min' => 'Debe seleccionar al menos un estudiante.',
            'idEstudiantes.*.integer' => 'Los IDs de estudiantes deben ser números enteros.',
            'idEstudiantes.*.exists' => 'Uno o más estudiantes no existen.',
        ]);

        // Creación del usuario
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'rol' => self::ROLE_PADRE,
        ]);

        // Obtener los IDs de estudiantes del request
        $idEstudiantes = $request->input('idEstudiantes', []);

        // Crear registros en Estudiante_padre para cada estudiante
        foreach ($idEstudiantes as $idEstudiante) {
            Estudiante_padre::create([
                'idEstudiante' => $idEstudiante,
                'idUsuario' => $user->id,
            ]);
        }

        // Iniciar sesión con el nuevo usuario
        Auth::login($user);

        return redirect('/home');
    }

    public function addEstudiante(Request $request, $id)
    {
        Log::info('addEstudiante llamado con ID: ' . $id);
        $estudiante = Estudiante::find($id);
        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }
        $mensaje = '';
        $estudiantes = session()->get('estudiantes', []);
    
        // Verifica si el estudiante ya está en la sesión
        try {
            $objeto = collect($estudiantes)->first(function ($item) use ($id) {
                return $item['id'] == $id;
            });
            if (!$objeto) {
                $estudiantes[] = [
                    'id' => $estudiante->idEstudiante,
                    'nombre' => $estudiante->nombre,
                    'dni' => $estudiante->DNI
                ];
                // Actualiza la sesión con el nuevo array de estudiantes
                session()->put('estudiantes', $estudiantes);
            }else{
                return response()->json([
                    'message' => 'Estudiante ya agregado',
                    'estudiantes' => $estudiantes
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }
        // Retorna la respuesta en formato JSON
        return response()->json([
            'message' => 'Estudiante agregado correctamente',
            'estudiantes' => $estudiantes
        ]);
    }
    public function regRol(Request $request)
    {
        // Validación de los datos del formulario
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'string', 'max:255', 'in:director,secretario,tesorero,cajero'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        // Creación del usuario
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'rol' => $request->input('rol'),
        ]);

        // Iniciar sesión con el nuevo usuario
        // Auth::login($user);

        return redirect()->route('usuarios.index')->with('datos', 'Usuario registrado con éxito.');
    }

// $objeto = $estudiantes->find(function($item){
    //     return $item->id == $id;
    // });
    // if (!isset($objeto)) {
    //     $estudiantes[] = [
    //         'id' => $estudiante->id,
    //         'nombre' => $estudiante->nombre,
    //         'dni' => $estudiante->DNI
    //     ];
    //     // Actualiza la sesión con el nuevo array de estudiantes
    //     session()->put('estudiantes', $estudiantes);
    // }
    // if (!collect($estudiantes)->contains('id', $id)) {
    //     $estudiantes[] = [
    //         'id' => $estudiante->id,
    //         'nombre' => $estudiante->nombre,
    //         'dni' => $estudiante->DNI
    //     ];
    //     // Actualiza la sesión con el nuevo array de estudiantes
    //     session()->put('estudiantes', $estudiantes);
    // }



    public function showLoginPadre()
    {
        return view('auth.login',['role' => $this::ROLE_PADRE]);
    }
    public function loginPadre(Request $request)
    {
        return $this->loginByRole($request, $this::ROLE_PADRE);
    }

    public function showLoginAdmin()
    {
        return view('auth.login',['role' => $this::ROLE_ADMIN]);
    }

    public function loginAdmin(Request $request)
    {
        return $this->loginByRole($request, $this::ROLE_ADMIN);
    }
    
    protected function loginByRole(Request $request, $role)
    {
        // Validar los datos de inicio de sesión
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Por favor, ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // Agregar el rol a las credenciales
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'rol' => $role, 
        ];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            return redirect()->intended("/home"); 
        }
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden o no tienes permiso para este tipo de acceso.',
        ]);
    }
    public function salir()
    {
        Auth::logout();
        return redirect('/identificacion');
    }

    public function index()
    {
        //restringir acceso a usuarios no autenticados
        if (!Auth::check()) {
            return redirect('/login');
        }
        $rol = $this::ROLE_ADMIN;
        $datos = User::paginate($this::PAGINATION);
        return view('pages.usuario.index',compact('datos','rol'));
    }
    public function editRol($id)
{
    // Buscar al usuario por su ID
    $user = User::findOrFail($id);

    return view('pages.usuario.edit', compact('user'));
}

public function updateRol(Request $request, $id)
{
    // Validación de los datos del formulario
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,{$id}"],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        'rol' => ['required', 'string', 'max:255', 'in:director,secretario,tesorero,cajero'],
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.unique' => 'El correo electrónico ya está registrado.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'rol.required' => 'El rol es obligatorio.',
        'rol.in' => 'El rol seleccionado no es válido.',
    ]);

    // Buscar al usuario por su ID
    $user = User::findOrFail($id);

    // Actualizar los datos del usuario
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    if ($request->filled('password')) {
        $user->password = Hash::make($request->input('password'));
    }
    $user->rol = $request->input('rol');
    $user->save();

    return redirect()->route('usuarios.index')->with('datos', 'Usuario actualizado con éxito.');
}
public function destroyRol($id)
{
    // Buscar al usuario por su ID
    $user = User::findOrFail($id);

    // Eliminar al usuario
    $user->delete();

    return redirect()->route('usuarios.index')->with('datos', 'Usuario eliminado con éxito.');
}

}
