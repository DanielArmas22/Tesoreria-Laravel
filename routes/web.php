<?php
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\conceptoEscalaController;
use App\Http\Controllers\CondonacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\escalaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\escalaEstudianteController;
use App\Http\Controllers\pagoController;
use App\Http\Controllers\DirectorSolicitudController;
use App\Http\Controllers\ReportesDirectorController;

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



Route::get('conceptos/', [conceptoEscalaController::class, 'show'])->name('conceptoEscala');
Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware('auth');
Route::post('/identificacion', [UsuarioController::class, 'verificalogin'])->name('identificacion');

Route::get('/register/padre', [UsuarioController::class, 'showRegPadre'])->name('register.padre');
Route::post('/register/padre', [UsuarioController::class, 'regPadre']);

Route::get('/login/padre', [UsuarioController::class, 'showLoginPadre'])->name('login.padre');
Route::post('/login/padre', [UsuarioController::class, 'loginPadre']);

//Tesorero
Route::get('/register/tesorero', [UsuarioController::class, 'showRegtesorero'])->name('register.tesorero');
Route::post('/register/tesorero', [UsuarioController::class, 'regtesorero']);

Route::get('/login/tesorero', [UsuarioController::class, 'showLogintesorero'])->name('login.tesorero');
Route::post('/login/tesorero', [UsuarioController::class, 'logintesorero']);

//estudiante
Route::get('estudiante/{id}/confirmar', [EstudianteController::class, 'confirmar'])->name('estudiante.confirmar');
Route::resource('/estudiante', EstudianteController::class);
Route::get('/cancelarEstudiante', function () {
    return redirect()->route('estudiante.index')->with(['datos' => 'Acción Cancelada ..!', 'color' => 'error']);
})->name('cancelarEstudiante');

Route::get('/descripcionEscala/{idEstudiante}/{periodo}', [EstudianteController::class, 'desEscala']);


//condonacion
route::resource('/condonacion', CondonacionController::class);
Route::get('/condonacion/{id}/edit/{generarPDF}', [CondonacionController::class, 'edit'])->name('generarCondonacion');

// Route::get('/editarCondonacion', [CondonacionController::class, 'editPadre'])->name('generarCondonacion');


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

// Route::resource('/escala', escalaController::class);
// Route::get('escala/{id}/confirmar', [escalaController::class, 'confirmar'])->name('escala.confirmar');
// Route::post('/escala', [escalaController::class, 'store'])->name('escala.store');
Route::resource('/escala', escalaController::class);
Route::get('escala/{id}/confirmar', [escalaController::class, 'confirmar'])->name('escala.confirmar');
Route::post('/escala', [escalaController::class, 'store'])->name('escala.store');

// Solicitudes
Route::resource('/solicitudes', DirectorSolicitudController::class);
Route::post('/solicitudes/{id}/observar', [DirectorSolicitudController::class, 'observar'])->name('solicitudes.observar');
Route::post('/solicitudes/{id}/aceptar', [DirectorSolicitudController::class, 'aceptar'])->name('solicitudes.aceptar');
Route::get('/reporteSolicitudes/pdf/{idCondonacion}', [DirectorSolicitudController::class, 'verSolicitud'])->name('verSolicitud.pdf');

// dashboard
Route::get('/dashboard/index',  [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/deudas', [DashboardController::class, 'deudas'])->name('dashboard.deudas');
Route::get('/dashboard/ingresos', [DashboardController::class, 'ingresos'])->name('dashboard.ingresos');

// Reportes director

// Rutas para generar cada reporte
Route::get('/director/reportes/index', [ReportesDirectorController::class, 'index'])->name('director.reportes.index');
Route::get('/director/reportes/resumen-ingresos', [ReportesDirectorController::class, 'resumenIngresos'])->name('director.reportes.resumen-ingresos');
Route::get('/director/reportes/resumen-deudas', [ReportesDirectorController::class, 'resumenDeudas'])->name('director.reportes.resumen-deudas');
Route::get('/director/reportes/ingresos-vs-deudas', [ReportesDirectorController::class, 'ingresosVsDeudas'])->name('director.reportes.ingresos-vs-deudas');
Route::get('/director/reportes/ingresos-detallados', [ReportesDirectorController::class, 'ingresosDetallados'])->name('director.reportes.ingresos-detallados');
Route::get('/director/reportes/deudores', [ReportesDirectorController::class, 'deudores'])->name('director.reportes.deudores');
Route::get('/director/reportes/inscripcion-grado-seccion', [ReportesDirectorController::class, 'inscripcionGradoSeccion'])->name('director.reportes.inscripcion-grado-seccion');

// Rutas para generar PDFs de cada reporte
Route::get('/director/reportes/resumen-ingresos/pdf', [ReportesDirectorController::class, 'resumenIngresosPDF'])->name('director.reportes.resumen-ingresos.pdf');
Route::get('/director/reportes/resumen-deudas/pdf', [ReportesDirectorController::class, 'resumenDeudasPDF'])->name('director.reportes.resumen-deudas.pdf');
Route::get('/director/reportes/ingresos-vs-deudas/pdf', [ReportesDirectorController::class, 'ingresosVsDeudasPDF'])->name('director.reportes.ingresos-vs-deudas.pdf');
Route::get('/director/reportes/ingresos-detallados/pdf', [ReportesDirectorController::class, 'ingresosDetalladosPDF'])->name('director.reportes.ingresos-detallados.pdf');
Route::get('/director/reportes/deudores/pdf', [ReportesDirectorController::class, 'deudoresPDF'])->name('director.reportes.deudores.pdf');
Route::get('/director/reportes/inscripcion-grado-seccion/pdf', [ReportesDirectorController::class, 'inscripcionGradoSeccionPDF'])->name('director.reportes.inscripcion-grado-seccion.pdf');