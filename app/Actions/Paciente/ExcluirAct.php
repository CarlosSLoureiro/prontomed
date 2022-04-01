<?php

namespace App\Actions\Paciente;

use App\Repositories\PacienteRepository;
use Exception;

class ExcluirAct {

    private $pacienteRepository;

    public function __construct(PacienteRepository $pacienteRepository) {
        $this->pacienteRepository = $pacienteRepository;
    }

    public function executar(int $paciente_id) : void {
        $paciente = $this->pacienteRepository->obter_paciente($paciente_id);

        if ($paciente == null) throw new Exception('Paciente não encontrado.');

        $this->pacienteRepository->excluir_paciente($paciente);
    }

}