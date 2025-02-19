<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pagoController;

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