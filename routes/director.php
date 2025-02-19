<?php
use Illuminate\Support\Facades\Route;
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
