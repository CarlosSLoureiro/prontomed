<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Medico extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome', 'email', 'senha', 'status'
    ];

    protected $hidden = [
        'senha', 'remember_token'
    ];

    public const opcoes = [
        'status' => [
            'ativo',
            'inativo'
        ]
    ];

    public function consultas() {
        return $this->hasMany(Consulta::class);
    }

    public function consultas_do_dia() {
        $hoje = date('Y-m-d');
        $amanha = date('Y-m-d', strtotime($hoje . ' +1 day'));
        return $this->consultas()->whereDate('data', '>=', $hoje)->whereDate('data', '<', $amanha);
    }

    public function consultas_passadas() {
        $hoje = date('Y-m-d');
        return $this->consultas()->whereDate('data', '<', $hoje);
    }

    public function consultas_agendadas() {
        $hoje = date('Y-m-d');
        $amanha = date('Y-m-d', strtotime($hoje . ' +1 day'));
        return $this->consultas()->whereDate('data', '>=', $amanha);
    }

    public function setSenhaAttribute($value) {
        $this->attributes['senha'] = Hash::make($value);
    }

    public function getAuthPassword() {
        return $this->attributes['senha'];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}