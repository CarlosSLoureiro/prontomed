<?php

namespace App\Actions\Consulta;

use App\Repositories\ConsultaRepository;
use App\Models\Medico;
use Exception;

class ExcluirAct {

    private $consultaRepository;

    public function __construct(ConsultaRepository $consultaRepository) {
        $this->consultaRepository = $consultaRepository;
    }

    public function executar(Medico $usuario, int $consulta_id) : void {
        // Verifica se a consulta existe
        $consulta = $this->consultaRepository->obter_consulta($consulta_id);

        if ($consulta == null) throw new Exception('Consulta não encontrada.');

        // Apenas o médico responsável pela consulta pode excluí-la.
        if ($consulta->medico_id != $usuario->id) throw new Exception('Apenas o médico responsável por essa consulta pode excluí-la.');

        $this->consultaRepository->excluir_consulta($consulta);
    }

}