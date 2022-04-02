<div id="formulario-senha" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar senha de login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('api.alterar.senha') }}">
                <div class="modal-body">
                    <div class="input-group">
                        <div class="col m-3">
                            <label for="medico-senha-atual" class="col-form-label">Senha atual:</label>
                            <input name='senha-atual' id="medico-senha-atual" type="password" class="form-control" placeholder="Digite sua senha atual" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col m-3">
                            <label for="medico-senha-nova" class="col-form-label">Nova senha:</label>
                            <input name='senha-nova' id="medico-senha-nova" type="password" class="form-control" placeholder="Digite sua senha nova" minlength="8" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col m-3">
                            <label for="medico-senha-nova-confirmar" class="col-form-label">Confirmar senha:</label>
                            <input name='senha-nova-confirmar' id="medico-senha-nova-confirmar" type="password" class="form-control"  placeholder="Confirme sua senha nova" minlength="8" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-primary">Alterar</button>
                </div>
            </form>
        </div>
    </div>
</div>