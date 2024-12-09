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

    public function detalleDevolucion()
    {
        return $this->hasMany(detalle_devolucion::class, 'nroOperacion', 'nroOperacion');
    }

    public function estudiante(){
        return $this->hasOne(estudiante::class, 'idEstudiante', 'idEstudiante');
    }
}
