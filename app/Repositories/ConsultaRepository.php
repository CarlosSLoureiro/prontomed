<?php

namespace App\Repositories;

use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Observacoes;
use Carbon\Carbon;

class ConsultaRepository {

    public const TEMPO_MEDIO_DA_CONSULTA = 15; //Em minutos

    public function obter_consulta(int $id) : Consulta|null {
        return Consulta::where('id', $id)->first();
    }

    public function obter_possivel_consulta_confilto_de_datas(Medico $medico, Carbon $data) : Consulta|null {
        return Consulta::where('medico_id', $medico->id)
            ->where('data', '>', $data->addMinutes(-self::TEMPO_MEDIO_DA_CONSULTA))
            ->where('data', '<', $data->addMinutes(self::TEMPO_MEDIO_DA_CONSULTA))
            ->first();
    }

    public function cadastrar_consulta(Medico $medico, Paciente $paciente, Carbon $data) : Consulta|null {
        return Consulta::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'data' => $data
        ]);
    } 

    public function observar_consulta(Consulta $consulta, string $obs) : Observacoes|null {
        return Observacoes::create([
            'consulta_id' => $consulta->id,
            'mensagem' => $obs
        ]);
    }

    public function excluir_consulta(Consulta $consulta) : void {
        $consulta->delete();
    }

}
