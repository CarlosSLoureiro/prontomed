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
        $dados = request()->all();

        LoginValidator::validar($dados);

        $usuario = $this->login->executar($dados);

        $token = Auth::login($usuario, true);

        $dados = [
            'nome' => $usuario->nome,
            'url' => route("principal"),
            'token' => $token
        ];

        return response()->json($dados, Response::HTTP_OK);
    }

    public function logout() {
        Session::flush();
        
        Auth::logout();

        return response()->json(['logged out' => true], Response::HTTP_OK);
    }

    public function medico_dados() {
        $dados = [
            'medico' => Auth::user(),
            'consultas_do_dia' => Auth::user()->consultas_do_dia()->count(),
            'consultas_agendadas' => Auth::user()->consultas_agendadas()->count(),
            'consultas_passadas' => Auth::user()->consultas_passadas()->count()
        ];

        return response()->json($dados, Response::HTTP_OK);
    }

    public function alterar_senha() {
        $dados = request()->all();

        SenhaValidator::validar($dados);

        $resultado = $this->alterar_senha->executar(request()->user(), $dados);

        return response()->json($resultado, Response::HTTP_OK);
    }
}
