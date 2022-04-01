<?php

namespace App\Validators\Consulta;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\ValidatorException;

class CadastrarValidator {

    public static function validar($dados) : bool {
        $validator = Validator::make($dados, [
            'paciente_id'    => 'required|numeric',
            'data'           => 'required|date',
        ]);

        // Lança uma exceção caso o validator não valide todos os dados
        if ($validator->fails()) throw new ValidatorException($validator->getMessageBag());

        return true;
    }

}