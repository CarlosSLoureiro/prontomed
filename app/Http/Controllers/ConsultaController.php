<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Validators\Consulta\CadastrarValidator;
use App\Validators\Consulta\EditarValidator;
use App\Validators\Consulta\ObservarValidator;
use App\Actions\Consulta\CadastrarAct;
use App\Actions\Consulta\EditarAct;
use App\Actions\Consulta\ExcluirAct;
use App\Actions\Consulta\ObservarAct;
use App\Exceptions\ValidatorException;
use Exception;

class ConsultaController extends Controller
{

    private $cadastrar, $editar, $excluir, $observar;

    public function __construct(CadastrarAct $cadastrar, EditarAct $editar, ExcluirAct $excluir, ObservarAct $observar) {
        $this->cadastrar = $cadastrar;
        $this->editar = $editar;
        $this->excluir = $excluir;
        $this->observar = $observar;
    }

    public function agendar() {
        try {
            $dados = request()->all();

            CadastrarValidator::validar($dados);

            return response()->json($this->cadastrar->executar(request()->user(), $dados), Response::HTTP_OK);
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
            
            return response()->json($this->editar->executar(request()->user(), $id, $dados), Response::HTTP_OK);
        }
        catch (ValidatorException $e) {
            return response()->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    public function deletar($id) {
        try {
            return response()->json($this->excluir->executar(request()->user(), $id), Response::HTTP_OK);
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function cadastrar_observacao($id) {
        try {
            $dados = request()->all();

            ObservarValidator::validar($dados);

            return response()->json($this->observar->executar(request()->user(), $id, $dados), Response::HTTP_OK);
        }
        catch (ValidatorException $e) {
            return response()->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
