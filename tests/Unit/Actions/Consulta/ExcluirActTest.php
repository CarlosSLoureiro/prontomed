<?php

namespace Tests\Unit\Actions\Consulta;

use Tests\TestCase;
use App\Actions\Consulta\ExcluirAct;
use App\Repositories\ConsultaRepository;
use App\Models\Medico;
use App\Models\Consulta;
use Mockery;
use Exception;

class ExcluirActTest extends TestCase {

    private function getMockedSut() {
        $consultaRepository = (object) Mockery::mock(ConsultaRepository::class);
        $act = new ExcluirAct($consultaRepository);
        return compact([ 'consultaRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_exception_caso_o_medico_esteja_excluindo_uma_consulta_que_nao_existe() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $consulta_id = 1;

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta_id)->andReturn(null);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Consulta não encontrada.');

        // Run
        $sut['act']->executar($medico, $consulta_id);
    }

    /** @test */
    public function deve_receber_exception_caso_o_medico_esteja_excluindo_uma_consulta_que_nao_eh_responsavel() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $consulta = Consulta::factory(['id' => 1, 'medico_id' => 2])->make();

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta->id)->andReturn($consulta);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas o médico responsável por essa consulta pode excluí-la.');

        // Run
        $sut['act']->executar($medico, $consulta->id);
    }

    /** @test */
    public function deve_excluir_a_consulta() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $consulta = Consulta::factory(['id' => 1, 'medico_id' => $medico->id])->make();

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta->id)->andReturn($consulta);

        // Assert
        $sut['consultaRepository']->shouldReceive('excluir_consulta')->once()->with($consulta);

        // Run
        $sut['act']->executar($medico, $consulta->id);
    }

}