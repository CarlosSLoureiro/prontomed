<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Validators\Paciente\CadastrarValidator;
use App\Validators\Paciente\EditarValidator;
use App\Actions\Paciente\CadastrarAct;
use App\Actions\Paciente\EditarAct;
use App\Actions\Paciente\ExcluirAct;
use App\Actions\Paciente\ListarAct;
use App\Exceptions\ValidatorException;
use Exception;

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
        try {
            $dados = request()->all();

            CadastrarValidator::validar($dados);

            return response()->json(
                $this->cadastrar->executar($dados),
                Response::HTTP_OK
            );
        }
        catch (ValidatorException $e) {
            return response()->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editar($id) {
        try {
            $dados = request()->all();

            EditarValidator::validar($dados);

            return response()->json(
                $this->editar->executar($id, $dados),
                Response::HTTP_OK
            );
        }
        catch (ValidatorException $e) {
            return response()->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function excluir($id) {
        try {
            return response()->json($this->excluir->executar($id), Response::HTTP_OK);
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function listar() {
        try {
            return $this->listar->executar(request()->query());
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_EXPECTATION_FAILED);
        }
    }
}
