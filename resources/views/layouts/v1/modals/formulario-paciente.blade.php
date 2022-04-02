<div id="formulario-paciente" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar novo Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('api.paciente.cadastrar') }}">
                <div class="modal-body">
                    <div class="input-group">
                        <div class="col m-3">
                            <label for="paciente-name" class="col-form-label">Nome:</label>
                            <input name='nome' id="paciente-name" type="text" placeholder="Nome completo" class="form-control" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col m-3">
                            <label for="paciente-email" class="col-form-label">Email:</label>
                            <input name='email' id="paciente-email" type="email" class="form-control" placeholder="nome@exemplo.com" required>
                        </div>
                        <div class="col m-3">
                            <label for="paciente-telefone" class="col-form-label">Telefone Celular:</label>
                            <input name='telefone' id="paciente-telefone" type="text" placeholder="(xx) xxxxx-xxxx" class="form-control" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col m-3">
                            <label for="paciente-nascimento" class="col-form-label">Data de nascimento:</label>
                            <input name='nascimento' id="paciente-nascimento" type="date" class="form-control" required>
                        </div>
                        <div class="col m-3">
                            <label for="paciente-sexo" class="col-form-label">Sexo:</label>
                            <select id="paciente-sexo" name="sexo" class="form-control text-capitalize">
                                @foreach ($sexos as &$sexo)
                                <option value="{{ $sexo }}">{{ $sexo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col col-md-3 m-3">
                            <label for="paciente-peso" class="col-form-label">Peso (em Kilograma):</label>
                            <input name='peso' id="paciente-peso" type="number" class="form-control" step='0.01' placeholder="75.3">
                        </div>
                        <div class="col col-md-3 m-3">
                            <label for="paciente-altura" class="col-form-label">Altura (em Metros):</label>
                            <input name='altura' id="paciente-altura" type="number" class="form-control" step='0.01' placeholder="1.73">
                        </div>
                        <div class="col m-3">
                            <label for="paciente-sangue" class="col-form-label">Tipo sanguíneo:</label>
                            <select name='tipo_sanguineo' id="paciente-sangue" name="tipo_sanguineo" class="form-control text-capitalize">
                                @foreach ($tipos_sanguineo as &$tipo_sanguineo)
                                <option value="{{ $tipo_sanguineo }}">{{ $tipo_sanguineo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
