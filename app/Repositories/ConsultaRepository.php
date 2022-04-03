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
            ->where('data', '>', $data->addMinutes(-self::TEMPO_MEDIO_DA_CONSULTA)->format(Carbon::DEFAULT_TO_STRING_FORMAT))
            ->where('data', '<', $data->addMinutes(self::TEMPO_MEDIO_DA_CONSULTA)->format(Carbon::DEFAULT_TO_STRING_FORMAT))
            ->orderBy('data', 'DESC')->first();
    }

    public function cadastrar_consulta(Medico $medico, Paciente $paciente, Carbon $data) : Consulta|null {
        return Consulta::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'data' => $data->format(Carbon::DEFAULT_TO_STRING_FORMAT)
        ]);
    } 

    public function editar_consulta(Consulta $consulta, array $dados) : Consulta|null {
        $consulta->fill($dados);
        $consulta->save();
        return $consulta;
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

    public function obter_consultas(Medico $medico, string $tipo, array $filtros) : mixed {
        // Cria a lista 
        $lista = $this->criar_lista_de_acordo_com_tipo($medico, $tipo);

        // Define a lista de acordo com a ordem
        if (array_key_exists('ordem', $filtros) && !empty($filtros['ordem'])) {
            $lista = $this->definir_ordem_da_lista($lista, $filtros['ordem']);
        }

        // Adiciona as relacões de paciente e observações a consulta
        $lista = $lista->with([
            'paciente' => function ($query) {
                $query->select('id', 'nome', 'email', 'telefone');
            },
            'observacoes'
        ]);

        // Verifica pelo nome do paciente
        if (array_key_exists('paciente', $filtros)) {
            $lista = $lista->whereRelation('paciente', 'nome', 'LIKE', '%' .  $filtros['paciente'] . '%');
        }

        // Verifica por data
        if (array_key_exists('data', $filtros) && !empty($filtros['data'])) {
            $lista = $this->adicionar_filtro_de_data_na_lista($lista, $filtros['data']);
        }

        return $lista;
    }

    private function criar_lista_de_acordo_com_tipo(Medico $medico, string $tipo) : mixed {
        switch ($tipo) {
            case "hoje": return $medico->consultas_do_dia();
            case "agendadas": return $medico->consultas_agendadas();
            case "passadas": return $medico->consultas_passadas();
            default: return $medico->consultas();
        }
    }

    private function definir_ordem_da_lista(mixed $lista, $ordem) : mixed {
        switch ($ordem) {
            default:
            case "consulta_decrescente": return $lista->orderBy('data', 'ASC');
            case "consulta_crescente": return $lista->orderBy('data', 'DESC');
            case "cadastro_decrescente": return $lista->orderBy('created_at', 'DESC');
            case "cadastro_crescente": return $lista->orderBy('created_at', 'ASC');
        }
    }

    private function adicionar_filtro_de_data_na_lista(mixed $lista, string $data) : mixed {
        // Verifica a data da consulta
        if ($data != 'qualquer') {
            $datas = explode("|", $data);

            $entre = date('Y-m-d H:i:s', strtotime($datas[0]));

            if (count($datas) >= 2) {
                $entre_x_e = date('Y-m-d H:i:s', strtotime($datas[1]));

                // Caso a data maior (inicio-fim) seja enviada em ordem inversa (fim-inicio)
                if ($entre > $entre_x_e) {
                    $temp = $entre_x_e;
                    $entre_x_e = $entre;
                    $entre = $temp;
                }

                $lista = $lista->where('data', '>=', $entre);
                $lista = $lista->where('data', '<=', $entre_x_e);
            } else {
                $lista = $lista->where('data', '>=', $entre);
            }
        }

        return $lista;
    }

}
