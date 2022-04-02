<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Validators\Consulta\CadastrarValidator;
use App\Validators\Consulta\EditarValidator;
use App\Validators\Consulta\ObservarValidator;
use App\Actions\Consulta\CadastrarAct;
use App\Actions\Consulta\EditarAct;
use App\Actions\Consulta\ExcluirAct;
use App\Actions\Consulta\ListarAct;
use App\Actions\Consulta\ObservarAct;
use App\Exceptions\ValidatorException;
use Exception;

class ConsultaController extends Controller
{

    private $cadastrar, $editar, $excluir, $observar, $listar;

    public function __construct(CadastrarAct $cadastrar, EditarAct $editar, ExcluirAct $excluir, ObservarAct $observar, ListarAct $listar) {
        $this->cadastrar = $cadastrar;
        $this->editar = $editar;
        $this->excluir = $excluir;
        $this->observar = $observar;
        $this->listar = $listar;
    }

    public function agendar() {
        $dados = request()->all();

        CadastrarValidator::validar($dados);

        return response()->json($this->cadastrar->executar(request()->user(), $dados), Response::HTTP_OK);
    }

    public function editar($id) {
        $dados = request()->all();

        EditarValidator::validar($dados);
        
        return response()->json($this->editar->executar(request()->user(), $id, $dados), Response::HTTP_OK);
    }


    public function excluir($id) {
        return response()->json($this->excluir->executar(request()->user(), $id), Response::HTTP_OK);
    }

    public function cadastrar_observacao($id) {
        $dados = request()->all();

        ObservarValidator::validar($dados);

        return response()->json($this->observar->executar(request()->user(), $id, $dados), Response::HTTP_OK);
    }

    public function listar($tipo) {
        return $this->listar->executar(request()->user(), $tipo, request()->query());
    }
}
