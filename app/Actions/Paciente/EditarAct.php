<?php

namespace App\Actions\Paciente;

use App\Repositories\PacienteRepository;
use App\Models\Paciente;
use Exception;

class EditarAct {

    private $pacienteRepository;

    public function __construct(PacienteRepository $pacienteRepository) {
        $this->pacienteRepository = $pacienteRepository;
    }

    public function executar(int $paciente_id, array $dados) : Paciente {
        // Verifica se o paciente existe
        $paciente = $this->pacienteRepository->obter_paciente($paciente_id);
        if ($paciente == null) throw new Exception('Paciente não encontrado.');

        // Verifica se está alterando o email do paciente
        if ($dados['email'] != $paciente->email) {
            // Se o novo email já existe
            $paciente_com_mesmo_email = $this->pacienteRepository->obter_paciente_por_email($dados['email']);
            if ($paciente_com_mesmo_email != null) throw new Exception('Este email já está sendo utilizado por outro paciente.');
        }

        return $this->pacienteRepository->editar_paciente($paciente, $dados);
    }

}