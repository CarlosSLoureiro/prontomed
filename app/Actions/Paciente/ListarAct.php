<?php

namespace App\Actions\Paciente;

use App\Repositories\PacienteRepository;

class ListarAct {

    private $pacienteRepository;

    public function __construct(PacienteRepository $pacienteRepository) {
        $this->pacienteRepository = $pacienteRepository;
    }

    public function executar(array $filtros = []) : mixed {
        return $this->pacienteRepository->listar_pacientes($filtros)->paginate(5)->appends($filtros);
    }

}