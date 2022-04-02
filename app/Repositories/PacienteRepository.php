<?php

namespace App\Repositories;

use App\Models\Paciente;

class PacienteRepository {

    public function obter_paciente($id) : Paciente|null {
        return Paciente::where('id', $id)->first();
    }

    public function obter_paciente_por_email(string $email) : Paciente|null {
        return Paciente::where('email', $email)->first();
    }

    public function cadastrar_paciente(array $dados) : Paciente|null {
        return Paciente::create($dados);
    }

    public function editar_paciente(Paciente $paciente, array $dados) : Paciente|null {
        $paciente->fill($dados);
        $paciente->save();
        return $paciente;
    }

    public function excluir_paciente(Paciente $paciente) : void {
        $paciente->delete();
    }

    public function listar_pacientes(array $filtros) : mixed {
        // Cria a lista
        $lista = new Paciente;

        if (array_key_exists('por', $filtros) && in_array($filtros['por'], array('nome','email','telefone','idade','tipo_sanguineo'))
            && array_key_exists('valor', $filtros) && !empty($filtros['valor'])) {

            // Busca a lista de acordo com os filtros
            switch ($filtros['por']) {
                case 'nome':
                case 'email':
                    $lista = $lista->where($filtros['por'], 'LIKE', '%' . $filtros['valor'] . '%');
                    break;
                case 'telefone':
                    $lista = $lista->where($filtros['por'], 'LIKE', '%' . preg_replace( '/[^0-9]/', '', $filtros['valor'] ) . '%');
                    break;
                case 'idade':
                    $lista = $lista->where('nascimento', '<=', date('Y-m-d', strtotime('-' . $filtros['valor'] . ' years')))
                                    ->where('nascimento', '>', date('Y-m-d', strtotime('-' . $filtros['valor'] + 1 . ' years')));
                    break;
                default:
                    $lista = $lista->where($filtros['por'], '=', $filtros['valor']);
                    break;
            }

        }

        return $lista->orderBy('created_at', 'DESC')->withCount(['consultas']);
    }

}