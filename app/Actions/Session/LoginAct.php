<?php

namespace App\Actions\Session;

use Illuminate\Support\Facades\Hash;
use App\Repositories\MedicoRepository;
use App\Models\Medico;
use App\Exceptions\LoginException;

class LoginAct {

    private $medicoRepository;

    public function __construct(MedicoRepository $medicoRepository) {
        $this->medicoRepository = $medicoRepository;
    }

    public function executar(array $dados) : Medico {
        $usuario = $this->medicoRepository->obter_medico_por_email($dados["email"]);

        // Verifica se o usuário é valido para logar no sistema
        if ($usuario == NULL) throw new LoginException("Usuário '" . $dados["email"] .  "' não encontrado.");
        if (!Hash::check($dados["senha"], $usuario->senha)) throw new LoginException('Senha incorreta.');
        if ($usuario->status != 'ativo') throw new LoginException("Você não possui mais acesso ao sistema. &#128533;");

        return $usuario;
    }

}