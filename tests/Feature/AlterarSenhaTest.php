<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Models\Medico;
use Tests\TestCase;

class AlterarSenhaTest extends TestCase {

    use RefreshDatabase;
    
    /** @test */
    public function nao_deve_alterar_senha_usando_uma_senha_atual_incorreta() {
        // Arrange
        $medico = Medico::factory()->create([
            'senha' => "sou uma senha!"
        ]);
        $dados = [
            'senha-atual' => "sou uma senha incorreta!",
            'senha-nova' => "123456789",
            'senha-nova-confirmar' => "123456789"
        ];

        // Feature
        $resposta = parent::actingAs($medico)->json('POST', route('api.alterar.senha'), $dados);

        // Assert
        $resposta->assertStatus(Response::HTTP_BAD_REQUEST);
        $resposta->assertJsonStructure(["mensagem"]);
    }

    /** @test */
    public function deve_alterar_a_senha_do_medico() {
        // Arrange
        $senha_atual = Str::random(10);
        $senha_nova  = Str::random(10);
        $medico = Medico::factory()->create([
            'senha' => $senha_atual
        ]);
        $dados = [
            'senha-atual' => $senha_atual,
            'senha-nova' => $senha_nova,
            'senha-nova-confirmar' => $senha_nova
        ];

        // Feature
        $resposta = parent::actingAs($medico)->json('POST', route('api.alterar.senha'), $dados);

        // Assert
        $resposta->assertStatus(Response::HTTP_OK);
        parent::assertTrue(Hash::check($senha_nova, $medico->senha));
    }
}