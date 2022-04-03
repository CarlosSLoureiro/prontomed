<?php

namespace App\Http\Controllers;

use App\Models\Paciente;

class ProntoMedController extends SessionController
{
    public function index() {
        return view('prontomed', [
            'tipos_sanguineo' => Paciente::opcoes['tipo_sanguineo'],
            'sexos' => Paciente::opcoes['sexo']
        ]);
    }
}
