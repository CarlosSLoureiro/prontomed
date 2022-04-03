/*!
 *
 * Criado por Carlos Loureiro
 * https://www.linkedin.com/in/carlos-s-loureiro
 *
 */
(function() {
    let debug = true;
    let cache_rand = null;
    let cache_busca = null;
    let MEDICO = {};

    let bootstrap_modal_backdrop = (function() {
        let old_focus;

        let force_focus = function(elmt) {
            let focus_tries = 0;

            var interval = setInterval(function() {
                if (document.activeElement != elmt && focus_tries >= 30) {
                    old_focus = document.activeElement;
                    elmt.focus();
                    focus_tries++;
                } else {
                    clearInterval(interval);
                }
            }, 100);
        }

        let verificar_backdrops = function() {
            let index = 0;
            $('.modal-backdrop').each(function() {
                index = parseInt($(this).css('z-index'));
                let remove = true;
                $('.modal:visible').each(function() {
                    if (parseInt($(this).css('z-index')) > index) {
                        if (remove) {
                            remove = false;
                        }
                        return;
                    }
                });

                if (remove) {
                    $(this).remove();
                }
            });
        };
        // Solução temporária para fixar a função de backdrop do bootstrap 5.1.3
        return function(selector, temp = 0) {
            let elmt = $(selector);
            let backdrop = $(".modal-backdrop:last");

            let index;

            let modal = new bootstrap.Modal(elmt.get(0));
            modal._config.backgrop = 'static';
            modal.show();
        
            if (backdrop.css('z-index') == 1050) {
                index = 1055;
            } else {
                index = parseInt(backdrop.css('z-index'));
            }
        
            index += 1;
            $(".modal-backdrop:last").css("z-index", index);
            index += 1;
            elmt.css('z-index', index);
    
            if (temp <= 0) {
                elmt.on('hidden.bs.modal', function () {
                    setTimeout(function() {
                        force_focus(old_focus);
                        verificar_backdrops();
                    }, 200);
                });
                force_focus(elmt.get(0));
            }

            if (temp > 0) {
                setTimeout(function() {
                    modal.hide();
                }, temp * 1000);
            }
        
            return elmt;
        };
    })();

    let definir_headers = function() {
        let Authorization = localStorage.getItem('Authorization');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': ((!(Authorization === null)) ? 'Bearer ' + Authorization : '')
            }
        });
    };

    let definir_login = function(){
        let debug = true;
        let solicitando = false;
        let form = $('.login-form');
        let title = form.find('.title').html();
        let modal = new bootstrap.Modal($('#modal-mensagem-login').get(0), { keyboard: false });
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
                        localStorage.setItem('Authorization', response.token);
                        definir_headers();
                        definir_medico_infos();
                        form.find('input').val('');
                        setTimeout(function() {
                            form.find('.title').html(title);
                        }, 3000);
                        solicitando = false;
                    },
                    error: function (request, status, error) {
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
    };

    let definir_logout = function() {
        $('.logout').on('click', function(){
            $.ajax({
                type: 'GET',
                url: '/api/logout',
                dataType: "json",
                success: function(response) {
                    localStorage.removeItem('Authorization');
                    definir_headers();
                    $(".app-page").slideUp({
                        done: function(){
                            $(".login-page").slideDown();
                        }
                    });
                },
                error: request_error('Não foi possível fazer o logout!')
            });
        });
    };

    let definir_pagina = function() {
        $(".login-page").hide();
        $(".app-page").hide();
        $("body").show();
    };

    let definir_app = function() {
        $('.table').DataTable({searching: false, paging: false, info: false, ordering: false});
        $('#listar-pacientes').on('show.bs.modal', function(e) { carregar_pacientes($(this)) });
        $('#listar-consultas-do-dia').on('show.bs.modal', function(e) { carregar_consultas($(this))() });
        $('#listar-consultas-agendadas').on('show.bs.modal', function(e) { carregar_consultas($(this))() });
        $('#listar-consultas-anteriores').on('show.bs.modal', function(e) { carregar_consultas($(this))() });
        modal_cadastrar($('#formulario-paciente'), 'paciente');
        $('input[name="telefone"]').mask("(99) 99999-9999");
        $('#formulario-senha').find('form').on('submit', alterar_senha);
        $('button[type="submit"]').each(function(index,item){
            $(item).data('name', $(item).text());
        });
    };

    let back_submits = function() {
        $('button[type="submit"]').each(function(index,item){
            $(item).text($(item).data('name'));
        });
    };

    let calcular_idade = function(dateString) {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    };

    let IMC_status = function(imc, sexo, idade) {
        if (idade >= 18) {
            idade -= 18;
            idade /= 10;
            if (sexo.toLowerCase() != "feminino") {
                idade + 1
            }

            let abaixo = (19 + parseInt(idade));
            let normal = (24 + parseInt(idade));
            let acima = (29 + parseInt(idade));

            if (imc < abaixo) {
                return 'bg-soft-warning';
            } else if (imc < normal) {
                return 'bg-soft-primary';
            } else if (imc < acima) {
                return 'bg-soft-warning';
            } else {
                return 'bg-soft-danger';
            }
        } else {
            return 'bg-soft-secondary';
        }
    };

    let formatar_telefone = function(tel) {
        if (tel.length > 10) {
            return tel.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
        } else {
            return tel.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
        }
    };

    let formatar_int_com_0 = function(n) {
        return String((String(n).length > 1) ? n : ('0' + n));
    }

    let formatar_data = function(m) {
        return formatar_int_com_0(m.getDate()) + '/' + formatar_int_com_0(m.getMonth()+1) +"/"+ m.getFullYear()
    };

    let formatar_data_hora = function(m) {
        return 'dia ' + formatar_int_com_0(m.getDate()) + '/' + formatar_int_com_0(m.getMonth()+1) +"/"+ m.getFullYear() + "<br>as " + formatar_int_com_0(m.getHours()) + ":" + formatar_int_com_0(m.getMinutes());
    };

    let formatar_data_hora_pt_BR = function(m) {
        return (['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'][m.getDay()] + ", " +
            (m.getDate()) + ' de ' +
            ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'][m.getMonth()] + ' de ' +
            m.getFullYear() + ' as ' + formatar_int_com_0(m.getHours()) + ":" + formatar_int_com_0(m.getMinutes()));
    };

    let formatar_data_ISO8601 = function(date) {
        return date.getFullYear() + '-' +
        ('00' + (date.getMonth()+1)).slice(-2) + '-' +
        ('00' + date.getDate()).slice(-2) + ' ' + 
        ('00' + date.getHours()).slice(-2) + ':' + 
        ('00' + date.getMinutes()).slice(-2) + ':' + 
        ('00' + date.getSeconds()).slice(-2);
    }

    let fixar_table_dropdown = function() {
        $('.custom-dropdown').off('show.bs.dropdown');
        $('.custom-dropdown').on('show.bs.dropdown', function(e) {
            $('.table-responsive').css( "overflow", "inherit" );
        });

        $('.custom-dropdown').off('hide.bs.dropdown');
        $('.custom-dropdown').on('hide.bs.dropdown', function(e) {
            $('.table-responsive').css( "overflow", "auto" );
        });
    }

    let atualizar_pagina = function() {
        definir_medico_infos();
    }

    let definir_medico_infos = function() {
        $.ajax({
            type: 'GET',
            url: '/api/meus-dados',
            dataType: "json",
            success: function(response) {
                MEDICO.nome = response.medico;
                $('.medico-nome').text(MEDICO.nome);
                $('.n-consultas-do-dia').text(response.consultas_do_dia);
                $('.n-consultas-agendadas').text(response.consultas_agendadas);
                $('.n-consultas-anteriores').text(response.consultas_anteriores);
                
                $(".login-page").slideUp({
                    done: function(){
                        $(".app-page").slideDown();
                    }
                });
            },
            error: function(request, status, error) {
                switch (error) {
                    case 'Unauthorized':
                        $(".app-page").slideUp({
                            done: function(){
                                $(".login-page").slideDown();
                            }
                        });
                        return;
                    default:
                        request_error('Não foi possível carregar seus dados!')(request, status, error);
                        return;
                }
            }
        });
    }

    let modal_msg = function(title, body, temp = 0) {
        let modal = bootstrap_modal_backdrop('#modal-mensagem', temp);
        modal.find('.modal-title').html(title);
        modal.find('.modal-body').html(body);
    };

    let request_success = function(title, body, elmt, func = null, list_modal = null) {
        return function() {
            back_submits();
            modal_msg(title, body);
            elmt.modal('hide');
            atualizar_pagina();
            if (func != null && list_modal != null) {
                func(list_modal, cache_busca);
            }
        }
    };

    let request_error = function(title) {
        return function (request, status, error) {
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

            back_submits();
            
            modal_msg(title, err);
        };
    };

    let preencher_tabela = function(table, api, func) {
        if (api == null) {
            api = table.data('api');
        }

        // Para o modal não ficar pequeno e a tela subir quando a tabela for preenchida
        if (table.find("tbody").data('height') != null) {
            table.find("tbody").css('height', table.find("tbody").data('height'));
        }

        table.find("tbody").html('<tr><td colspan="' + table.find('thead th').length + '" style="text-align:center;"><b>Carregando...</b></td></td>');
        if (typeof api !== 'undefined' && api !== false) {
            $.ajax({
                url: (cache_busca = api),
                method: 'GET',
                success: function (response){
                    back_submits();
                    func(response);
                    if (table.find("tbody").data('height') == null || table.find("tbody").data('height') != null && parseFloat(table.find("tbody").data('height')) < parseFloat(table.find("tbody").css('height'))) {
                        table.find("tbody").data('height', table.find("tbody").css('height'));
                    }
                    table.find("tbody").css('height', 'auto');
                },
                error: function (request, status, error) {
                    let err;

                    if (request.responseJSON.mensagem != null) {
                        err = request.responseJSON.mensagem;
                    } else if (debug && request.responseJSON.message != null && request.responseJSON.message != "") {
                        err = request.responseJSON.message;
                    } else {
                        err = error;
                    }

                    back_submits();

                    table.find("tbody").html('<tr><td colspan="' + table.find('thead th').length + '" style="text-align:center;"><b>Não foi possível realizar a requisição.<br>' + err + '</b></td></td>');
                }
            });
        }
    };

    let atualizar_tabnav = function(nav, links, func, total) {
        let elmt = nav.find('ul');
        elmt.empty();

        for (let link of links) {
            let li = $('<li>', { class: ('page-item') });

            let p = $('<p>', { class: ('page-link') });
            p.html(link.label);
            p.appendTo(li);

            if (!(link.url == null || link.active)) {
                li.addClass('clickable');
                li.on('click', function(){ func($(this).closest('.modal'), link.url) });
            } else {
                li.addClass(link.active ? 'active' : 'disabled');
            }

            li.appendTo(elmt);
        }

        nav.find('.total').html('Total: ' + total + '');
    };

    let adicionar_filtros = function(modal, func) {
        let form = modal.find('form');
        if (form.length) {
            form.off('submit');
            form.on("submit", function(e){
                e.preventDefault();
                $(this).find('button[type="submit"]').text('Buscando...');
                func(modal, modal.find('table').data('api') + '?' + $(this).serialize());
            });
        }
    };

    let adicionar_filtros_consulta = function(modal, func) {
        let form = modal.find('form');
        if (form.length) {
            if (form.find('select[name="datas"]').length) {
                form.find('select[name="datas"]').off('change');
                form.find('select[name="datas"]').on('change', function(e){
                    if (Boolean(parseInt($(this).val()))) {
                        form.find('.datas').css('visibility', 'visible');
                    } else {
                        form.find('.datas').css('visibility', 'hidden');
                    }
                });
            }

            form.off('submit');
            form.on("submit", function(e){
                e.preventDefault();
                $(this).find('button[type="submit"]').text('Buscando...');

                let filters = new Array();
                let values = $(this).serializeArray();
                let use_dates = false;
                let dates = "";

                values.forEach(function(data) {
                    switch (data.name) {
                        case 'paciente':
                        case 'ordem':
                            filters.push({
                                name: data.name,
                                value: data.value
                            });
                            break;
                        case 'datas':
                            use_dates = Boolean(parseInt(data.value));
                            break;
                        case 'data-inicio':
                            dates = data.value;
                            break;
                        case 'data-fim':
                            dates = ((dates != "") ? (dates + "|") : "") + data.value;
                            break;
                    }
                });

                filters.push({
                    name: 'data',
                    value: ((use_dates && dates != "") ? dates : 'qualquer')
                });

                func(modal, modal.find('table').data('api') + '?' + $.param(filters));
            });
        }
    };

    let modal_edicao = function(list_modal, func, id, data) {
        return function() {
            
            if (!Boolean($('#' + data.modal.base + '-clone').length)) {
                let elmt = $('#' + data.modal.base);
                if (elmt.length) {
                    elmt = elmt.clone();
                    elmt.attr('id', data.modal.base + '-clone');
                    elmt.appendTo($('body'));
                } else {
                    console.error('Não existe o elemento base \'' + data.modal.base + '\' para clonar.');
                    return;
                }
            }

            elmt = bootstrap_modal_backdrop('#' + data.modal.base + '-clone');

            elmt.find('.modal-title').html(data.modal.title);
            let api = elmt.find('form').attr('action');
            elmt.find('form').find('button[type="submit"]').html('Salvar');
            elmt.find('form').find('button[type="submit"]').data('name', 'Salvar');

            try {
                elmt.find('form').find('input[name="telefone"]').mask("(99) 99999-9999");
            } catch (e) {
            }

            for (const [name, value] of Object.entries(data.modal.data)) {
                let form_elmt = elmt.find('form').find('[name="' + name + '"]');
                if (form_elmt.length) {
                    form_elmt.val(((name == 'telefone') ? formatar_telefone(value) : value));
                }
            };

            elmt.find('form').off('submit');
            elmt.find('form').on("submit", function(e){
                e.preventDefault();
                $(this).find('button[type="submit"]').text('Salvando...');
                let form_data = new FormData();
            
                form_data.append('_method', 'PUT');
                form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $(this).serializeArray().forEach(function(data) {
                    form_data.append(data.name, data.value);
                });

                $.ajax({
                    type: 'POST',
                    url: api + '/' + id,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: request_success(data.success.title, data.success.body, elmt, func, list_modal),
                    error: request_error(data.error)
                });
            });
        };
    };

    let modal_exclusao = function(list_modal, func, api, id, data, consulta = false) {
        return function() {
            let elmt = bootstrap_modal_backdrop('#confirmar-exclusao');
            elmt.find('.modal-title').html(data.modal.title);
            elmt.find('.modal-body').html(data.modal.body);

            elmt.find('form').off('submit');
            elmt.find('form').on("submit", function(e){
                e.preventDefault();
                $(this).find('button[type="submit"]').text('Deletando...');
                $.ajax({
                    type: 'DELETE',
                    url: (consulta ? "/api/consulta" : api.slice(0, -1)) + "/" + id,
                    dataType: "json",
                    success: request_success(data.success.title, data.success.body, elmt, func, list_modal),
                    error: request_error(data.error)
                });
            });
        };
    };

    let modal_consulta_observacoes = function(list_modal, func, id, data) {
        return function() {
            let elmt = bootstrap_modal_backdrop('#listar-consulta-observacoes');
            elmt.find('.modal-title').html(data.modal.title);
            elmt.find('.modal-body').empty();
            if (data.modal.observacoes.length > 0) {
                data.modal.observacoes.forEach(function(observacao) {
                    elmt.find('.modal-body').append('<div class="observacao">' +
                        '<img src="/img/medico.png" class="rounded-circle">' + 
                        '<blockquote class="quote-card">' +
                            '<h5>' + MEDICO.nome + '</h5>' +
                            '<p>' + observacao.mensagem + '</p>' +
                            '<cite>' + formatar_data_hora_pt_BR(new Date(observacao.created_at)) + '</cite>' +
                        '</blockquote>'+
                    '</div>');
                });
            } else {
                elmt.find('.modal-body').append('<h3 class="text-center">Não há observações</h3>');
            }

            elmt.find('form').on("submit", function(e){
                e.preventDefault();
                adicionar_consulta_observacao(list_modal, func, elmt, id, data, data.modal.observacoes);
            });
        };
    };

    let adicionar_consulta_observacao = function(list_modal, func, obs_modal, id, data, observacoes) {
        let elmt = bootstrap_modal_backdrop('#cadastrar-observacao');
        elmt.find('.modal-title').html(data.modal.sub);
        elmt.find('form').find('textarea').val("");
        elmt.find('form').off('submit');
        elmt.find('form').on("submit", function(e){
            e.preventDefault();
            if (elmt.find('form').find('textarea').val() != "") {
                $(this).find('button[type="submit"]').text('Enviando...');
                let form_data = new FormData();
            
                form_data.append('_method', 'POST');
                form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $(this).serializeArray().forEach(function(data) {
                    form_data.append(data.name, data.value);
                });

                $.ajax({
                    type: 'POST',
                    url: '/api/consulta/' + id + '/observacao',
                    processData: false,
                    contentType: false,
                    data: form_data,
                    dataType: "json",
                    success: function() {
                        let agora = new Date();
                        let observacao = {
                            medico: MEDICO,
                            mensagem: elmt.find('form').find('textarea').val(),
                            created_at: formatar_data_ISO8601(agora)
                        };
                        obs_modal.find('.modal-body').find('h3').remove();
                        obs_modal.find('.modal-body').append('<div class="observacao">' +
                        '<img src="/img/medico.png" class="rounded-circle">' + 
                        '<blockquote class="quote-card">' +
                            '<h5>' + observacao.medico.nome + '</h5>' +
                            '<p>' + observacao.mensagem  + '</p>' +
                            '<cite>' + formatar_data_hora_pt_BR(agora) + '</cite>' +
                        '</blockquote>'+
                    '</div>');
                        // Não precisa dessa linha, pois a lista é recarregada com as novas informaões
                        //Object.assign(observacoes, observacao);
                        func(list_modal, cache_busca);
                        elmt.modal('hide');
                    },
                    error: request_error(data.error)
                });
            }
        });
    }; 

    let modal_consulta_reagendar = function(list_modal, func, id, data) {
        return function() {
            let elmt = bootstrap_modal_backdrop('#reagendar-consulta');
            elmt.find('.modal-title').html(data.modal.title);

            elmt.find('.data-agendada').html(formatar_data_hora_pt_BR(new Date(data.modal.data)));
            elmt.find('form').find('input[name="data"]').val("")

            elmt.find('form').off('submit');
            elmt.find('form').on("submit", function(e){
                e.preventDefault();
                $(this).find('button[type="submit"]').text('Reagendando...');
                let form_data = new FormData();
            
                form_data.append('_method', 'PATCH');
                form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $(this).serializeArray().forEach(function(data) {
                    form_data.append(data.name, data.value);
                });

                if (elmt.find('form').find('input[name="data"]').val() != "") {
                    $.ajax({
                        type: 'POST',
                        url: '/api/consulta/' + id,
                        processData: false,
                        contentType: false,
                        data: form_data,
                        dataType: "json",
                        success: request_success(data.success.title, data.success.body, elmt, func, list_modal),
                        error: request_error(data.error)
                    });
                } else {
                    elmt.find('form').find('input[name="data"]').get(0).focus();
                }
            });
        };
    };

    let modal_cadastrar = function(elmt, tipo) {
        elmt.find('form').on("submit", function(e){
            e.preventDefault();
            $(this).find('button[type="submit"]').text('Cadastrando...');
            let form_data = new FormData();
            
            $(this).serializeArray().forEach(function(data) {
                form_data.append(data.name, data.value);
            });

            $.ajax({
                type: 'POST',
                url: elmt.find('form').attr("action"),
                processData: false,
                contentType: false,
                data: form_data,
                dataType: "json",
                success: function() {
                    request_success(
                        'Cadastrado com sucesso!',
                        'O ' + tipo + ' \'' + elmt.find('form').find('input[name="nome"]').val() + '\' foi cadastrado com sucesso no sistema.',
                        elmt)();
                    let token = elmt.find('form').find('input[name="_token"]').val();
                    elmt.find('form').find('input').val('');
                    elmt.find('form').find('input[name="_token"]').val(token);
                    elmt.find('form').find('select').prop("selectedIndex", 0);
                },
                error: request_error('Não foi possível realizar o cadastro!')
            });
        });
    }

    let modal_agendar_consulta = function(paciente) {
        return function () {
            let elmt = bootstrap_modal_backdrop('#agendar-consulta');

            elmt.find('.modal-title').html('Agendar consulta para o paciente ' + paciente.nome);

            elmt.find('form').find('input[name="data"]').val('');

            elmt.find('form').off("submit");
            elmt.find('form').on("submit", function(e){
                e.preventDefault();
                $(this).find('button[type="submit"]').text('Agendando...');
                if (elmt.find('form').find('input[name="data"]').val() != "") {
                    let form_data = new FormData();
                
                    form_data.append('_method', 'POST');
                    form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    form_data.append('paciente_id', paciente.id);

                    $(this).serializeArray().forEach(function(data) {
                        form_data.append(data.name, data.value);
                    });

                    $.ajax({
                        type: 'POST',
                        url: elmt.find('form').attr("action"),
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        data: form_data,
                        success: request_success(
                                'Consulta agendada com sucesso!',
                                'O consulta foi agendada para ' + formatar_data_hora_pt_BR(new Date(elmt.find('form').find('input[name="data"]').val())) + '.',
                                elmt),
                        error: request_error('Não foi possível agendar a consulta!')
                    });
                } else {
                    elmt.find('form').find('input[name="data"]').get(0).focus();
                }
            });
        }
    }

    let carregar_pacientes = function(modal_elmt, api = null) {
        let table = modal_elmt.find("table");
        let nav = modal_elmt.find("nav");

        adicionar_filtros(modal_elmt, carregar_pacientes);

        // Preencher a tabela
        preencher_tabela(table, api, function(response){
            var dt = table.dataTable();
            dt.fnClearTable();

            if (response.data.length > 0) {
                for (let paciente of response.data) {
                    let imc = (paciente.peso / (paciente.altura ^ 2));
                    let idade = calcular_idade(paciente.nascimento);

                    dt.fnAddData([
                        ('<span>' + paciente.nome + '</span>'),
                        ('<a class="text-current" href="mailto:' + paciente.email + '">' + paciente.email + '</a>'),
                        ('<a class="text-current" href="tel:+55' + paciente.telefone.match(/\d+/g).join('') + '">' + formatar_telefone(paciente.telefone) + '</a>'),
                        ('<span>' + formatar_data(new Date(paciente.created_at)) + '</span>'),
                        ('<span>' + idade + '</span>'),
                        ('<span class="badge badge-color-' + paciente.sexo + ' text-primary text-capitalize">' + paciente.sexo + '</span>'),
                        ('<span class="badge ' + IMC_status(imc, paciente.sexo, idade) + ' text-primary">' + imc.toFixed(1) + '</span>'),
                        ('<span>' + paciente.tipo_sanguineo + '</span>'),
                        ('<span>' + paciente.consultas_count + '</span>'),
                        ('<span><div class="dropdown text-center custom-dropdown">' +
                            '<a class="text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bars-staggered icon-size"></i></a>' + 
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            '<a id="agendar-consulta-' + paciente.id + '" href="#" class="dropdown-item">Agendar Consulta</a>' +
                            '<a id="editar-paciente-' + paciente.id + '" href="#" class="dropdown-item">Editar Paciente</a>' +
                            '<a id="excluir-paciente-' + paciente.id + '" href="#" class="dropdown-item">Excluir Paciente</a>' +
                            '</div></span>'),
                    ]);

                    fixar_table_dropdown();

                    $('#agendar-consulta-' + paciente.id).on('click', modal_agendar_consulta(paciente));

                    $('#editar-paciente-' + paciente.id).on('click', modal_edicao(modal_elmt, carregar_pacientes, paciente.id, {
                        modal: {
                            base: 'formulario-paciente',
                            title: 'Paciente: ' + paciente.nome,
                            data: paciente
                        },
                        success: {
                            title: 'Alterações salvas com sucesso!',
                            body: 'Os dados do paciente \'' + paciente.nome + '\' foram atualizados.'
                        },
                        error: 'Não foi possível efetuar alteração!'
                    }));

                    $('#excluir-paciente-' + paciente.id).on('click', modal_exclusao(modal_elmt, carregar_pacientes, table.data('api'), paciente.id, {
                        modal: {
                            title: 'Deseja excluir o paciente \'' + paciente.nome + '\'?',
                            body: 'Todas os dados desse paciente será excluído. Não será possível recuperar os dados. Tem certeza? As consultas desse paciente não serão excluídas, porém, não será possível identificar o paciente na consulta.'
                        },
                        success: {
                            title: 'Peciente excluído com sucesso!',
                            body: 'Todos os dados do paciente \'' + paciente.nome + '\' foram excluídos.'
                        },
                        error: 'Não foi possível efetuar a exclusão do paciente!'
                    }));
                };
            } else {
                table.find("tbody").html('<tr><td colspan="' + table.find('thead th').length + '" style="text-align:center;">Não há pacientes.</td></td>');
            }

            // Preencher a paginação
            atualizar_tabnav(nav, response.links, carregar_pacientes, response.total);
        });
    };

    let carregar_consultas = function(modal_elmt) {
        return function(modal_elmt_recursive, api = null) {
            if (modal_elmt_recursive != null) {
                modal_elmt = modal_elmt_recursive;
            }

            let table = modal_elmt.find("table");
            let nav = modal_elmt.find("nav");

            // Só pra garantir que o id dos elementos de outra tabela nao será o mesmo
            let r;
            do {
                r = Math.floor(Math.random() * (999 - 1 + 1) + 1);
            } while (r == cache_rand);

            cache_rand = r;

            adicionar_filtros_consulta(modal_elmt, carregar_consultas(modal_elmt));

            // Preencher a tabela
            preencher_tabela(table, api, function(response){
                var dt = table.dataTable();
                dt.fnClearTable();

                if (response.data.length > 0) {
                    for (let consulta of response.data) {
                        dt.fnAddData([
                            ('<span>' + consulta.id + '</span>'),
                            ((consulta.paciente != null) ? '<span class="fw-bold">' + consulta.paciente.nome + '</span>' : '<i>Paciente Excluído</i>'),
                            ((consulta.paciente != null) ? '<a class="text-current" href="mailto:' + consulta.paciente.email + '">' + consulta.paciente.email + '</a>' : '<i>Paciente Excluído</i>'),
                            ((consulta.paciente != null) ? '<a class="text-current" href="tel:+55' + consulta.paciente.telefone.match(/\d+/g).join('') + '">' + formatar_telefone(consulta.paciente.telefone) + '</a>' : '<i>Paciente Excluído</i>'),
                            ('<span>' + formatar_data_hora(new Date(consulta.data)) + '</span>'),
                            ('<span>' + consulta.observacoes.length + '</span>'),
                            ('<span>' + formatar_data(new Date(consulta.created_at)) + '</span>'),
                            ('<span><div class="dropdown text-center custom-dropdown">' +
                                '<a class="text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa-solid fa-bars-staggered icon-size"></i></a>' + 
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a id="observacoes-consulta-' + consulta.id + '-' + cache_rand + '" href="#" class="dropdown-item">Observações</a>' + 
                                '<a id="reagendar-consulta-' + consulta.id + '-' + cache_rand + '" href="#" class="dropdown-item">Reagendar</a>' +
                                '<a id="excluir-consulta-' + consulta.id + '-' + cache_rand + '" href="#" class="dropdown-item">Excluir Consulta</a>' +
                                '</div></span>'),
                        ]);

                        fixar_table_dropdown();

                        $('#observacoes-consulta-' + consulta.id + '-' + cache_rand).on('click', modal_consulta_observacoes(modal_elmt, carregar_consultas(modal_elmt), consulta.id, {
                            modal: {
                                title: 'Observações da consulta Nº ' + consulta.id,
                                sub: 'Cadastrar observação na consulta Nº ' + consulta.id,
                                observacoes: consulta.observacoes
                            },
                            error: 'Não foi possível cadastrar observação na consulta Nº ' + consulta.id + "!"
                        }));

                        $('#reagendar-consulta-' + consulta.id + '-' + cache_rand).on('click', modal_consulta_reagendar(modal_elmt, carregar_consultas(modal_elmt), consulta.id, {
                            modal: {
                                title: 'Reagendar consulta Nº ' + consulta.id,
                                data: consulta.data
                            },
                            success: {
                                title: 'A Nº ' + consulta.id + ' foi reagendada com sucesso!',
                                body: 'A consulta será refletida no seu sistema.'
                            },
                            error: 'Não foi possível reagendar a consulta Nº ' + consulta.id + "!"
                        }));

                        $('#excluir-consulta-' + consulta.id + '-' + cache_rand).on('click', modal_exclusao(modal_elmt, carregar_consultas(modal_elmt), table.data('api'), consulta.id, {
                            modal: {
                                title: 'Deseja excluir a consulta Nº ' + consulta.id + '?',
                                body: (consulta.paciente != null) ? 'A consulta com o paciente ' + consulta.paciente.nome + ' será excluída. Tem certeza?' : 'A consulta Nº ' + consulta.id + ' será excluída. Tem certeza?'
                            },
                            success: {
                                title: 'Consulta excluída com sucesso!',
                                body: (consulta.paciente != null) ? 'Todas as observações relacionadas a consulta com o paciente ' + consulta.paciente.nome + ' foram excluídas.' : 'Todas as observações relacionadas a consulta Nº ' + consulta.id + ' foram excluídas.'
                            },
                            error: 'Não foi possível efetuar a exclusão da consulta!'
                        }, true));
                    };
                } else {
                    table.find("tbody").html('<tr><td colspan="' + table.find('thead th').length + '" style="text-align:center;">Não há consultas.</td></td>');
                }

                // Preencher a paginação
                atualizar_tabnav(nav, response.links, carregar_consultas(modal_elmt), response.total);
            });
        };
    };

    let alterar_senha = function(e){
        e.preventDefault();
        let form_data = new FormData();
                
        form_data.append('_method', 'POST');
        form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $(this).serializeArray().forEach(function(data) {
            form_data.append(data.name, data.value);
        });

        $(this).find('button[type="submit"]').text('Alterando...');

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            processData: false,
            contentType: false,
            dataType: "json",
            data: form_data,
            success: function(res) {
                request_success('Senha alterada com sucesso!', 'Faça o próximo login usando sua nova senha.', $('#formulario-senha'))();
                $(this).find('input[type="password"]').val('');
            },
            error: request_error('Não foi possível alterar sua senha')
        });
    };

    $(document).ready(function(){
        definir_pagina();
        definir_headers();
        definir_login();
        definir_logout();
        definir_medico_infos();
        definir_app();
    });
})();