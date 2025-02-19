<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conceptoEscala extends Model
{
    use HasFactory;
    protected $table = 'concepto_escala';
    protected $primaryKey = 'idConceptoEscala';
    public $timestamps = false;
    //clave foranea
    protected $fillable = ['descripcion', 'conMora','nmes', 'estado'];

    public function escala()
    {
        return $this->hasOne(escala::class, 'idEscala', 'idEscala');
    }
    public function deuda(){
        return $this->hasMany(deuda::class,'idDeuda','idDeuda');
    }
}

