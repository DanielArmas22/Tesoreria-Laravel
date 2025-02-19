<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pago extends Model
{
    use HasFactory;
    protected $table = 'pago';
    protected $primaryKey = 'nroOperacion';
    public $timestamps = false;
    protected $fillable = ['fechaPago', 'idEstudiante', 'periodo', 'metodoPago','estadoPago'];

    public function detallePago()
    {
        return $this->hasMany(detalle_pago::class, 'nroOperacion', 'nroOperacion');
    }

    public function devolucion()
    {
        return $this->hasOneThrough(
            devolucion::class, // Modelo final
            detalle_devolucion::class, // Modelo intermedio
            'idDevolucion', // Clave foránea en el intermedio
            'idDevolucion', // Clave foránea en el final
            'nroOperacion', // Clave primaria en el modelo
            'nroOperacion' // Clave primaria en el intermedio
        );
    }
    public function estudiante(){
        return $this->hasOne(estudiante::class, 'idEstudiante', 'idEstudiante');
    }
}
