<?php

namespace Tests\Unit\Actions\Paciente;

use Tests\TestCase;
use App\Actions\Paciente\ExcluirAct;
use App\Repositories\PacienteRepository;
use App\Models\Paciente;
use Mockery;
use App\Exceptions\RequestException;

class ExcluirActTest extends TestCase {

    private function getMockedSut() {
        $pacienteRepository = (object) Mockery::mock(PacienteRepository::class);
        $act = new ExcluirAct($pacienteRepository);
        return compact([ 'pacienteRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_requestexception_caso_o_medico_esteja_excluindo_um_paciente_que_nao_existe() {
        // Arrange
        $sut = $this->getMockedSut();
        $paciente_id = 2;

        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with($paciente_id)->andReturn(null);

        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Paciente não encontrado.');

        // Run
        $sut['act']->executar($paciente_id);
    }

    /** @test */
    public function deve_excluir_o_paciente() {
        // Arrange
        $sut = $this->getMockedSut();
        $paciente = Paciente::factory(['id' => 2])->make();

        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with($paciente->id)->andReturn($paciente);

        // Assert
        $sut['pacienteRepository']->shouldReceive('excluir_paciente')->once()->with($paciente);

        // Run
        $sut['act']->executar($paciente->id);
    }

}