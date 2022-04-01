<?php

namespace App\Actions\Session;

use Illuminate\Support\Facades\Hash;
use App\Repositories\MedicoRepository;
use App\Models\Medico;
use Exception;

class AlterarSenhaAct {

    private $medicoRepository;

    public function __construct(MedicoRepository $medicoRepository) {
        $this->medicoRepository = $medicoRepository;
    }

    public function executar(Medico $usuario, array $dados) : Medico {
        if (!Hash::check($dados['senha-atual'], $usuario->senha)) throw new Exception('A sua senha atual está incorreta.');

        return $this->medicoRepository->salvar_medico($usuario, ['senha' => $dados['senha-nova']]);
    }

}