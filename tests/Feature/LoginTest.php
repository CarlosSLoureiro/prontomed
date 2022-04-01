<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Medico;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function nao_deve_realizar_o_login_com_senha_incorreta() {
        // Arrange
        $medico = Medico::factory()->create([
            'senha' => 'test'
        ]);

        // Feature
        $resposta = parent::json('POST', route('api.logar'), [
            'email' => $medico->email,
            'senha' => '123456789'
        ]);

        // Assert
        $resposta->assertStatus(Response::HTTP_UNAUTHORIZED);
        $resposta->assertJsonStructure(['mensagem']);
        parent::assertEquals(json_encode(array(
            "mensagem" => "Senha incorreta."
        )), $resposta->getContent());
    }

    /** @test */
    public function nao_deve_logar_com_status_inativo() {
        // Arrange
        $medico = Medico::factory()->create([
            'senha' => 'test',
            'status' => 'inativo'
        ]);

        // Feature
        $resposta = parent::json('POST', route('api.logar'), [
            'email' => $medico->email,
            'senha' => 'test'
        ]);

        // Assert
        $resposta->assertStatus(Response::HTTP_UNAUTHORIZED);
        $resposta->assertJsonStructure(['mensagem']);
        parent::assertEquals(json_encode(array(
            "mensagem" => "Você não possui mais acesso ao sistema. &#128533;"
        )), $resposta->getContent());
    }

    /** @test */
    public function deve_realizar_o_login_com_sucesso() {
        // Arrange
        $medico = Medico::factory()->create([
            'senha' => 'test'
        ]);

        // Feature
        $resposta = parent::json('POST', route('api.logar'), [
            'email' => $medico->email,
            'senha' => 'test'
        ]);

        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        $resposta->assertJsonStructure(['nome', 'url']);
        parent::assertAuthenticatedAs($medico);
    }
}