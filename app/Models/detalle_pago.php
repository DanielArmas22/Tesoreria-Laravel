<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_pago extends Model
{
    use HasFactory;
    protected $table = 'detalle_pago';
    protected $primaryKey = ['nroOperacion', 'idDeuda'];
    public $incrementing = false; // Necesario para claves compuestas
    public $timestamps = false;
    protected $fillable = ['nroOperacion', 'idDeuda', 'monto', 'estado'];

    public function operacion()
    {
        return $this->hasOne(Pago::class, 'nroOperacion', 'nroOperacion');
    }

    public function deuda()
    {
        return $this->hasOne(Deuda::class, 'idDeuda', 'idDeuda');
    }

    protected function setKeysForSaveQuery($query)
    {
        $query->where('nroOperacion', $this->getAttribute('nroOperacion'))
              ->where('idDeuda', $this->getAttribute('idDeuda'));

        return $query;
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }
}
