<?php

namespace App\Validators\Session;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\ValidatorException;

class SenhaValidator {

    public static function validar($dados) : bool {
        $validator = Validator::make($dados, [
            'senha-atual'           => 'required|string|max:255',
            'senha-nova'            => 'required|string|min:8|max:255',
            'senha-nova-confirmar'  => 'required|string|same:senha-nova'
        ]);

        // Define o nome dos campos
        $validator->setAttributeNames([
            'senha-atual'           => 'senha atual',
            'senha-nova'            => 'nova senha',
            'senha-nova-confirmar'  => 'confirmar senha'
        ]);

        // Lança uma exceção caso o validator não valide todos os dados
        if ($validator->fails()) throw new ValidatorException($validator->getMessageBag());

        return true;
    }

}