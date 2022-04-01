<?php

namespace App\Validators\Session;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\ValidatorException;

class LoginValidator {

    public static function validar($dados) : bool {
        $validator = Validator::make($dados, [
            'email' => 'required|string|email|max:255',
            'senha' => 'required|string|max:255',
        ]);

        // Lança uma exceção caso o validator não valide todos os dados
        if ($validator->fails()) throw new ValidatorException($validator->getMessageBag());

        return true;
    }

}