<?php

namespace Tests\Unit\Actions\Consulta;

use App\Utils\DateUtils;
use Tests\TestCase;
use App\Actions\Consulta\CadastrarAct;
use App\Repositories\ConsultaRepository;
use App\Repositories\PacienteRepository;
use App\Models\Medico;
use App\Models\Consulta;
use App\Models\Paciente;
use Mockery;
use Exception;

class CadastrarActTest extends TestCase {

    private function getMockedSut() {
        $consultaRepository = (object) Mockery::mock(ConsultaRepository::class);
        $pacienteRepository = (object) Mockery::mock(PacienteRepository::class);
        $act = new CadastrarAct($consultaRepository, $pacienteRepository);
        return compact([ 'consultaRepository', 'pacienteRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_exception_caso_o_paciente_nao_exista() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['id' => 1])->make();
        $dados = [ 'paciente_id' => 3, ];

        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with(3)->andReturn(null);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Paciente não encontrado.');

        // Run
        $sut['act']->executar($medico, $dados);
    }

    /** @test */
    public function deve_receber_exception_caso_o_medico_tenha_conflito_de_consultas() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory()->make();
        $paciente = Paciente::factory(['id' => 1])->make();
        $consulta = Consulta::factory([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
        ])->make();
        $dados = [
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'data' => $consulta->data
        ];
        
        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with($paciente->id)->andReturn($paciente);
        $sut['consultaRepository']->shouldReceive('obter_possivel_consulta_confilto_de_datas')->once()->andReturn($consulta);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Você já possui uma consulta agendada para ' . DateUtils::getAsDate($dados['data'])->isoFormat(DateUtils::pt_BR) . '. Tente agendar para ' . ConsultaRepository::TEMPO_MEDIO_DA_CONSULTA . ' minutos antes ou após.');

        // Run
        $sut['act']->executar($medico, $dados);
    }

    /** @test */
    public function deve_cadatrar_consulta() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory()->make();
        $paciente = Paciente::factory(['id' => 1])->make();
        $consulta = Consulta::factory([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
        ])->make();
        $dados = [
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'data' => $consulta->data
        ];
        
        // Mock
        $sut['pacienteRepository']->shouldReceive('obter_paciente')->once()->with($paciente->id)->andReturn($paciente);
        $sut['consultaRepository']->shouldReceive('obter_possivel_consulta_confilto_de_datas')->once()->andReturn(null);

        // Assert
        $sut['consultaRepository']->shouldReceive('cadastrar_consulta')->once()->andReturn($consulta);

        // Run
        $sut['act']->executar($medico, $dados);
    }

}