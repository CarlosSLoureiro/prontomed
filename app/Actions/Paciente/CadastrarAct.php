<?php

namespace App\Actions\Paciente;

use App\Repositories\PacienteRepository;
use App\Models\Paciente;

class CadastrarAct {

    private $pacienteRepository;

    public function __construct(PacienteRepository $pacienteRepository) {
        $this->pacienteRepository = $pacienteRepository;
    }

    public function executar(array $dados) : Paciente {
        return $this->pacienteRepository->cadastrar_paciente($dados);
    }

}