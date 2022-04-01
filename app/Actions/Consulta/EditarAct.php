<?php

namespace App\Actions\Consulta;

use App\Utils\DateUtils;
use App\Models\Consulta;
use App\Models\Medico;
use App\Repositories\ConsultaRepository;
use Exception;

class EditarAct {

    private $consultaRepository;

    public function __construct(ConsultaRepository $consultaRepository) {
        $this->consultaRepository = $consultaRepository;
    }

    public function executar(Medico $medico, int $consulta_id, array $dados) : Consulta {
        // Verifica se a consulta existe
        $consulta = $this->consultaRepository->obter_consulta($consulta_id);

        if ($consulta == null) throw new Exception('Consulta não encontrada.');

        // Apenas o médico responsável pela consulta pode reagendar
        if ($consulta->medico_id != $medico->id) throw new Exception('Apenas o médico responsável por essa consulta pode reagendá-la.');

        if (array_key_exists('data', $dados) && !empty($dados['data'])) {
            $consulta = $this->alterar_data($medico, $consulta, $dados);
        }

        return $consulta;
    }
    
    private function alterar_data(Medico $medico, Consulta $consulta, array $dados) {
        // Verifica se há consulta agendada 15 minutos antes da data escolhida
        $data = DateUtils::getAsDate($dados['data']);

        $consulta_em_conflito = $this->consultaRepository->obter_possivel_consulta_confilto_de_datas($medico, $data);
        
        if ($consulta_em_conflito != null) throw new Exception('Você já possui uma consulta agendada para ' . DateUtils::getAsDate($consulta_em_conflito->data)->isoFormat(DateUtils::pt_BR) . '. Tente reagendar para ' . ConsultaRepository::TEMPO_MEDIO_DA_CONSULTA . ' minutos antes ou após.');

        // Faz a troca
        $this->consultaRepository->editar_consulta($consulta, ['data' => $data]);

        // Cadastra uma observação na consulta
        $this->consultaRepository->observar_consulta($consulta,
            'Reagendei essa consulta para ' . DateUtils::getAsDate($consulta->data)->isoFormat(DateUtils::pt_BR) . '.'
        );
    }
}