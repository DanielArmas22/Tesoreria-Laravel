<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class escala extends Model
{
    use HasFactory;
    protected $table = 'escala';
    protected $primaryKey = 'idEscala';
    public $timestamps = false;
    protected $fillable = ['monto', 'descripcion', 'estado'];

    public function conceptoEscala()
    {
        return $this->hasMany(conceptoEscala::class, 'idEscala', 'idEscala');
    }

    public function escalaEstudiante()
    {
        return $this->hasMany(escalaEstudiante::class, 'idEscala', 'idEscala');
    }
}
