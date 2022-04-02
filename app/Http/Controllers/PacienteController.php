<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Validators\Paciente\CadastrarValidator;
use App\Validators\Paciente\EditarValidator;
use App\Actions\Paciente\CadastrarAct;
use App\Actions\Paciente\EditarAct;
use App\Actions\Paciente\ExcluirAct;
use App\Actions\Paciente\ListarAct;

class PacienteController extends Controller
{

    private $cadastrar, $editar, $excluir, $listar;

    public function __construct(CadastrarAct $cadastrar, EditarAct $editar, ExcluirAct $excluir, ListarAct $listar) {
        $this->cadastrar = $cadastrar;
        $this->editar = $editar;
        $this->excluir = $excluir;
        $this->listar = $listar;
    }
    
    public function cadastrar() {
        $dados = request()->all();

        CadastrarValidator::validar($dados);

        $resultado = $this->cadastrar->executar($dados);

        return response()->json($resultado, Response::HTTP_OK);
    }

    public function editar($id) {
        $dados = request()->all();

        EditarValidator::validar($dados);

        $resultado = $this->editar->executar($id, $dados);

        return response()->json($resultado, Response::HTTP_OK);
    }

    public function excluir($id) {
        $resultado = $this->excluir->executar($id);

        return response()->json($resultado, Response::HTTP_OK);
    }

    public function listar() {
        $resultado = $this->listar->executar(request()->query());

        return $resultado;
    }
}
