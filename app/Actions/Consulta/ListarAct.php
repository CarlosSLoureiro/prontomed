<?php

namespace App\Actions\Consulta;

use App\Repositories\ConsultaRepository;
use App\Models\Medico;

class ListarAct {

    private $consultaRepository;

    public function __construct(ConsultaRepository $consultaRepository) {
        $this->consultaRepository = $consultaRepository;
    }

    public function executar(Medico $usuario, string $tipo, mixed $filtros = []) : mixed {
        return $this->consultaRepository->obter_consultas($usuario, $tipo, $filtros)->paginate(5)->appends($filtros);
    }

}