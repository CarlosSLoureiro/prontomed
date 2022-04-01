<?php

namespace Tests\Unit\Actions\Paciente;

use Tests\TestCase;
use App\Actions\Paciente\CadastrarAct;
use App\Repositories\PacienteRepository;
use App\Models\Paciente;
use Mockery;

class CadastrarActTest extends TestCase {

    private function getMockedSut() {
        $pacienteRepository = (object) Mockery::mock(PacienteRepository::class);
        $act = new CadastrarAct($pacienteRepository);
        return compact([ 'pacienteRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_criar_o_medico() {
        // Arrange
        $sut = $this->getMockedSut();
        $paciente = Paciente::factory()->make();

        // Assert
        $sut['pacienteRepository']->shouldReceive('cadastrar_paciente')->once()->with($paciente->getAttributes())->andReturn($paciente);

        // Run
        $sut['act']->executar($paciente->getAttributes());
    }

}