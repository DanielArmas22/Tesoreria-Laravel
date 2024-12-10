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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorSolicitudController;
use App\Http\Controllers\ReportesDirectorController;

Route::get('/', function () {
    return view('welcome');
});


// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::get('redirect',[HomeController::class.'redirect'])->name('redirect');

Route::get('conceptos/', [conceptoEscalaController::class, 'show'])->name('conceptoEscala');
Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware('auth');
Route::post('/identificacion', [UsuarioController::class, 'verificalogin'])->name('identificacion');

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

//estudiante
Route::get('estudiante/{id}/confirmar', [EstudianteController::class, 'confirmar'])->name('estudiante.confirmar');
Route::resource('/estudiante', EstudianteController::class);
Route::get('/cancelarEstudiante', function () {
    return redirect()->route('estudiante.index')->with(['datos' => 'Acción Cancelada ..!', 'color' => 'error']);
})->name('cancelarEstudiante');

Route::get('/descripcionEscala/{idEstudiante}/{periodo}', [EstudianteController::class, 'desEscala']);
Route::get('/buscarApoderado', [EstudianteController::class, 'buscarApoderado'])->name('buscarApoderado');


//condonacion
route::resource('/condonacion', CondonacionController::class);
Route::get('/condonacion/{id}/edit/{generarPDF}', [CondonacionController::class, 'edit'])->name('generarCondonacion');

// Route::get('/editarCondonacion', [CondonacionController::class, 'editPadre'])->name('generarCondonacion');


Route::get('condonacion/{id}/confirmar', [CondonacionController::class, 'confirmar'])->name('condonacion.confirmar');
Route::get('/cancelarCondonacion', function () {
    return redirect()->route('condonacion.index')->with('datos', 'Acción Cancelada ..!');
})->name('cancelarCondonacion');

Route::get('/indexCondonacionRR', [CondonacionController::class, 'indexCondonacionRR'])->name('condonacion.indexCondonacionRR');
Route::get('/condonacion/{id}/editCondonacion', [CondonacionController::class, 'editCondonacion'])->name('condonacion.editCondonacion');
Route::get('/condonacion/{id}/realizarCondonacionA', [CondonacionController::class, 'realizarCondonacionA'])->name('condonacion.realizarCondonacionA');
Route::get('/condonacion/{id}/realizarCondonacionO', [CondonacionController::class, 'realizarCondonacionO'])->name('condonacion.realizarCondonacionO');
Route::get('/condonacion/{id}/edit', [CondonacionController::class, 'edit'])->name('condonacion.edit');
Route::put('/condonacion/{idCondonacion}/actualizarSolicitud', [CondonacionController::class, 'actualizarSolicitud'])->name('condonacion.actualizarSolicitud');
Route::put('/condonacion/{idCondonacion}/actualizarCondonacionA', [CondonacionController::class, 'actualizarCondonacionA'])->name('condonacion.actualizarCondonacionA');
Route::put('/condonacion/{idCondonacion}/actualizarCondonacionO', [CondonacionController::class, 'actualizarCondonacionO'])->name('condonacion.actualizarCondonacionO');
Route::get('/condonacionPdfGeneral', [CondonacionController::class, 'index'])->name('generarCondonacionGeneral');
Route::get('/condonacionPdfGeneral1', [CondonacionController::class, 'indexCondonacionRR'])->name('generarCondonacionGeneral1');


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
Route::post('/DevolucionPdf', [DevolucionController::class, 'showpdf'])->name('generarDevolucion');

Route::get('/actualizarDevolucion/{idDevolucion}/{Operacion}',[DevolucionController::class,'actualizarDevolucion'])->name('devolucion.actualizarDevolucion');
Route::get('/devolucionesRealizadas',[DevolucionController::class,'devolucionesRealizadas'])->name('devolucion.devolucionesRealizadas');
Route::post('/devolucion/datosRealizados',[DevolucionController::class,'datosRealizados'])->name('devolucion.datosRealizados');

Route::get('/indexDevolucionR', [DevolucionController::class, 'indexDevolucionR'])->name('devolucion.indexDevolucionR');
Route::post('/devolucion/datosDevolucion',[DevolucionController::class,'datosDevolucion'])->name('devolucion.datosDevolucion');
Route::get('/devolucionPdfTeso', [DevolucionController::class, 'index'])->name('generarDevolucionTeso');
Route::get('/devolucionPdfRR', [DevolucionController::class, 'indexDevolucionR'])->name('generarDevolucionRR');
Route::put('/devolucion/{idDevolucion}/actualizarSolicitud', [DevolucionController::class, 'actualizarSolicitud'])->name('devolucion.actualizarSolicitud');
Route::put('/devolucion/{idDevolucion}/actualizarDevolucion1', [DevolucionController::class, 'actualizarDevolucion1'])->name('devolucion.actualizarDevolucion1');

//Route::get('/devolucion/{idDevolucion}/{operacion}/actualizarDevolucion', [DevolucionController::class, 'actualizarDevolucion'])->name('devolucion.actualizarDevolucion');


//pagos
Route::resource('/pago', pagoController::class);
Route::get('/pagoPdf', [pagoController::class, 'index'])->name('generarPago');
Route::get('/pago/estudiante', [PagoController::class, 'show'])->name('pago.show');
Route::get('/boleta/{nroOperacion}', [PagoController::class, 'showBoleta'])->name('pago.showBoleta');


Route::get('/FichaPago/{nroOperacion}', [PagoController::class, 'detalleFichaPago'])->name('pago.detalleFichaPago');
Route::get('/FichaPagos',[pagoController::class,'fichapagos'])->name('pago.fichapagos');
Route::get('/ActualizaFichaPago/{nroOperacion}',[pagoController::class,'actualizaFichaPago'])->name('pago.actualizaFichaPago');
Route::get('/ListaPagos',[pagoController::class,'indexCajero'])->name('pago.listaPagosCajero');


Route::get('/index1', [pagoController::class, 'index1'])->name('pago.index1');
Route::get('/pagoPdf1', [pagoController::class, 'index1'])->name('generarPago1');

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
