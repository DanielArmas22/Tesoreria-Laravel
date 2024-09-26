<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\User;

class Estudiante extends Model
{
    use HasFactory;
    protected $table = 'estudiante';
    protected $primaryKey = 'idEstudiante';
    public $timestamps = false;
    protected $fillable = ['nombre', 'apellidoP', 'apellidoM', 'estado'];
    public function escalaEstudiante()
    {
        return $this->hasMany(escalaEstudiante::class, 'idEstudiante', 'idEstudiante');
    }
    public function deuda()
    {
        return $this->hasMany(deuda::class, 'idDeuda', 'idDeuda');
    }
    public function detalle_estudiante_gs()
    {
        return $this->hasOne(detalle_estudiante_GS::class, 'idEstudiante', 'idEstudiante');
    }
    public function user()
    {
        return $this->hasOne(User::class,'idEstudiante','idEstudiante');
    }
}
