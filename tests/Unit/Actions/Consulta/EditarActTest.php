<?php

namespace Tests\Unit\Actions\Consulta;

use App\Utils\DateUtils;
use Tests\TestCase;
use App\Actions\Consulta\EditarAct;
use App\Repositories\ConsultaRepository;
use App\Models\Medico;
use App\Models\Consulta;
use App\Models\Observacoes;
use Mockery;
use Exception;

class EditarActTest extends TestCase {

    private function getMockedSut() {
        $consultaRepository = (object) Mockery::mock(ConsultaRepository::class);
        $act = new EditarAct($consultaRepository);
        return compact([ 'consultaRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_exception_caso_o_medico_esteja_editando_uma_consulta_que_nao_existe() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $consulta_id = 1;
        $dados = [];

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta_id)->andReturn(null);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Consulta não encontrada.');

        // Run
        $sut['act']->executar($medico, $consulta_id, $dados);
    }

    /** @test */
    public function deve_receber_exception_caso_o_medico_esteja_editando_uma_consulta_que_nao_eh_responsavel() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $consulta = Consulta::factory(['id' => 1, 'medico_id' => 2])->make();
        $dados = [];

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta->id)->andReturn($consulta);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Apenas o médico responsável por essa consulta pode reagendá-la.');

        // Run
        $sut['act']->executar($medico, $consulta->id, $dados);
    }

    /** @test */
    public function deve_receber_exception_caso_o_medico_esteja_editando_a_data_agendada_da_consulta_e_tenha_conflito_de_consultas() {
        // Arrange
        $sut = $this->getMockedSut();
        $usuario = Medico::factory(['id' => 1])->make();
        $consulta = Consulta::factory([
            'id' => 1,
            'medico_id' => $usuario->id,
        ])->make();
        $consulta_em_conflito = Consulta::factory([
            'id' => 2,
            'medico_id' => $usuario->id,
            'date' => DateUtils::getAsDate($consulta->data)->addMinutes(40),
        ])->make();

        $dados = ['data' => DateUtils::getAsDate($consulta->data)->addMinutes(50)];

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta->id)->andReturn($consulta);
        $sut['consultaRepository']->shouldReceive('obter_possivel_consulta_confilto_de_datas')->once()->andReturn($consulta_em_conflito);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Você já possui uma consulta agendada para ' . DateUtils::getAsDate($consulta_em_conflito->data)->isoFormat(DateUtils::pt_BR) . '. Tente reagendar para ' . ConsultaRepository::TEMPO_MEDIO_DA_CONSULTA . ' minutos antes ou após.');

        // Run
        $sut['act']->executar($usuario, $consulta->id, $dados);
    }

    /** @test */
    public function deve_editar_data_da_consulta() {
        // Arrange
        $sut = $this->getMockedSut();
        $usuario = Medico::factory(['id' => 1])->make();
        $consulta = Consulta::factory([
            'id' => 1,
            'medico_id' => $usuario->id,
        ])->make();
        $dados = ['data' => DateUtils::getAsDate($consulta->data)->addMinutes(20)];
        $obs = Observacoes::factory([
            'consulta_id' => $consulta->id,
            'medico_id' => $usuario->id,
            'mensagem' => 'Reagendei essa consulta para ' . DateUtils::getAsDate($dados['data'])->isoFormat(DateUtils::pt_BR) . '.'
        ])->make();

        // Mock
        $sut['consultaRepository']->shouldReceive('obter_consulta')->once()->with($consulta->id)->andReturn($consulta);
        $sut['consultaRepository']->shouldReceive('obter_possivel_consulta_confilto_de_datas')->once()->andReturn(null);

        // Assert
        $sut['consultaRepository']->shouldReceive('editar_consulta')->once()->andReturn($consulta);
        $sut['consultaRepository']->shouldReceive('observar_consulta')->once()->andReturn($obs);

        // Run
        $sut['act']->executar($usuario, $consulta->id, $dados);
    }

}
