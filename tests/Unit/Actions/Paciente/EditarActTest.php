<?php

namespace Tests\Unit\Actions\Paciente;

use Tests\TestCase;
use App\Actions\Paciente\EditarAct;
use App\Repositories\PacienteRepository;
use App\Models\Paciente;
use Mockery;
use App\Exceptions\RequestException;

class EditarActTest extends TestCase {

    private function getMockedSut() {
        $pacienteRepository = (object) Mockery::mock(PacienteRepository::class);
        $act = new EditarAct($pacienteRepository);
        return compact([ 'pacienteRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_requestexception_caso_o_paciente_nao_exista() {
        // Arrange
        $sut = $this->getMockedSut();
        $paciente_id = 2;
        $dados = [];

        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with($paciente_id)->andReturn(null);

        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Paciente não encontrado.');

        // Run
        $sut['act']->executar($paciente_id, $dados);
    }

    /** @test */
    public function deve_receber_requestexception_caso_o_novo_email_ja_exista() {
        // Arrange
        $sut = $this->getMockedSut();
        $paciente = Paciente::factory(['id' => 1])->make();
        $paciente_existante = Paciente::factory(['id' => 2])->make();
        $dados = Paciente::factory()->make(['email' => $paciente_existante->email])->getAttributes();

        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with($paciente->id)->andReturn($paciente);
        $sut['pacienteRepository']->shouldReceive('obter_paciente_por_email')->once()->with($dados['email'])->andReturn($paciente_existante);

        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Este email já está sendo utilizado por outro paciente.');

        // Run
        $sut['act']->executar($paciente->id, $dados);
    }

    /** @test */
    public function deve_editar_o_paciente() {
        // Arrange
        $sut = $this->getMockedSut();
        $paciente = Paciente::factory(['id' => 1])->make();
        $dados = Paciente::factory()->make()->getAttributes();

        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with($paciente->id)->andReturn($paciente);
        $sut['pacienteRepository']->shouldReceive('obter_paciente_por_email')->once()->with($dados['email'])->andReturn(null);

        // Assert
        $sut['pacienteRepository']->shouldReceive('editar_paciente')->once()->with($paciente, $dados)->andReturn($paciente);

        // Run
        $sut['act']->executar($paciente->id, $dados);
    }

}