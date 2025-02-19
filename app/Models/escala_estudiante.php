<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class escala_estudiante extends Model
{
    use HasFactory;
    protected $table = 'escala_estudiante';
    public $timestamps = false;
    protected $primaryKey = ['idEstudiante', 'periodo'];
    protected $fillable = ['periodo', 'idEstudiante', 'idEscala', 'observacion','estado','fechaEE'];
    public $incrementing = false;
    protected $keyType = 'string';
    
    public function Estudiante()
    {
        return $this->hasOne(Estudiante::class,'idEstudiante', 'idEstudiante');
    }

    public function escala()
    {
        return $this->hasOne(escala::class,'idEscala', 'idEscala');
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getAttribute($keyName));
        }

        return $query;
    }
}
