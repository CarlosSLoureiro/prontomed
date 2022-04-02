<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use App\Validators\Session\LoginValidator;
use App\Validators\Session\SenhaValidator;
use App\Actions\Session\LoginAct;
use App\Actions\Session\AlterarSenhaAct;

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
        $dados = request()->all();

        LoginValidator::validar($dados);

        $usuario = $this->login->executar($dados);

        Auth::login($usuario, true);
        
        return response()->json(
            ['nome' => $usuario->nome, 'url' => route("principal")],
            Response::HTTP_OK
        );
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }

    public function alterar_senha() {
        $dados = request()->all();

        SenhaValidator::validar($dados);

        $resultado = $this->alterar_senha->executar(request()->user(), $dados);

        return response()->json($resultado, Response::HTTP_OK);
    }
}
