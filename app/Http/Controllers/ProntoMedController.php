<?php

namespace App\Http\Controllers;

use App\Models\Paciente;

class ProntoMedController extends SessionController
{
    public function index() {
        // Verifica se o médico ainda possui acesso ao sistema.
        if (parent::medico()->status == 'ativo') {

            return view('paginas.principal', [
                'tipos_sanguineo' => Paciente::opcoes['tipo_sanguineo'],
                'sexos' => Paciente::opcoes['sexo']
            ]);

        } else {
            return parent::logout();
        }
    }
}
