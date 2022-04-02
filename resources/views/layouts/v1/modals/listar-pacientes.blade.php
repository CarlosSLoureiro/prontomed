<div id="listar-pacientes" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista de Pacientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <form method="post" action="">
                    <div class="input-group mb-3 search">
                        <select name="por" class="form-select">
                            <option value="nome">Buscar por Nome</option>
                            <option value="email">Buscar por Email</option>
                            <option value="telefone">Buscar por Telefone</option>
                            <option value="idade">Buscar por Idade</option>
                            <option value="tipo_sanguineo">Buscar por Tipo Sanguíneo</option>
                        </select>
                        <input name="valor" type="text" class="form-control" placeholder="Digite algo para ser buscado...">
                        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                    </div>
                </form>

                <div class="table-style">
                    <div class="card">
                        <div class="table-responsive">
                        <table class="table table-hover table-nowrap" data-api="{{ route('api.pacientes.listar') }}">
                            <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Cadastro</th>
                                <th>Idade</th>
                                <th>Sexo</th>
                                <th>IMC</th>
                                <th>Sangue</th>
                                <th>Consultas</th>
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