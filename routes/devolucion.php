<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DevolucionController;

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