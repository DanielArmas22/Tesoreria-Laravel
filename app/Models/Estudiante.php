<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Models\User;

class Estudiante extends Model
{
    use HasFactory;
    protected $table = 'estudiante';
    protected $primaryKey = 'idEstudiante';
    public $timestamps = false;
    protected $fillable = ['nombre', 'apellidoP', 'apellidoM', 'estado'];
    public function escalaEstudiante()
    {
        return $this->hasMany(escalaEstudiante::class, 'idEstudiante', 'idEstudiante');
    }
    public function deuda()
    {
        return $this->hasMany(deuda::class, 'idDeuda', 'idDeuda');
    }
    public function detalle_estudiante_gs()
    {
        return $this->hasOne(detalle_estudiante_GS::class, 'idEstudiante', 'idEstudiante');
    }
    public function estudiante_padre()
    {
        return $this->hasMany(Estudiante_padre::class, 'idEstudiante', 'idEstudiante');
    }
    public function pagos()
    {
        return $this->hasMany(pago::class, 'idEstudiante', 'idEstudiante');
    }
    public function getDeudas(){
        $deudas = Deuda::select(
            'deuda.*',
            DB::raw('(SELECT SUM(DC.MONTO) FROM DETALLE_CONDONACION as DC WHERE DC.IDDEUDA = deuda.idDeuda GROUP BY DC.IDDEUDA) as totalCondonacion')
        )
        ->where('idEstudiante', '=', $this->idEstudiante)
        ->where('estado', '1')
        ->orderBy('fechaLimite')
        ->get();
        return $deudas;
    }
    public function getDeudasProximas()
    {
        $deudas = $this->getDeudas();
    
        // Obtener la fecha límite más próxima que no esté vencida
        $fechaLimiteMasProxima = $deudas->filter(function ($deuda) {
            return $deuda->fechaLimite >= now()->toDateString();
        })->first()?->fechaLimite;
    
        // Filtrar las deudas que tengan la misma fecha límite más próxima
        $deudasProximas = $deudas->filter(function ($deuda) use ($fechaLimiteMasProxima) {
            return $deuda->fechaLimite === $fechaLimiteMasProxima;
        });
    
        return $deudasProximas;
    }
    public function getDeudasVencidas()
    {
        $deudas = $this->getDeudas();

        // Filtrar las deudas cuya fecha límite es inferior a la fecha actual
        $deudasVencidas = $deudas->filter(function ($deuda) {
            return $deuda->fechaLimite < now()->toDateString();
        });

        return $deudasVencidas;
    }
    public function countDeudas()
    {
        $deudas = $this->getDeudas();
        return $deudas->count();
    }

    public function devoluciones()
    {
        return $this->hasManyThrough(
            DetalleDevolucion::class, // Modelo final
            Pago::class, // Modelo intermedio
            'idEstudiante', // Clave foránea en pagos
            'nroOperacion', // Clave foránea en detalle_devolucion
            'idEstudiante', // Clave primaria en estudiante
            'nroOperacion' // Clave primaria en pagos
        );
    }
}
