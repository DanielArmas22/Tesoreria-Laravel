<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class devolucion extends Model
{
    use HasFactory;
    protected $table = 'devolucion';
    protected $primaryKey = 'idDevolucion';
    public $timestamps = false;
    protected $fillable = ['fechaDevolucion','estado'];

    public function detalleDevolucion()
    {
        return $this->hasMany(detalle_devolucion::class, 'idDevolucion', 'idDevolucion');
    }
}
