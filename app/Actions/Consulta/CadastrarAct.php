<?php

namespace App\Actions\Consulta;

use App\Utils\DateUtils;
use App\Models\Medico;
use App\Models\Consulta;
use App\Repositories\ConsultaRepository;
use App\Repositories\PacienteRepository;
use App\Exceptions\RequestException;

class CadastrarAct {

    private $consultaRepository, $pacienteRepository;

    public function __construct(ConsultaRepository $consultaRepository, PacienteRepository $pacienteRepository) {
        $this->consultaRepository = $consultaRepository;
        $this->pacienteRepository = $pacienteRepository;
    }

    public function executar(Medico $medico, array $dados) : Consulta {
        // Verifica se paciente existe
        $paciente = $this->pacienteRepository->obter_paciente($dados['paciente_id']);

        if ($paciente == null) throw new RequestException('Paciente não encontrado.');

        // Verifica se há consulta agendada 15 minutos antes da data escolhida
        $data = DateUtils::getAsDate($dados['data']);

        $consulta_em_conflito = $this->consultaRepository->obter_possivel_consulta_confilto_de_datas($medico, $data);

        if ($consulta_em_conflito != null) throw new RequestException('Você já possui uma consulta agendada para ' . DateUtils::getAsDate($consulta_em_conflito->data)->isoFormat(DateUtils::pt_BR) . '. Tente agendar para ' . ConsultaRepository::TEMPO_MEDIO_DA_CONSULTA . ' minutos antes ou após.');
        
        // Cria a consulta
        return $this->consultaRepository->cadastrar_consulta($medico, $paciente, $data);
    }

}