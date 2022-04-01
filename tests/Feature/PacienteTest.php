<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Medico;
use App\Models\Paciente;

class PacienteTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function deve_cadastrar_novo_paciente() {
        // Arrange
        $medico = Medico::factory()->create();
        $paciente = Paciente::factory()->make();

        // Feature
        $resposta = parent::actingAs($medico)->json('POST', route('api.paciente.cadastrar'), $paciente->getAttributes());

        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        parent::assertDatabaseHas('pacientes', $paciente->getAttributes());
    }

    /** @test */
    public function deve_editar_paciente_existente() {
        // Arrange
        $medico = Medico::factory()->create();
        $paciente = Paciente::factory()->create();
        $dados = Paciente::factory()->make(['email' => $paciente->email])->getAttributes();

        // Feature
        $resposta = parent::actingAs($medico)->json('PUT', route('api.paciente.editar', ['id' => $paciente->id]), $dados);
        
        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        parent::assertDatabaseHas('pacientes', $dados);
    }

    /** @test */
    public function deve_deletar_paciente_existente() {
        // Arrange
        $medico = Medico::factory()->create();
        $paciente = Paciente::factory()->create();
        $dados = ['id' => $paciente->id];

        // Feature
        $resposta = parent::actingAs($medico)->json('DELETE', route('api.paciente.deletar', $dados));
        
        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        parent::assertDatabaseMissing('pacientes', $dados);
    }
    
}