/*!
 *
 * Criado por Carlos Loureiro
 * https://www.linkedin.com/in/carlos-s-loureiro
 *
 */

$(document).ready(function(){
    let debug = true;
    let solicitando = false;
    let form = $('.login-form');
    let title = form.find('.title').html();
    if (form.length) {
        let modal = new bootstrap.Modal($('#modal-mensagem').get(0), { keyboard: false });
        form.on("submit", function(e){
            e.preventDefault();
            form.find('.title').html('Logando...');
            if (!solicitando) {
                solicitando = true;
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: "json",
                    success: function(response) {
                        $(modal._element).find('.modal-title').html('Login realizado com sucesso');
                        $(modal._element).find('.modal-body').html('Seja bem-vindo ' + response.nome + '! &#128516;');
                        $(modal._element).find('.modal-footer').hide();
                        modal.show();
                        setTimeout(() => {
                            window.location = response.url
                        }, 2000);
                    },
                    error: 
                    function (request, status, error) {
                        let err = error;
                
                        if (request.responseJSON.mensagem != null) {
                            err = request.responseJSON.mensagem;
                        } else if (debug && request.responseJSON.message != null && request.responseJSON.message != "") {
                            err = request.responseJSON.message;
                        } else if (request.responseJSON != null) {
                            let errors = request.responseJSON;
                            err = '';
                            Object.keys(errors).forEach(function(k){
                                errors[k].forEach(function(msg) {
                                    err += ((err.length > 0) ? '<br>' : '') + msg;
                                });
                            });
                        }
                        $(modal._element).find('.modal-title').html('Não foi possível efetuar o login');
                        $(modal._element).find('.modal-body').html(err);
                        modal.show();
                        solicitando = false;
                        form.find('.title').html(title);
                    }
                });
            }
        });
    }
});