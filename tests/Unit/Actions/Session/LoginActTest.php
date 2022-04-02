<?php

namespace Tests\Unit\Actions\Session;

use Tests\TestCase;
use App\Actions\Session\LoginAct;
use App\Repositories\MedicoRepository;
use App\Models\Medico;
use Mockery;
use App\Exceptions\LoginException;

class LoginActTest extends TestCase {

    private function getMockedSut() {
        $medicoRepository = (object) Mockery::mock(MedicoRepository::class);
        $act = new LoginAct($medicoRepository);
        return compact([ 'medicoRepository', 'act' ]);
    }

    /** @test */
    public function deve_receber_loginexception_caso_o_medico_nao_exista() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory()->make();
        $dados = ['email' => $medico->email, 'senha' => '123456789'];

        // Mock
        $sut['medicoRepository']->shouldReceive('obter_medico_por_email')->once()->with($dados['email'])->andReturn(null);

        // Assert
        $this->expectException(LoginException::class);
        $this->expectExceptionMessage('Usuário \'' . $dados['email'] . '\' não encontrado.');

        // Run
        $sut['act']->executar($dados);
    }

    /** @test */
    public function deve_receber_loginexception_caso_a_senha_nao_confere() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['senha' => 'admin000'])->make();
        $dados = ['email' => $medico->email, 'senha' => '000admin'];

        // Mock
        $sut['medicoRepository']->shouldReceive('obter_medico_por_email')->once()->with($dados['email'])->andReturn($medico);

        // Assert
        $this->expectException(LoginException::class);
        $this->expectExceptionMessage('Senha incorreta.');

        // Run
        $sut['act']->executar($dados);
    }

    /** @test */
    public function deve_receber_loginexception_caso_o_medico_esteja_desativado() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['senha' => 'admin000', 'status' => 'desativado'])->make();
        $dados = ['email' => $medico->email, 'senha' => 'admin000'];

        // Mock
        $sut['medicoRepository']->shouldReceive('obter_medico_por_email')->once()->with($dados['email'])->andReturn($medico);

        // Assert
        $this->expectException(LoginException::class);
        $this->expectExceptionMessage('Você não possui mais acesso ao sistema. &#128533;');

        // Run
        $sut['act']->executar($dados);
    }

    /** @test */
    public function deve_realizar_o_login() {
        // Arrange
        $sut = $this->getMockedSut();
        $medico = Medico::factory(['senha' => 'admin000'])->make();
        $dados = ['email' => $medico->email, 'senha' => 'admin000'];

        // Assert
        $sut['medicoRepository']->shouldReceive('obter_medico_por_email')->once()->with($dados['email'])->andReturn($medico);

        // Run
        $sut['act']->executar($dados);
    }

}