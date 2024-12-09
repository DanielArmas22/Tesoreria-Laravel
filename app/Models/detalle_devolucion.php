<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_devolucion extends Model
{
    use HasFactory;
    protected $table = 'detalle_devolucion';
    protected $primaryKey = 'nroOperacion';
    public $timestamps = false;
    protected $fillable = ['observacion', 'estadoDevolucion'];

    public function devolucion()
    {
        return $this->hasOne(devolucion::class, 'idDevolucion', 'idDevolucion');
    }

    public function Pago()
    {
        return $this->hasOne(pago::class, 'nroOperacion', 'nroOperacion');
    }
}
