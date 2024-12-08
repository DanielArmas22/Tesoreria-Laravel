<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        // 'idEstudiante',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getRedirectRoute(): string
    {
        return 'home';
    }
    public function hasRole($rol)
    {
        return $this->rol === $rol;
    }
    public function estudiantes()
    {
        return $this->hasMany(Estudiante_padre::class, 'idUsuario', 'id');
    }
    public function countEstudiantes()
    {
        $contador = $this->estudiantes()->count();
        return $contador;
    }
    public function findEstudiante($id)
    {
        return $this->estudiantes()->where('idEstudiante', $id)->exists();
    }
    public function countTotalDeudas($tipo)
    {
        $total = 0;
        $estudiantes = $this->estudiantes()->get();
        foreach ($estudiantes as $estudiante) {
            switch ($tipo) {
                case 'totales':
                    $total += $estudiante->estudiante->countDeudas();
                    break;
                case 'proximas':
                    $total += $estudiante->estudiante->getDeudasProximas()->count();
                    break;
                case 'vencidas':
                    $total += $estudiante->estudiante->getDeudasVencidas()->count();
                    break;
                default:
                $total += $estudiante->estudiante->countDeudas();
                    break;
            }
        }
        return $total;
    }
    public function getTotalDeudas()
{
    $estudiantes = $this->estudiantes()->get();
    
    // Iniciamos una colección vacía.
    $deudasCollection = collect();
    
    // Iteramos cada estudiante y fusionamos sus deudas en una sola colección.
    foreach ($estudiantes as $estudiante) {
        $deudasCollection = $deudasCollection->merge($estudiante->estudiante->getDeudas());
    }

    return $deudasCollection;
}

}
