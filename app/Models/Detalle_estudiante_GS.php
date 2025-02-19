<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Detalle_estudiante_GS extends Model
{
    use HasFactory;
    protected $table = 'detalle_estudiante_gs';
    protected $primaryKey = ['idEstudiante', 'gradoEstudiante','seccionEstudiante'];
    public $timestamps = false;
    protected $fillable = [ 'fechaAsignacion','estado','idEstudiante','gradoEstudiante','seccionEstudiante'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function Grado()
    {
        return $this->hasOne(Grado::class, 'gradoEstudiante', 'gradoEstudiante');
    }

    public function Seccion()
    {
        return $this->hasOne(Seccion::class, 'seccionEstudiante', 'seccionEstudiante');
    }

    public function Estudiante()
    {
        return $this->hasOne(Estudiante::class, 'idEstudiante', 'idEstudiante');
    }
    
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    protected function getKeyForSaveQuery($keyName = null)
{
    if(is_null($keyName)){
        $keyName = $this->getKeyName();
    }

    if (isset($this->original[$keyName])) {
        return $this->original[$keyName];
    }

    return $this->getAttribute($keyName);
}
}
