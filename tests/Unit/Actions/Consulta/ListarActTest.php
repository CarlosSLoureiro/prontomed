<?php

namespace Tests\Unit\Actions\Consulta;

use Tests\TestCase;
use App\Actions\Consulta\ListarAct;
use App\Repositories\ConsultaRepository;
use App\Models\Medico;
use Mockery;

class ListarActTest extends TestCase {

    private function getMockedSut() {
        $consultaRepository = (object) Mockery::mock(ConsultaRepository::class);
        $act = new ListarAct($consultaRepository);
        return compact([ 'consultaRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_suas_as_listas_de_consultas() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $tipo = 'hoje';

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consultas')->once()->with($medico, $tipo, [])->andReturn(null);

        // Assert
        $this->expectError();
        $this->expectErrorMessage('Call to a member function paginate() on null');

        // Run
        $sut['act']->executar($medico, $tipo);
    }

}