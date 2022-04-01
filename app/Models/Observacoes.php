<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observacoes extends Model
{
    use HasFactory;

    protected $table = 'observacoes';

    protected $fillable = [
        'consulta_id', 'medico_id', 'mensagem'
    ];

    public function medico() {
        return $this->belongsTo(Medico::class);
    }
}
