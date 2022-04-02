@php
    $modals = array(
        array(
            'id' => 'listar-consultas-do-dia',
            'title' => 'Suas consultas agendadas para hoje',
            'filtro_simples' => true,
            'api' => route('api.consultas.listar', ['tipo'=>'hoje'])
        ),
        array(
            'id' => 'listar-consultas-agendadas',
            'title' => 'Suas consultas agendadas',
            'filtro_simples' => false,
            'api' => route('api.consultas.listar', ['tipo'=>'agendadas'])
        ),
        array(
            'id' => 'listar-consultas-anteriores',
            'title' => 'Suas consultas anteriores',
            'filtro_simples' => false,
            'api' => route('api.consultas.listar', ['tipo'=>'anteriores'])
        )
    );
@endphp
@foreach ($modals as &$modal)
<div id="{{ $modal['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $modal['title'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <form>
                    @if ($modal['filtro_simples'])
                    <div class="input-group mb-3">
                        <input name="paciente" type="text" class="form-control w-25" placeholder="Digite o nome do paciente... (opcional)">
                        <label class="col-form-label search-label-diff">Ordenar por:</label>
                        <select name="ordem" class="form-select w-25">
                            <option value="consulta_decrescente">Data da consulta recente</option>
                            <option value="consulta_crescente">Data da consulta antiga</option>
                            <option value="cadastro_decrescente">Cadastro da consulta recente</option>
                            <option value="cadastro_crescente">Cadastro da consulta antiga</option>
                        </select>
                        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                    </div>
                    @else
                    <div class="input-group mb-3">
                        <input name="paciente" type="text" class="form-control w-25" placeholder="Digite o nome do paciente... (opcional)">
                        <label class="col-form-label search-label-diff">Ordenar por:</label>
                        <select name="ordem" class="form-select w-25">
                            <option value="consulta_decrescente">Data da consulta recente</option>
                            <option value="consulta_crescente">Data da consulta antiga</option>
                            <option value="cadastro_decrescente">Cadastro da consulta recente</option>
                            <option value="cadastro_crescente">Cadastro da consulta antiga</option>
                        </select>
                        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                    </div>
                    <div class="input-group mb-3">
                        <select name="datas" class="form-select w-25">
                            <option value="0">Buscar em qualquer data</option>
                            <option value="1">Buscar entre as datas</option>
                        </select>
                        <div class="input-group mb3 w-75 datas">
                            <input name="data-inicio" type="datetime-local" class="form-control w-25">
                            <label class="col-form-label search-label-diff">e:</label>
                            <input name="data-fim" type="datetime-local" class="form-control w-25">
                        </div>
                    </div>
                    @endif 
                </form>

                <div class="table-style">
                    <div class="card">
                        <div class="table-responsive">
                        <table class="table table-hover table-nowrap" data-api="{{ $modal['api'] }}">
                            <thead class="table-light">
                            <tr>
                                <th>Nº</th>
                                <th>Paciente</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Data</th>
                                <th>Observações</th>
                                <th>Cadastro</th>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <nav>
                <ul class="pagination justify-content-center">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Anterior</a>
                  </li>
                  <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                  <li class="page-item">
                    <a class="page-link disabled" href="#">Próximo</a>
                  </li>
                </ul>
                <p class="total text-center">Total: 0</p>
            </nav>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
            </div>
        </div>
    </div>
</div>
@endforeach