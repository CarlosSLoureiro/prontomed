<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use App\Validators\Session\LoginValidator;
use App\Validators\Session\SenhaValidator;
use App\Actions\Session\LoginAct;
use App\Actions\Session\AlterarSenhaAct;
use App\Exceptions\ValidatorException;
use Exception;

class SessionController extends Controller
{
    private $login, $alterar_senha;

    public function __construct(LoginAct $login, AlterarSenhaAct $alterar_senha) {
        $this->login = $login;
        $this->alterar_senha = $alterar_senha;
    }

    public function login() {
        return view('paginas.login');
    }

    public function logar() {
        try {
            $dados = request()->all();

            LoginValidator::validar($dados);

            $usuario = $this->login->executar($dados);

            Auth::login($usuario, true);
            
            return response()->json(
                ['nome' => $usuario->nome, 'url' => route("principal")],
                Response::HTTP_OK
            );
        }
        catch (ValidatorException $e) {
            return response()->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        catch (Exception $e) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }

    public function alterar_senha() {
        try {
            $dados = request()->all();

            SenhaValidator::validar($dados);

            return response()->json(
                $this->alterar_senha->executar(request()->user(), $dados),
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
}
