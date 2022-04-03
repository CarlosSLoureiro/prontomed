<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SessionController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['as' => 'api.'], function() {
    Route::post('/login', [SessionController::class, 'logar'])->name('logar');
    Route::post('/logout', [SessionController::class, 'logout'])->name('logout');

    // Apenas medicos autenticados com JWT válido e médicos com status ativos
    Route::group(['middleware' => ['auth:api', 'can:ativo']], function() {
        // Cadastra nova senha
        Route::get('/meus-dados', [SessionController::class, 'medico_dados'])->name('medico.dados');

        // Cadastra nova senha
        Route::post('/alterar-senha', [SessionController::class, 'alterar_senha'])->name('alterar.senha');

        // Lista todos os pacientes
        Route::get('/pacientes', [PacienteController::class, 'listar'])->name('pacientes.listar');

        // Cadastra um novo paciente
        Route::post('/paciente', [PacienteController::class, 'cadastrar'])->name('paciente.cadastrar');

        // Edita um paciente
        Route::put('/paciente/{id}', [PacienteController::class, 'editar'])->name('paciente.editar');

        // Deleta um paciente
        Route::delete('/paciente/{id}', [PacienteController::class, 'excluir'])->name('paciente.excluir');

        // Lista consultas do médico de acordo com o tipo
        Route::get('/consultas/{tipo}', [ConsultaController::class, 'listar'])->name('consultas.listar');

        // Agenda uma nova consulta
        Route::post('/consulta', [ConsultaController::class, 'agendar'])->name('consulta.agendar');

        // Edita apenas a data agendada da consulta
        Route::patch('/consulta/{id}', [ConsultaController::class, 'editar'])->name('consulta.editar');

        // Deleta uma consulta
        Route::delete('/consulta/{id}', [ConsultaController::class, 'excluir'])->name('consulta.excluir');

        // Cadastra uma nova observação na consulta
        Route::post('/consulta/{id}/observacao', [ConsultaController::class, 'cadastrar_observacao'])->name('consulta.cadastrar_observacao');
    });
});
