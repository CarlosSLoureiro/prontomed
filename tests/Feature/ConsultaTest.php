<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Utils\DateUtils;

class ConsultaTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function deve_cadastrar_nova_consulta() {
        // Arrange
        $medico = Medico::factory()->create();
        $paciente = Paciente::factory()->create();
        $consulta = Consulta::factory([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id
        ])->make();

        // Feature
        $resposta = parent::actingAs($medico)->json('POST', route('api.consulta.agendar'), $consulta->getAttributes());

        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        parent::assertDatabaseHas('consultas', $consulta->getAttributes());
    }

    /** @test */
    public function deve_ragendar_consulta_cadastrada() {
        // Arrange
        $medico = Medico::factory()->create();
        $paciente = Paciente::factory()->create();
        $consulta = Consulta::factory([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id
        ])->create();
        $data = DateUtils::getAsDate($consulta->data)->addMinutes(20);
        $dados = ['data' => $data];
        $consulta_esperada = Consulta::factory([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'data' => $data
        ])->make();

        // Feature
        $resposta = parent::actingAs($medico)->json('PATCH', route('api.consulta.editar', ['id' => $consulta->id]), $dados);

        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        parent::assertDatabaseHas('consultas', $consulta_esperada->getAttributes());
    }

    /** @test */
    public function deve_excluir_consulta_cadastrada() {
        // Arrange
        $medico = Medico::factory()->create();
        $paciente = Paciente::factory()->create();
        $consulta = Consulta::factory([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id
        ])->create();
        $dados = ['id' => $consulta->id];

        // Feature
        $resposta = parent::actingAs($medico)->json('DELETE', route('api.consulta.excluir', $dados));

        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        $resposta->assertJson(['deleted' => true]);
        parent::assertDatabaseMissing('consultas', $consulta->getAttributes());
    }
    
}