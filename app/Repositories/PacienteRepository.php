<?php

namespace App\Repositories;

use App\Models\Paciente;

class PacienteRepository {

    public function obter_paciente($id) : Paciente|null {
        return Paciente::where('id', $id)->first();
    }

    public function obter_paciente_por_email(string $email) : Paciente|null {
        return Paciente::where('email', $email)->first();
    }

    public function cadastrar_paciente(array $dados) : Paciente|null {
        return Paciente::create($dados);
    }

    public function editar_paciente(Paciente $paciente, array $dados) : Paciente|null {
        $paciente->fill($dados);
        $paciente->save();
        return $paciente;
    }

    public function excluir_paciente(Paciente $paciente) : void {
        $paciente->delete();
    }

}