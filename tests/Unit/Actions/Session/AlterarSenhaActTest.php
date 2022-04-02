<?php

namespace Tests\Unit\Actions\Session;

use Tests\TestCase;
use App\Actions\Session\AlterarSenhaAct;
use App\Repositories\MedicoRepository;
use App\Models\Medico;
use Mockery;
use App\Exceptions\RequestException;

class AlterarSenhaActTest extends TestCase {

    private function getMockedSut() {
        $medicoRepository = (object) Mockery::mock(MedicoRepository::class);
        $act = new AlterarSenhaAct($medicoRepository);
        return compact([ 'medicoRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_requestexception_caso_a_senha_atual_nao_confere() {
        // Arrange
        $sut = $this->getMockedSut();
        $usuario = Medico::factory(['senha' => 'admin000'])->make();
        $dados = ['senha-atual' => '000admin', 'senha-nova' => '00admin00'];
        
        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('A sua senha atual está incorreta.');

        // Run
        $sut['act']->executar($usuario, $dados);
    }

    /** @test */
    public function deve_alterar_a_senha() {
        // Arrange
        $sut = $this->getMockedSut();
        $usuario = Medico::factory(['senha' => 'admin000'])->make();
        $dados = ['senha-atual' => 'admin000', 'senha-nova' => '00admin00'];

        // Assert
        $sut['medicoRepository']->shouldReceive('editar_medico')->once()->with($usuario, ['senha' => $dados['senha-nova']])->andReturn($usuario);

        // Run
        $sut['act']->executar($usuario, $dados);
    }

}