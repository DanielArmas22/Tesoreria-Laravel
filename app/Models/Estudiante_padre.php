<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante_padre extends Model
{
    use HasFactory;
    protected $table = 'usuario_padre';
    protected $primaryKey = 'idEstudiante';
    protected $fillable = ['idEstudiante','idUsuario'];
    public $incrementing = false;
    public $timestamps = false;

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'idEstudiante', 'idEstudiante');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id','idUsuario');
    }
}
