<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;
    protected $table = 'seccion';
    protected $primaryKey = 'seccionEstudiante';
    public $timestamps = false;
    protected $fillable = ['descripcionSeccion'];

    public function Detalle_grado_seccion()
    {
        return $this->hasMany(Detalle_grado_seccion::class, 'seccionEstudiante', 'seccionEstudiante');
    }
    public function Detalle_estudiante_GS()
    {
        return $this->hasMany(Detalle_estudiante_GS::class, 'seccionEstudiante', 'seccionEstudiante');
    }
}
