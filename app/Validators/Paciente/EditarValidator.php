<?php

namespace App\Validators\Paciente;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\ValidatorException;
use App\Models\Paciente;

class EditarValidator {

    public static function validar($dados) : bool {
        $validator = Validator::make($dados, [
            'nome'              => 'required|string|min:6|max:255',
            'email'             => 'required|string|email|max:255',
            'telefone'          => 'required|string|max:15|min:14',
            'nascimento'        => 'required|date',
            'sexo'              => 'required|string|in:' . implode(',', Paciente::opcoes['sexo']),
            'peso'              => 'required|numeric|min:1|max:400',
            'altura'            => 'required|numeric|min:0.001|max:3.000',
            'tipo_sanguineo'    => 'required|string|in:' . implode(',', Paciente::opcoes['tipo_sanguineo'])
        ]);

        // Define o nome dos campos
        $validator->setAttributeNames([
            'tipo_sanguineo' => 'tipo sanguíneo'
        ]);

        // Lança uma exceção caso o validator não valide todos os dados
        if ($validator->fails()) throw new ValidatorException($validator->getMessageBag());

        return true;
    }

}