<?php
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\conceptoEscalaController;
use App\Http\Controllers\CondonacionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\escalaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\escalaEstudianteController;
use App\Http\Controllers\pagoController;
Route::get('/', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('redirect',[HomeController::class.'redirect'])->name('redirect');
// Route::get('/', [UsuarioController::class, 'showLogin'])->name('login');
// Route::get('/registro', [UsuarioController::class, 'showRegistro'])->name('registro');
// route::resource('/deuda', EstudianteController::class);
// route::resource('/escala', EstudianteController::class);


// Route::get('/', [DeudaController::class, 'showDeuda']);
Route::get('conceptos/', [conceptoEscalaController::class, 'show'])->name('conceptoEscala');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::post('/identificacion', [UsuarioController::class, 'verificalogin'])->name('identificacion');

//estudiante
// route::delete('/eliminarEstudiante/{id}', [EstudianteController::class, 'destroy'])->name('eliminarEstudiante');
Route::get('estudiante/{id}/confirmar', [EstudianteController::class, 'confirmar'])->name('estudiante.confirmar');
Route::resource('/estudiante', EstudianteController::class);
Route::get('/cancelarEstudiante', function () {
    return redirect()->route('estudiante.index')->with(['datos' => 'Acción Cancelada ..!', 'color' => 'error']);
})->name('cancelarEstudiante');

Route::get('/descripcionEscala/{idEstudiante}/{periodo}', [EstudianteController::class, 'desEscala']);


//condonacion
route::resource('/condonacion', CondonacionController::class);
Route::get('/condonacion/{id}/edit/{generarPDF}', [CondonacionController::class, 'edit'])->name('generarCondonacion');
Route::get('condonacion/{id}/confirmar', [CondonacionController::class, 'confirmar'])->name('condonacion.confirmar');
Route::get('/cancelarCondonacion', function () {
    return redirect()->route('condonacion.index')->with('datos', 'Acción Cancelada ..!');
})->name('cancelarCondonacion');

//DEUDA
Route::resource('/deuda', DeudaController::class);
Route::get('deuda/{id}/confirmar', [DeudaController::class, 'confirmar'])->name('deuda.confirmar');
Route::get('cancelarDeuda', function () {
    return redirect()->route('deuda.index')->with(['deuda' => 'Acción Cancelada ..!', 'color' => 'error']);
})->name('cancelarDeuda');

//concepto Escala
Route::get('conceptoEscala/{id}/confirmar', [conceptoEscalaController::class, 'confirmar'])->name('conceptoEscala.confirmar');
Route::resource('/conceptoEscala', conceptoEscalaController::class);

//escalaEstudiante
Route::resource('/escalaEstudiante', escalaEstudianteController::class);
// Ruta para editar
Route::get('/escalaEstudiante/{idEstudiante}/{periodo}/edit', [escalaEstudianteController::class, 'edit'])->name('escalaEstudiante.edit');
// Ruta para actualizar
Route::put('/escalaEstudiante/{idEstudiante}/{periodo}', [escalaEstudianteController::class, 'update'])->name('escalaEstudiante.update');

//DEVOLUCION
Route::resource('/devolucion', DevolucionController::class);
Route::get('/devolucion/realizarDevolucion/{operacion}', [DevolucionController::class, 'realizarDevolucion'])->name('devolucion.realizarDevolucion');
Route::get('cancelarDevolucion', function () {
    return redirect()->route('devolucion.index')->with(['devolucion' => 'Acción Cancelada ..!', 'color' => 'error']);
})->name('cancelarDevolucion');
Route::post('/devolucion/datos',[DevolucionController::class,'datos'])->name('devolucion.datos');


//pagos
Route::resource('/pago', pagoController::class);
Route::get('/pagoPdf', [pagoController::class, 'index'])->name('generarPago');
Route::get('/pago/estudiante', [PagoController::class, 'show'])->name('pago.show');
Route::get('/boleta/{nroOperacion}', [PagoController::class, 'showBoleta'])->name('pago.showBoleta');

//escala
//web escala
Route::resource('/escala', escalaController::class);
Route::get('escala/{id}/confirmar', [escalaController::class, 'confirmar'])->name('escala.confirmar');

Route::post('/escala', [escalaController::class, 'store'])->name('escala.store');
