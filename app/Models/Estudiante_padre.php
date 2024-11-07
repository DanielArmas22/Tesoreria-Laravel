<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante_padre extends Model
{
    use HasFactory;
    protected $table = 'usuario_padre';
    protected $primaryKey = ['idUsuario','idEstudiante'];
    protected $fillable = ['idUsuario', 'idEstudiante'];
    public $incrementing = false;

    protected $keyType = 'string';
    public $timestamps = false;

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'idEstudiante', 'idEstudiante');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id','idUsuario');
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
