<?php
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\conceptoEscalaController;
use App\Http\Controllers\CondonacionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\escalaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\escalaEstudianteController;
use App\Http\Controllers\pagoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorSolicitudController;
use App\Http\Controllers\ReportesDirectorController;

Route::get('/', function () {
    return view('welcome');
});


require __DIR__ . '/estudiante.php';
require __DIR__ . '/condonacion.php';
require __DIR__ . '/pago.php';
require __DIR__ . '/devolucion.php';
require __DIR__ . '/director.php';


Route::get('redirect',[HomeController::class.'redirect'])->name('redirect');

Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware('auth');

Route::middleware('role:admin')->group(function () {
    Route::get('/registar', [UsuarioController::class, 'showRegRoles'])->name('registarRol');
    Route::post('/registar', [UsuarioController::class, 'regRol'])->name('registrate');
});

Route::get('/register/padre', [UsuarioController::class, 'showRegPadre'])->name('register.padre');
Route::post('/register/padre', [UsuarioController::class, 'regPadre']);

Route::post('/addEstudiante/{id}', [UsuarioController::class, 'addEstudiante'])->name('addEstudiante');

Route::get('/admin', [UsuarioController::class, 'showLoginAdmin'])->name('login.admin');
Route::post('/admin', [UsuarioController::class, 'loginAdmin']);

Route::get('/login/padre', [UsuarioController::class, 'showLoginPadre'])->name('login.padre');
Route::post('/login/padre', [UsuarioController::class, 'loginPadre']);

// route::post('/login', [UsuarioController::class, 'loginByRole'])->name('login');





//DEUDA
Route::resource('/deuda', DeudaController::class);
Route::get('deuda/{id}/confirmar', [DeudaController::class, 'confirmar'])->name('deuda.confirmar');
Route::get('cancelarDeuda', function () {
    return redirect()->route('deuda.index')->with(['deuda' => 'Acción Cancelada ..!', 'color' => 'error']);
})->name('cancelarDeuda');
Route::get('/deudaPdf', [DeudaController::class, 'index'])->name('generarDeuda');


//concepto Escala
Route::get('conceptoEscala/{id}/confirmar', [conceptoEscalaController::class, 'confirmar'])->name('conceptoEscala.confirmar');
Route::resource('/conceptoEscala', conceptoEscalaController::class);
Route::get('conceptos/', [conceptoEscalaController::class, 'show'])->name('conceptoEscala');


//escala
//web escala

// Route::resource('/escala', escalaController::class);
// Route::get('escala/{id}/confirmar', [escalaController::class, 'confirmar'])->name('escala.confirmar');
// Route::post('/escala', [escalaController::class, 'store'])->name('escala.store');
Route::resource('/escala', escalaController::class);
Route::get('escala/{id}/confirmar', [escalaController::class, 'confirmar'])->name('escala.confirmar');
Route::post('/escala', [escalaController::class, 'store'])->name('escala.store');

//usuarios
Route::middleware('role:admin')->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/{id}/edit', [UsuarioController::class, 'editRol'])->name('usuarios.edit');
    Route::put('usuarios/{id}', [UsuarioController::class, 'updateRol'])->name('usuarios.update');
    Route::delete('usuarios/{id}', [UsuarioController::class, 'destroyRol'])->name('usuarios.destroy');
});




