<?php

namespace App\Validators\Consulta;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\ValidatorException;

class EditarValidator {

    public static function validar($dados) : bool {
        $validator = Validator::make($dados,  [
            'data'           => 'nullable|date'
        ]);

        // Lança uma exceção caso o validator não valide todos os dados
        if ($validator->fails()) throw new ValidatorException($validator->getMessageBag());

        return true;
    }

}