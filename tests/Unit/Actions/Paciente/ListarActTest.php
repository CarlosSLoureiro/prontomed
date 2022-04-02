<?php

namespace Tests\Unit\Actions\Paciente;

use Tests\TestCase;
use App\Actions\Paciente\ListarAct;
use App\Repositories\PacienteRepository;
use Mockery;

class ListarActTest extends TestCase {

    private function getMockedSut() {
        $pacienteRepository = (object) Mockery::mock(PacienteRepository::class);
        $act = new ListarAct($pacienteRepository);
        return compact([ 'pacienteRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_lista_de_pacientes() {
        // Arrange
        $sut = $this->getMockedSut();
        $filtros = array();

        // Mock
        $sut['pacienteRepository']->shouldReceive('listar_pacientes')->once()->with($filtros)->andReturn(null);

        // Assert
        $this->expectError();
        $this->expectErrorMessage('Call to a member function paginate() on null');

        // Run
        $sut['act']->executar($filtros);
    }

}