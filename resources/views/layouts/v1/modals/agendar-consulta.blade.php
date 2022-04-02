<div id="agendar-consulta" class="modal fade" tabindex="-1" data-bs-backdrop='static'>
    @if(auth()->user()->status == 'administrador')
    <div class="modal-dialog modal-lg">
    @else
    <div class="modal-dialog">
    @endif
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agendar consulta para o paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('api.consulta.agendar') }}">
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <label for="data-consulta" class="col-form-label">Data e hora da consulta:</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <input name="data" id="data-consulta" type="datetime-local" class="form-control w-50">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-primary">Agendar</button>
                </div>
            </form>
        </div>
    </div>
</div>
