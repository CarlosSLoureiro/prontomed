<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Medico extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function consultas_anteriores() {
        $hoje = date('Y-m-d');
        return $this->consultas()->where('status', '=', 'pendente')->whereDate('data', '<', $hoje);
    }

    public function consultas_agendadas() {
        $hoje = date('Y-m-d');
        $amanha = date('Y-m-d', strtotime($hoje . ' +1 day'));
        return $this->consultas()->where('status', '=', 'pendente')->whereDate('data', '>=', $amanha);
    }

    public function setSenhaAttribute($value) {
        $this->attributes['senha'] = Hash::make($value);
    }

    public function getAuthPassword() {
        return $this->attributes['senha'];
    }
}