<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;
    protected $table = 'grado';
    protected $primaryKey = 'gradoEstudiante';
    public $timestamps = false;
    protected $fillable = ['descripcionGrado'];

    public function Detalle_grado_seccion(){
        return $this->hasMany(Detalle_grado_seccion::class,'gradoEstudiante','gradoEstudiante');
    }
    public function Detalle_estudiante(){
        return $this->hasMany(Detalle_estudiante_GS::class,'gradoEstudiante','gradoEstudiante');
    }

}
