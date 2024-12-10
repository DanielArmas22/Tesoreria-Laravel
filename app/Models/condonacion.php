<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\deuda;
use App\Models\detalle_condonacion;

class condonacion extends Model
{
    use HasFactory;
    protected $table = 'condonacion';
    protected $primaryKey = 'idCondonacion';
    public $timestamps = false;
    protected $fillable = ['fecha', 'estado'];

    public function detalleCondonaciones()
    {
        return $this->hasMany(detalle_condonacion::class, 'idCondonacion', 'idCondonacion');
    }

    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'idDeuda', 'idDeuda');
    }
    

}
