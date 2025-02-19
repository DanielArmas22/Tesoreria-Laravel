<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_grado_seccion extends Model
{
    use HasFactory;
    protected $table = 'detalle_grado_seccion';
    public $timestamps = false;
    //protected $fillable = [];

    public function Grado(){
        return $this->hasOne(Grado::class,'gradoEstudiante','gradoEstudiante');
    }

    public function Seccion(){
        return $this->hasOne(Seccion::class,'seccionEstudiante','seccionEstudiante');
    }

}
