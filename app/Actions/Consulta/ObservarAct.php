<?php

namespace App\Actions\Consulta;

use App\Models\Medico;
use App\Models\Observacoes as Observacao;
use App\Repositories\ConsultaRepository;
use Exception;

class ObservarAct {

    private $consultaRepository;

    public function __construct(ConsultaRepository $consultaRepository) {
        $this->consultaRepository = $consultaRepository;
    }

    public function executar(Medico $usuario, int $consulta_id, array $dados) : Observacao {
        // Verifica se paciente existe
        $consulta = $this->consultaRepository->obter_consulta($consulta_id);

        if ($consulta == null) throw new Exception('Consulta não encontrada.');

        // Apenas o médico responsável pela consulta pode observá-la.
        if ($consulta->medico_id != $usuario->id) throw new Exception('Apenas o médico responsável por essa consulta pode observá-la.');

        return $this->consultaRepository->observar_consulta($consulta, $dados['observacao']);
    }

}