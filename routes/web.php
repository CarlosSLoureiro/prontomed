<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProntoMedController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Apenas usuarios não autenticados
Route::group(['middleware' => ['guest']], function() {

    Route::group(['prefix' => 'api', 'as'=>'api.'], function(){
        // Realiza o login
        Route::post('/login', [SessionController::class, 'logar'])->name('logar');
    });

    // Exibe tela de login
    Route::get('/login', [SessionController::class, 'login'])->name("login");
});

// Apenas usuário autenticados
Route::group(['middleware' => ['auth']], function() {

    // Exibe a tela principal
    Route::get('/', [ProntoMedController::class, 'index'])->name("principal");

    // Realiza o logout
    Route::get('/logout', [SessionController::class, 'logout'])->name('logout');


    // Apenas medicos ativos
    Route::group(['middleware' => ['can:ativo'], 'prefix' => 'api', 'as' => 'api.'], function() {
        // Cadastra nova senha
        Route::post('/alterar-senha', [SessionController::class, 'alterar_senha'])->name('alterar.senha');

        // Lista todos os pacientes
        Route::get('/pacientes', [PacienteController::class, 'listar'])->name('pacientes.listar');

        // Cadastra um novo paciente
        Route::post('/paciente', [PacienteController::class, 'cadastrar'])->name('paciente.cadastrar');

        // Edita um paciente
        Route::put('/paciente/{id}', [PacienteController::class, 'editar'])->name('paciente.editar');

        // Deleta um paciente
        Route::delete('/paciente/{id}', [PacienteController::class, 'deletar'])->name('paciente.deletar');

        // Lista consultas do médico de acordo com o tipo
        Route::get('/consultas/{tipo}', [ConsultaController::class, 'listar'])->name('consultas.listar');

        // Agenda uma nova consulta
        Route::post('/consulta', [ConsultaController::class, 'agendar'])->name('consulta.agendar');

        // Edita apenas a data agendada da consulta
        Route::patch('/consulta/{id}', [ConsultaController::class, 'editar'])->name('consulta.editar');

        // Deleta uma consulta
        Route::delete('/consulta/{id}', [ConsultaController::class, 'deletar'])->name('consulta.deletar');

        // Cadastra uma nova observação na consulta
        Route::post('/consulta/{id}/observacao', [ConsultaController::class, 'cadastrar_observacao'])->name('consulta.cadastrar_observacao');
    });
});
