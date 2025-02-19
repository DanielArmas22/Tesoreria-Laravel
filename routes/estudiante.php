<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\escalaEstudianteController;


//estudiante
Route::get('estudiante/{id}/confirmar', [EstudianteController::class, 'confirmar'])->name('estudiante.confirmar');
Route::resource('/estudiante', EstudianteController::class);
Route::get('/cancelarEstudiante', function () {
    return redirect()->route('estudiante.index')->with(['datos' => 'Acción Cancelada ..!', 'color' => 'error']);
})->name('cancelarEstudiante');

Route::get('/descripcionEscala/{idEstudiante}/{periodo}', [EstudianteController::class, 'desEscala']);
Route::get('/buscarApoderado', [EstudianteController::class, 'buscarApoderado'])->name('buscarApoderado');


//escalaEstudiante
Route::resource('/escalaEstudiante', escalaEstudianteController::class);
// Ruta para editar
Route::get('/escalaEstudiante/{idEstudiante}/{periodo}/edit', [escalaEstudianteController::class, 'edit'])->name('escalaEstudiante.edit');
// Ruta para actualizar
Route::put('/escalaEstudiante/{idEstudiante}/{periodo}', [escalaEstudianteController::class, 'update'])->name('escalaEstudiante.update');