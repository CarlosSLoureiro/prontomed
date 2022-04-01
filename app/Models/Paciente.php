<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'email', 'telefone', 'nascimento',
        'sexo', 'tipo_sanguineo', 'peso', 'altura'
    ];

    public const opcoes = [
        'sexo' => ['masculino', 'feminino', 'outro'],
        'tipo_sanguineo' => ['não informado', 'A+', 'B+', 'AB+', 'O+', 'A-', 'B-', 'AB-', 'O-']
    ];

    public function consultas() {
        return $this->hasMany(Consulta::class);
    }

    public function idade() {
        $hoje = date("Y-m-d");
        $diff = date_diff(date_create($this->nascimento), date_create($hoje));
        return $diff->format('%y');
    }

    public function imc() {
        return ($this->peso / ($this->altura ^ 2));
    }

}
