<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CondonacionController;

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