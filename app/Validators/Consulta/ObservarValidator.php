<?php

namespace App\Validators\Consulta;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\ValidatorException;

class ObservarValidator {

    public static function validar($dados) : bool {
        $validator = Validator::make($dados,  [
            'observacao' => 'required|string|min:6'
        ]);

        // Define o nome dos campos
        $validator->setAttributeNames([
            'observacao' => 'observação'
        ]);

        // Lança uma exceção caso o validator não valide todos os dados
        if ($validator->fails()) throw new ValidatorException($validator->getMessageBag());

        return true;
    }

}