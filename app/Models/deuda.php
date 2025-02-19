<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estudiante;
use App\Models\conceptoEscala;

class deuda extends Model
{
    use HasFactory;
    protected $table = 'deuda';
    protected $primaryKey = 'idDeuda';
    public $timestamps = false;
    protected $fillable = ['montoMora','fechaRegistro',  'fechaLimite', 'estado', 'adelanto','idEstudiante', 'idConceptoEscala'];

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'idEstudiante', 'idEstudiante');
    }

    public function conceptoEscala()
    {
        return $this->hasOne(conceptoEscala::class, 'idConceptoEscala', 'idConceptoEscala');
    }

    public function detalleCondonaciones()
    {
        return $this->hasMany(detalle_condonacion::class, 'idDeuda', 'idDeuda');
    }

    public function detallePago()
    {
        return $this->hasMany(detalle_pago::class, 'idDeuda', 'idDeuda');
    }
    // En tu modelo Deuda.php

    public function isVencida()
    {
        // Define la lógica para determinar si la deuda está vencida
        return $this->fechaLimite < now();
    }

    public function detalleEstudianteGs()
    {
        return $this->hasOne(Detalle_estudiante_GS::class, 'idEstudiante', 'idEstudiante');
    }


}
