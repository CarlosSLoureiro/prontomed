<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'medico_id', 'paciente_id', 'data'
    ];
    
    public function medico() {
        return $this->belongsTo(Medico::class);
    }

    public function paciente() {
        return $this->belongsTo(Paciente::class);
    }

    public function observacoes() {
        return $this->hasMany(Observacoes::class);
    }
}
