<?php

namespace Tests\Unit\Actions\Consulta;

use Tests\TestCase;
use App\Actions\Consulta\ObservarAct;
use App\Repositories\ConsultaRepository;
use App\Models\Medico;
use App\Models\Consulta;
use Mockery;
use App\Exceptions\RequestException;

class ObservarActTest extends TestCase {

    private function getMockedSut() {
        $consultaRepository = (object) Mockery::mock(ConsultaRepository::class);
        $act = new ObservarAct($consultaRepository);
        return compact([ 'consultaRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_requestexception_caso_o_medico_esteja_observando_uma_consulta_que_nao_existe() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $consulta_id = 1;
        $dados = ['mensagem' => 'lorem ipsum dolor sit amet'];

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta_id)->andReturn(null);

        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Consulta não encontrada.');

        // Run
        $sut['act']->executar($medico, $consulta_id, $dados);
    }

    /** @test */
    public function deve_receber_requestexception_caso_o_neduci_esteja_observando_uma_consulta_que_nao_eh_responsavel() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $consulta = Consulta::factory(['id' => 1, 'medico_id' => 2])->make();
        $consulta_id = 1;
        $dados = ['mensagem' => 'lorem ipsum dolor sit amet'];

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta->id)->andReturn($consulta);

        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Apenas o médico responsável por essa consulta pode observá-la.');

        // Run
        $sut['act']->executar($medico, $consulta_id, $dados);
    }

}