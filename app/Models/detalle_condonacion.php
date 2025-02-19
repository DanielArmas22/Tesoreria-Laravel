<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_condonacion extends Model
{
    use HasFactory;
    protected $table = 'detalle_condonacion';
    // protected $primaryKey = 'idDetalleCondonacion';
    protected $primaryKey = ['idCondonacion', 'idDeuda'];
    public $timestamps = false;
    protected $fillable = ['monto', 'estado', 'idCondonacion', 'idDeuda'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function condonacion()
    {
        return $this->hasOne(condonacion::class, 'idCondonacion', 'idCondonacion');
    }

    public function deuda()
    {
        return $this->hasOne(deuda::class, 'idDeuda', 'idDeuda');
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
