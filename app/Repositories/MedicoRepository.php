<?php

namespace App\Repositories;

use App\Models\Medico;

class MedicoRepository {

    public function obter_medico(int $id) : Medico|null {
        return Medico::where('id', $id)->first();
    }

    public function obter_medico_por_email(string $email) : Medico|null {
        return Medico::where('email', $email)->first();
    }

    public function editar_medico(Medico $medico, array $dados) : Medico|null {
        $medico->fill($dados);
        $medico->save();
        return $medico;
    }

}